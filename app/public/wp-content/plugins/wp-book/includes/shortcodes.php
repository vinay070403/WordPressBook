<?php
/**
 * Shortcode for displaying books
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function wp_book_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(array(
        'id' => '',
        'author_name' => '',
        'year' => '',
        'category' => '',
        'tag' => '',
        'publisher' => ''
    ), $atts, 'book');

    // Build query args
    $args = array(
        'post_type' => 'book',
        'posts_per_page' => get_option('wp_book_per_page', 10),
        'meta_query' => array('relation' => 'AND'),
        'tax_query' => array('relation' => 'AND')
    );

    // Filter by ID
    if (!empty($atts['id'])) {
        $args['post__in'] = array_map('intval', explode(',', $atts['id']));
    }

    // Filter by author name
    if (!empty($atts['author_name'])) {
        $args['meta_query'][] = array(
            'key' => '_wp_book_author',
            'value' => sanitize_text_field($atts['author_name']),
            'compare' => 'LIKE'
        );
    }

    // Filter by year
    if (!empty($atts['year'])) {
        $args['meta_query'][] = array(
            'key' => '_wp_book_year',
            'value' => intval($atts['year']),
            'compare' => '='
        );
    }

    // Filter by publisher
    if (!empty($atts['publisher'])) {
        $args['meta_query'][] = array(
            'key' => '_wp_book_publisher',
            'value' => sanitize_text_field($atts['publisher']),
            'compare' => 'LIKE'
        );
    }

    // Filter by category
    if (!empty($atts['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'book_category',
            'field' => 'slug',
            'terms' => sanitize_text_field($atts['category'])
        );
    }

    // Filter by tag
    if (!empty($atts['tag'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'book_tag',
            'field' => 'slug',
            'terms' => sanitize_text_field($atts['tag'])
        );
    }

    // Run the query
    $books_query = new WP_Query($args);
    $output = '';
    $currency = get_option('wp_book_currency', '$');

    if ($books_query->have_posts()) {
        $output .= '<div class="wp-book-shortcode">';
        
        while ($books_query->have_posts()) {
            $books_query->the_post();
            $book_id = get_the_ID();
            
            $output .= '<div class="book-item">';
            $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            // Display featured image if available
            if (has_post_thumbnail()) {
                $output .= '<div class="book-thumbnail">' . get_the_post_thumbnail(null, 'medium') . '</div>';
            }
            
            // Display book meta
            $author = get_post_meta($book_id, '_wp_book_author', true);
            $price = get_post_meta($book_id, '_wp_book_price', true);
            $publisher = get_post_meta($book_id, '_wp_book_publisher', true);
            $year = get_post_meta($book_id, '_wp_book_year', true);
            $edition = get_post_meta($book_id, '_wp_book_edition', true);
            $url = get_post_meta($book_id, '_wp_book_url', true);
            
            $output .= '<div class="book-meta">';
            if ($author) $output .= '<p><strong>' . __('Author:', 'wp-book') . '</strong> ' . esc_html($author) . '</p>';
            if ($price) $output .= '<p><strong>' . __('Price:', 'wp-book') . '</strong> ' . esc_html($currency . $price) . '</p>';
            if ($publisher) $output .= '<p><strong>' . __('Publisher:', 'wp-book') . '</strong> ' . esc_html($publisher) . '</p>';
            if ($year) $output .= '<p><strong>' . __('Year:', 'wp-book') . '</strong> ' . esc_html($year) . '</p>';
            if ($edition) $output .= '<p><strong>' . __('Edition:', 'wp-book') . '</strong> ' . esc_html($edition) . '</p>';
            if ($url) $output .= '<p><a href="' . esc_url($url) . '" target="_blank">' . __('View Book', 'wp-book') . '</a></p>';
            
            // Display categories and tags
            $categories = get_the_terms($book_id, 'book_category');
            if ($categories && !is_wp_error($categories)) {
                $category_links = array();
                foreach ($categories as $category) {
                    $category_links[] = '<a href="' . get_term_link($category) . '">' . $category->name . '</a>';
                }
                $output .= '<p><strong>' . _n('Category:', 'Categories:', count($categories), 'wp-book') . '</strong> ' . implode(', ', $category_links) . '</p>';
            }
            
            $tags = get_the_terms($book_id, 'book_tag');
            if ($tags && !is_wp_error($tags)) {
                $tag_links = array();
                foreach ($tags as $tag) {
                    $tag_links[] = '<a href="' . get_term_link($tag) . '">' . $tag->name . '</a>';
                }
                $output .= '<p><strong>' . _n('Tag:', 'Tags:', count($tags), 'wp-book') . '</strong> ' . implode(', ', $tag_links) . '</p>';
            }
            
            $output .= '</div>'; // .book-meta
            $output .= '</div>'; // .book-item
            $output .= '<hr>';
        }
        
        // Add pagination if needed
        $output .= '<div class="book-pagination">';
        $output .= paginate_links(array(
            'total' => $books_query->max_num_pages,
            'current' => max(1, get_query_var('paged'))
        ));
        $output .= '</div>';
        
        $output .= '</div>'; // .wp-book-shortcode
        
        // Reset post data
        wp_reset_postdata();
    } else {
        $output = '<p>' . __('No books found.', 'wp-book') . '</p>';
    }
    
    return $output;
}
add_shortcode('book', 'wp_book_shortcode');
