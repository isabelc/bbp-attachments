<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('gdbbp_Error')) {
	class gdbbp_Error {
		var $errors = array();

		function __construct() {
		}

		function add($code, $message, $data) {
			$this->errors[$code][] = array($message, $data);
		}
	}
}
function d4p_bbpress_get_user_roles() {
	$roles = array();
	$dynamic_roles = bbp_get_dynamic_roles();
	foreach ($dynamic_roles as $role => $obj) {
		$roles[$role] = $obj['name'];
	}
	return $roles;
}
function d4p_is_user_moderator() {
	global $current_user;
	if (is_array($current_user->roles)) {
		return in_array('bbp_moderator', $current_user->roles);
	} else {
		return false;
	}
}
function d4p_is_user_admin() {
	global $current_user;
	if (is_array($current_user->roles)) {
		return in_array('administrator', $current_user->roles);
	} else {
		return false;
	}
}
