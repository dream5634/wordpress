<!-- GET AGENT LISTINGS-->
<?php
global $agent_id;
global $current_user;
global $leftcompare;
global $wp_query;
global $wpestate_property_unit_slider;
global $wpestate_no_listins_per_row;
global $wpestate_uset_unit;
global $wpestate_custom_unit_structure;
global $wpestate_align_class;
global $wpestate_prop_unit;
global $agent_listings_no;

$wpestate_custom_unit_structure    =   get_option('wpestate_property_unit_structure');
$wpestate_uset_unit       =   intval ( get_option('wpestate_uset_unit','') );
$wpestate_no_listins_per_row       =   intval( get_option('wp_estate_listings_per_row', '') );
$paged = (get_query_var('page')) ? get_query_var('page') : 1;

if(isset($_GET['pagelist'])){
    $paged = intval( $_GET['pagelist'] );
}else{
    $paged = 1;
}

$property_card_type         =   intval(get_option('wp_estate_unit_card_type'));
$property_card_type_string  =   '';
if($property_card_type==0){
    $property_card_type_string='';
}else{
    $property_card_type_string='_type'.$property_card_type; //escaped above
}
    

$current_user = wp_get_current_user();

$userID             =   $current_user->ID;
$user_option        =   'favorites'.$userID;
$curent_fav         =   get_option($user_option);
$show_compare_link  =   'no';
$wpestate_currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$leftcompare        =   1;
$wpestate_property_unit_slider = get_option('wp_estate_prop_list_slider','');

$args = array(
    'post_type'         => 'estate_property',
    'post_status'       => 'publish',
    'paged'             => $paged,
    'posts_per_page'    => 9,
    'meta_key'          => 'prop_featured',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
    'meta_query'        => array(
                                array(
                                    'key'   => 'property_agent',
                                    'value' => $agent_id,
                                )
                        )
                );

$mapargs = array(
    'post_type'         => 'estate_property',
    'post_status'       => 'publish',
    'posts_per_page'    => -1,
    'meta_query'        => array(
                                array(
                                    'key'   => 'property_agent',
                                    'value' => $agent_id,
                                )
                        )
                );

$prop_selection='';
if(function_exists('wpestate_return_filtered_by_order')){
    $prop_selection=wpestate_return_filtered_by_order($args);
}

$agent_listings_no=$prop_selection->found_posts;


if ( $prop_selection->have_posts() ) {
    $show_compare   =   1;
    $compare_submit =   wpestate_get_compare_link();
    
    $wpestate_prop_unit                  =   esc_html ( get_option('wp_estate_prop_unit','') );
    $wpestate_prop_unit_class            =   '';
    if($wpestate_prop_unit=='list'){
        $wpestate_prop_unit_class="ajax12";
        $wpestate_align_class=   'the_list_view';
    }


    ?>
    <div class="mylistings">
  
        <?php   
        print'<h3 id="agent_listings" class="agent_listings_title">'.esc_html__('My Listings','wpestate').'</h3>';
        while ($prop_selection->have_posts()): $prop_selection->the_post();                     
            get_template_part('templates/property_unit'.$property_card_type_string); //escaped above
        endwhile;
        // Reset postdata
        wp_reset_postdata();
        // Custom query loop pagination
    
        ?>
        
    <?php 
        wpestate_second_loop_pagination($prop_selection->max_num_pages,$range =2,$paged,esc_url ( get_permalink()));      
    ?>    
    </div>
<?php        
}


if (wp_script_is( 'googlecode_regular', 'enqueued' )) {
    
    $max_pins                   =   intval( get_option('wp_estate_map_max_pins') );
    $mapargs['posts_per_page']  =   $max_pins;
    $mapargs['offset']          =   ($paged-1)*9;
 
    $selected_pins  =   wpestate_listing_pins($mapargs,1);//call the new pins   
    wp_localize_script('googlecode_regular', 'googlecode_regular_vars2', 
                array('markers2'          =>  $selected_pins,
                      'agent_id'             =>  $agent_id ));

}






////////////////////////////////////////////////////////////////////////////////////////
/////// Second loop Pagination
///////////////////////////////////////////////////////////////////////////////////////////
function wpestate_second_loop_pagination($pages = '', $range = 2,$paged,$link){
        $newpage    =   $paged -1;
        if ($newpage<1){
            $newpage=1;
        }
        $next_page  =   esc_url_raw ( add_query_arg('pagelist',$newpage, esc_url ($link) ) );
        $showitems = ($range * 2)+1; 
        if($pages>1)
        {
            print "<ul class='pagination pagination_nojax pagination_agent'>";
            echo "<li class=\"roundleft\"><a href='".esc_url($next_page)."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
             
            for ($i=1; $i <= $pages; $i++)
            {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    $newpage    =   $paged -1;
                    $next_page  =  esc_url_raw (add_query_arg('pagelist',$i,esc_url ($link)));
                    if($paged == $i){
                        echo "<li class='active'><a href='' >".esc_html($i)."</a><li>";
                    }else{
                        echo "<li><a href='".esc_url($next_page)."' >".esc_html($i)."</a><li>";
                    }
                }
            }

            $prev_page= get_pagenum_link($paged + 1);
            if ( ($paged +1) > $pages){
                $prev_page  =   get_pagenum_link($paged );
                $newpage    =   $paged;
                $prev_page  =   esc_url_raw(add_query_arg('pagelist',$newpage,esc_url ($link)));
            }else{
                $prev_page  =   get_pagenum_link($paged + 1);
                $newpage    =   $paged + 1;
                $prev_page  =   esc_url_raw(add_query_arg('pagelist',$newpage,esc_url ($link)));
            }

            echo "<li class=\"roundright\"><a href='".esc_url($prev_page)."'><i class=\"fa fa-angle-right\"></i></a><li>";
            echo "</ul>\n";
        }
}


?>