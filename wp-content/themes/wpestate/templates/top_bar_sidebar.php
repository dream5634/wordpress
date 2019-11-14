<?php
global $logo_header_align;
if($logo_header_align=='center'){
    $logo_header_align='left';
}

?>
<div id="header_type3_wrapper" class="header_type3_menu_sidebar <?php echo 'header_'.esc_attr($logo_header_align); ?>">
    
    <ul class="xoxo">
        <?php dynamic_sidebar('sidebar-menu-widget-area-before'); ?>
    </ul>
    
    
    <nav id="access">
        <?php 
            wp_nav_menu( 
                array(  'theme_location'    => 'primary' ,
                        'walker'            => new wpestate_custom_walker
                    ) 
            ); 
        ?>
    </nav><!-- #access -->
    
    <ul class="xoxo">
        <?php dynamic_sidebar('sidebar-menu-widget-area-after'); ?>
    </ul>
    
</div> 