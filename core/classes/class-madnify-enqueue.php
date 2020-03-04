<?php

final class Madnify_Enqueue {

	static $styles = array();
	static $scripts = array();
	static $admin_styles = array();
	static $admin_scripts = array();

	public static function get_styles($extension = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$styles)) {
				return self::$styles[$extension];
			}
			return array();
		}
		return self::$styles;
	}

	public static function get_admin_styles($extension = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$admin_styles)) {
				return self::$admin_styles[$extension];
			}
			return array();
		}
		return self::$admin_styles;
	}

	public static function get_scripts($extension = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$scripts)) {
				return self::$scripts[$extension];
			}
			return array();
		}
		return self::$scripts;
	}

	public static function get_admin_scripts($extension = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$admin_scripts)) {
				return self::$admin_scripts[$extension];
			}
			return array();
		}
		return self::$admin_scripts;
	}

	
	public static function enqueue_style($extension, $handle, $src = '', $deps = array(), $ver = false, $media = 'all', $inline = false) {
		self::$styles[$extension][$handle] = array(
			'handle' => $handle,
			'src' => $src,
			'deps' => $deps,
			'ver' => $ver,
			'media' => $media
		);
		add_action('wp_enqueue_scripts', function() use ($handle, $src, $deps, $ver, $media, $inline) {
			wp_enqueue_style($handle, $src, $deps, $ver, $media);
			if ($inline) {
				wp_add_inline_style($handle, $inline());
			}
		});
	}

	public static function admin_enqueue_style($extension, $handle, $src = '', $deps = array(), $ver = false, $media = 'all', $inline = false) {
		self::$admin_styles[$extension][$handle] = array(
			'handle' => $handle,
			'src' => $src,
			'deps' => $deps,
			'ver' => $ver,
			'media' => $media
		);
		add_action('admin_enqueue_scripts', function() use ($handle, $src, $deps, $ver, $media, $inline) {
			wp_enqueue_style($handle, $src, $deps, $ver, $media);
			if ($inline) {
				wp_add_inline_style($handle, $inline());
			}
		});
	}


	public static function enqueue_script($extension, $handle, $src = '', $deps = array(), $ver = false, $in_footer = false, $inline = false) {
		self::$scripts[$extension][$handle] = array(
			'handle' => $handle,
			'src' => $src,
			'deps' => $deps,
			'ver' => $ver,
			'in_footer' => $in_footer
		);
		add_action('wp_enqueue_scripts', function() use ($handle, $src, $deps, $ver, $in_footer, $inline) {
			wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
			if ($inline) {
				wp_add_inline_script($handle, $inline());
			}
		});
	}

	public static function admin_enqueue_script($extension, $handle, $src = '', $deps = array(), $ver = false, $in_footer = false, $inline = false) {
		self::$admin_scripts[$extension][$handle] = array(
			'handle' => $handle,
			'src' => $src,
			'deps' => $deps,
			'ver' => $ver,
			'in_footer' => $in_footer
		);
		add_action('admin_enqueue_scripts', function() use ($handle, $src, $deps, $ver, $in_footer, $inline) {
			wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
			if ($inline) {
				wp_add_inline_script($handle, $inline());
			}
		});
	}

}