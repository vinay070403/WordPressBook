<?php
defined('ABSPATH') or die('No script kiddies please!');

function wp_book_create_custom_meta_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'book_meta';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        book_id BIGINT(20) UNSIGNED NOT NULL,
        author VARCHAR(255) DEFAULT '',
        price DECIMAL(10,2) DEFAULT 0.00,
        publisher VARCHAR(255) DEFAULT '',
        year INT DEFAULT 0,
        edition VARCHAR(100) DEFAULT '',
        url VARCHAR(255) DEFAULT '',
        PRIMARY KEY  (book_id),
        FOREIGN KEY (book_id) REFERENCES {$wpdb->prefix}posts(ID) ON DELETE CASCADE
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
