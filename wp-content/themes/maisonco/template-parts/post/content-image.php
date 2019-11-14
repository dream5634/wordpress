<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-inner">

        <?php if ('' !== get_the_post_thumbnail() && !is_single()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('maisonco-featured-image-full'); ?>
                </a>
                <span class="posted-on"> <?php echo maisonco_time_link() ?> </span>
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

            <div class="entry-content">

                <?php if (is_single()) : ?>
                    <div class="entry-meta">
                        <?php echo '<span class="post-date">' . esc_html__('On', 'maisonco') . ' ' . maisonco_time_link() . ' </span>' ?>
                        <?php maisonco_posted_on(); ?>
                        <?php maisonco_social_share(); ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>

                <?php

                /* translators: %s: Name of current post */
                the_content('');

                if (is_single() || '' === get_the_post_thumbnail()) {
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'maisonco'),
                        'after' => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after' => '</span>',
                    ));

                };
                ?>
            </div><!-- .entry-content -->

            <?php if ('post' === get_post_type() && !is_single()) : ?>
                <div class="entry-meta">
                    <?php maisonco_posted_on(); ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>

        </div><!-- .post-content -->
        <?php if (is_single()) : ?>
            <div class="author-post">
                <div class="author-post-header text-center">
                    <?php echo get_avatar(get_the_author_meta('email'), 70) ?>
                    <span>About the author</span>
                    <h4 class="author-title">William Smith</h4>
                </div>
                <div class="description">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </div>
            </div>
        <?php endif; ?>
        <?php
        if (is_single()) {
            maisonco_entry_footer();
        }
        ?>
    </div>

</article><!-- #post-## -->
