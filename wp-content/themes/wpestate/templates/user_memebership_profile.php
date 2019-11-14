<?php
$current_user           =   wp_get_current_user();
$userID                 =   $current_user->ID;
$user_login             =   $current_user->user_login;  
$add_link               =   wpestate_get_dasboard_add_listing();
$dash_profile           =   wpestate_get_dashboard_profile_link();
$dash_favorite          =   wpestate_get_dashboard_favorites();
$dash_link              =   wpestate_get_dashboard_link();
$activeprofile          =   '';
$activedash             =   '';
$activeadd              =   '';
$activefav              =   '';
$user_pack              =   get_the_author_meta( 'package_id' , $userID );    
$clientId               =   esc_html( get_option('wp_estate_paypal_client_id','') );
$clientSecret           =   esc_html( get_option('wp_estate_paypal_client_secret','') );  
$user_registered        =   get_the_author_meta( 'user_registered' , $userID );
$user_package_activation=   get_the_author_meta( 'package_activation' , $userID );
$is_membership          =   0;
$paid_submission_status = esc_html ( get_option('wp_estate_paid_submission','') );  

?>


<div class="col-md-12 top_dahsboard_wrapper dashboard_package_row"> 
<?php
if ($paid_submission_status == 'membership'){
    wpestate_get_pack_data_for_user_top($userID,$user_pack,$user_registered,$user_package_activation); 
    $is_membership=1;             
}

?>

<?php
if ( $is_membership==1){ 
    $stripe_profile_user    =   get_user_meta($userID,'stripe',true);
    $subscription_id        =   get_user_meta( $userID, 'stripe_subscription_id', true );
    $enable_stripe_status   =   esc_html ( get_option('wp_estate_enable_stripe','') );
    if( $stripe_profile_user!='' && $subscription_id!='' && $enable_stripe_status==='yes'){
        echo '<span id="stripe_cancel" data-original-title="'.esc_attr_e('subscription will be cancelled at the end of current period','wpestate').'" data-stripeid="'.esc_attr($userID).'">'.esc_html__('cancel stripe subscription','wpestate').'</span>';
    }
    ?>
    
    <div class="pack_description ">
        <?php 
        print '<div id="open_packages" class="wrapper_packages wpresidence_button wpresidence_success" >'.esc_html__('Change your Subscription ', 'wpestate');
        print '</div>';
        ?>
    </div>

    
        <div class="pack_description_row ">
            <div class="add-estate profile-page profile-onprofile row"> 
                <div class="pack-unit">
                    <div class="pack_description_unit_head">     
                        <?php print '<h4>'.esc_html__('Packages Available', 'wpestate').'</h4>'; ?>
                    </div>    

                    <?php
                    $wpestate_currency           = esc_html( get_option('wp_estate_submission_curency', '') );
                    $wpestate_where_currency     = esc_html( get_option('wp_estate_where_currency_symbol', '') );
                    $args = array(
                        'post_type'         => 'membership_package',
                        'posts_per_page'    => -1,
                        'meta_query'        => array(
                                                    array(
                                                        'key' => 'pack_visible',
                                                        'value' => 'yes',
                                                        'compare' => '=',
                                                    )
                        )
                    );
                    $pack_selection = new WP_Query($args);

                    while($pack_selection->have_posts() ){
                        $pack_selection->the_post();
                        $postid                 = $post->ID;
                        $pack_list              = get_post_meta($postid, 'pack_listings', true);
                        $pack_featured          = get_post_meta($postid, 'pack_featured_listings', true);
                        $pack_price             = get_post_meta($postid, 'pack_price', true);
                        $unlimited_lists        = get_post_meta($postid, 'mem_list_unl', true);
                        $biling_period          = get_post_meta($postid, 'biling_period', true);
                        $billing_freq           = get_post_meta($postid, 'billing_freq', true);
                        $pack_time              = get_post_meta($postid, 'pack_time', true);
                        $unlimited_listings     = get_post_meta($postid, 'mem_list_unl', true);

                        if($billing_freq>1){
                            $biling_period.='s';
                        }
                        if ($wpestate_where_currency == 'before') {
                            $price = $wpestate_currency . ' ' . $pack_price;
                        }else {
                            $price = $pack_price . ' ' . $wpestate_currency;
                        }

                        $title = get_the_title();
                        print'<div class="pack-listing">';
                            print'<div class="pack-listing-title" data-stripetitle2="'.sanitize_title($title).'"  data-stripetitle="'.sanitize_title($title).' '.esc_attr__('Package Payment','wpestate').'" data-stripepay="'.($pack_price*100).'" data-packprice="'.esc_attr($pack_price).'" data-packid="'.esc_attr($postid).'">'.esc_html($title).' </div>';
                            print'<div class="submit-price">'.esc_html($price).'</div>';

                            print '<div class="pack-listing-period">'.esc_html($billing_freq).' '.wpestate_show_bill_period($biling_period).'</div>';
                            if($unlimited_listings==1){
                                print'<div class="pack-listing-period">'.esc_html__('Unlimited', 'wpestate').' '.esc_html__('listings ', 'wpestate').' </div>';
                            }else{
                                print'<div class="pack-listing-period">'.esc_html($pack_list).' '.esc_html__('Listings', 'wpestate').' </div>';
                            }

                            print'<div class="pack-listing-period">'.esc_html($pack_featured).' '.esc_html__('Featured', 'wpestate').'</div> ';

                        print'<div class="buypackage">';
                           esc_html_e('Select', 'wpestate');  
                        print '</div>';
                        print'</div>';//end pack listing;
                    }//end while 
                    wp_reset_query();?>
                </div>
            </div>
        </div>

        <div class="pack_description_row ">
            <div class="add-estate profile-page profile-onprofile row"> 
                <div class="pack-unit">
                    <div class="pack_description_unit_head">
                        <?php  print '<h4>'.esc_html__('Payment Method','wpestate').'</h4>'; ?>
                    </div>

                    <div id="package_pick">
                            <div class="recuring_wrapper">
                                <input type="checkbox" name="pack_recuring" id="pack_recuring" value="1" style="display:block;" /> 
                                <label for="pack_recurring"><?php esc_html_e('make payment recurring ','wpestate');?></label>
                            </div>

                        <?php
                            $enable_paypal_status= esc_html ( get_option('wp_estate_enable_paypal','') );
                            $enable_stripe_status= esc_html ( get_option('wp_estate_enable_stripe','') );
                            $enable_direct_status= esc_html ( get_option('wp_estate_enable_direct_pay','') );


                            if($enable_paypal_status==='yes'){
                                print '<div id="pick_pack"></div>';
                            }
                            if($enable_stripe_status==='yes'){
                                wpestate_show_stripe_form_membership();
                            }

                            if($enable_direct_status==='yes'){
                                print '<div id="direct_pay" class="wpresidence_button">'.esc_html__('Wire Transfer','wpestate').'</div>';
                            }
                        ?>
                    </div>
                </div> 
            </div>
        </div>
 
   
<?php } ?> 
    
</div>

           
<?php

function wpestate_show_bill_period($biling_period){

    if($biling_period=='Day' || $biling_period=='Days'){
        return  esc_html__('days','wpestate');
    }
    else if($biling_period=='Week' || $biling_period=='Weeks'){
       return  esc_html__('weeks','wpestate');
    }
    else if($biling_period=='Month' || $biling_period=='Months'){
        return  esc_html__('months','wpestate');
    }
    else if($biling_period=='Year'){
        return  esc_html__('year','wpestate');
    }

}

?>           