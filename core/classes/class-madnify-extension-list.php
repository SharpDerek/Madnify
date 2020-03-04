<?php

final class Madnify_Extensions_List {

	public static function list_extensions() {
		$extensions = Madnify_Extensions_Loader::get_loaded_extensions();
		if (count($extensions) > 0): ?>
			<div class="madnify-loaded-extensions">
				<h2><?php echo __('Active Madnify Extensions:', 'madnify'); ?></h2>
				<?php foreach ($extensions as $extension):
					$info = $extension->get_info();
					$ver = $extension->get_version();
					$capabilities = $extension->get_capabilities();
					$cap_string = "";
					$index = count($capabilities) - 1;
					if (count($capabilities) < 1) {
						$cap_string = "<i>".__("Looks like this extension isn't doing anything at the moment.", 'madnify')."</i>";
					} else {
						foreach($capabilities as $fn => $details) {
							extract($details);
							$has_effects = count($effects) > 0;

							$cap_string.= '<span class="madnify-extension-function madnify-extension-function-'.$fn.' '.(($has_effects) ? 'has-effects' : '').'">';
							$cap_string.= $fn."(".implode(", ", $params).")";
							$cap_string.= '</span>';
							if ($has_effects) {
								$cap_string.= '<div class="madnify-extension-effects madnify-extension-'.$fn.'-effects">';
								$cap_string.= '<div class="madnify-extension-effects-inner madnify-extension-'.$fn.'-effects-inner">';
								$cap_string.= Madnify_Fns::parse_effects($fn, $effects);
								$cap_string.= '</div></div>';
							}
							$index--;
						}
					}
				?>
					<div class="madnify-extension madnify-extension-<?php echo $info['slug']; ?>">
						<div class="madnify-screenshot">
							<img src="<?php echo $info['screenshot']; ?>" alt="Madnify <?php echo $info['name']; ?> Extension Screenshot">
						</div>
						<div class="madnify-details">
							<h3 class="madnify-extension-title"><?php echo __($info['name'], 'madnify'); ?><span class="madnify-extension-version"><?php echo $ver; ?></h3>
							<p class="madnify-extension-description"><?php echo $info['description']; ?></p>
							<div class="madnify-extension-capabilities"><?php echo $cap_string; ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="madnify-loaded-extensions no-loaded-extensions">
				<h2><?php __('No loaded extensions', 'madnify'); ?></h2>
			</div>
		<?php endif;

	}

	public static function list_failed_extensions() {
		$extensions = Madnify_Extensions_Loader::get_failed_extensions();
		if (count($extensions) > 0): ?>
			<div class="madnify-failed-extensions">
				<h4><?php echo __('The following extensions failed to load due to one or more of their dependencies not being properly met:', 'madnify'); ?></h4>
				<ul class="madnify-failed-extensions-list">
					<?php foreach($extensions as $extension):
						$name = $extension->get_name();
						$url = $extension->get_url();
					?>
						<li><a href="<?php echo $url; ?>"><?php echo $name; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif;
	}

}

add_action('madnify_admin_page_content', 'Madnify_Extensions_List::list_extensions', 5);
add_action('madnify_admin_page_content', 'Madnify_Extensions_List::list_failed_extensions', 10);