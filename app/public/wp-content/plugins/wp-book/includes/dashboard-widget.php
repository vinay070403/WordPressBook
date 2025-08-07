<?php
/**
 * Add dashboard widget for top book categories
 */
function wp_book_add_dashboard_widget() {
    // Add debug log
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('WP Book: Attempting to add dashboard widget');
    }
    
    wp_add_dashboard_widget(
        'wp_book_dashboard_widget',
        '📚 ' . __('Top 5 Book Categories', 'wp-book'),
        'wp_book_dashboard_widget_render',
        null,
        null,
        'normal',
        'high' // High priority to put it at the top
    );
}
add_action('wp_dashboard_setup', 'wp_book_add_dashboard_widget');

/**
 * Render the dashboard widget content
 */
function wp_book_dashboard_widget_render() {
    // Add debug log
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('WP Book: Rendering dashboard widget');
    }

    // Get top 5 book categories by count
    $categories = get_terms(array(
        'taxonomy' => 'book_category',
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 5,
        'hide_empty' => true,
    ));

    if (empty($categories) || is_wp_error($categories)) {
        echo '<div class="notice notice-warning">';
        echo '<p>' . esc_html__('No book categories found. Add some books and categories first!', 'wp-book') . '</p>';
        
        // Add helpful links
        echo '<p>';
        echo '<a href="' . admin_url('post-new.php?post_type=book') . '" class="button button-primary">' . __('Add New Book', 'wp-book') . '</a> ';
        echo '<a href="' . admin_url('edit-tags.php?taxonomy=book_category&post_type=book') . '" class="button">' . __('Manage Categories', 'wp-book') . '</a>';
        echo '</p>';
        
        echo '</div>';
        return;
    }

    echo '<div class="wp-book-dashboard-widget">';
    echo '<div class="wp-book-widget-header">';
    echo '<p>' . __('Here are your top book categories:', 'wp-book') . '</p>';
    echo '</div>';
    
    echo '<ul class="wp-book-category-list">';
    
    foreach ($categories as $index => $category) {
        $category_link = get_edit_term_link($category->term_id, 'book_category', 'book');
        $book_count = $category->count;
        
        // Add a medal emoji for top 3 categories
        $medal = '';
        if ($index === 0) $medal = '🥇 ';
        elseif ($index === 1) $medal = '🥈 ';
        elseif ($index === 2) $medal = '🥉 ';
        
        echo '<li class="wp-book-category-item">';
        echo '<a href="' . esc_url($category_link) . '" class="wp-book-category-link">';
        echo '<span class="wp-book-category-medal">' . $medal . '</span>';
        echo '<span class="wp-book-category-name">' . esc_html($category->name) . '</span>';
        echo ' <span class="wp-book-category-count">(' . number_format_i18n($book_count) . ' ' . _n('book', 'books', $book_count, 'wp-book') . ')</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    
    // Add a link to manage all book categories
    $manage_categories_url = admin_url('edit-tags.php?taxonomy=book_category&post_type=book');
    $add_new_book_url = admin_url('post-new.php?post_type=book');
    
    echo '<div class="wp-book-dashboard-footer">';
    echo '<a href="' . esc_url($add_new_book_url) . '" class="button button-primary">' . esc_html__('Add New Book', 'wp-book') . '</a> ';
    echo '<a href="' . esc_url($manage_categories_url) . '" class="button">' . esc_html__('Manage All Categories', 'wp-book') . '</a>';
    echo '</div>';
    
    echo '</div>'; // .wp-book-dashboard-widget
    
    // Add some nice inline styles
    ?>
    <style>
        .wp-book-dashboard-widget {
            font-size: 14px;
            line-height: 1.6;
        }
        .wp-book-widget-header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .wp-book-category-list {
            margin: 0 0 15px 0;
            padding: 0;
            list-style: none;
        }
        .wp-book-category-item {
            padding: 10px 0;
            margin: 0;
            border-bottom: 1px solid #f0f0f1;
            display: flex;
            align-items: center;
        }
        .wp-book-category-item:last-child {
            border-bottom: none;
        }
        .wp-book-category-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #1d2327;
            width: 100%;
            transition: all 0.2s ease;
            padding: 5px 0;
        }
        .wp-book-category-link:hover {
            color: #2271b1;
        }
        .wp-book-category-medal {
            margin-right: 8px;
            font-size: 16px;
            width: 24px;
            text-align: center;
        }
        .wp-book-category-name {
            flex-grow: 1;
            font-weight: 500;
        }
        .wp-book-category-count {
            background: #f0f0f1;
            color: #50575e;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: normal;
        }
        .wp-book-dashboard-footer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f1;
            display: flex;
            gap: 8px;
        }
        .wp-book-dashboard-footer .button {
            font-size: 13px;
            line-height: 1.5;
            height: auto;
            padding: 4px 12px;
        }
    </style>
    <?php
}
