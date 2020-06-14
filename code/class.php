<?php

if (!defined('ABSPATH')) {
	exit;
}

class GDATTCore {
	private $plugin_path;
	private $plugin_url;

	public $l;
	public $o;

	function __construct() {
		$gdd = new GDATTDefaults();
		$this->o = get_option('gd-bbpress-attachments');
		if (!is_array($this->o)) {
			$this->o = $gdd->default_options;
			update_option('gd-bbpress-attachments', $this->o);
		}
		$this->plugin_path = dirname(dirname(__FILE__)).'/';
		$this->plugin_url = plugins_url('/bbp-attachments/');

		define('BBPATTACHMENTS_URL', $this->plugin_url);
		define('BBPATTACHMENTS_PATH', $this->plugin_path);

		add_action('after_setup_theme', array($this, 'load'), 5);
	}

	public static function instance() {
		static $instance = false;

		if ($instance === false) {
			$instance = new GDATTCore();
		}

		return $instance;
	}
	public function load() {
		add_action('init', array($this, 'delete_attachments'));
		add_action('before_delete_post', array($this, 'delete_post'));
		if (is_admin()) {
			require_once(BBPATTACHMENTS_PATH.'code/admin.php');
			require_once(BBPATTACHMENTS_PATH.'code/meta.php');

			GDATTAdmin::instance();
			GDATTAdminMeta::instance();
		} else {
			require_once(BBPATTACHMENTS_PATH.'code/front.php');

			GDATTFront::instance();
		}
	}
	public function delete_attachments() {
		if (isset($_GET['d4pbbaction'])) {
			$nonce = wp_verify_nonce($_GET['_wpnonce'], 'd4p-bbpress-attachments');

			if ($nonce) {
				global $user_ID;

				$action = $_GET['d4pbbaction'];
				$att_id = intval($_GET['att_id']);
				$bbp_id = intval($_GET['bbp_id']);

				$post = get_post($bbp_id);
				$author_ID = $post->post_author;

				$file = get_attached_file($att_id);
				$file = pathinfo($file, PATHINFO_BASENAME);

				$allow = 'no';
				if (d4p_is_user_admin()) {
					$allow = d4p_bba_o('delete_visible_to_admins');
				} else if (d4p_is_user_moderator()) {
					$allow = d4p_bba_o('delete_visible_to_moderators');
				} else if ($author_ID == $user_ID) {
					$allow = d4p_bba_o('delete_visible_to_author');
				}

				if ($action == 'delete' && ($allow == 'delete' || $allow == 'both')) {
					wp_delete_attachment($att_id);

					add_post_meta($bbp_id, '_bbp_attachment_log', array(
						'code' => 'delete_attachment',
						'user' => $user_ID,
						'file' => $file)
					);
				}

				if ($action == 'detach' && ($allow == 'detach' || $allow == 'both')) {
					global $wpdb;
					$wpdb->update($wpdb->posts, array('post_parent' => 0), array('ID' => $att_id));

					add_post_meta($bbp_id, '_bbp_attachment_log', array(
						'code' => 'detach_attachment',
						'user' => $user_ID,
						'file' => $file)
					);
				}
			}

			$url = remove_query_arg(array('_wpnonce', 'd4pbbaction', 'att_id', 'bbp_id'));
			wp_redirect($url);
			exit;
		}
	}

	public function delete_post($id) {
		if (class_exists('bbPress')) {
			if (bbp_is_reply($id) || bbp_is_topic($id)) {
				if ($this->o['delete_attachments'] == 'delete') {
					$files = d4p_get_post_attachments($id);

					if (is_array($files) && !empty($files)) {
						foreach ($files as $file) {
							wp_delete_attachment($file->ID);
						}
					}
				} else if ($this->o['delete_attachments'] == 'detach') {
					global $wpdb;

					$wpdb->update($wpdb->posts, array('post_parent' => 0), array('post_parent' => $id, 'post_type' => 'attachment'));
				}
			}
		}
	}

	public function enabled_for_forum($id = 0) {
		$meta = get_post_meta(bbp_get_forum_id($id), '_gdbbatt_settings', true);
		return !isset($meta['disable']) || (isset($meta['disable']) && $meta['disable'] == 0);
	}

	public function get_file_size($global_only = false, $forum_id = 0) {
		$forum_id = $forum_id == 0 ? bbp_get_forum_id() : $forum_id;
		$value = $this->o['max_file_size'];

		if (!$global_only) {
			$meta = get_post_meta($forum_id, '_gdbbatt_settings', true);

			if (is_array($meta) && $meta['to_override'] == 1) {
				$value = $meta['max_file_size'];
			}
		}

		return $value;
	}

	public function get_max_files($global_only = false, $forum_id = 0) {
		$forum_id = $forum_id == 0 ? bbp_get_forum_id() : $forum_id;
		$value = $this->o['max_to_upload'];

		if (!$global_only) {
			$meta = get_post_meta($forum_id, '_gdbbatt_settings', true);

			if (is_array($meta) && $meta['to_override'] == 1) {
				$value = $meta['max_to_upload'];
			}
		}

		return $value;
	}
	public function is_right_size($file, $forum_id = 0) {
		$forum_id = $forum_id == 0 ? bbp_get_forum_id() : $forum_id;
		$file_size = $this->get_file_size(false, $forum_id);
		return $file["size"] < $file_size * 1024;
	}
	public function is_user_allowed() {
		$allowed = false;
		if (is_user_logged_in()) {
			if (!isset($this->o['roles_to_upload'])) {
				$allowed = true;
			} else {
				$value = $this->o['roles_to_upload'];
				if (!is_array($value)) {
					$allowed = true;
				}
				global $current_user;
				if (is_array($current_user->roles)) {
					$matched = array_intersect($current_user->roles, $value);
					$allowed = !empty($matched);
				}
			}
		}
		return $allowed;
	}
	public function is_hidden_from_visitors($forum_id = 0) {
		$forum_id = $forum_id == 0 ? bbp_get_forum_id() : $forum_id;

		$value = $this->o['hide_from_visitors'];
		$meta = get_post_meta($forum_id, '_gdbbatt_settings', true);

		if (is_array($meta) && $meta['to_override'] == 1) {
			$value = $meta['hide_from_visitors'];
		}

		return ($value == 1);
	}
}
