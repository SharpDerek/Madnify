<?php

final Class Madnify_Classes_Loader {

	static $_classes = array ();

	public static function add_classes(array $classes) {
		self::$_classes = array_merge(self::$_classes, $classes);
		return self::$_classes;
	}

	public static function load_classes() {
		do_action("madnify_before_classes_loaded");
		foreach(self::$_classes as $class => $filepath) {
			do_action("madnify_before_{$class}_loaded");
			require_once($filepath);
			do_action("madnify_after_{$class}_loaded");
		}
		do_action("madnify_after_classes_loaded");
	}

}

Madnify_Classes_Loader::add_classes(array(
	'madnify-fns'				=> plugin_dir_path(__FILE__).'classes/class-madnify-fns.php', 				// Helper functions for Madnify
	'madnify-shortcodes'		=> plugin_dir_path(__FILE__).'classes/class-madnify-shortcodes.php', 		// Handles adding, removing, and logging shortcodes
	'madnify-posttypes'			=> plugin_dir_path(__FILE__).'classes/class-madnify-posttypes.php', 		// Handles registering and logging custom post types
	'madnify-templates'			=> plugin_dir_path(__FILE__).'classes/class-madnify-templates.php', 		// Handles registering and logging custom templates
	'madnify-rest-endpoints'	=> plugin_dir_path(__FILE__).'classes/class-madnify-rest-endpoints.php', 	// Handles registering and logging custom rest endpoints
	'madnify-admin-pages'		=> plugin_dir_path(__FILE__).'classes/class-madnify-admin-pages.php', 		// Handles registering and logging custom admin pages
	'madnify-enqueue'			=> plugin_dir_path(__FILE__).'classes/class-madnify-enqueue.php',			// Handles logging enqueued CSS and JS
	'madnify-extension'			=> plugin_dir_path(__FILE__).'classes/class-madnify-extension.php', 		// Parent class for extensions
	'madnify-extensions-loader' => plugin_dir_path(__FILE__).'classes/class-madnify-extensions-loader.php', // Loader for extension instances
	'madnify-extension-list' 	=> plugin_dir_path(__FILE__).'classes/class-madnify-extension-list.php', 	// Lists loaded extensions on admin page
	'madnify-admin-enqueue' 	=> plugin_dir_path(__FILE__).'classes/class-madnify-admin-enqueue.php', 	// Admin styling and scripts
	'madnify-admin-page' 		=> plugin_dir_path(__FILE__).'classes/class-madnify-admin-page.php', 		// Loads admin page
));

Madnify_Classes_Loader::load_classes();