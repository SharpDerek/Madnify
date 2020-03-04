<?php

final class Madnify_Admin_Enqueue {
	
	public static function enqueue_scripts() {
		wp_enqueue_script('madnify-admin-js', MADNIFY_URL.'core/assets/js/madnify-admin.js', array('jquery'), MADNIFY_VERSION);
	}

	public static function enqueue_styles() {
		wp_enqueue_style('madnify-admin-css', MADNIFY_URL.'core/assets/css/madnify-admin.css', array(), MADNIFY_VERSION);
	}

}

add_action('admin_enqueue_scripts', 'Madnify_Admin_Enqueue::enqueue_scripts');
add_action('admin_enqueue_scripts', 'Madnify_Admin_Enqueue::enqueue_styles');