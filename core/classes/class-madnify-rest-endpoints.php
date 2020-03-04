<?php

final class Madnify_Rest_Endpoints {
	
	static $endpoints = array();

	public static function init() {
		add_action('madnify_after_extensions_loaded', __CLASS__.'::register_rest_endpoints');
	}

	public static function get_registered_rest_endpoints($extension = false, $route = false) {
		if ($extension) {
			if (array_key_exists($extension, self::$endpoints)) {
				if ($route) {
					if (array_key_exists($route, self::$endpoints[$extension])) {
						return self::$endpoints[$extension][$route];
					}
					return array();
				}
				return self::$endpoints[$extension];
			}
			return array();
		}
		return self::$endpoints;
	}

	public static function register_rest_route($extension, $settings) {
		$default = array(
			'namespace' => 'madnify/'.$extension,
			'route' => '/rest',
			'args' => array()
		);
		$settings = array_merge($default, $settings);
		if (!($settings['namespace'] && $settings['route'])) {
			return;
		}
		self::$endpoints[$extension][$settings['namespace'].$settings['route']] = $settings;
	}

	public static function register_rest_endpoints() {
		add_action('rest_api_init', function() {
			foreach(self::$endpoints as $extension => $routes) {
				foreach($routes as $route) {
			   		register_rest_route(
			   			$route['namespace'],
			   			$route['route'],
			   			$route['args']
			      	);
			   	}
			}
		});
	}
}
Madnify_Rest_Endpoints::init();