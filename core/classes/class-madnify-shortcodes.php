<?php

final class Madnify_Shortcodes {
	
	static $shortcodes = array();

	public static function init() {
		add_action('madnify_after_extensions_loaded', __CLASS__.'::execute_shortcodes');
	}

	public static function get_shortcodes($extension = false, $tag = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$shortcodes)) {
				if ($tag) {
					if (array_key_exists($tag, self::$shortcodes[$extension])) {
						return self::$shortcodes[$extension][$tag];
					}
					return array();
				}
				return self::$shortcodes[$extension];
			}
			return array();
		}
		return self::$shortcodes;
	}

	public static function add_shortcode(string $extension, array $settings) {
		$defaults = array (
			'tag' => '',
			'atts' => array(),
			'content' => false,
			'function' => false,
			'enabled' => true,
		);
		extract(array_merge($defaults, $settings));
		if (!($extension && $tag && $function && $enabled)) {
			return;
		}
		self::$shortcodes[$extension][$tag] = array (
			'atts' => $atts,
			'content' => $content,
			'function' => $function
		);
	}

	public static function remove_shortcode($extension, $tag) {
		unset(self::$shortcodes[$extension][$tag]);
	}

	public static function execute_shortcodes() {
		foreach(self::$shortcodes as $extension => $shortcode) {
			foreach ($shortcode as $tag => $elements) {
				add_shortcode(
					$tag,
					function($atts, $content) use ($elements) {
						$atts = shortcode_atts($elements['atts'], $atts);
						if ($content) {
							return $elements['function']($atts, $content);
						} else {
							return $elements['function']($atts);
						}
					}
				);
			}
		}
	}
}
Madnify_Shortcodes::init();