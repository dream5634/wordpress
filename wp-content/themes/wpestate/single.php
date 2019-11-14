<?php
// Sigle - Blog post
// Wp Estate Pack
get_header();
$wpestate_options=wpestate_page_details($post->ID); 
global $more;
$more = 0;
?>

<div id="post" <?php post_class('row');?>>
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php print esc_html($wpestate_options['content_class']);?> single_width_blog">
        <?php get_template_part('templates/ajax_container'); ?>
        <?php 
        while ( have_posts() ) : the_post();
        
        if (has_post_thumbnail()){
            $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'property_full_map');
        }
        ?>

        <div class="single-content single_width_blog single-blog">
     
            <div class="wrapper_content">
                <?php
                if (esc_html( get_post_meta($post->ID, 'post_show_title', true) ) != 'no') { ?> 
                    <h1 class="entry-title single-title" ><?php the_title(); ?></h1>
                    <div class="meta-element author-meta"><?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?> <?php esc_html_e(' by ', 'wpestate'); print ' '.get_the_author().' ';esc_html_e('on', 'wpestate') ; print' '.the_date('', '', '', FALSE); ?></div>
                <?php 
                } 

                $full_img        = get_the_post_thumbnail_url($post->ID, 'listing_full_slider_1');
                $defaults = array(
                    'echo'   => false,
                );
                if($full_img!=''){
                ?>
                    <img  src="<?php echo esc_url($full_img);?>" alt="<?php print the_title_attribute($defaults); ?>" class="img-responsive" />
                <?php
                }
                ?>

                <div class="meta-info"> 
                    <div class="meta-element"> <?php print '<i class="fa fa-file-o"></i> '; the_category(', ')?></div>
                    <?php print '<div class="post-no-comments"> '.esc_html__('Comments','wpestate').':'; comments_number( '0', '1' );  ?>   </div> 
            </div>
    
            
            
            <?php 
                the_content('Continue Reading');                     
                $args = array(
                           'before'           => '<p>' . esc_html__('Pages:','wpestate'),
                           'after'            => '</p>',
                           'link_before'      => '',
                           'link_after'       => '',
                           'next_or_number'   => 'number',
                           'nextpagelink'     => esc_html__('Next page','wpestate'),
                           'previouspagelink' => esc_html__('Previous page','wpestate'),
                           'pagelink'         => '%',
                           'echo'             => 1
                  ); 
                wp_link_pages( $args ); 

            ?>
       

 
            <div class="prop_social_single"> <span> <?php esc_html_e('Share', 'wpestate');?></span>

                <a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_facebook"><div class="social-facebook"></div></a>
                <a href="http://twitter.com/home?status=<?php echo urlencode(get_the_title() .' '. esc_url ( get_permalink())); ?>" class="share_tweet" target="_blank"><div class="social-twitter"></div></a>
                <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" class="share_google"><div class="social-google"></div></a> 
                <?php if (isset($pinterest[0])){ ?>
                   <a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo esc_url($pinterest[0]);?>&amp;description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_pinterest"><div class="social-pinterest"></div></a>      
                <?php } ?>


            </div>
        </div>
    </div>
        <!-- #related posts start-->    
        <?php  get_template_part('templates/related_posts');?> 
        <!-- #end related posts -->   
        
        <!-- #comments start-->
        <?php if ( get_comments_number(get_the_ID() ) !==0 ) :?>
            <div class="wrapper_content"><?php comments_template('', true);?> </div>
        <?php endif; ?>
        <!-- end comments -->   
        
        <?php endwhile; // end of the loop. ?>
    </div>
       
<?php  include(get_theme_file_path('sidebar.php'));  ?>
</div>   

<?php get_footer(); ?>