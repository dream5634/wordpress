<div class="column-item post-style-2">
    <div class="post-inner">
        <?php if (has_post_thumbnail() && '' !== get_the_post_thumbnail()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('maisonco-featured-image-large'); ?>
                </a>
                <span class="posted-on"> <?php echo maisonco_time_link() ?> </span>
            </div><!-- .post-thumbnail -->
        <?php endif; ?>
        <div class="post-content">
            <div class="entry-header">
                <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            </div>
            <div class="entry-meta ">
                <div class="entry-meta-inner ">
                    <?php maisonco_posted_on(); ?>
                </div>
            </div>

        </div>
    </div>
</div>