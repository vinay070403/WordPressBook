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

// Include all required files
require_once plugin_dir_path(__FILE__) . 'includes/install.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/widgets.php';
require_once plugin_dir_path(__FILE__) . 'includes/dashboard-widget.php';

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
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'books' ),
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'show_in_rest'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-book-alt',
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'capability_type'     => 'post',
        'hierarchical'        => false,
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

// Display Book Categories and Tags after book content
function wp_book_append_taxonomies_to_content( $content ) {
    if ( is_singular( 'book' ) && in_the_loop() && is_main_query() ) {
        $categories = get_the_term_list( get_the_ID(), 'book_category', '<strong>Category:</strong> ', ', ' );
        $tags       = get_the_term_list( get_the_ID(), 'book_tag', '<strong>Tags:</strong> ', ', ' );

        $taxonomy_output = '<div class="book-taxonomies">';
        if ( $categories ) {
            $taxonomy_output .= '<p>' . $categories . '</p>';
        }
        if ( $tags ) {
            $taxonomy_output .= '<p>' . $tags . '</p>';
        }
        $taxonomy_output .= '</div>';

        $content .= $taxonomy_output;
    }

    return $content;
}
add_filter( 'the_content', 'wp_book_append_taxonomies_to_content' );

// Include meta box file
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';

// Include settings page
require_once plugin_dir_path( __FILE__ ) . 'includes/settings-page.php';

// Register activation and uninstall hooks
register_activation_hook(__FILE__, 'wp_book_activate');
register_uninstall_hook(__FILE__, 'wp_book_uninstall');

/**
 * Plugin activation function
 */
function wp_book_activate() {
    // Create custom meta table
    wp_book_create_custom_meta_table();
    
    // Flush rewrite rules for custom post type and taxonomies
    flush_rewrite_rules();
}

/**
 * Plugin uninstallation function
 */
function wp_book_uninstall() {
    global $wpdb;
    
    // Delete plugin options
    delete_option('wp_book_currency');
    delete_option('wp_book_per_page');
    
    // Delete all book meta
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '\_wp_book\_%'");
    
    // Delete the custom table
    $table_name = $wpdb->prefix . 'book_meta';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    
    // Clear any cached data that might be related to our plugin
    wp_cache_flush();
}

