<?php

class Madnify_Extension {

	/*==================== ESSENTIAL EXTENSION STUFF =========================*/
	/* Don't override these in an extension unless you know what you're doing */

	/* Extension Variables */

	/* Extension name (human-readable) */
	protected $_name = "";

	/* Extension slug */
	protected $_slug = "";

	/* Extension Description Text */
	protected $_description = "";

	/* Filepath to Extension */
	protected $_filepath = "";

	/* URL of Extension */
	protected $_url = "";

	/* Extension Icon (For use on List Page) */
	protected $_screenshot = "";

	/* Extension Version number */
	protected $_version = "";

	/* Extension Dependencies */
	protected $_dependencies = array();

	/* Plugin Dependencies */
	protected $_plugin_dependencies = array();

	/* Functions this extension calls (filtered) */
	protected $_capabilities = array();

	/* All functions this extension initially calls (unfiltered) */
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

	/* Constructs this extension instance */
	function __construct(array $settings) {
		$defaults = array (
			'location' => false,
			'name' => false,
			'slug' => false,
			'description' => '',
			'version' => 'v0.0.0',
			'dependencies' => array(),
			'plugin_dependencies' => array()
		);
		extract(array_merge($defaults, $settings));

		$this->_filepath = plugin_dir_path($location);
		$this->_url = plugin_dir_url($location);
		$this->_name = $name;
		$this->_slug = ($slug) ? $slug : Madnify_Fns::slug_from_name($this->_name);
		$this->_description = $description;
		$this->_version = $version;
		$this->_dependencies = $dependencies;
		$this->_plugin_dependencies = $plugin_dependencies;
	}

	public function init() {
		$this->initialize();
		do_action("madnify_before_extension_".$this->get_slug()."_init");
		foreach(apply_filters("madnify_extension_".$this->get_slug()."_init_fns", $this->_init_fns) as $fn => $params) {
			if (method_exists($this, $fn)) {
				do_action("madnify_before_extension_".$this->get_slug()."_".$fn);
					call_user_func_array(array($this, $fn), $params);
					$this->_capabilities[$fn] = array(
						'params' => $params,
						'effects' => array(),
					);
				do_action("madnify_after_extension_".$this->get_slug()."_".$fn);
			}
		}

		do_action("madnify_after_extension_".$this->get_slug()."_init");
	}

	function add_shortcode($args) {
		Madnify_Shortcodes::add_shortcode($this->get_slug(), $args);
	}

	function register_post_type($slug, $args) {
		Madnify_Posttypes::register_post_type($this->get_slug(), $slug, $args);
	}

	function add_single_template($args) {
		Madnify_Templates::add_single_template($this->get_slug(), $args);
	}

	function add_archive_template($args) {
		Madnify_Templates::add_archive_template($this->get_slug(), $args);
	}

	function register_admin_page($args) {
		Madnify_Admin_Pages::register_admin_page($this->get_slug(), $args);
	}

	function register_rest_route($args) {
		Madnify_Rest_Endpoints::register_rest_route($this->get_slug(), $args);
	}

