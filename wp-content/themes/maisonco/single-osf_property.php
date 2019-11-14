<?php
get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">

                <?php
                while (have_posts()) : the_post();
                    get_template_part('template-parts/property/content', 'single');
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
            <?php
            $prev_link = maisonco_get_post_link('category', 'osf_story')->previous_post;
            $next_link = maisonco_get_post_link('category', 'osf_story')->next_post;

            if (!empty($prev_link) || !empty($next_link)):
                ?>
                <div class="navigation">
                    <div class="previous-nav">
                        <?php if (!empty($prev_link)): ?>
                            <div class="nav-content">
                                <div class="nav-title"><?php esc_html_e('Previous Post', 'maisonco'); ?></div>
                                <div class="nav-link"><?php echo wp_kses_post($prev_link); ?></div>
                                <?php echo wp_kses_post($prev_link); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="next-nav">
                        <?php if (!empty($next_link)): ?>
                            <div class="nav-content">
                                <div class="nav-title"><?php esc_html_e('Next Post', 'maisonco'); ?></div>
                                <div class="nav-link"><?php echo wp_kses_post($next_link); ?></div>
                                <?php echo wp_kses_post($next_link); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif;?>
        </div><!-- #primary -->
        <?php get_sidebar(); ?>
    </div><!-- .wrap -->


<?php get_footer();
