<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Get the list of attachments for a post.
 *
 * @param int $post_id topic or reply ID to get attachments for
 *
 * @return array list of attachments objects
 */
function d4p_get_post_attachments($post_id) {
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => -1,
		'post_status' => null,
		'post_parent' => $post_id,
		'orderby' => 'ID',
		'order' => 'ASC'
	);

	return get_posts($args);
}
/**
 * Handle upload file error.
 *
 * @param string $file    file with error
 * @param string $message error message
 *
 * @return WP_Error error message
 */
function d4p_bbattachment_handle_upload_error(&$file, $message) {
	return new WP_Error("wp_upload_error", $message);
}

/**
 * Get current page forum ID. Handles the edge cases with the edit forms.
 *
 * @return int
 */
function d4p_get_forum_id() {
	$forum_id = bbp_get_forum_id();

	if ($forum_id == 0) {
		if (bbp_is_topic_edit()) {
			$topic_id = bbp_get_topic_id();
			$forum_id = bbp_get_topic_forum_id($topic_id);
		} else if (bbp_is_reply_edit()) {
			$reply_id = bbp_get_reply_id();
			$forum_id = bbp_get_reply_forum_id($reply_id);
		}
	}

	return $forum_id;
}

function d4p_bba_o($name) {
	return GDATTCore::instance()->o[$name];
}
