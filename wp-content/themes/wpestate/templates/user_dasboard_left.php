<?php
global $user_small_picture;
global $user_login;
global $add_link;
global $home_url;
$show_header_dashboard      =  get_option('wp_estate_show_header_dashboard','');
$dashboard_cover_class="";
if( $add_link ==  $home_url ){ 
    $dashboard_cover_class=" no_add_prop ";
}   
 
if($show_header_dashboard=='no'){
    $dashboard_cover_class.=" no_header ";
}

?>
<div class="col-md-3 user_menu_wrapper <?php echo esc_attr($dashboard_cover_class); ?>">
        <div class="dashboard_cover  " style="background-image: url('<?php print esc_url($user_small_picture[0]);  ?>');"></div>
        <div class="dashboard_menu_user_image">
            <div class="menu_user_picture" style="background-image: url('<?php print esc_url($user_small_picture[0]);  ?>');" ></div>
            <div class="dashboard_username">
                <?php esc_html_e('Welcome back, ','wpestate'); echo esc_html($user_login).'!';?>
            </div> 
          
            <?php if( $add_link !=  $home_url ){ ?>
                <a href="<?php print esc_url ($add_link);?>" class="wpresidence_button user_menu_add"><?php esc_html_e('Add New Property','wpestate');?></a>  
            <?php } ?>

        </div>
    <?php  get_template_part('templates/user_menu');  ?>
</div>  
