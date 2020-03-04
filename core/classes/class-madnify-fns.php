<?php

final class Madnify_Fns {
	
	public static function slug_from_name($name) {
		return strtolower(preg_replace('/\W/', '_', $name));
	}
	
	public static function get_extension_default_screenshot() {
		return apply_filters('madnify_extension_default_screenshot', MADNIFY_URL.'core/assets/img/madwire.png');
	}
	
	public static function parse_effects($fn, $effects) {
		if (!$effects || count($effects) <= 0) {
			return;
		}
		ob_start();
		switch($fn) {
			case 'shortcodes':
				self::parse_shortcode_effects($effects);
			break;
			case 'posttypes':
				self::parse_posttype_effects($effects);
			break;
			case 'templates':
				self::parse_templates_effects($effects);
			break;
			case 'admin_pages':
				self::parse_admin_pages_effects($effects);
			break;
			case 'rest_endpoints':
				self::parse_rest_endpoints_effects($effects);
			break;
			case 'admin_enqueue_styles':
			case 'admin_enqueue_scripts':
			case 'frontend_enqueue_styles':
			case 'frontend_enqueue_scripts':
				self::parse_asset_effects($effects, $fn);
			break;
			default:
				self::parse_default_effects($fn, $effects);
			break;
		}
		return ob_get_clean();
	}

	static function parse_default_effects($fn, $effects) {
		echo apply_filters("madnify_parse_{$fn}_effects", $effects);
	}

	public static function parse_asset_effects($effects, $fn) {
		switch ($fn) {
			case 'admin_enqueue_styles':
				$title = "Registered Admin Styles";
			break;
			case 'admin_enqueue_scripts':
				$title = "Registered Admin Scripts";
			break;
			case 'frontend_enqueue_styles':
				$title = "Registered Frontend Styles";
			break;
			case 'frontend_enqueue_scripts':
				$title = "Registered Frontend Scripts";
			break;
			default:
				$title = apply_filters("madnify_asset_effects_{$fn}_title", "Registered Assets");
			break;
		}
		ob_start(); ?>
			<h4><?php echo __($title); ?></h4>
			<ul class="madnify-effects-list madnify-asset-list">
				<?php foreach($effects as $handle => $details) {
					echo '<li>';
						if (array_key_exists('in_footer', $details)) {
							echo '<b>';
								echo ($details['in_footer']) ? "FOOTER" : "HEAD";
							echo '</b>';
						}
						echo '<a href="'.$details['src'].'?ver='.$details['ver'].'">'.$handle.'</a>';
						echo (count($details['deps']) > 0) ? " (".implode(", ", $details['deps']).")" : "";
					echo '</li>';
				} ?>
			</ul>
		<?php echo ob_get_clean();
	}

	static function parse_shortcode_effects($effects) {
		ob_start(); ?>
			<h4><?php echo __('Registered Shortcodes', 'madnify'); ?></h4>
			<ul class="madnify-effects-list madnify-shortcodes-list">
				<?php foreach($effects as $tag => $details) {
					echo "<li>[$tag";
					if (count($details['atts']) <= 0) {
						echo "]";
					} else {
						foreach($details['atts'] as $att => $default) {
							echo " <i>$att=\"$default\"</i>";
						}
						echo "]";
					}
					if ($details['content']) {
						echo " <b>CONTENT</b> [/$tag]";
					}
					echo "</li>";
				} ?>
			</ul>
		<?php echo ob_get_clean();
	}

	static function parse_posttype_effects($effects) {
		ob_start(); ?>
			<h4><?php echo __('Registered Post Types', 'madnify'); ?></h4>
			<ul class="madnify-effects-list madnify-posttypes-list">
				<?php foreach($effects as $slug => $posttype) {
				 	echo '<li class="dashicons-before '.$posttype['menu_icon'].'">';
				 		echo '<a href="'.admin_url('edit.php?post_type='.$slug).'">'.$posttype['labels']['name'].'</a>';
				 	echo '</li>';
				} ?>
			</ul>
		<?php echo ob_get_clean();
	}

	static function parse_templates_effects($effects) {
		ob_start();
			if (count($effects['single']) > 0): ?>
				<h4><?php echo __('Registered Single Templates', 'madnify'); ?></h4>
				<ul class="madnify-effects-list madnify-templates-list madnify-single-templates-list">
					<?php foreach($effects['single'] as $single_template) {
						echo '<li>';
							echo '<b>'.strtoupper($single_template['post_type']).'</b>'.$single_template['label'].': <i>'.$single_template['path'].'</i>';
						echo '</li>';
					} ?>
				</ul>
			<?php endif;
			if (count($effects['archive']) > 0): ?>
				<h4><?php echo __('Registered Archive Templates', 'madnify'); ?></h4>
				<ul class="madnify-effects-list madnify-templates-list madnify-archive-templates-list">
					<?php foreach($effects['archive'] as $archive_template) {
						echo '<li>';
							echo '<b>'.strtoupper($archive_template['post_type']).'</b><i>'.$archive_template['path'].'</i>';
						echo '</li>';
					} ?>
				</ul>
			<?php endif; ?>
		<?php echo ob_get_clean();
	}

	static function parse_admin_pages_effects($effects) {
		ob_start(); ?>
			<h4><?php echo __('Registered Admin Pages', 'madnify'); ?></h4>
			<ul class="madnify-effects-list madnify-admin-pages-list">
				<?php foreach($effects as $slug => $admin_page) {
					echo '<li class="dashicons-before '.$admin_page['icon'].'">';
						echo '<a href="'. admin_url('admin.php?page='.$slug).'">'.$admin_page['page_name'].'</a>';
					echo '</li>';
				} ?>
			</ul>
		<?php echo ob_get_clean();
	}

	static function parse_rest_endpoints_effects($effects) {
		ob_start(); ?>
			<h4><?php echo __('Registered REST Endpoints', 'madnify'); ?></h4>
			<ul class="madnify-effects-list madnify-rest-endpoints-list">
				<?php foreach($effects as $route => $args) {
					if (is_array($args['args'][0])) {
						foreach($args['args'] as $pirate) {
							echo '<li>';
								echo '<b>'.strtoupper($pirate['methods']).'</b><a href="'. rest_url($route).'">/wp-json/'.$route.'</a>';
							echo '</li>';
						}
					} else {
						echo '<li>';
							echo '<b>'.strtoupper($args['args']['methods']).'</b><a href="'. rest_url($route).'">/wp-json/'.$route.'</a>';
						echo '</li>';
					}
				} ?>
			</ul>
		<?php echo ob_get_clean();
	}
}