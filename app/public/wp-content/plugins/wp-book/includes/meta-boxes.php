<?php
// Add Meta Box
function wp_book_add_meta_boxes() {
    add_meta_box(
        'wp_book_details_meta_box',
        __( 'Book Details', 'wp-book' ),
        'wp_book_display_meta_box',
        'book',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wp_book_add_meta_boxes' );

// Meta Box HTML
function wp_book_display_meta_box( $post ) {
    $author    = get_post_meta( $post->ID, '_wp_book_author', true );
    $price     = get_post_meta( $post->ID, '_wp_book_price', true );
    $publisher = get_post_meta( $post->ID, '_wp_book_publisher', true );
    $year      = get_post_meta( $post->ID, '_wp_book_year', true );
    $edition   = get_post_meta( $post->ID, '_wp_book_edition', true );
    $url       = get_post_meta( $post->ID, '_wp_book_url', true );

    wp_nonce_field( 'wp_book_meta_box_nonce', 'wp_book_nonce' );

    ?>
    <p><label><?php _e( 'Author Name:', 'wp-book' ); ?></label><br/>
    <input type="text" name="wp_book_author" value="<?php echo esc_attr( $author ); ?>" class="widefat"/></p>

    <p><label><?php _e( 'Price:', 'wp-book' ); ?></label><br/>
    <input type="number" name="wp_book_price" value="<?php echo esc_attr( $price ); ?>" class="widefat"/></p>

    <p><label><?php _e( 'Publisher:', 'wp-book' ); ?></label><br/>
    <input type="text" name="wp_book_publisher" value="<?php echo esc_attr( $publisher ); ?>" class="widefat"/></p>

    <p><label><?php _e( 'Year:', 'wp-book' ); ?></label><br/>
    <input type="number" name="wp_book_year" value="<?php echo esc_attr( $year ); ?>" class="widefat"/></p>

    <p><label><?php _e( 'Edition:', 'wp-book' ); ?></label><br/>
    <input type="text" name="wp_book_edition" value="<?php echo esc_attr( $edition ); ?>" class="widefat"/></p>

    <p><label><?php _e( 'Book URL:', 'wp-book' ); ?></label><br/>
    <input type="url" name="wp_book_url" value="<?php echo esc_attr( $url ); ?>" class="widefat"/></p>
    <?php
}

// Save Meta Fields
function wp_book_save_meta_boxes( $post_id ) {
    if ( ! isset( $_POST['wp_book_nonce'] ) || ! wp_verify_nonce( $_POST['wp_book_nonce'], 'wp_book_meta_box_nonce' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        'wp_book_author'    => '_wp_book_author',
        'wp_book_price'     => '_wp_book_price',
        'wp_book_publisher' => '_wp_book_publisher',
        'wp_book_year'      => '_wp_book_year',
        'wp_book_edition'   => '_wp_book_edition',
        'wp_book_url'       => '_wp_book_url',
    ];

    foreach ( $fields as $input_name => $meta_key ) {
        if ( isset( $_POST[ $input_name ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $input_name ] ) );
        }
    }
}
add_action( 'save_post', 'wp_book_save_meta_boxes' );
