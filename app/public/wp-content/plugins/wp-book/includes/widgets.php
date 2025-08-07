<?php
/**
 * Register and load the widget
 */
function wp_book_load_widget() {
    if (!class_exists('WP_Book_Widget')) {
        error_log('WP Book Widget: WP_Book_Widget class not found');
        return;
    }
    
    // Debug: Check if we can register the widget
    if (!function_exists('register_widget')) {
        error_log('WP Book Widget: register_widget function not available');
        return;
    }
    
    $result = register_widget('WP_Book_Widget');
    
    // Debug: Check if registration was successful
    if (is_wp_error($result)) {
        error_log('WP Book Widget registration failed: ' . $result->get_error_message());
    } else {
        error_log('WP Book Widget registered successfully');
    }
}
add_action('widgets_init', 'wp_book_load_widget', 20);

/**
 * Book Widget Class
 */
class WP_Book_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'wp_book_widget',
            __('Book Category Widget', 'wp-book'),
            array('description' => __('Display books from a specific category', 'wp-book'))
        );
    }

    /**
     * Widget front-end display
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_author = !empty($instance['show_author']) ? true : false;
        $show_price = !empty($instance['show_price']) ? true : false;
        $currency = get_option('wp_book_currency', '$');

        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        // Query arguments
        $query_args = array(
            'post_type' => 'book',
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'tax_query' => array()
        );

        // Add category filter if selected
        if (!empty($category)) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'book_category',
                'field' => 'term_id',
                'terms' => $category
            );
        }

        // Run the query
        $books_query = new WP_Query($query_args);

        if ($books_query->have_posts()) {
            echo '<ul class="wp-book-widget-list">';
            
            while ($books_query->have_posts()) {
                $books_query->the_post();
                $book_id = get_the_ID();
                
                echo '<li class="wp-book-widget-item">';
                echo '<a href="' . get_permalink() . '" class="book-widget-title">' . get_the_title() . '</a>';
                
                if ($show_author || $show_price) {
                    echo '<div class="book-widget-meta">';
                    
                    if ($show_author) {
                        $author = get_post_meta($book_id, '_wp_book_author', true);
                        if ($author) {
                            echo '<span class="book-widget-author">' . esc_html($author) . '</span>';
                        }
                    }
                    
                    if ($show_price) {
                        $price = get_post_meta($book_id, '_wp_book_price', true);
                        if ($price) {
                            echo '<span class="book-widget-price">' . esc_html($currency . $price) . '</span>';
                        }
                    }
                    
                    echo '</div>'; // .book-widget-meta
                }
                
                echo '</li>';
            }
            
            echo '</ul>';
            
            // Reset post data
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No books found.', 'wp-book') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget backend form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_author = !empty($instance['show_author']) ? true : false;
        $show_price = !empty($instance['show_price']) ? true : false;
        
        // Get all book categories
        $categories = get_terms(array(
            'taxonomy' => 'book_category',
            'hide_empty' => false,
        ));
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'wp-book'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>">
                <?php esc_html_e('Select Category:', 'wp-book'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('category')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('category')); ?>">
                <option value=""><?php esc_html_e('All Categories', 'wp-book'); ?></option>
                <?php
                if (!empty($categories) && !is_wp_error($categories)) {
                    foreach ($categories as $cat) {
                        echo '<option value="' . esc_attr($cat->term_id) . '" ' . 
                             selected($category, $cat->term_id, false) . '>' . 
                             esc_html($cat->name) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>">
                <?php esc_html_e('Number of books to show:', 'wp-book'); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('number')); ?>" 
                   type="number" 
                   step="1" 
                   min="1" 
                   value="<?php echo esc_attr($number); ?>" 
                   size="3">
        </p>
        
        <p>
            <input class="checkbox" 
                   type="checkbox" 
                   id="<?php echo esc_attr($this->get_field_id('show_author')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" 
                   <?php checked($show_author, true); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>">
                <?php esc_html_e('Display author name?', 'wp-book'); ?>
            </label>
        </p>
        
        <p>
            <input class="checkbox" 
                   type="checkbox" 
                   id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_price')); ?>" 
                   <?php checked($show_price, true); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>">
                <?php esc_html_e('Display price?', 'wp-book'); ?>
            </label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['category'] = (!empty($new_instance['category'])) ? absint($new_instance['category']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        $instance['show_author'] = !empty($new_instance['show_author']);
        $instance['show_price'] = !empty($new_instance['show_price']);
        
        return $instance;
    }
}
