<?php

final class Madnify_Posttypes {

	static $posttypes = array();

	public static function register_post_type($extension, $slug, $posttype_args) {
		self::$posttypes[$extension][$slug] = $posttype_args;
		add_action('init', function() use ($slug, $posttype_args) {
			register_post_type($slug, $posttype_args);
		});
	}

	public static function get_post_types($extension = false, $slug = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$posttypes)) {
				if ($slug) {
					if (array_key_exists($slug, self::$posttypes[$extension])) {
						return self::$posttypes[$extension][$slug];
					}
					return array();
				}
				return self::$posttypes[$extension];
			}
			return array();
		}
		return self::$posttypes;
	}

}