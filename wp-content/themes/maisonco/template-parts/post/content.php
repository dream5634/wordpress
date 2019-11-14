<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="post-inner">

        <?php if ('' !== get_the_post_thumbnail()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('maisonco-featured-image-full'); ?>
                </a>
                <?php
                if (!is_single()) { ?>
                    <span class="posted-on"> <?php echo maisonco_time_link() ?> </span>
                <?php } ?>
            </div><!-- .post-thumbnail -->
        <?php endif; ?>

        <div class="post-content">
            <header class="entry-header">

                <?php

                if (is_single()) {
                } elseif (is_front_page() && is_home()) {
                    the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
                } else {
                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                }
                ?>

            </header><!-- .entry-header -->

            <div class="entry-content">

                <?php if (is_single()) : ?>
                    <div class="entry-meta">
                        <?php echo '<span class="post-date">' . esc_html__('On', 'maisonco') . ' ' . maisonco_time_link() . ' </span>' ?>
                        <?php maisonco_posted_on(); ?>
                        <?php maisonco_social_share(); ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>

                <div class="content-boxed 1">
                    <?php
                    /* translators: %s: Name of current post */
                    the_content('');


                    wp_link_pages(array(
                        'before'      => '<div class="page-links">' . esc_html__('Pages:', 'maisonco'),
                        'after'       => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    ));

                    ?>
                </div>

            </div><!-- .entry-content -->

            <?php if ('post' === get_post_type() && !is_single()) : ?>
                <div class="entry-meta">
                    <?php maisonco_posted_on(); ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>

        </div><!-- .post-content -->

        <?php
        if (is_single()) {
            maisonco_entry_footer();
        }
        ?>
    </div>

</article><!-- #post-## -->