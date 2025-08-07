<?php
/**
 * Add dashboard widget for top book categories
 */
function wp_book_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'wp_book_dashboard_widget',
        __('Top 5 Book Categories', 'wp-book'),
        'wp_book_dashboard_widget_render'
    );
}
add_action('wp_dashboard_setup', 'wp_book_add_dashboard_widget');

/**
 * Render the dashboard widget content
 */
function wp_book_dashboard_widget_render() {
    // Get top 5 book categories by count
    $categories = get_terms(array(
        'taxonomy' => 'book_category',
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 5,
        'hide_empty' => true,
    ));

    if (empty($categories) || is_wp_error($categories)) {
        echo '<p>' . esc_html__('No book categories found.', 'wp-book') . '</p>';
        return;
    }

    echo '<div class="wp-book-dashboard-widget">';
    echo '<ul class="wp-book-category-list">';
    
    foreach ($categories as $category) {
        $category_link = get_edit_term_link($category->term_id, 'book_category', 'book');
        $book_count = $category->count;
        
        echo '<li class="wp-book-category-item">';
        echo '<a href="' . esc_url($category_link) . '" class="wp-book-category-link">';
        echo '<span class="wp-book-category-name">' . esc_html($category->name) . '</span>';
        echo ' <span class="wp-book-category-count">(' . number_format_i18n($book_count) . ' ' . _n('book', 'books', $book_count, 'wp-book') . ')</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    
    // Add a link to manage all book categories
    $manage_categories_url = admin_url('edit-tags.php?taxonomy=book_category&post_type=book');
    echo '<p class="wp-book-dashboard-footer">';
    echo '<a href="' . esc_url($manage_categories_url) . '">' . esc_html__('Manage all book categories', 'wp-book') . ' &rarr;</a>';
    echo '</p>';
    
    echo '</div>'; // .wp-book-dashboard-widget
    
    // Add some basic inline styles
    ?>
    <style>
        .wp-book-category-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .wp-book-category-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .wp-book-category-item:last-child {
            border-bottom: none;
        }
        .wp-book-category-link {
            display: block;
            text-decoration: none;
            color: #2271b1;
        }
        .wp-book-category-link:hover {
            color: #135e96;
        }
        .wp-book-category-count {
            color: #646970;
            font-size: 0.9em;
        }
        .wp-book-dashboard-footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
    </style>
    <?php
}
