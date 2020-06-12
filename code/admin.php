<?php

if (!defined('ABSPATH')) {
    exit;
}

class GDATTAdmin {
    private $page_ids = array();
    private $admin_plugin = false;

    function __construct() {
        add_action('after_setup_theme', array($this, 'load'));
    }

    public static function instance() {
        static $instance = false;

        if ($instance === false) {
            $instance = new GDATTAdmin();
        }

        return $instance;
    }

    public function admin_init() {
        if (isset($_GET['page'])) {
            $this->admin_plugin = $_GET['page'] == 'gdbbpress_attachments';
        }

        if ($this->admin_plugin) {
            wp_enqueue_style('gd-bbpress-attachments', GDBBPRESSATTACHMENTS_URL."css/admin.css", array(), GDBBPRESSATTACHMENTS_VERSION);
        }
    }

    public function load() {
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));

        add_filter('plugin_action_links', array($this, 'plugin_actions'), 10, 2);
    }

    public function admin_menu() {
        $this->page_ids[] = add_submenu_page('edit.php?post_type=forum', 'bbPress Attachments', __("Attachments", "gd-bbpress-attachments"), GDBBPRESSATTACHMENTS_CAP, 'gdbbpress_attachments', array($this, 'menu_attachments'));
    }
    public function plugin_actions($links, $file) {
        if ($file == 'bbp-attachments/bbp-attachments.php') {
            $settings_link = '<a href="edit.php?post_type=forum&page=gdbbpress_attachments">'.__("Settings", "gd-bbpress-attachments").'</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }
    public function menu_attachments() {
        $options = GDATTCore::instance()->o;
        $_user_roles = d4p_bbpress_get_user_roles();

        include(GDBBPRESSATTACHMENTS_PATH.'forms/panels.php');
    }
}