	function enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all', $inline = false) {
		Madnify_Enqueue::enqueue_style($this->get_slug(), $handle, $src, $deps, $ver, $media, $inline);
	}
	function enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false, $inline = false) {
		Madnify_Enqueue::enqueue_script($this->get_slug(), $handle, $src, $deps, $ver, $in_footer, $inline);
	}
	function admin_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all', $inline = false) {
		Madnify_Enqueue::admin_enqueue_style($this->get_slug(), $handle, $src, $deps, $ver, $media, $inline);
	}
	function admin_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false, $inline = false) {
		Madnify_Enqueue::admin_enqueue_script($this->get_slug(), $handle, $src, $deps, $ver, $in_footer, $inline);
	}

	function get_function_effects($fn) {
		switch($fn) {
			case 'shortcodes':
				return Madnify_Shortcodes::get_shortcodes($this->get_slug());
			case 'posttypes':
				return Madnify_Posttypes::get_post_types($this->get_slug());
			case 'templates':
				return Madnify_Templates::get_templates($this->get_slug());
			case 'admin_pages':
				return Madnify_Admin_Pages::get_registered_admin_pages($this->get_slug());
			case 'rest_endpoints':
				return Madnify_Rest_Endpoints::get_registered_rest_endpoints($this->get_slug());
			case 'admin_enqueue_styles':
				return Madnify_Enqueue::get_admin_styles($this->get_slug());
			case 'admin_enqueue_scripts':
				return Madnify_Enqueue::get_admin_scripts($this->get_slug());
			case 'frontend_enqueue_styles':
				return Madnify_Enqueue::get_styles($this->get_slug());
			case 'frontend_enqueue_scripts':
				return Madnify_Enqueue::get_scripts($this->get_slug());
			default:
				return apply_filters('madnify_extension_'.$this->get_slug().'_'.$fn.'_effects', array());
		}
	}

	public function get_fns() {
		return $this->_init_fns;
	}

	public function assign_function_effects($fn) {
		$this->_capabilities[$fn]['effects'] = $this->get_function_effects($fn);
	}

	public function get_filepath(string $location = "") {
		return trailingslashit(trailingslashit($this->_filepath) . $location);
	}

	public function get_url() {
		return $this->_url;
	}

	public function get_name() {
		return $this->_name;
	}

	public function get_slug() {
		return $this->_slug;
	}

	public function get_description() {
		return $this->_description;
	}

	public function get_version() {
		return $this->_version;
	}

	public function get_dependencies() {
		return $this->_dependencies;
	}

	public function get_plugin_dependencies() {
		return $this->_plugin_dependencies;
	}

	public function get_screenshot() {
		$file_types = array (
			'png',
			'jpg',
			'jpeg',
			'gif',
			'svg'
		);
		foreach(apply_filters('madnify_extension_screenshot_filetypes', $file_types) as $file_type) {
			$screenshot_url = trailingslashit($this->get_url()).'screenshot.'.$file_type;
			if (strpos(@get_headers($screenshot_url)[0], '200') > -1) {
				return $screenshot_url;
			}
		}
		return Madnify_Fns::get_extension_default_screenshot();
	}

	public function get_info() {
		return array (
			'name' => $this->get_name(),
			'slug' => $this->get_slug(),
			'filepath' => $this->get_filepath(),
			'description' => $this->get_description(),
			'screenshot' => $this->get_screenshot(),
		);
	}

	public function get_capabilities() {
		return $this->_capabilities;
	}

	/* Ensures ACF is installed/Active before trying to register options pages */
	public function acf_register_options() {
		if(function_exists('acf_add_options_page')) {
			add_action('acf/init', array($this, 'acf_options'));
		}
	}

	/* Calls this extension's classes-loader.php file */
	public function classes() {
		require_once($this->get_filepath('classes').'classes-loader.php');
	}

	/* Ensures WooCommerce is installed/Active before trying to enabled WooCommerce mods */
	public function woocommerce_mods() {
		add_action('plugins_loaded', function() {
			if (class_exists('WooCommerce')) {
				require_once($this->get_filepath('includes').'woocommerce_mods.php');
			}
		});
	}

	/* Calls this extensions's shortcodes.php file */
	public function shortcodes() {
		require_once($this->get_filepath('includes').'shortcodes.php');
	}

	/* Calls this extensions's custom.php file */
	public function custom_functions() {
		require_once($this->get_filepath('includes').'custom.php');
	}

	/* Calls this extensions's posttypes.php file */
	public function posttypes() {
		require_once($this->get_filepath('includes').'posttypes.php');
	}

	/* Calls this extensions's templates.php file */
	public function templates() {
		$templates_dir = $this->get_filepath('templates');
		require_once($templates_dir.'templates.php');
	}

	/*==================== END ESSENTIAL EXTENSION STUFF ====================*/



	/*==================== EXTENSION OVERRIDE FUNCTIONS ====================*/
	/* These functions can be overridden in an extension */

	/* Registers ACF-dependent options pages. Do not call this method outside of 'acf/init' hook */
	public function acf_options() {
		acf_add_options_page(array(
			'page_title' => 'Madnify ACF Options Page',
			'menu_title' => 'Madnify ACF',
			'menu_slug' => $this->get_slug().'madnify_acf',
			'capability' => 'edit_posts',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' 	=> 'Madnify ACF Options Sub Page',
			'menu_title'	=> 'Madnify ACF Sub',
			'parent_slug'	=> $this->get_slug().'madnify_acf',
		));
	}

	/* Begin Enqueue Templates */
	public function admin_enqueue_styles() {
		$this->admin_enqueue_style(
			'madnify-extension-'.$this->get_slug().'-admin-css',
			$this->get_url().'admin/css/extension-admin.css',
			array(),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version()
		);
	}

	public function admin_enqueue_scripts() {
		$this->admin_enqueue_script(
			'madnify-extension-'.$this->get_slug().'-admin-js',
			$this->get_url().'admin/js/extension-admin.js',
			array('jquery'),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version()
		);
	}

	public function frontend_enqueue_styles() {
		$this->enqueue_style(
			'madnify-extension-'.$this->get_slug().'-frontend-css',
			$this->get_url().'frontend/css/extension-frontend.css',
			array(),
			MADNIFY_VERSION.'_'.$this->get_slug().'_'.$this->get_version()
		);
	}

	public function frontend_enqueue_scripts() {

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
	/* End Enqueue Templates */


	public function admin_pages() {
	
	}

	public function includes() {
		
	}

	public function rest_endpoints() {
		
	}

	public function initialize() {
		
	}

	/*==================== END EXTENSION OVERRIDE FUNCTIONS ====================*/

}