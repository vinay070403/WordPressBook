<?php
/*
Plugin Name: WP Book
Plugin URI: https://github.com/yourusername/wp-book
Description: A plugin to manage and display books using custom post types, taxonomies, meta fields, shortcodes, and widgets.
Version: 1.0.0
Author: Vinay Chavada
Text Domain: wp-book
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Load plugin textdomain
function wp_book_load_textdomain() {
    load_plugin_textdomain( 'wp-book', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wp_book_load_textdomain' );

// Include all required files in later steps

// Register Custom Post Type: Book
function wp_book_register_post_type() {
    $labels = array(
        'name'                  => __( 'Books', 'wp-book' ),
        'singular_name'         => __( 'Book', 'wp-book' ),
        'menu_name'             => __( 'Books', 'wp-book' ),
        'name_admin_bar'        => __( 'Book', 'wp-book' ),
        'add_new'               => __( 'Add New', 'wp-book' ),
        'add_new_item'          => __( 'Add New Book', 'wp-book' ),
        'new_item'              => __( 'New Book', 'wp-book' ),
        'edit_item'             => __( 'Edit Book', 'wp-book' ),
        'view_item'             => __( 'View Book', 'wp-book' ),
        'all_items'             => __( 'All Books', 'wp-book' ),
        'search_items'          => __( 'Search Books', 'wp-book' ),
        'not_found'             => __( 'No books found.', 'wp-book' ),
        'not_found_in_trash'    => __( 'No books found in Trash.', 'wp-book' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'books' ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'show_in_rest'       => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-book-alt',
    );

    register_post_type( 'book', $args );
}
add_action( 'init', 'wp_book_register_post_type' );

// Register Hierarchical Taxonomy: Book Category
function wp_book_register_taxonomy_category() {
    $labels = array(
        'name'              => __( 'Book Categories', 'wp-book' ),
        'singular_name'     => __( 'Book Category', 'wp-book' ),
        'search_items'      => __( 'Search Book Categories', 'wp-book' ),
        'all_items'         => __( 'All Book Categories', 'wp-book' ),
        'parent_item'       => __( 'Parent Category', 'wp-book' ),
        'parent_item_colon' => __( 'Parent Category:', 'wp-book' ),
        'edit_item'         => __( 'Edit Category', 'wp-book' ),
        'update_item'       => __( 'Update Category', 'wp-book' ),
        'add_new_item'      => __( 'Add New Category', 'wp-book' ),
        'new_item_name'     => __( 'New Category Name', 'wp-book' ),
        'menu_name'         => __( 'Book Categories', 'wp-book' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => array( 'slug' => 'book-category' ),
    );

    register_taxonomy( 'book_category', array( 'book' ), $args );
}
add_action( 'init', 'wp_book_register_taxonomy_category' );



// Register Non-Hierarchical Taxonomy: Book Tag
function wp_book_register_taxonomy_tag() {
    $labels = array(
        'name'                       => __( 'Book Tags', 'wp-book' ),
        'singular_name'              => __( 'Book Tag', 'wp-book' ),
        'search_items'               => __( 'Search Book Tags', 'wp-book' ),
        'popular_items'              => __( 'Popular Book Tags', 'wp-book' ),
        'all_items'                  => __( 'All Book Tags', 'wp-book' ),
        'edit_item'                  => __( 'Edit Tag', 'wp-book' ),
        'update_item'                => __( 'Update Tag', 'wp-book' ),
        'add_new_item'               => __( 'Add New Tag', 'wp-book' ),
        'new_item_name'              => __( 'New Tag Name', 'wp-book' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'wp-book' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'wp-book' ),
        'choose_from_most_used'      => __( 'Choose from the most used tags', 'wp-book' ),
        'menu_name'                  => __( 'Book Tags', 'wp-book' ),
    );

    $args = array(
        'hierarchical'          => false, // NON-HIERARCHICAL = like tags
        'labels'                => $labels,
        'show_ui'               => true,
        'show_in_rest'          => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'rewrite'               => array( 'slug' => 'book-tag' ),
    );

    register_taxonomy( 'book_tag', 'book', $args );
}
add_action( 'init', 'wp_book_register_taxonomy_tag' );

