<?php

final class Madnify_Templates {

	static $templates = array();

	public static function init() {
		add_action('madnify_after_extensions_loaded', __CLASS__.'::register_templates');
	}

	public static function get_templates($extension = false, $type = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$templates)) {
				if ($type) {
					if (array_key_exists($type, self::$templates[$extension])) {
						return self::$templates[$extension][$type];
					}
					return array();
				}
				return self::$templates[$extension];
			}
			return array();
		}
		return self::$templates;
	}

	public static function add_single_template(string $extension, array $settings) {
		$default = array(
			'post_type' => 'page',
			'slug' => '',
			'label' => '',
			'path' => '',
			'priority' => 10,
			'filter' => function($base_template, $this_template) {
				return $this_template;
			}
		);
		self::$templates[$extension]['single'][] = array_merge($default, $settings);
	}
	public static function add_archive_template(string $extension, array $settings) {
		$default = array(
			'post_type' => 'post',
			'path' => '',
			'priority' => 10,
			'filter' => function($base_template, $this_template) {
				return $this_template;
			}
		);
		self::$templates[$extension]['archive'][] = array_merge($default, $settings);
	}

	static function register_single_template($settings) {
		add_filter('theme_'.$settings['post_type'].'_templates', function($templates) use ($settings) {
			$templates[$settings['slug']] = $settings['label'];
			return $templates;
		});
	}

	static function redirect_single_template($settings) {
		$type = ($settings['post_type'] == 'page') ? 'page' : 'single';
		add_filter("{$type}_template", function($single_template) use ($settings) {
			global $post;
			$post_template = get_post_meta($post->ID,'_wp_page_template',true);

			if ($post->post_type === $settings['post_type']) {
				if (basename($post_template) == $settings['slug']) {
					if (is_file($settings['path'])) {
						return $settings['filter']($single_template, $settings['path']);
					}
				}
			}
			return $single_template;
		}, $settings['priority']);
	}

	static function redirect_archive_template($settings) {
		add_filter('template_include', function($archive_template) use ($settings) {
			if (
				($settings['post_type'] == 'post' && is_home()) || 
				is_post_type_archive($settings['post_type']) ||
				is_category() ||
				is_tax(get_object_taxonomies($settings['post_type']))
			) {
				if (is_file($settings['path'])) {
					return $settings['filter']($archive_template, $settings['path']);
				}
			}
			return $archive_template;
		}, $settings['priority']);
	}

	public static function register_templates() {
		foreach(self::$templates as $extension => $types) {
			foreach(array_keys($types) as $type) {
				switch($type) {
					case 'single':
						foreach(self::$templates[$extension][$type] as $template) {
							self::register_single_template($template);
							self::redirect_single_template($template);
						}
					break;
					case 'archive':
						foreach(self::$templates[$extension][$type] as $template) {
							self::redirect_archive_template($template);
						}
					break;
				}
			}
		}
	}
}
Madnify_Templates::init();