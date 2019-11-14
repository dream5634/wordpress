<?php
get_header('404'); ?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <?php if (get_theme_mod('osf_page_404_page_enable') != 'default' && !empty(get_theme_mod('osf_page_404_page_custom'))): ?>
                    <?php $query = new WP_Query('page_id=' . get_theme_mod('osf_page_404_page_custom'));
                    if ($query->have_posts()):
                        while ($query->have_posts()) : $query->the_post();
                            the_content();
                        endwhile;
                    endif; ?>
                <?php else: ?>
                    <section class="error-404 not-found">
                        <div class="page-content">
                            <div class="svg-bkg">
                                <img src="<?php echo get_theme_file_uri('assets/images/404.png') ?>">
                            </div>
                            <div class="error-404-title">
                                <h1 class="p-0 m-0"><?php esc_html_e('404', 'maisonco'); ?></h1>
                                <div class="error-404-subtitle">
                                    <h2 class="sub-h2-1 p-0 m-0"><?php esc_html_e('OOPS...', 'maisonco'); ?></h2>
                                    <h2 class="sub-h2-2 p-0 m-0"><?php esc_html_e('Page not found', 'maisonco'); ?></h2>
                                </div>
                            </div>
                            <div class="error-text">
                                <?php esc_html_e('Oops! The page you are looking for does not exist.', 'maisonco'); ?>
                                <br>
                                <?php esc_html_e('It might have been moved or deleted.', 'maisonco'); ?>
                            </div>
                            <div class="back-home">
                                <a href="<?php echo esc_url(home_url('/')); ?>"
                                   class="return-homepage"><?php esc_html_e('back to home', 'maisonco'); ?></a>
                            </div>
                        </div>
                    </section><!-- .error-404 -->
                <?php endif; ?>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
