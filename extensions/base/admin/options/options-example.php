<?php
/*
Duplicate this function to register an admin page
*/


$this->register_admin_page(array(
	'page_name' => __('Madnify Admin Menu', 'madnify'), // Page name
	'menu_name' => __('Madnify', 'madnify'), // Page name in the admin sidebar
	'capability' => 'manage_options', // Who can access this page?
	'slug' => 'madnify_example', // Page slug. MUST BE UNIQUE
	'icon' => 'dashicons-admin-generic', // Icon for page in sidebar
	'position' => 2, // Position of page in sidebar (from top-down)
	'setting_groups' => array(
		array( // Duplicate this array to make another setting group
			'name' => 'madnify_options_example_group', // Setting group name. MUST BE UNIQUE
			'settings' => array(
				'madnify_options_example_setting' => array(
					'type' => 'string',	// Field type. Options are 'string', 'boolean', 'integer', or 'number'
					'sanitize_callback' => 'sanitize_text_field', // Function called to sanitize value before being put into database
					'show_in_rest' => false, // Should this setting be included in the REST API (Or Gutenberg)?
					'default' => NULL // Default value for setting
				),
			),
		),
	),
	'callback' => function($page_name, $settings) { ?>
		<div class="wrap">
				<h1><?php echo $page_name; ?></h1>

				<form method="post" action="options.php">
					<?php $settings(); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">Example Option Name</th>
							<td>
								<input
									type="text"
									name="madnify_options_example_setting"
									value="<?php echo esc_attr(get_option('madnify_options_example_setting')); ?>"
								>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
		</div>
	<?php }
));
