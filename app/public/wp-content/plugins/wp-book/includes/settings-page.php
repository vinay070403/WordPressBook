<?php
// Add settings submenu under Books
function wp_book_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=book', // Parent slug
        __( 'Book Settings', 'wp-book' ),
        __( 'Settings', 'wp-book' ),
        'manage_options',
        'wp-book-settings',
        'wp_book_settings_page_html'
    );
}
add_action( 'admin_menu', 'wp_book_settings_menu' );

// Register settings
function wp_book_register_settings() {
    register_setting( 'wp_book_settings_group', 'wp_book_currency' );
    register_setting( 'wp_book_settings_group', 'wp_book_per_page' );

    add_settings_section(
        'wp_book_main_section',
        __( 'General Settings', 'wp-book' ),
        null,
        'wp-book-settings'
    );

    add_settings_field(
        'wp_book_currency',
        __( 'Currency Symbol', 'wp-book' ),
        'wp_book_currency_field_html',
        'wp-book-settings',
        'wp_book_main_section'
    );

    add_settings_field(
        'wp_book_per_page',
        __( 'Books Per Page', 'wp-book' ),
        'wp_book_per_page_field_html',
        'wp-book-settings',
        'wp_book_main_section'
    );
}
add_action( 'admin_init', 'wp_book_register_settings' );

// Field: Currency
function wp_book_currency_field_html() {
    echo '<input type="text" name="wp_book_currency" value="' . esc_attr( get_option('wp_book_currency', '$') ) . '" />';
    echo '<p class="description">' . __('Enter the currency symbol (e.g., $, €, ₹)', 'wp-book') . '</p>';
}

// Field: Books per Page
function wp_book_per_page_field_html() {
    echo '<input type="number" name="wp_book_per_page" min="1" value="' . esc_attr( get_option('wp_book_per_page', 10) ) . '" />';
    echo '<p class="description">' . __('Number of books to display per page', 'wp-book') . '</p>';
}

// Settings page HTML
function wp_book_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wp_book_settings_group');
            do_settings_sections('wp-book-settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}
