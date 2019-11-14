<?php
	// Register and load the widget
	function rockers_header_topbar_info_classic_widget() {
	    register_widget( 'rockers_header_topbar_info_classic_widget' );
	}
	add_action( 'widgets_init', 'rockers_header_topbar_info_classic_widget' );

// Creating the widget
	class rockers_header_topbar_info_classic_widget extends WP_Widget {
	 
	function __construct() {
		parent::__construct(
			'rockers_header_topbar_info_classic_widget', // Base ID
			esc_html__('ST: Classic Header info widget','rockers'), // Widget Name
			array(
				'classname' => 'rockers_header_topbar_info_classic_widget',
				'description' => esc_html__('Topbar Classic Header info widget.','rockers'),
			),
			array(
				'width' => 600,
			)
		);
		
	 }
	
	public function widget( $args, $instance ) {
	
	//echo $args['before_widget']; 
	
	if($args['id']=='sidebar_primary')
	{
		$instance['before_title']='<div class="sm-widget-title wow fadeInDown animated" data-wow-delay="0.4s"><h3>';
		$instance['before_title']='</h3></div><div class="sm-sidebar-widget wow fadeInDown animated" data-wow-delay="0.4s">';
	}
	echo $args['before_widget'];
	?>

        <div class="contact-area">
								<div class="media">
								<?php if(!empty($instance['fa_icon'])) { ?>
									<div class="contact-icon"><i class="fa <?php echo esc_attr($instance['fa_icon']); ?>"></i></div>
								<?php } else { ?>
                                    <div class="contact-icon"><i class="fa fa-map-marker"></i></div>
                                <?php } ?>
								
									<div class="media-body">
										<?php if(!empty($instance['link'])) { ?>
										<a href="<?php echo esc_url($instance['link']); ?>" <?php echo (esc_attr($instance['target'])?'target="_blank"':''); ?> >
										<?php if(!empty($instance['header_title'])) { ?>
										
										<?php echo '<h4>'.esc_html($instance['header_title']).'</h4>'; ?>
										</a>
										<?php } } 	else
										{
										echo '<h4>'.esc_html($instance['header_title']).'</h4>';
										} 
										?>
									
										<?php if(!empty($instance['description'])) { ?>
										
											<h5><?php echo esc_html($instance['description']); ?></h5>
											
										<?php }?>
										
										
									</div>
								</div>
		</div>





		
	<?php
	echo $args['after_widget'];
	}
	         
	// Widget Backend
	public function form( $instance ) {
		
	if ( isset( $instance[ 'fa_icon' ])){
		$fa_icon = $instance[ 'fa_icon' ];
	}
	else {
	$fa_icon = 'fa-map-marker';
	}
	
    if ( isset( $instance[ 'header_title' ])){
	$header_title = $instance[ 'header_title' ];
	}
	else {
	$header_title = __( 'Chestnut Road,', 'rockers' );
	}
	
	if ( isset( $instance[ 'description' ])){
	$description = $instance[ 'description' ];
	}
	else {
	$description = __( 'California - United States', 'rockers' );
	}
	
	if ( isset( $instance[ 'link' ])){
	$link = $instance[ 'link' ];
	}
	else {
	$link = '';
	}
	
	if ( isset( $instance[ 'target' ])){
	$target = $instance[ 'target' ];
	}
	else {
	$target = false;
	}

	// Widget admin form
	?>
	
	<label for="<?php echo esc_attr($this->get_field_id( 'fa_icon' )); ?>"><?php esc_html_e('Font Awesome icon','rockers' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'fa_icon' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'fa_icon' )); ?>" type="text" value="<?php if($fa_icon) echo esc_attr( $fa_icon ); else echo 'fa-map-marker';?>" />
	<span><?php esc_html_e('Link to get Font Awesome icons','rockers'); ?><a href="<?php echo 'http://fortawesome.github.io/Font-Awesome/icons/'; ?>" target="_blank" ><?php esc_html_e('fa-icon','rockers'); ?></a></span><br><br>
	
	<label for="<?php echo esc_attr($this->get_field_id( 'header_title' )); ?>"><?php esc_html_e( 'Title','rockers' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'header_title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'header_title' )); ?>" type="text" value="<?php if($header_title) echo esc_attr($header_title); else esc_attr_e( 'Chestnut Road,', 'rockers' );?>" /><br><br>
	
	<label for="<?php echo esc_attr($this->get_field_id( 'description' )); ?>"><?php esc_html_e( 'Description','rockers' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'description' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'description' )); ?>" type="text" value="<?php if($description) echo esc_attr($description); else esc_attr_e( 'California - United States', 'rockers' );?>" /><br><br>
	
	<label for="<?php echo esc_attr($this->get_field_id( 'link' )); ?>"><?php esc_html_e( 'Link','rockers' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'link' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'link' )); ?>" type="text" value="<?php if($link) echo esc_url($link); else '';?>" /><br><br>
	
	<input type="checkbox" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'target' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'target' )); ?>" <?php if($target!=false) echo 'checked'; ?> />
	<label for="<?php echo esc_attr($this->get_field_id( 'target' )); ?>"><?php esc_html_e( 'Open link in new tab','rockers' ); ?></label></br></br>
	
	
	
	<?php
    }  
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	
		$instance = array();
		$instance['fa_icon'] = ( ! empty( $new_instance['fa_icon'] ) ) ? strip_tags( $new_instance['fa_icon'] ) : '';
		$instance['header_title'] = ( ! empty( $new_instance['header_title'] ) ) ? htmlspecialchars_decode($new_instance['header_title']) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? htmlspecialchars_decode($new_instance['description']) : '';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';
		$instance['target'] = ( ! empty( $new_instance['target'] ) ) ? $new_instance['target'] : '';
		
		return $instance;
	}
	}