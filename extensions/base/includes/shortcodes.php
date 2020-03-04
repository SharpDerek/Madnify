<?php
/*
Use this file to register custom shortcodes. Below are some examples.
*/

$this->add_shortcode(array(
	'tag' => 'madnify_example', // Name of the shortcode
	'atts' => array( // Accepted attributes and their default values
		'att1' => 'att1_default',
		'att2' => 'att2_default',
	),
	'content' => false, // Does the shortcode wrap content?
	'function' => function($atts) { // Function the shortcode fires
		extract($atts);

		ob_start(); ?>
			<div>
				<p>This is a custom shortcode!<br>
				Att1: <b><?php echo $att1; ?></b><br>
				Att2: <b><?php echo $att2; ?></b></p>
			</div>
		<?php return ob_get_clean();
	},
	'enabled' => true // Whether the shortcode is enabled. Set to false to prevent shortcode from being registered
));

$this->add_shortcode(array(
	'tag' => 'madnify_content',
	'atts' => array(
		'att1' => 'att1_default',
		'att2' => 'att2_default',
	),
	'content' => true,
	'function' => function($atts, $content) {
		extract($atts);

		ob_start(); ?>
			<div>
				<p>This is a custom shortcode!<br>
				Att1: <b><?php echo $att1; ?></b><br>
				Att2: <b><?php echo $att2; ?></b></p>
				<p>And this is the rest of the content:</p>
				<?php echo $content; ?>
			</div>
		<?php return ob_get_clean();
	},
	'enabled' => true
));