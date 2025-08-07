<?php
/**
 * The template for displaying single book posts
 *
 * @package WP_Book
 */

get_header();

// Get book meta data
$author    = get_post_meta( get_the_ID(), '_wp_book_author', true );
$price     = get_post_meta( get_the_ID(), '_wp_book_price', true );
$publisher = get_post_meta( get_the_ID(), '_wp_book_publisher', true );
$year      = get_post_meta( get_the_ID(), '_wp_book_year', true );
$edition   = get_post_meta( get_the_ID(), '_wp_book_edition', true );
$url       = get_post_meta( get_the_ID(), '_wp_book_url', true );
$currency  = get_option( 'wp_book_currency', '$' );
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="book-thumbnail">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="book-meta">
                        <?php if ( $author ) : ?>
                            <p><strong><?php esc_html_e( 'Author:', 'wp-book' ); ?></strong> <?php echo esc_html( $author ); ?></p>
                        <?php endif; ?>

                        <?php if ( $price ) : ?>
                            <p><strong><?php esc_html_e( 'Price:', 'wp-book' ); ?></strong> <?php echo esc_html( $currency . ' ' . number_format( (float) $price, 2 ) ); ?></p>
                        <?php endif; ?>

                        <?php if ( $publisher ) : ?>
                            <p><strong><?php esc_html_e( 'Publisher:', 'wp-book' ); ?></strong> <?php echo esc_html( $publisher ); ?></p>
                        <?php endif; ?>

                        <?php if ( $year ) : ?>
                            <p><strong><?php esc_html_e( 'Year:', 'wp-book' ); ?></strong> <?php echo esc_html( $year ); ?></p>
                        <?php endif; ?>

                        <?php if ( $edition ) : ?>
                            <p><strong><?php esc_html_e( 'Edition:', 'wp-book' ); ?></strong> <?php echo esc_html( $edition ); ?></p>
                        <?php endif; ?>

                        <?php if ( $url ) : ?>
                            <p><strong><?php esc_html_e( 'More Info:', 'wp-book' ); ?></strong> 
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                                    <?php esc_html_e( 'View Book', 'wp-book' ); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="book-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="book-taxonomies">
                        <?php
                        // Display categories
                        $categories = get_the_terms( get_the_ID(), 'book_category' );
                        if ( $categories && ! is_wp_error( $categories ) ) {
                            echo '<div class="book-categories">';
                            echo '<strong>' . esc_html__( 'Categories:', 'wp-book' ) . '</strong> ';
                            $category_links = array();
                            foreach ( $categories as $category ) {
                                $category_links[] = '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
                            }
                            echo implode( ', ', $category_links );
                            echo '</div>';
                        }

                        // Display tags
                        $tags = get_the_terms( get_the_ID(), 'book_tag' );
                        if ( $tags && ! is_wp_error( $tags ) ) {
                            echo '<div class="book-tags">';
                            echo '<strong>' . esc_html__( 'Tags:', 'wp-book' ) . '</strong> ';
                            $tag_links = array();
                            foreach ( $tags as $tag ) {
                                $tag_links[] = '<a href="' . esc_url( get_term_link( $tag ) ) . '">' . esc_html( $tag->name ) . '</a>';
                            }
                            echo implode( ', ', $tag_links );
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <footer class="entry-footer">
                    <?php
                    // Display edit post link for admins
                    edit_post_link(
                        sprintf(
                            wp_kses(
                                /* translators: %s: Name of current post */
                                __( 'Edit <span class="screen-reader-text">%s</span>', 'wp-book' ),
                                array(
                                    'span' => array(
                                        'class' => array(),
                                    ),
                                )
                            ),
                            wp_kses_post( get_the_title() )
                        ),
                        '<span class="edit-link">',
                        '</span>'
                    );
                    ?>
                </footer>
            </article>
            <?php
        endwhile; // End of the loop.
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
