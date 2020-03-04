<?php

// Use this file to register whatever custom post types you may need. Look below for an example.
	
// Example Post Type
$posttype_labels = array(
	'name' => __('MADNIFY POST TYPE', 'madnify'),
	'singular_name' => __('MADNIFY POST TYPE', 'madnify'),
	'add_new' => __('Add MADNIFY POST TYPE', 'madnify'),
	'add_new_item' => __('Add New MADNIFY POST TYPE', 'madnify'),
	'edit_item' => __('Edit MADNIFY POST TYPE', 'madnify'),
	'new_item' => __('New MADNIFY POST TYPE', 'madnify'),
	'all_items' => __('All MADNIFY POST TYPES', 'madnify'),
	'view_item' => __('View MADNIFY POST TYPE', 'madnify'),
	'search_items' => __('Search MADNIFY POST TYPES', 'madnify'),
	'not_found' =>  __('No MADNIFY POST TYPES found', 'madnify'),
	'not_found_in_trash' => __('No MADNIFY POST TYPES found in Trash', 'madnify'),
	'parent_item_colon' => '',
	'menu_name' => __('MADNIFY POST TYPE', 'madnify')
);
$posttype_args = array(
	'labels' => $posttype_labels,
	'public' => true,
	'publicly_queryable' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'show_in_menu' => true,
	'menu_icon'   => 'dashicons-admin-generic',
	'query_var' => true,
	'rewrite' => array( 'slug' => 'madnify_post_type' ),
	'capability_type' => 'page',
	'has_archive' => false,
	'hierarchical' => false,
	'menu_position' => 20,
	'supports' => array( 'title', 'page-attributes', 'editor', 'thumbnail' )
);

$this->register_post_type('madnify_post_type', $posttype_args);
