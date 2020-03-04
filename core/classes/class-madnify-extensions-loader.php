<?php

final class Madnify_Extensions_Loader {

	private static $registered_extensions = array();
	private static $loaded_extensions_unsorted = array();
	private static $loaded_extensions = array();
	private static $failed_extensions = array();

	public static function get_loaded_extensions() {
		return self::$loaded_extensions;
	}

	public static function get_failed_extensions() {
		return self::$failed_extensions;
	}

	public static function add_extension(Madnify_Extension $extension) {
		self::$loaded_extensions_unsorted[$extension->get_slug()] = $extension;
	}

	static function begin_extension_dependency_sort() {
		foreach(self::$loaded_extensions_unsorted as $slug => $extension) {
			foreach($extension->get_dependencies() as $dep) {
				if (!array_key_exists($dep, self::$loaded_extensions_unsorted)) {
					self::$failed_extensions[$slug] = self::$loaded_extensions_unsorted[$slug];
					unset(self::$loaded_extensions_unsorted[$slug]);
					break;
				}
			}
			foreach($extension->get_plugin_dependencies() as $plugin_dep) {
				if (!is_plugin_active($plugin_dep)) {
					self::$failed_extensions[$slug] = self::$loaded_extensions_unsorted[$slug];
					unset(self::$loaded_extensions_unsorted[$slug]);
					break;
				}
			}
		}
		if (count(self::$loaded_extensions_unsorted) > 0) {
			self::extension_dependency_sort_item(self::$loaded_extensions_unsorted);
		}
	}

	static function extension_dependency_sort_item($to_be_sorted) {
		$extension_slug = array_keys($to_be_sorted)[0];
		$extension = $to_be_sorted[$extension_slug];
		$all_deps_loaded = true;
		foreach($extension->get_dependencies() as $dep) {
			if (!array_key_exists($dep, self::$loaded_extensions)) {
				$all_deps_loaded = false;
				break;
			}
		}
		if ($all_deps_loaded) {
			self::$loaded_extensions[$extension->get_slug()] = $extension;
			unset($to_be_sorted[$extension->get_slug()]);
		} else {
			switch(count($to_be_sorted)) {
				case 0:
					return;
					break;
				case 1:
					self::$loaded_extensions[$extension->get_slug()] = $extension;
					unset($to_be_sorted[$extension->get_slug()]);
					return;
					break;
				default:
					$first_element = array_shift($to_be_sorted);
					$second_element = array_shift($to_be_sorted);
					$to_be_sorted = array_merge(
						array (
							$second_element->get_slug() => $second_element,
							$first_element->get_slug() => $first_element,
						),
						$to_be_sorted
					);
					break;
			}
		}
		if (count($to_be_sorted) > 0) {
			self::extension_dependency_sort_item($to_be_sorted);
		}
	}

	static function extensions_finalize() {
		foreach(self::$loaded_extensions as $extension) {
			$extension->init();
			foreach($extension->get_fns() as $fn_name => $fn) {
				$extension->assign_function_effects($fn_name);
			}
		}
	}

	public static function register_extensions(array $extension_paths) {
		self::$registered_extensions = array_merge(self::$registered_extensions, $extension_paths);
	}

	public static function load_extensions() {
		require_once(MADNIFY_PATH.'extensions/extensions-loader.php');
		do_action("madnify_before_extensions_loaded");

		foreach(apply_filters("madnify_registered_extensions", self::$registered_extensions) as $extension => $filepath) {
			if (file_exists($filepath)) {
				do_action("madnify_before_{$extension}_extension_loaded");

				require_once($filepath);

				do_action("madnify_after_{$extension}_extension_loaded");
			}
		}
		self::begin_extension_dependency_sort();
		self::extensions_finalize();

		do_action("madnify_after_extensions_loaded");
	}
}
Madnify_Extensions_Loader::load_extensions();