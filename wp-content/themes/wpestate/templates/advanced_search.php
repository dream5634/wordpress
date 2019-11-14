<?php
global $adv_search_type;
global $post;
$adv_submit                 =   wpestate_get_adv_search_link();
$args                       =   wpestate_get_select_arguments();
$action_select_list         =   wpestate_get_action_select_list($args);
$categ_select_list          =   wpestate_get_category_select_list($args);
$select_city_list           =   wpestate_get_city_select_list($args); 
$select_area_list           =   wpestate_get_area_select_list($args);
$select_county_state_list   =   wpestate_get_county_state_select_list($args);
$adv_search_type            =   get_option('wp_estate_adv_search_type','');
$show_adv_search_visible    =   get_option('wp_estate_show_adv_search_visible','');
$close_class_wr             =   ' ';

if($show_adv_search_visible=='no'){
    $close_class_wr='search_wrapper-close-extended';
}
   
$float_search_form_class    =   '';
$float_search_form          =   esc_html ( get_option('wp_estate_use_float_search_form','') );
if(wpestate_float_search_placement() ){
    $float_search_form_class.= ' wrapper_search_is_float ';
}

if(isset( $post->ID)){
    $post_id = $post->ID;
}else{
    $post_id = '';
}

$prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') );

if (  (isset($post->ID) && is_page($post->ID) &&  basename( get_page_template() ) == 'contact_page.php') || 
      (is_singular('estate_property') && get_post_meta($post->ID, 'local_pgpr_slider_type', true)=='global' && $prpg_slider_type_status === 'full width header' ) ||
      (is_singular('estate_property') && get_post_meta($post->ID, 'local_pgpr_slider_type', true)=='full width header' ) ) {

        $do_noting=0;
        //do nothing
}else if( !is_404() ){
?>
<div class="search_wrapper search_wr_<?php echo esc_attr( $adv_search_type.' '.$close_class_wr.' '.$float_search_form_class); ?>" id="search_wrapper"  data-postid="<?php echo intval($post_id); ?>">       

<div id="search_wrapper_color"></div>
<?php

if ($adv_search_type==1){
    include(get_theme_file_path('templates/advanced_search_type1.php'));
}else if ($adv_search_type==2){
    include(get_theme_file_path('templates/advanced_search_type2.php'));
}else if ($adv_search_type==3){
    include(get_theme_file_path('templates/advanced_search_type3.php'));
}else if ($adv_search_type==4){
    include(get_theme_file_path('templates/advanced_search_type4.php'));
}else if ($adv_search_type==5){
    include(get_theme_file_path('templates/advanced_search_type5.php'));
}else if ($adv_search_type==6){
    include(get_theme_file_path('templates/advanced_search_type6.php'));
}else{
    //nothing
}
?>
</div>
<?php
}    
?>

<!-- end search wrapper--> 
<!-- END SEARCH CODE -->