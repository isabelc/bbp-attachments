<?php

if (!defined('ABSPATH')) {
	exit;
}

class GDATTDefaults {
	var $default_options = array(
		'delete_attachments' => 'detach',
		'include_always' => 1,
		'hide_from_visitors' => 1,
		'max_file_size' => 512,
		'max_to_upload' => 4,
		'roles_to_upload' => null,
		'image_thumbnail_active' => 1,
		'image_thumbnail_caption' => 1,
		'log_upload_errors' => 1,
		'errors_visible_to_admins' => 1,
		'errors_visible_to_moderators' => 1,
		'errors_visible_to_author' => 1,
		'delete_visible_to_admins' => 'both',
		'delete_visible_to_moderators' => 'no',
		'delete_visible_to_author' => 'no'
	);

	function __construct() {
	}
}

$d4p_upload_error_messages = array(
	"File exceeds allowed file size.",
	"File not uploaded.",
	"Upload file size exceeds PHP maximum file size allowed.",
	"Upload file size exceeds FORM specified file size.",
	"Upload file only partially uploaded.",
	"Can't write file to the disk.",
	"Temporary folder for upload is missing.",
	"Server extension restriction stopped upload."
);
