<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-inner">
        <div class="post-content">
            <header class="entry-header">
                <?php if (is_front_page() && !is_home()) {

                    // The excerpt is being displayed within a front page section, so it's a lower hierarchy than h2.
                    the_title(sprintf('<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                } else {
                    the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
                } ?>

                <?php if ('post' === get_post_type() && !is_single()) : ?>
                    <div class="entry-meta">
                        <?php maisonco_posted_on(); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_single()) : ?>
                    <div class="entry-avatar">
                        <?php echo get_avatar(get_the_author_meta('email'), 38) ?>
                        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"><?php the_author() ?></a>
                    </div><!-- .entry-meta -->
                <?php endif; ?>

            </header><!-- .entry-header -->

            <div class="entry-summary">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
        </div>
    </div>
</article><!-- #post-## -->
