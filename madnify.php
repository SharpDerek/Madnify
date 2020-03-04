<?php

/*

Plugin Name: Madnify

Plugin URI: http://www.madwire.com/

Description: Proprietary development plugin by Madwire. Instantly see custom functionality on your site and what it does by viewing the extension dashboard.

Version: 3.1.1

Author: Madwire

Author URI: http://www.madwire.com/

*/

define('MADNIFY_URL', plugin_dir_url(__FILE__));
define('MADNIFY_PATH', plugin_dir_path(__FILE__));
define('MADNIFY_VERSION', '3.1.1');

final class Madnify {

	static function settings_page() {
		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", function($links) {
			$settings_link = '<a href="'.admin_url('admin.php?page=madnify-options').'">'.__('Extensions', 'madnify').'</a>';
			array_push($links, $settings_link);
			return $links;
		});
	}

	public static function init() {
		do_action('madnify_before_init');
		self::settings_page();
		self::classes_loader();
		do_action('madnify_after_init');
	}

	static function classes_loader() {
		require_once(MADNIFY_PATH.'core/classes-loader.php');
	}

}
Madnify::init();
