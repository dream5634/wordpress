<?php
global $edit_link;
global $token;
global $processor_link;
global $paid_submission_status;
global $submission_curency_status;
global $price_submission;
global $floor_link;
global $user_pack;

$post_id                    =   get_the_ID();
$featured                   =   intval  ( get_post_meta($post_id, 'prop_featured', true) );
$preview                    =   wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'property_listings');
$edit_link                  =   esc_url_raw(add_query_arg( 'listing_edit', $post_id, $edit_link )) ;
$floor_link                 =   esc_url_raw(add_query_arg( 'floor_edit', $post_id,  $floor_link )) ;
$post_status                =   get_post_status($post_id);
$property_address           =   esc_html ( get_post_meta($post_id, 'property_address', true) );
$property_city              =   get_the_term_list($post_id, 'property_city', '', ', ', '') ;
$property_area              =   get_the_term_list($post_id, 'property_area', '', ', ', '');
$property_category          =   get_the_term_list($post_id, 'property_category', '', ', ', '');
$property_action_category   =   get_the_term_list($post_id, 'property_action_category', '', ', ', '');
$price_label                =   esc_html ( get_post_meta($post_id, 'property_label', true) );
$price_label_before         =   esc_html ( get_post_meta($post_id, 'property_label_before', true) );
$price                      =   floatval( get_post_meta($post->ID, 'property_price', true) );
$wpestate_currency          =   esc_html( get_option('wp_estate_submission_curency', '') );
$currency_title             =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency    =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$status                     =   '';
$link                       =   '';
$pay_status                 =   '';
$is_pay_status              =   '';
$paid_submission_status     =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission           =   floatval( get_option('wp_estate_price_submission','') );
$price_featured_submission  =   floatval( get_option('wp_estate_price_featured_submission','') );
$th_separator               =   stripslashes ( get_option('wp_estate_prices_th_separator','') );
$no_views                   =    intval( get_post_meta($post_id, 'wpestate_total_views', true));
if ($price != 0) {
   
    if( $price == intval($price)){
        $price = number_format($price,0,'.',$th_separator);
    }else{
        $price = number_format($price,2,'.',$th_separator);
    }
   
   if ($wpestate_where_currency == 'before') {
       $price_title =   $currency_title . ' ' . $price;
       $price       =   $wpestate_currency . ' ' . $price;
   } else {
       $price_title = $price . ' ' . $currency_title;
       $price       = $price . ' ' . $wpestate_currency;
     
   }
}else{
    $price='';
    $price_title='';
}



if($post_status=='expired'){ 
    $status='<span class="label label-danger">'.esc_html__('Expired','wpestate').'</span>';
}else if($post_status=='publish'){ 
    $link=esc_url ( get_permalink());
    $status='<span class="label label-success">'.esc_html__('Published','wpestate').'</span>';
}else if($post_status=='disabled'){
    $link='';
    $status='<span class="label label-info">'.esc_html__('Disabled','wpestate').'</span>';
}else{
    $link='';
    $status='<span class="label label-info">'.esc_html__('Waiting for approval','wpestate').'</span>';
}


if ($paid_submission_status=='per listing'){
    $pay_status    = get_post_meta(get_the_ID(), 'pay_status', true);
    if($pay_status=='paid'){
        $is_pay_status.='<span class="label label-success">'.esc_html__('Paid','wpestate').'</span>';
    }
    if($pay_status=='not paid'){
        $is_pay_status.='<span class="label label-info">'.esc_html__('Not Paid','wpestate').'</span>';
    }
}
$featured  = intval  ( get_post_meta($post->ID, 'prop_featured', true) );


$free_feat_list_expiration= intval ( get_option('wp_estate_free_feat_list_expiration','') );
$pfx_date = strtotime ( get_the_date("Y-m-d",  $post->ID ) );
$expiration_date=$pfx_date+$free_feat_list_expiration*24*60*60;
?>



