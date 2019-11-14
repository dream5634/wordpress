<?php 
global $post;
 
$show_adv_search_visible    =   get_option('wp_estate_show_adv_search_visible','');
$close_class                =   '';
if($show_adv_search_visible=='no'){
    $close_class='adv-search-1-close';
}
if(isset( $post->ID)){
    $post_id = $post->ID;
}else{
    $post_id = '';
}

$allowed_html=array();   
?>

<div class="adv-search-1  adv-search-2 <?php echo esc_html($close_class);?>" id="adv-search-2" data-postid="<?php echo intval($post_id); ?>"> 
  
        <form role="search" method="get" class="visible-wrapper"  action="<?php print esc_url($adv_submit); ?>" >
            <?php wp_nonce_field( 'wpestate_adv_search_nonce', 'wpestate_adv_search_nonce_field' ) ?>
            <div class="adv2_holder">
                <?php
                if (function_exists('icl_translate') ){
                    print do_action( 'wpml_add_language_form_field' );
                }
                ?> 
                
                <div class="col-md-6">
                    <input type="text" id="adv_location" class="form-control" name="adv_location"  placeholder="<?php esc_html_e('Search State, City or Area','wpestate');?>" 
                    value="<?php 
                        if(isset($_GET['adv_location'])){
                            echo esc_attr( wp_kses($_GET['adv_location'], $allowed_html) );
                        }
                    ?>">      
                </div>

                
                <?php

                if( isset($_GET['filter_search_type'][0]) && $_GET['filter_search_type'][0]!=''&& $_GET['filter_search_type'][0]!='all'  ){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['filter_search_type'][0],$allowed_html) ),'property_category');
                    $adv_categ_value= $adv_categ_value1=$full_name->name;
                    $adv_categ_value1 = mb_strtolower ( str_replace(' ', '-', $adv_categ_value1));
                }else{
                    $adv_categ_value    = esc_html__('All Types','wpestate');
                    $adv_categ_value1   ='all';
                }
                
                ?>

                <div class="col-md-2">
                    <div class="dropdown form-control" >
                        <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger" data-value="<?php //echo  $adv_categ_value1;?>"> 
                            <?php 
                            echo esc_html($adv_categ_value);
                            ?> 
                        <span class="caret caret_filter"></span> </div>   

                        <input type="hidden" name="filter_search_type[]" value="<?php if(isset($_GET['filter_search_type'][0])){echo esc_attr( wp_kses($_GET['filter_search_type'][0], $allowed_html) );}?>">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                            <?php echo trim($categ_select_list);?>
                        </ul>        
                    </div> 
                </div>    

                
                
                <?php
                if(isset($_GET['filter_search_action'][0]) && $_GET['filter_search_action'][0]!='' && $_GET['filter_search_action'][0]!='all'){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['filter_search_action'][0],$allowed_html) ),'property_action_category');
                    $adv_actions_value=$adv_actions_value1= $full_name->name;
                    $adv_actions_value1 = mb_strtolower ( str_replace(' ', '-', $adv_actions_value1) );
                }else{
                    $adv_actions_value=esc_html__('All Actions','wpestate');
                    $adv_actions_value1='all';
                }
                
                ?>
                
                
                <div class="col-md-2">
                    <div class="dropdown form-control" >
                        <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger" data-value="<?php //; ?>"> 
                            <?php echo esc_html($adv_actions_value);?> 
                            <span class="caret caret_filter"></span> </div>           

                        <input type="hidden" name="filter_search_action[]" value="<?php if(isset($_GET['filter_search_action'][0])){echo esc_attr( wp_kses($_GET['filter_search_action'][0], $allowed_html) );}?>">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                            <?php echo trim($action_select_list);?>
                        </ul>        
                    </div>
                </div>   


                <input type="hidden" name="is2" value="1">

                <div class="col-md-2">
                    <input name="submit" type="submit" class="wpresidence_button" id="advanced_submit_22" value="<?php esc_html_e('SEARCH','wpestate');?>">
                </div>
               
            </div>
                
        </form> 
    
    
</div>  

<?php
$availableTags='';
$args = array(
    'orderby' => 'count',
    'hide_empty' => 0,
); 

$terms = get_terms( 'property_city', $args );
foreach ( $terms as $term ) {
   $availableTags.= '"'.esc_html($term->name).'",';
}

$terms = get_terms( 'property_area', $args );

foreach ( $terms as $term ) {
   $availableTags.= '"'.esc_html($term->name).'",';
}

$terms = get_terms( 'property_county_state', $args );
foreach ( $terms as $term ) {
   $availableTags.= '"'.esc_html($term->name).'",';
}

 print '<script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready(function(){
             var availableTags = ['.$availableTags.'];
             jQuery("#adv_location").autocomplete({
                 source: availableTags,
                 change: function() {
                     wpestate_show_pins();
                 }
             });
        });
        //]]>
        </script>';
?>