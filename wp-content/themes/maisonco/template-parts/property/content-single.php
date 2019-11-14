<article id="post-<?php the_ID(); ?>" <?php post_class('osf-property-single'); ?>>
    <div class="post-inner">
        <?php
        $gallery = osf_get_metabox(get_the_ID(), 'osf_apartment_gallery');
        if ($gallery) {
            echo '<div data-opal-carousel data-items="1" data-tablet="1" class="apartment-gallery owl-carousel owl-theme">';
            foreach ((array)$gallery as $attachment_id => $attachment_url) {
                echo '<div class="item">' . wp_get_attachment_image($attachment_id, 'full') . '</div>';
            }
            echo '</div>';
        } else {
            if (has_post_thumbnail()) : ?>
                <div class="property-thumbnail">
                    <?php the_post_thumbnail('full'); ?>
                </div><!-- .post-thumbnail -->
            <?php endif;
        } ?>
        <div class="entry-content">
            <?php the_content(); ?>
        </div><!-- .entry-content -->

    </div>

</article><!-- #post-## -->