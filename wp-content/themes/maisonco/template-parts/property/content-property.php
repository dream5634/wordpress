<?php
if (!defined('ABSPATH')) {
    exit;
}

if (has_post_thumbnail()) {
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'maisonco-featured-image-large');
} else {
    $thumbnail_url = maisonco_get_placeholder_image();
}

?>
<article  <?php post_class('osf-property-article column-item'); ?>">
<div class="osf-property-article-inner">
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('maisonco-featured-image-large'); ?>
        </a>
    </div><!-- .post-thumbnail -->
    <div class="entry-content">
        <?php the_title('<h3 class="property-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
        <div class="link-more">
            <a href="<?php the_permalink(); ?>"><?php esc_html_e('Explore', 'maisonco'); ?></a>
        </div>
    </div>
</div>
</article>
<!-- #post-## -->

