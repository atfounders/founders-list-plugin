<?php
/*
Plugin Name: Founders List Plugin
Plugin URI: 
Description: 
Author: Ryan Imel
Version: 0.1
Author URI: http://wpcandy.com
*/


/**
 * Add our custom content.
 */
add_action( 'init', 'fl_custom_content_types' );

function fl_custom_content_types() {
	$person_labels = array(
		'name' => _x('People', 'post type general name', 'founders_list_plugin'),
		'singular_name' => _x('Person', 'post type singular name', 'founders_list_plugin'),
		'add_new' => _x('Add New', 'book', 'founders_list_plugin'),
		'add_new_item' => __('Add New Person', 'founders_list_plugin'),
		'edit_item' => __('Edit Person', 'founders_list_plugin'),
		'new_item' => __('New Person', 'founders_list_plugin'),
		'all_items' => __('All People', 'founders_list_plugin'),
		'view_item' => __('View Person', 'founders_list_plugin'),
		'search_items' => __('Search People', 'founders_list_plugin'),
		'not_found' =>  __('No people found', 'founders_list_plugin'),
		'not_found_in_trash' => __('No people found in Trash', 'founders_list_plugin'), 
		'parent_item_colon' => '',
		'menu_name' => __('People', 'founders_list_plugin')
	);
	$person_args = array(
		'labels' => $person_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => _x( 'person', 'URL slug', 'founders_list_plugin' ) ),
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title' )
	);
	register_post_type( 'fl_person', $person_args );
	
	$labels = array(
		'name' => _x( 'Trades', 'taxonomy general name', 'founders_list_plugin' ),
		'singular_name' => _x( 'Trade', 'taxonomy singular name', 'founders_list_plugin' ),
		'search_items' =>  __( 'Search Trades', 'founders_list_plugin' ),
		'all_items' => __( 'All Trades', 'founders_list_plugin' ),
		'parent_item' => __( 'Parent Trade', 'founders_list_plugin' ),
		'parent_item_colon' => __( 'Parent Trade:', 'founders_list_plugin' ),
		'edit_item' => __( 'Edit Trade', 'founders_list_plugin' ), 
		'update_item' => __( 'Update Trade', 'founders_list_plugin' ),
		'add_new_item' => __( 'Add New Trade', 'founders_list_plugin' ),
		'new_item_name' => __( 'New Trade Name', 'founders_list_plugin' ),
		'menu_name' => __( 'Trades', 'founders_list_plugin' ),
	); 	
	
	register_taxonomy(
		'fl_trade',
		array( 'fl_person' ), 
		array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'trade' ),
		)
	);
}


/**
 * Remove what we don't need.
 */
add_action( 'admin_menu', 'fl_remove_menu_items' );

function fl_remove_menu_items() {
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'upload.php' );
	remove_menu_page( 'edit.php?post_type=page' );
	remove_menu_page( 'edit-comments.php' );
}


/**
 * Remove dashboard widgets we don't need.
 */
add_action( 'wp_dashboard_setup', 'fl_remove_dashboard_widgets' );

function fl_remove_dashboard_widgets() {
	// Main widgets
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	
	// Side widgets
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
}


/**
 * Add our own custom metaboxes.
 */
add_filter( 'cmb_meta_boxes', 'fl_cmb_meta_boxes' );

function fl_cmb_meta_boxes() {
	
	$prefix = '_fl_';
	
	$meta_boxes[] = array(
			'id'         => 'fl_people_meta_box',
			'title'      => 'Contact Information',
			'pages'      => array( 'fl_person', ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => array(
				array(
					'name' => 'Email',
					'desc' => '',
					'id'   => $prefix . 'email_address',
					'type' => 'text_medium',
				),
				array(
					'name' => 'Website',
					'desc' => 'Full address, ex: http://google.com',
					'id'   => $prefix . 'website_url',
					'type' => 'text_medium',
				),
				array(
					'name' => 'Twitter',
					'desc' => 'Full address to Twitter, ex: http://twitter.com/founders',
					'id'   => $prefix . 'twitter_url',
					'type' => 'text_medium',
				),
				array(
					'name' => 'Facebook',
					'desc' => 'Full Facebook address',
					'id'   => $prefix . 'facebook_url',
					'type' => 'text_medium',
				),
				array(
					'name' => 'Founders',
					'desc' => 'Founders profile, ex: http://chat.atfounders.com/author/ryanimel',
					'id'   => $prefix . 'founders_url',
					'type' => 'text_medium',
				),
				array(
					'name' => 'Recommended by',
					'desc' => 'Who recommends this person? Everyone on this list should be.',
					'id'   => $prefix . 'recommended_by',
					'type' => 'text_medium',
				),
			),
		);

		$meta_boxes[] = array(
			'id'         => 'about_page_metabox',
			'title'      => 'About Page Metabox',
			'pages'      => array( 'page', ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'show_on'    => array( 'key' => 'id', 'value' => array( 2, ), ), // Specific post IDs to display this metabox
			'fields' => array(
				array(
					'name' => 'Test Text',
					'desc' => 'field description (optional)',
					'id'   => $prefix . 'test_text',
					'type' => 'text',
				),
			)
		);

		return $meta_boxes;	
}


/**
 * Initialize the metabox class.
 */
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'metabox/init.php';

}