<div class="col-md-12 row_dasboard-prop-listing property_wrapper_dash">
    <div class="col-md-12 dasboard-prop-listing">
       <div class="blog_listing_image col-md-2">
           <?php
            if($featured==1){
                print '<div class="featured_div">'.esc_html__('Featured','wpestate').'</div>';
            }
            if (has_post_thumbnail($post_id)){
            ?>
                <a href="<?php print esc_url($link); ?>"><img  src="<?php  echo esc_url($preview[0]); ?>"  /></a>
            <?php 
            } 
            ?>
       </div>

        
        <div class="prop-info col-md-4">
            <h4 class="listing_title">
                <a href="<?php print esc_url($link); ?>">
                    <?php 
                    $title=get_the_title();
                    echo mb_substr( $title,0,25); 
                    if(mb_strlen($title)>25){
                        echo '...';   
                    } ?>
                </a> 
            </h4>
            
            <div class="user_dashboard_listed">
           
                <?php print wp_kses_post($property_action_category); ?> 
                <?php 
                    if( $property_action_category!='') {
                        print', ';
                    } 
                    print wp_kses_post($property_category);
                ?>                     
            </div>

            <div class="user_dashboard_listed">
               
                <?php print get_the_term_list($post_id, 'property_city', '', ', ', '');?>
                <?php 
                    if( $property_area!='') {
                        print', ';
                    } 
                    print wp_kses_post($property_area);
                ?> 
            </div>
            
            <?php 
                if ( $paid_submission_status=='membership' && $user_pack=='') {
                    echo '<div class="expires_on">'. esc_html__('Expires on ','wpestate');echo date("Y-m-d",$expiration_date).'</div>';
                } 
            ?>
        </div>
        


            <div class="user_dashboard_listed_price col-md-2">
                <?php print'<span class="price_label"> '.$price_label_before.' '. $price_title.' '.$price_label.'</span></br>'; //escaped above ?>
               
            </div>

        

            <div class="info-container col-md-2">
                <a  data-original-title="<?php esc_attr_e('Edit Property','wpestate');?>"   data-placement="bottom"     class="dashboad-tooltip property_action_button" href="<?php  print  esc_url($edit_link);?>"><i class="demo-icon icon-pencil"></i></a>
                <a  data-original-title="<?php esc_attr_e('Delete Property','wpestate');?>" data-placement="bottom"     class="dashboad-tooltip property_action_button" onclick="return confirm(' <?php echo esc_html__('Are you sure you wish to delete ','wpestate').get_the_title(); ?>?')" href="<?php print esc_url_raw(add_query_arg( 'delete_id', $post_id,  wpestate_get_template_link('user_dashboard.php')  ) );?>"><i class="demo-icon icon-trash"></i></a>  
                <a  data-original-title="<?php esc_attr_e('Floor Plans','wpestate');?>"     data-placement="bottom"     class="dashboad-tooltip property_action_button" href="<?php  print esc_url($floor_link);?>"><i class="demo-icon icon-docs"></i></a>
                
                <?php if ($no_views>0){ ?>
                    <a  data-original-title="<?php esc_attr_e('Views Stats','wpestate');?>"   data-placement="bottom"     class="dashboad-tooltip show_stats property_action_button"  data-listingid="<?php echo intval($post_id);?>" href="#"><i class="demo-icon icon-eye"></i></a>
                <?php }else{ ?>
                    <a  data-original-title="<?php esc_attr_e('Item has 0 views','wpestate');?>"  data-placement="bottom"  class="dashboad-tooltip show_statsx property_action_button"  data-listingid="<?php echo intval($post_id);?>" href="#"><i class="demo-icon icon-eye"></i></a>
                <?php }?>
                
                <?php
                    if( $post_status == 'expired' ){ 
                       print'<a data-original-title="'.esc_html__('Resend for approval','wpestate').'"  data-placement="bottom"  class="dashboad-tooltip resend_pending property_action_button" data-listingid="'.intval($post_id).'"><i class="demo-icon icon-upload-cloud"></i></a>';   
                    }
                ?>    

                <?php
                   if( $post_status == 'publish' ){ 
                       print ' <span  data-original-title="'.esc_html__('Disable Listing','wpestate').'" data-placement="bottom"  class="dashboad-tooltip disable_listing disabledx property_action_button" data-postid="'.intval($post_id).'" ><i class="demo-icon icon-pause"></i></span>';
                   }else if($post_status=='disabled') {
                       print ' <span  data-original-title="'.esc_html__('Enable Listing','wpestate').'" data-placement="bottom"  class="dashboad-tooltip disable_listing property_action_button" data-postid="'.intval($post_id).'" ><i class="demo-icon icon-play"></i></span>';

                   }
                ?>

                <?php
                $no_payment='';
                if($paid_submission_status=='membership'){
                    $no_payment=' no_payment ';
                    if ( intval(get_post_meta($post_id, 'prop_featured', true))==1){
                        // print '<span class="label label-success">'.esc_html__('Property is featured','wpestate').'</span>';       
                    }
                    else{
                        print ' <span  data-original-title="'.esc_html__('Set as featured,  *Listings set as featured are subtracted from your package','wpestate').'" data-placement="bottom" class="property_action_button dashboad-tooltip make_featured" data-postid="'.intval($post_id).'" ><i class="demo-icon icon-star-filled"></i></span>';
                    }
                }

                if($paid_submission_status=='no'){
                     $no_payment=' no_payment ';
                }

                ?>

            </div>
      
            <div class="user_dashboard_status">
                <?php print trim($status.$is_pay_status);?>      
            </div>
       
          
            <div class="payment-container <?php echo esc_html($no_payment); ?>">
                <?php $pay_status    = get_post_meta($post_id, 'pay_status', true);
                    if( $post_status == 'expired' ){ 
                    }else{
                       if($paid_submission_status=='per listing'){
                            $enable_paypal_status= esc_html ( get_option('wp_estate_enable_paypal','') );
                            $enable_stripe_status= esc_html ( get_option('wp_estate_enable_stripe','') );
                            $enable_direct_status= esc_html ( get_option('wp_estate_enable_direct_pay','') );

                            if($pay_status!='paid' ){
                                print' 
                                    <div class="listing_submit">
                                    '.esc_html__('Submission Fee','wpestate').': <span class="submit-price submit-price-no">'.esc_html($price_submission).'</span><span class="submit-price"> '.esc_html($wpestate_currency).'</span></br>
                                    <input type="checkbox" class="extra_featured" name="extra_featured" style="display:block;" value="1" >
                                    '.esc_html__('Featured Fee','wpestate').': <span class="submit-price submit-price-featured">'.esc_html($price_featured_submission).'</span><span class="submit-price"> '.esc_html($wpestate_currency).'</span> </br>
                                    '.esc_html__('Total Fee','wpestate').': <span class="submit-price submit-price-total">'.esc_html($price_submission).'</span> <span class="submit-price">'.esc_html($wpestate_currency).'</span>  </br> ';
                                    print  '</div>'; 
                                    $stripe_class='';
                                    if($enable_paypal_status==='yes'){
                                        $stripe_class=' stripe_paypal ';
                                        print ' <div class="listing_submit_normal label label-danger" data-listingid="'.intval($post_id).'">'.esc_html__('Pay with Paypal','wpestate').'</div>';
                                    }

                                    if($enable_stripe_status==='yes'){
                                        wpestate_show_stripe_form_per_listing($stripe_class,$post_id,$price_submission,$price_featured_submission);
                                    }
                                    if($enable_direct_status=='yes'){
                                        print '<div data-listing="'.intval($post_id).'" class="perpack">'.esc_html__('Wire Transfer','wpestate').'</div>';
                                    }



                            }else{
                                if ( intval(get_post_meta($post_id, 'prop_featured', true))==1){
                               
                                }else{
                                     print' 
                                     <div class="listing_submit upgrade_post">
                                    '.esc_html__('Featured  Fee','wpestate').': <span class="submit-price submit-price-total">'.esc_html($price_featured_submission).'</span> <span class="submit-price">'.esc_html($wpestate_currency).'</span>  </br> ';
                                    print  '</div>'; 

                                    $stripe_class='';
                                    if($enable_paypal_status==='yes'){
                                        print'<span class="listing_upgrade label label-danger" data-listingid="'.intval($post_id).'">'.esc_html__('Upgrade to featured','wpestate').' - '.esc_html($price_featured_submission).' '.esc_html($wpestate_currency).'</span>'; 
                                    }
                                    if($enable_stripe_status==='yes'){
                                        wpestate_show_stripe_form_upgrade($stripe_class,$post_id,$price_submission,$price_featured_submission);
                                    }
                                    if($enable_direct_status=='yes'){
                                        print '<div data-listing="'.intval($post_id).'" data-isupgrade="1" class="perpack">'.esc_html__('Upgrade to featured','wpestate').'</div>';
                                    }
                                } 
                            }
                        }

                    }?></div>


        <div class="statistics_wrapper">
            <div class="statistics_wrapper_total_views">
                <?php esc_html_e('Total number of views:','wpestate'); echo esc_html($no_views); ?>
            </div>
            <canvas class="my_chart_dash" id="myChart_<?php echo intval($post_id);?>"></canvas>
        </div>   
    </div>
</div>