<!-- begin sidebar -->
<div class="clearfix visible-xs"></div>
<?php 
$sidebar_name   =   $wpestate_options['sidebar_name'];
$sidebar_class  =   $wpestate_options['sidebar_class'];

$local_pgpr_slider_type_status  = '';
if(isset($post->ID)){
    $local_pgpr_slider_type_status  =   get_post_meta($post->ID, 'local_pgpr_slider_type', true);
}
$prpg_slider_type_status        =   esc_html ( get_option('wp_estate_global_prpg_slider_type','') );

 
if( ('no sidebar' != $wpestate_options['sidebar_class']) && ('' != $wpestate_options['sidebar_class'] ) && ('none' != $wpestate_options['sidebar_class']) ){
?>    
    <div class="col-xs-12 <?php print esc_html($wpestate_options['sidebar_class']);?> widget-area-sidebar" id="primary" >
        <?php 
            if( is_singular( 'estate_property') ){
                if( wpestate_check_full_width_header($prpg_slider_type_status,$local_pgpr_slider_type_status) || 
                    wpestate_check_gallery_slider   ($prpg_slider_type_status,$local_pgpr_slider_type_status) ){
                    echo '<div class="widget-container agent_widget_sidebar">';
                        get_template_part('templates/agent_area_slider'); 
                    echo '</div>';
                }
            }
        
        ?>
        
        
        <ul class="xoxo">
            <?php 
            generated_dynamic_sidebar( $wpestate_options['sidebar_name'] ); ?>
        </ul>

    </div>   

<?php
}
?>
<!-- end sidebar -->