<div class="column-item post-style-3">
    <div class="post-inner">
        <div class="post-content">
            <span class="posted-on"> <?php echo maisonco_time_link() ?> </span>

            <div class="entry-header">
                <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            </div>

            <div class="entry-content">
                <?php echo wp_trim_words(get_the_content(), 20); ?>
            </div>

            <div class="entry-meta ">
                <div class="entry-meta-inner ">
                    <?php maisonco_posted_on(); ?>
                </div>
            </div>
        </div>
    </div>
</div>