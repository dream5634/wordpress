<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-inner">

        <?php if ('' !== get_the_post_thumbnail() && !get_post_gallery()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('maisonco-featured-image-full'); ?>
                </a>
            </div><!-- .post-thumbnail -->
        <?php endif; ?>

        <div class="post-content">
            <header class="entry-header">
                <?php

                if (is_single()) {
                    the_title('<h1 class="entry-title">', '</h1>');
                } elseif (is_front_page() && is_home()) {
                    the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
                } else {
                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                }
                ?>
            </header><!-- .entry-header -->

            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta">
                    <?php maisonco_posted_on(); ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>

            <?php
            // If not a single post, highlight the gallery.
            if (get_post_gallery()) {
                echo '<div class="entry-gallery">';
                echo get_post_gallery();
                echo '</div>';
            };
            ?>


            <div class="entry-content">

                <?php if (is_single()) : ?>
                    <div class="entry-meta">
                        <?php echo '<span class="post-date">' . esc_html__('On', 'maisonco') . ' ' . maisonco_time_link() . ' </span>' ?>
                        <?php maisonco_posted_on(); ?>
                        <?php maisonco_social_share(); ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>

                <?php
                if (is_single() || !get_post_gallery()) {

                    /* translators: %s: Name of current post */
                    the_content(sprintf(
                        __('Read more<span class="screen-reader-text"> "%s"</span>', 'maisonco'),
                        get_the_title()
                    ));

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'maisonco'),
                        'after' => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after' => '</span>',
                    ));

                };
                ?>

            </div><!-- .entry-content -->
        </div><!-- .postcontent -->

        <?php
        if (is_single()) {
            maisonco_entry_footer();
        }
        ?>
    </div>
</article><!-- #post-## -->
