<?php

final class Madnify_Admin_Pages {

	static $admin_pages = array();

	public static function init() {
		add_action('madnify_after_extensions_loaded', __CLASS__.'::register_admin_pages');
	}

	public static function get_registered_admin_pages($extension = false, $slug = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$admin_pages)) {
				if ($slug) {
					if (array_key_exists($slug, self::$admin_pages[$extension])) {
						return self::$admin_pages[$extension][$slug];
					}
					return array();
				}
				return self::$admin_pages[$extension];
			}
			return array();
		}
		return self::$admin_pages;
	}

	public static function register_admin_page($extension, $settings) {
		$default = array(
			'page_name' => '',
			'menu_name' => '',
			'capability' => 'manage_options',
			'slug' => '',
			'callback' => '',
			'icon' => 'dashicons-admin-generic',
			'position' => 2,
			'setting_groups' => array(),
		);

		$settings = array_merge($default, $settings);

		if (!($settings['page_name'] && $settings['menu_name'] && $settings['slug'])) {
			return;
		}

		self::$admin_pages[$extension][$settings['slug']] = $settings;
	}

	public static function register_setting_group($args) {
		$default = array(
			'name' => '',
			'settings' => array()
		);
		$args = array_merge($default, $args);
		foreach($args['settings'] as $name => $settings) {
			self::register_setting($args['name'], $name, $settings);
		}
	}

	public static function register_setting($group, $name, $args) {
		$default = array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default' => NULL
		);
		$args = array_merge($default, $args);
		add_action('admin_init', function() use ($group, $name, $args) {
			register_setting($group, $name, $args);
		});
	}

	static function register_page($args) {
		foreach($args['setting_groups'] as $group) {
			self::register_setting_group($group);
		}
		add_action('admin_menu', function() use ($args) {
			extract($args);
			add_menu_page(
				$page_name,
				$menu_name,
				$capability,
				$slug,
				function() use ($page_name, $callback, $setting_groups) {
					$settings = function() use ($setting_groups) {
						foreach($setting_groups as $group) {
							settings_fields($group['name']);
							do_settings_sections($group['name']);
						}
					};
					$callback($page_name, $settings);
				},
				$icon,
				$position
			);
		});
	}

	public static function register_admin_pages() {
		foreach(self::$admin_pages as $extension => $slugs) {
			foreach($slugs as $slug => $args) {
				self::register_page($args);
			}
		}
	}

}
Madnify_Admin_Pages::init();