<?php
/**
 * Register the necessary custom post types
 *
 *
 * @since      1.0.0
 * @package    Flo_Forms
 * @subpackage Flo_Forms/includes
 * @author     Alex G. <alexg@flothemes.com>
 */

class Flo_Forms_custom_posts {
	
	/**
	 *
	 * call the methods that are registering custom post types
	 * Add bellow this a method for each custom post type
	 *
	 */
	public function flo_reg_custom_post_type(){
		// call the methods that are registering the post types
		$this->flo_reg_forms_post_type();
		$this->flo_reg_entrie_post_type();

		$this->flo_register_form_entries_taxonomy();
	}


	/**
	 *
	 * Register the Forms post type
	 *
	 */
	public function flo_reg_forms_post_type(){

		$labels = array(
			'name'               => _x( 'Forms', 'post type general name', 'flo-forms' ),
			'singular_name'      => _x( 'Form', 'post type singular name', 'flo-forms' ),
			'menu_name'          => _x( 'Flo Forms', 'admin menu', 'flo-forms' ),
			'name_admin_bar'     => _x( 'Forms', 'add new on admin bar', 'flo-forms' ),
			'add_new'            => _x( 'Add New Form', 'form', 'flo-forms' ),
			'add_new_item'       => __( 'Add New Form', 'flo-forms' ),
			'new_item'           => __( 'New Form', 'flo-forms' ),
			'edit_item'          => __( 'Edit Form', 'flo-forms' ),
			'view_item'          => __( 'View Form', 'flo-forms' ),
			'all_items'          => __( 'All Forms', 'flo-forms' ),
			'search_items'       => __( 'Search Forms', 'flo-forms' ),
			'parent_item_colon'  => __( 'Parent Forms:', 'flo-forms' ),
			'not_found'          => __( 'No forms found.', 'flo-forms' ),
			'not_found_in_trash' => __( 'No forms found in Trash.', 'flo-forms' )
		);

		$args = array(
			'labels'             => $labels,
	        'public'             => false,
	        'exclude_from_search'=> true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'flo_forms' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 22.341,
			'menu_icon'			 => 'dashicons-feedback',
			'supports'           => array( 'title',  )
		);

		register_post_type( 'flo_forms', $args );
	}

	public function flo_reg_entrie_post_type(){

		$labels = array(
			'name'               => _x( 'Flo Form Entries', 'post type general name', 'flo-forms' ),
			'singular_name'      => _x( 'Entry', 'post type singular name', 'flo-forms' ),
			'menu_name'          => _x( 'Entries', 'admin menu', 'flo-forms' ),
			'name_admin_bar'     => _x( 'Entries', 'add new on admin bar', 'flo-forms' ),
			'add_new'            => _x( 'Add New', 'entry', 'flo-forms' ),
			'add_new_item'       => __( 'Add New Entry', 'flo-forms' ),
			'new_item'           => __( 'New Entry', 'flo-forms' ),
			'edit_item'          => __( 'Edit Entry', 'flo-forms' ),
			'view_item'          => __( 'View Entry', 'flo-forms' ),
			'all_items'          => __( 'All Entries', 'flo-forms' ),
			'search_items'       => __( 'Search Entries', 'flo-forms' ),
			'parent_item_colon'  => __( 'Parent Entries:', 'flo-forms' ),
			'not_found'          => __( 'No entry found.', 'flo-forms' ),
			'not_found_in_trash' => __( 'No entry found in Trash.', 'flo-forms' )
		);

		$args = array(
			'labels'             => $labels,
	        'public'             => false,
	        'exclude_from_search'=> true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'flo_forms_entries' ),
			'capability_type'    => 'post',
			// 'capabilities' => array(
			//     'create_posts' => 'do_not_allow', // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			// ),
			'has_archive'        => false,
			'hierarchical'       => false,
			//'menu_position'      => 23.341,
			'show_in_menu' => 'edit.php?post_type=flo_forms', // This is where we tell WordPress to add 'Entries' as a submenu for the Foms
	
			'supports'           => array( 'title',  )
		);

		register_post_type( 'flo_form_entry', $args );
	}

	/**
	 *
	 * Register the form taxonomy for the Entries
	 *
	 */
	public function flo_register_form_entries_taxonomy(){
		register_taxonomy(
			'entry_form',
			'flo_form_entry', // custom post type for which we register this taxonomy
			array(
				'label' => __( 'Forms','flo-forms' ),
				'rewrite' => array( 'slug' => 'entry_form' ),
				'show_admin_column' => true,
				'hierarchical' => true,
			)
		);
	}
}
