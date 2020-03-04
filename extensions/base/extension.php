<?php

Madnify_Extensions_Loader::add_extension(
	new Madnify_Base( // Use name of class below
		array(
			'location' => __FILE__, // Always __FILE__
			'name' => __(get_bloginfo('name').' Custom Functions (Base)', 'madnify'), // Extension name
			'slug' => 'base', // Extension slug, MUST BE UNIQUE
			'version' => 'v1.0.0',	//Extension version number
			'description' => __('Custom functionality for '.get_bloginfo('name').'.', 'madnify'), //Extension description. What does this extension do?
			'dependencies' => array(
 				// Does this extension need any other extensions to be registered first? List their slugs here.
			),
			'plugin_dependencies' => array(
				 // Does this extension need any specific plugins to be active? List their filepaths here.
				 	// Example: 'woocommerce/woocommerce.php'
			)
		)
	)
);

/*
class YOUR_CLASS_NAME extends Madnify_Extension
*/
class Madnify_Base extends Madnify_Extension {

	/*=============================================================================================
		EXTENSION FUNCTIONS
		* Uncomment the functions below to register them for this extension.
		* Feel free to add additional functions and reorder them to your liking.
		* Functions with parameters can be registered by putting items inside the array, like so:
			'some_function' => array(
				$parameter_1,
				$parameter_2
			)
		* Happy developing!
	=============================================================================================*/

	protected $_init_fns = array(
		// 'classes' => array(),
		// 'admin_enqueue_styles' => array(),
		// 'admin_enqueue_scripts' => array(),
		// 'admin_pages' => array(),
		// 'acf_register_options' => array(),
		// 'includes' => array(),
		// 'custom_functions' => array(),
		// 'shortcodes' => array(),
		// 'posttypes' => array(),
		// 'rest_endpoints' => array(),
		// 'woocommerce_mods' => array(),
		// 'frontend_enqueue_styles' => array(),
		// 'frontend_enqueue_scripts' => array(),
		// 'templates' => array()
	);


	function initialize() {
		// Put custom starting functions here

	}

	function admin_enqueue_styles() {
		$this->admin_enqueue_style(
			'madnify-extension-'.$this->get_slug().'-admin-css',
			$this->get_url().'admin/css/extension-admin.css',
			array(),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version()
		);
	}

	function admin_enqueue_scripts() {
		$this->admin_enqueue_script(
			'madnify-extension-'.$this->get_slug().'-admin-js',
			$this->get_url().'admin/js/extension-admin.js',
			array('jquery'),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version()
		);
	}

	function frontend_enqueue_styles() {
		$this->enqueue_style(
			'madnify-extension-'.$this->get_slug().'-frontend-css',
			$this->get_url().'frontend/css/extension-frontend.css',
			array(),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version()
		);
	}

	function frontend_enqueue_scripts() {

		$this->enqueue_script(
			'madnify-extension-'.$this->get_slug().'-frontend-js',
			$this->get_url().'frontend/js/extension-frontend.js',
			array('jquery'),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version()
		);

		$this->enqueue_script(
			'madnify-extension-'.$this->get_slug().'-frontend-footer-js',
			$this->get_url().'frontend/js/extension-frontend-footer.js',
			array('jquery'),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version(),
			true
		);
	}

	function admin_pages() {
		$options_dir = $this->get_filepath('admin/options/');
		// Register admin pages here using $options_dir to get this extension's /admin/options directory

		require_once($options_dir.'options-example.php'); // Use this file as a starting point for registering your own admin pages. Feel free to rename this file.
	}

	function includes() {
		$includes_dir = $this->get_filepath('includes');
		// Include additional files here using $includes_dir to get this extension's /includes directory
	}

	function rest_endpoints() {
		$rest_dir = $this->get_filepath('rest');
		// Register rest endpoints here using $rest_dir to get this extension's /rest directory

		require_once($rest_dir.'rest-example.php'); // Use this file as a starting point for registering your own rest endpoints. Feel free to rename this file.
	}

	function acf_options() {
		// Register ACF Options pages here. Function will not execute if ACF is not installed.
		acf_add_options_page(array(
			'page_title' => 'Madnify ACF Options Page',
			'menu_title' => 'Madnify ACF',
			'menu_slug' => 'madnify_acf',
			'capability' => 'edit_posts',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' 	=> 'Madnify ACF Options Sub Page',
			'menu_title'	=> 'Madnify ACF Sub',
			'parent_slug'	=> 'madnify_acf',
		));
	}

}