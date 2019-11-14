<?php get_header(); ?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <?php if (have_posts()) :
                    echo '<div class="row" data-elementor-columns="3">';
                    while (have_posts()) : the_post();
                        get_template_part('template-parts/property/content', 'property');
                    endwhile;
                    echo '</div>';
                    the_posts_pagination(array(
                        'prev_text'          => '<span class="arrow">&larr;</span><span class="screen-reader-text">' . esc_html__( 'Previous', 'maisonco' ) . '</span>',
                        'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'maisonco' ) . '</span><span class="arrow">&rarr;</span>',
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'maisonco' ) . ' </span>',
                    ));
                else :
                    get_template_part('template-parts/post/content', 'none');
                endif; ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php get_sidebar(); ?>
    </div><!-- .wrap -->
<?php get_footer();
