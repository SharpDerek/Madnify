<?php

/*
Use this file to register custom templates and template overrides.
Use the variable $templates_dir to easily grab this extension's /templates directory filepath.
*/


$this->add_single_template(array(
	'post_type' => 'page',
	'slug' => 'madnify_custom',
	'label' => 'Madnify Custom Template',
	'path' => $templates_dir.'template-example.php',
	'priority' => 10,
	'filter' => function($base_template, $this_template) {
		return $this_template;
	}
));



$this->add_archive_template(array(
	'post_type' => 'post',
	'path' => $templates_dir.'template-example.php',
	'priority' => 9999,
	'filter' => function($base_template, $this_template) {
		return $this_template;
	}
));
