<?php
$current_user               =   wp_get_current_user();
$user_custom_picture        =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
$user_small_picture_id      =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
if( $user_small_picture_id == '' ){
    $user_small_picture[0]=get_template_directory_uri().'/img/default_user_small.png';
}else{
    $user_small_picture=wp_get_attachment_image_src($user_small_picture_id,'user_thumb');
    
}
?>

<div class="mobilewrapper">
    <div class="snap-drawers">
        <!-- Left Sidebar-->
        <div class="snap-drawer snap-drawer-left">
            <div class="mobilemenu-close"><i class="fa fa-times"></i></div>
            <?php  
   
                wp_nav_menu( array( 
                    'theme_location'  => 'mobile',               
                    'container'       =>  false,
                    'menu_class'      => 'mobilex-menu',
                    'menu_id'    => 'menu-main-menu'
                ) );
     
            ?>
        </div>
    </div>
</div>


<div class="mobilewrapper-user">
    <div class="snap-drawers">
        <!-- Left Sidebar-->
        <div class="snap-drawer snap-drawer-right">
            <div class="mobilemenu-close-user"><i class="fa fa-times"></i></div>
            <?php
            if ( 0 != $current_user->ID  && is_user_logged_in() ) {
                $username               =   $current_user->user_login ;
                $add_link               =   wpestate_get_dasboard_add_listing();
                $dash_profile           =   wpestate_get_dashboard_profile_link();
                $dash_favorite          =   wpestate_get_dashboard_favorites();
                $dash_link              =   wpestate_get_dashboard_link();
                $dash_searches          =   wpestate_get_searches_link();
                $logout_url             =   wp_logout_url();      
                $home_url               =   esc_url( home_url('/') );
                $dash_invoices          =   wpestate_get_invoice_link();
                   
                ?> 
            
              
                <ul class="  mobile_user_menu mobilex-menu " role="menu" aria-labelledby="user_menu_trigger"> 
                    <?php if($home_url!=$dash_profile){?>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php print esc_url($dash_profile);?>"  class="active_profile"><?php esc_html_e('My Profile','wpestate');?></a></li>    
                    <?php   
                    }
                    ?>

                    <?php if($home_url!=$dash_link){?>
                     <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php print esc_url($dash_link);?>"     class="active_dash"><?php esc_html_e('My Properties List','wpestate');?></a></li>

                    <?php   
                    }
                    ?>

                    <?php if($home_url!=$add_link){?>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php print esc_url($add_link);?>"      class="active_add"><?php esc_html_e('Add New Property','wpestate');?></a></li>

                    <?php   
                    }
                    ?>

                    <?php if($home_url!=$dash_favorite){?>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php print esc_url($dash_favorite);?>" class="active_fav"><?php esc_html_e('Favorites','wpestate');?></a></li>
                    <?php   
                    }
                    ?>

                    <?php if($home_url!=$dash_searches){?>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php print esc_url($dash_searches);?>" class="active_fav"><?php esc_html_e('Saved Searches','wpestate');?></a></li>
                    <?php   
                    }

                    if($home_url!=$dash_invoices){?>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php print esc_url($dash_invoices);?>" class="active_fav"><?php esc_html_e('My Invoices','wpestate');?></a></li>
                    <?php   
                    }
                    ?>
                    <li role="presentation"><a href="<?php echo wp_logout_url( esc_url( home_url('/') ));?>" title="Logout" class="menulogout"><?php esc_html_e('Log Out','wpestate');?></a></li>
                </ul>
    
                <?php
                }else{
              
                    $front_end_register =   esc_html( get_option('wp_estate_front_end_register','') );
                    $front_end_login    =   esc_html( get_option('wp_estate_front_end_login ','') );
                    $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
                    $google_status      =   esc_html( get_option('wp_estate_google_login','') );
                    $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );
                    $mess='';
                
                    ?>

                
                        <div class="login_sidebar">
                            <h3  id="login-div-title-mobile"><?php esc_html_e('Login','wpestate');?></h3>
                            <div class="login_form" id="login-div_mobile">
                                <div class="loginalert" id="login_message_area_mobile" > </div>

                                <input type="text" class="form-control" name="log" id="login_user_mobile" placeholder="<?php esc_html_e('Username','wpestate');?>"/>
                                <input type="password" class="form-control" name="pwd" id="login_pwd_mobile" placeholder="<?php esc_html_e('Password','wpestate');?>"/>
                                <input type="hidden" name="loginpop" id="loginpop_wd_mobile" value="0">
                                <?php //wp_nonce_field( 'login_ajax_nonce_mobile', 'security-login-mobile',true);?>   
                                <input type="hidden" id="security-login-mobile" name="security-login-mobile" value="<?php  echo estate_create_onetime_nonce( 'login_ajax_nonce_mobile' );?>">
     
                                <button class="wpresidence_button" id="wp-login-but-mobile"><?php esc_html_e('Login','wpestate');?></button>
                                <div class="login-links">
                                    <a href="#" id="widget_register_mobile"><?php esc_html_e('Need an account? Register here!','wpestate');?></a>
                                    <a href="#" id="forgot_pass_mobile"><?php esc_html_e('Forgot Password?','wpestate');?></a>
                                    <?php 
                                    if($facebook_status=='yes'){ 
                                    print '<div id="facebookloginsidebar_mobile" data-social="facebook">'.esc_html__('Login with Facebook','wpestate').'</div>';
                                    }
                                    if($google_status=='yes'){
                                        print '<div id="googleloginsidebar_mobile" data-social="google">'.esc_html__('Login with Google','wpestate').'</div>';
                                    }
                                    if($yahoo_status=='yes'){
                                        print '<div id="yahoologinsidebar_mobile" data-social="yahoo">'.esc_html__('Login with Yahoo','wpestate').'</div>';
                                    } 
                                    ?>
                                </div>    
                           </div>

                            <h3   id="register-div-title-mobile"><?php esc_html_e('Register','wpestate');?></h3>
                            <div class="login_form" id="register-div-mobile">

                                <div class="loginalert" id="register_message_area_mobile" ></div>
                                <input type="text" name="user_login_register" id="user_login_register_mobile" class="form-control" placeholder="<?php esc_html_e('Username','wpestate');?>"/>
                                <input type="text" name="user_email_register" id="user_email_register_mobile" class="form-control" placeholder="<?php esc_html_e('Email','wpestate');?>"  />

                                <?php
                                $enable_user_pass_status= esc_html ( get_option('wp_estate_enable_user_pass','') );
                                if($enable_user_pass_status == 'yes'){
                                    print ' <input type="password" name="user_password" id="user_password_mobile" class="form-control" placeholder="'.esc_html__('Password','wpestate').'"/>
                                    <input type="password" name="user_password_retype" id="user_password_mobile_retype" class="form-control" placeholder="'.esc_html__('Retype Password','wpestate').'"  />
                                    ';
                                }
                                ?>

                                <input type="checkbox" name="terms" id="user_terms_register_mobile" />
                                <label id="user_terms_register_mobile_label" for="user_terms_register_mobile"><?php esc_html_e('I agree with ','wpestate');?><a href="<?php print wpestate_get_terms_links();?> " target="_blank" id="user_terms_register_mobile_link"><?php esc_html_e('terms & conditions','wpestate');?></a> </label>
                                
                                <?php
                                if(get_option('wp_estate_use_captcha','')=='yes'){
                                    print '<div id="mobile_register_menu"  style="float:left;transform:scale(0.75);-webkit-transform:scale(0.75);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';
                                }
                                ?>
                                
                                <?php   if($enable_user_pass_status != 'yes'){  ?>
                                    <p id="reg_passmail_mobile"><?php esc_html_e('A password will be e-mailed to you','wpestate');?></p>
                                <?php } ?>

                                <?php //wp_nonce_field( 'register_ajax_nonce_mobile', 'security-register-mobile',true );?>   
                                <input type="hidden" id="security-register-mobile" name="security-register-mobile" value="<?php  echo estate_create_onetime_nonce( 'register_ajax_nonce_mobile' );?>">
      
                                <button class="wpresidence_button" id="wp-submit-register_mobile" ><?php esc_html_e('Register','wpestate');?></button>
                                <div class="login-links">
                                    <a href="#" id="widget_login_mobile"><?php esc_html_e('Back to Login','wpestate');?></a>                       
                                </div>   
                            </div>

                            <h3  id="forgot-div-title-mobile"><?php esc_html_e('Reset Password','wpestate');?></h3>
                            <div class="login_form" id="forgot-pass-div-mobile">
                                <div class="loginalert" id="forgot_pass_area_mobile"></div>
                                <div class="loginrow">
                                        <input type="text" class="form-control" name="forgot_email" id="forgot_email_mobile" placeholder="<?php esc_html_e('Enter Your Email Address','wpestate');?>" size="20" />
                                </div>
                                <?php echo  wp_nonce_field( 'forgot_ajax_nonce-mobile', 'security-forgot-mobile',true,false );;?>  
                                <input type="hidden" id="postid_mobile" value="<?php echo intval($post_id);?>">    
                                <button class="wpresidence_button" id="wp-forgot-but-mobile" name="forgot" ><?php esc_html_e('Reset Password','wpestate');?></button>
                                <div class="login-links shortlog">
                                <a href="#" id="return_login_mobile"><?php esc_html_e('Return to Login','wpestate');?></a>
                                </div>
                            </div>


                        </div>
                   
                    <?php }?>
            
            
        </div>
    </div>
</div>