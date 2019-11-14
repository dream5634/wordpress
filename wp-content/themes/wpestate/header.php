<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
 
<?php         
if( !has_site_icon() ){
    print '<link rel="shortcut icon" href="'.get_theme_file_uri('/img/favicon.gif').'" type="image/x-icon" />';
}
?>

    
<?php wp_head();?>
</head>



<?php 
$wide_class      =   ' is_boxed ';
$wide_status     =   esc_html(get_option('wp_estate_wide_status',''));
if($wide_status==1){
    $wide_class=" wide ";
}

if( isset($post->ID) && wpestate_half_map_conditions ($post->ID) ){
    $wide_class="wide fixed_header ";
}



$logo_header_type            =   get_option('wp_estate_logo_header_type','');
$logo_header_align           =   get_option('wp_estate_logo_header_align','');
$wide_header                 =   get_option('wp_estate_wide_header','');
$wide_header_class           =  '';
if($wide_header=='yes'){
    $wide_header_class=" full_width_header ";
}

$top_menu_hover_type        =   get_option('wp_estate_top_menu_hover_type','');  
$header_transparent_class   =   '';
$header_transparent         =   get_option('wp_estate_header_transparent','');


if(isset($post->ID) && !is_tax() && !is_category() ){
        $header_transparent_page    =   get_post_meta ( $post->ID, 'header_transparent', true);
        if($header_transparent_page=="global" || $header_transparent_page==""){
            if ($header_transparent=='yes'){
                $header_transparent_class=' header_transparent ';
            }
        }else if($header_transparent_page=="yes"){
            $header_transparent_class=' header_transparent ';
        }
}else{
    if ($header_transparent=='yes'){
            $header_transparent_class=' header_transparent ';
    }
}

$logo                       =   get_option('wp_estate_logo_image','');  
$stikcy_logo_image          =   esc_html( get_option('wp_estate_stikcy_logo_image','') );
$logo_margin                =   intval( get_option('wp_estate_logo_margin','') );
$transparent_logo           =   esc_html( get_option('wp_estate_transparent_logo_image','') );
$show_top_bar_user_login    =   esc_html ( get_option('wp_estate_show_top_bar_user_login','') );

if(  trim($header_transparent_class) == 'header_transparent' && $transparent_logo!=''){
    $logo  = $transparent_logo;
}


$show_header_dashboard      =  get_option('wp_estate_show_header_dashboard','');

if( wpestate_is_user_dashboard() && $show_header_dashboard=='no'){
    $logo_header_type='';
}

if(wpestate_is_user_dashboard() && $show_header_dashboard=='yes'){
    $wide_class=" wide ";
    $logo_header_type = "type1  ";
    $wide_header_class=" full_width_header ";
}

$show_top_bar_user_login_class='';
if($show_top_bar_user_login != 'yes'){
    $show_top_bar_user_login_class=" no_user_submit ";
}

?>




<body <?php body_class(); ?>>  
   
<?php   get_template_part('templates/mobile_menu' ); ?> 
    
<div class="website-wrapper" id="all_wrapper" >
<div class="container main_wrapper <?php print esc_html($wide_class); print esc_html('has_header_'.$logo_header_type.' '.$header_transparent_class); print  'contentheader_'.$logo_header_align; print ' cheader_'.$logo_header_align;?> ">

    <div class="master_header <?php print esc_html($wide_class.' '.$header_transparent_class); echo ' '.$wide_header_class;?>">
        
        <?php   
            if(esc_html ( get_option('wp_estate_show_top_bar_user_menu','') )=="yes"){
                get_template_part( 'templates/top_bar' ); 
            } 
            get_template_part('templates/mobile_menu_header' );
            
            
        ?>
       
        
        <div class="header_wrapper <?php echo esc_attr( $show_top_bar_user_login_class.' header_'.$logo_header_type.' header_'.$logo_header_align. ' hover_type_'.$top_menu_hover_type );?> ">
            <div class="header_wrapper_inside <?php echo esc_attr($wide_header_class); ?>" data-logo="<?php echo esc_url($logo);?>" data-sticky-logo="<?php echo esc_url($stikcy_logo_image); ?>">
                
                <div class="logo">
                    <a href="<?php echo esc_url( home_url('','login')) ;?>">
                        <?php  
                        if ( $logo!='' ){
                           print '<img id="logo_image" style="margin-top:'.$logo_margin.'px;" src="'.esc_url($logo).'" class="img-responsive retina_ready" alt="'.esc_attr__('logo','wpestate').'"/>';	
                        } else {
                           print '<img id="logo_image" class="img-responsive retina_ready" src="'. get_template_directory_uri().'/img/logo.png" alt="'.esc_attr__('logo','wpestate').'"/>';
                        }
                        ?>
                    </a>
                </div>   

              
                <?php 
        
                    if( $show_top_bar_user_login == "yes" && $logo_header_type!='type3'){
                        get_template_part('templates/top_user_menu');  
                    }
                ?>    
                
                <?php 
                    if($logo_header_type!='type3'){
                ?>
                    <nav id="access">
                        <?php 
                            wp_nav_menu( 
                                array(  'theme_location'    => 'primary' ,
                                        'walker'            => new wpestate_custom_walker
                                    ) 
                            ); 
                        ?>
                    </nav><!-- #access -->
                <?php }else{ ?>
                    <a class="navicon-button header_type3_navicon" id="header_type3_trigger">
                        <div class="navicon"></div>
                    </a>
                <?php } ?>
                    
                <?php 
                if($logo_header_type=='type4'){
                    print '<div id="header4_footer"><ul class="xoxo">';
                        dynamic_sidebar('header4-widget-area');
                    print'</ul></div>';
                }
                ?>    
                    
            </div>
        </div>

     </div> 
    
    <?php 
    
    if ( !isset( $post->ID) ){
        $header_type=0;
    }else{
        $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
    }
    $global_header_type         =   get_option('wp_estate_header_type','');
    $search_on_start            =   get_option('wp_estate_search_on_start');
    $property_list_type_adv     =   esc_html(get_option('wp_estate_property_list_type_adv',''));
    $property_list_type_status  =    esc_html(get_option('wp_estate_property_list_type',''));
 
    get_template_part( 'templates/top_user_menu_login' );
    
    get_template_part( 'header_media' );
    

    if( !wpestate_float_search_placement() && $search_on_start=='no' && !is_page_template('property_list_half.php')   ){
        if( !(is_page_template('advanced_search_results.php') && $property_list_type_adv==2) ){
            if( !( ( is_tax('property_category') || is_tax('property_action_category') || is_tax('property_city') || is_tax('property_area') || is_tax('property_county_state') ) && $property_list_type_status==2 ) ){
                if( !wpestate_is_user_dashboard()  ){
                    if( $header_type != 1 || ( $header_type == 0 && $global_header_type!=0 ) ){
                      
                        get_template_part( 'templates/advanced_search' );
                        if( !wpestate_half_map_conditions('') ){
                            get_template_part( 'templates/adv_search_mobile');
                        }
    
                    }
                }
            }
        }
    }
    
    
   
    
    if( is_singular('estate_property')){
        get_template_part('templates/properties_header'); 
    }
    ?>
    
  <div class="container content_wrapper">