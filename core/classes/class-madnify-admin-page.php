<?php

final class Madnify_Admin_Page {

	static $icon = MADNIFY_URL.'core/assets/img/madwire.svg';

	static $admin_title = "Madnify ".MADNIFY_VERSION." Extension Dashboard";

	static function is_site_admin() {
		return in_array('administrator', wp_get_current_user()->roles);
	}

	public static function admin_html() {
		if (self::is_site_admin()):

			$nonce = wp_create_nonce( 'madnify_admin_page' ); ?>

			<script>
				var madnify_admin_nonce = "<?php echo $nonce; ?>";
			</script>

			<?php do_action("madnify_before_admin_html_wrap"); ?>

				<div class="wrap">
					<?php do_action("madnify_before_admin_html"); ?>

						<h1><?php echo apply_filters("madnify_admin_page_title", self::$admin_title); ?></h1>

						<div class="madnify-admin-page-content">
							<?php do_action('madnify_admin_page_content'); ?>
						</div>

					<?php do_action("madnify_after_admin_html"); ?>
				</div>

			<?php do_action("madnify_after_admin_html_wrap"); ?>
		<?php else:
			$nonce = "none" ?>
			<h2>You are not authorized to view this page. Please contact your administrator.</h2>

		<?php endif;
	}

	public static function render_admin() {
		add_menu_page(
			'Madnify',
			'Madnify',
			'manage_options',
			'madnify-options',
			__CLASS__.'::admin_html',
			'data:image/svg+xml;base64,'.base64_encode(file_get_contents(apply_filters('madnify_admin_icon', self::$icon))),
			2
		);
	}

}
add_action( 'admin_menu', 'Madnify_Admin_Page::render_admin' );