<?php	
add_action( 'widgets_init', 'rockers_widgets_init');
function rockers_widgets_init() {
	
    /* Top header widget sidebar */
		register_sidebar( array(
				'name' => esc_html__('Classic header widget area', 'rockers' ),
				'id' => 'header_widget_area',
				'description' => esc_html__('Classic header widget area', 'rockers' ),
				'before_widget' => '<div class="col-md-4 col-sm-6 col-xs-4"><aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside></div>',
				'before_title' => '<div class="section-header"><h3 class="widget-title">',
				'after_title' => '</h3><span></span></div>',
			) );
			
}	                     
?>