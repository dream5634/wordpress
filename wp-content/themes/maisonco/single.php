<?php
get_header(); ?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">

                <?php
                /* Start the Loop */
                while (have_posts()) : the_post();

                    get_template_part('template-parts/post/content', get_post_format());

                    get_template_part('template-parts/common/author-bio', get_post_format());

                    if (is_active_sidebar('sidebar-single-post') || (!class_exists('MaisonCoCore') && is_active_sidebar('sidebar-blog'))) {
                        maisonco_fnc_related_post(2);
                    } else {
                        maisonco_fnc_related_post(3);
                    }
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;


                endwhile; // End of the loop.
                ?>

            </main> <!-- #main -->
        </div> <!-- #primary -->
        <?php get_sidebar(); ?>
    </div><!-- .wrap -->

<?php get_footer();
