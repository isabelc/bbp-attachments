<?php

if (!defined('ABSPATH')) {
	exit;
}

class GDATTFront {
	function __construct() {
		add_action('bbp_init', array($this, 'load'));
	}
	public static function instance() {
		static $instance = false;

		if ($instance === false) {
			$instance = new GDATTFront();
		}

		return $instance;
	}
	public function load() {
		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));

		add_action('bbp_theme_before_reply_form_submit_wrapper', array($this, 'embed_form'));
		add_action('bbp_theme_before_topic_form_submit_wrapper', array($this, 'embed_form'));

		add_action('bbp_edit_reply', array($this, 'save_reply'), 10, 5);
		add_action('bbp_edit_topic', array($this, 'save_topic'), 10, 4);
		add_action('bbp_new_reply', array($this, 'save_reply'), 10, 5);
		add_action('bbp_new_topic', array($this, 'save_topic'), 10, 4);

		add_filter('bbp_get_reply_content', array($this, 'embed_attachments'), 100, 2);
		add_filter('bbp_get_topic_content', array($this, 'embed_attachments'), 100, 2);

		$this->register_scripts_and_styles();
	}
	public function register_scripts_and_styles() {
		wp_register_style('gdatt-attachments', BBPATTACHMENTS_URL.'css/front.css', array(), null);
		wp_register_script('gdatt-attachments', BBPATTACHMENTS_URL.'js/front.js', array('jquery'), null, true);
	}
	public function include_scripts_and_styles() {
		wp_enqueue_style('gdatt-attachments');
		wp_enqueue_script('gdatt-attachments');
		wp_localize_script('gdatt-attachments', 'bbpatt_str', array(
			'max_files' => GDATTCore::instance()->get_max_files(),
			'are_you_sure' => "This operation is not reversible. Are you sure?"
		));
	}
	public function wp_enqueue_scripts() {
		$isbbp = class_exists('bbPress') ? is_bbpress() : false;
		if (d4p_bba_o('include_always') == 1 || $isbbp) {
			$this->include_scripts_and_styles();
		}
	}
	public function save_topic($topic_id, $forum_id, $anonymous_data, $topic_author) {
		$this->save_reply(0, $topic_id, $forum_id, $anonymous_data, $topic_author);
	}
	public function save_reply($reply_id, $topic_id, $forum_id, $anonymous_data = null, $reply_author = null) {
		$uploads = array();

		if (!empty($_FILES) && !empty($_FILES['d4p_attachment'])) {

			require_once(ABSPATH.'wp-admin/includes/file.php');

			$errors = new gdbbp_Error();
			$overrides = array('test_form' => false, 'upload_error_handler' => 'd4p_bbattachment_handle_upload_error');

			foreach ($_FILES['d4p_attachment']['error'] as $key => $error) {
				$file_name = $_FILES['d4p_attachment']['name'][$key];

				if ($error == UPLOAD_ERR_OK) {
					$file = array('name' => $file_name,
						'type' => $_FILES['d4p_attachment']['type'][$key],
						'size' => $_FILES['d4p_attachment']['size'][$key],
						'tmp_name' => $_FILES['d4p_attachment']['tmp_name'][$key],
						'error' => $_FILES['d4p_attachment']['error'][$key]
					);

					$file_name = sanitize_file_name($file_name);

					if (GDATTCore::instance()->is_right_size($file, $forum_id)) {
						$upload = wp_handle_upload($file, $overrides);

						if (!is_wp_error($upload)) {
							$uploads[] = $upload;
						} else {
							$errors->add('wp_upload', $upload->errors['wp_upload_error'][0], $file_name);
						}
					} else {
						$errors->add('d4p_upload', 'File exceeds allowed file size.', $file_name);
					}
				} else {
					switch ($error) {
						default:
						case 'UPLOAD_ERR_NO_FILE':
							$errors->add('php_upload', 'File not uploaded.', $file_name);
							break;
						case 'UPLOAD_ERR_INI_SIZE':
							$errors->add('php_upload', 'Upload file size exceeds PHP maximum file size allowed.', $file_name);
							break;
						case 'UPLOAD_ERR_FORM_SIZE':
							$errors->add('php_upload', 'Upload file size exceeds FORM specified file size.', $file_name);
							break;
						case 'UPLOAD_ERR_PARTIAL':
							$errors->add('php_upload', 'Upload file only partially uploaded.', $file_name);
							break;
						case 'UPLOAD_ERR_CANT_WRITE':
							$errors->add('php_upload', 'Can\'t write file to the disk.', $file_name);
							break;
						case 'UPLOAD_ERR_NO_TMP_DIR':
							$errors->add('php_upload', 'Temporary folder for upload is missing.', $file_name);
							break;
						case 'UPLOAD_ERR_EXTENSION':
							$errors->add('php_upload', 'Server extension restriction stopped upload.', $file_name);
							break;
					}
				}
			}
		}

		$post_id = $reply_id == 0 ? $topic_id : $reply_id;

		if (!empty($errors->errors) && d4p_bba_o('log_upload_errors') == 1) {
			foreach ($errors->errors as $code => $errs) {
				foreach ($errs as $error) {
					if ($error[0] != '' && $error[1] != '') {
						add_post_meta($post_id, '_bbp_attachment_upload_error', array(
								'code' => $code, 'file' => $error[1], 'message' => $error[0])
						);
					}
				}
			}
		}

		if (!empty($uploads)) {
			require_once(ABSPATH.'wp-admin/includes/media.php');
			require_once(ABSPATH.'wp-admin/includes/image.php');

			foreach ($uploads as $upload) {
				$wp_filetype = wp_check_filetype(basename($upload['file']), null);
				$attachment = array('post_mime_type' => $wp_filetype['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', basename($upload['file'])),
					'post_content' => '', 'post_status' => 'inherit'
				);

				$attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);
				$attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
				wp_update_attachment_metadata($attach_id, $attach_data);
				update_post_meta($attach_id, '_bbp_attachment', '1');
			}
		}
	}
	public function embed_attachments($content, $id) {
		global $user_ID;

		$attachments = d4p_get_post_attachments($id);

		$post = get_post($id);
		$author_id = $post->post_author;

		if (!empty($attachments)) {
			$content .= '<div class="bbp-attachments">';
			$content .= '<h6>Attachments:</h6>';

			$_download = ' download';

			if (!is_user_logged_in() && GDATTCore::instance()->is_hidden_from_visitors()) {
				$content .= sprintf("You must be <a href='%s'>logged in</a> to view attached files.", wp_login_url(get_permalink()));
			} else {
				if (!empty($attachments)) {
					$listing = '<ol>';
					$thumbnails = $listing;
					$images = $files = 0;

					foreach ($attachments as $attachment) {
						$actions = array();

						$url = add_query_arg('_wpnonce', wp_create_nonce('d4p-bbpress-attachments'));
						$url = add_query_arg('att_id', $attachment->ID, $url);
						$url = add_query_arg('bbp_id', $id, $url);

						$allow = 'no';
						if (d4p_is_user_admin()) {
							$allow = d4p_bba_o('delete_visible_to_admins');
						} else if (d4p_is_user_moderator()) {
							$allow = d4p_bba_o('delete_visible_to_moderators');
						} else if ($author_id == $user_ID) {
							$allow = d4p_bba_o('delete_visible_to_author');
						}

						if ($allow == 'delete' || $allow == 'both') {
							$actions[] = '<a class="d4p-bba-action-delete" href="'.add_query_arg('d4pbbaction', 'delete', $url).'">delete</a>';
						}

						if ($allow == 'detach' || $allow == 'both') {
							$actions[] = '<a class="d4p-bba-action-detach" href="'.add_query_arg('d4pbbaction', 'detach', $url).'">detach</a>';
						}

						if (count($actions) > 0) {
							$actions = ' <span class="d4p-bba-actions">['.join(' | ', $actions).']</span>';
						} else {
							$actions = '';
						}

						$file = get_attached_file($attachment->ID);
						$filename = pathinfo($file, PATHINFO_BASENAME);
						$file_url = wp_get_attachment_url($attachment->ID);

						$html = $class_li = "";
						$a_title = $filename;
						$caption = false;

						$img = false;
						if (d4p_bba_o('image_thumbnail_active') == 1) {
							$html = wp_get_attachment_image($attachment->ID, 'thumbnail');

							if ($html != "") {
								$img = true;
								$class_li = 'bbp-atthumb';
								$caption = d4p_bba_o('image_thumbnail_caption') == 1;
							}
						}

						if ($html == '') {
							$html = $filename;
						}
						$item = '<li id="d4p-bbp-attachment_'.$attachment->ID.'" class="d4p-bbp-attachment '.$class_li.'">';

						if ($img) {
							$item .= '<a href="'.$file_url.'" title="'.$a_title.'">'.$html.'</a>';
						} else {
							$item .= '<a '.$_download.' href="'.$file_url.'" title="'.$a_title.'">'.$html.'</a>';
						}

						if ($caption) {
							$a_title = '<a href="'.$file_url.'"'.$_download.'>'.$a_title.'</a>';

							$item .= '<p class="wp-caption-text">'.$a_title.'<br/>'.$actions.'</p></div>';
						} else {
							$item .= $actions;
						}

						$item .= '</li>';

						if ($img) {
							$thumbnails .= $item;
							$images++;
						} else {
							$listing .= $item;
							$files++;
						}
					}

					$thumbnails .= '</ol>';
					$listing .= '</ol>';

					if ($images > 0) {
						$content .= $thumbnails;
					}

					if ($files > 0) {
						$content .= $listing;
					}
				}
			}

			$content .= '</div>';
		}

		if ((d4p_bba_o('errors_visible_to_author') == 1 && $author_id == $user_ID) || (d4p_bba_o('errors_visible_to_admins') == 1 && d4p_is_user_admin()) || (d4p_bba_o('errors_visible_to_moderators') == 1 && d4p_is_user_moderator())) {
			$errors = get_post_meta($id, '_bbp_attachment_upload_error');

			if (!empty($errors)) {
				$content .= '<div class="bbp-attachments-errors">';
				$content .= '<h6>Upload Errors:</h6>';
				$content .= '<ol>';
				$class_li = 'bbp-file-error';
				foreach ($errors as $error) {
					$content .= '<li class="'.$class_li.'"><strong>'.esc_html($error['file']).'</strong>: '.$error['message'] . '</li>';
				}
				$content .= '</ol></div>';
			}
		}

		return $content;
	}

	public function embed_form() {
		$can_upload = GDATTCore::instance()->is_user_allowed();
		if (!$can_upload) {
			return;
		}
		$is_enabled = GDATTCore::instance()->enabled_for_forum();
		if (!$is_enabled) {
			return;
		}

		$file_size = GDATTCore::instance()->get_file_size();

		include(BBPATTACHMENTS_PATH.'forms/uploader.php');
	}
}
