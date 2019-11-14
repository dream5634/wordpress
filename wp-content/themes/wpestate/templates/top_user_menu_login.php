<?php 
if ( !is_user_logged_in() ) {
    $front_end_register     =   esc_html( get_option('wp_estate_front_end_register','') );
    $front_end_login        =   esc_html( get_option('wp_estate_front_end_login ','') );
    $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
    $google_status      =   esc_html( get_option('wp_estate_google_login','') );
    $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );
    $mess='';
 
    ?>

    <div id="modal_login_wpestate">

        <div id="modal_login_wpestate_background"></div>
         
        <div id="user_menu_open" class="dropdown-menu topmenux" >
            <div id="modal_login_wpestate_close"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="login_sidebar">
              
                <h3   id="login-div-title-topbar"><?php esc_html_e('Login','wpestate');?></h3>
                <div class="login_form" id="login-div_topbar">
                    <div class="loginalert" id="login_message_area_topbar" > </div>

                    <input type="text" class="form-control" name="log" id="login_user_topbar" placeholder="<?php esc_html_e('Username','wpestate');?>"/>
                    <input type="password" class="form-control" name="pwd" id="login_pwd_topbar" placeholder="<?php esc_html_e('Password','wpestate');?>"/>
                    <input type="hidden" name="loginpop" id="loginpop_wd_topbar" value="0">
                    <?php //wp_nonce_field( 'login_ajax_nonce_topbar', 'security-login-topbar',true);?>   
                    <input type="hidden" id="security-login-topbar" name="security-login-topbar" value="<?php  echo estate_create_onetime_nonce( 'login_ajax_nonce_topbar' );?>">

                    <button class="wpresidence_button" id="wp-login-but-topbar"><?php esc_html_e('Login','wpestate');?></button>
                    <div class="login-links">
                        <a href="#" id="widget_register_topbar"><?php esc_html_e('Register here!','wpestate');?></a>
                        <a href="#" id="forgot_pass_topbar"><?php esc_html_e('Forgot Password?','wpestate');?></a>
                        <?php 
                        if($facebook_status=='yes'){ 
                        print '<div id="facebookloginsidebar_topbar" data-social="facebook">'.esc_html__('Login with Facebook','wpestate').'</div>';
                        }
                        if($google_status=='yes'){
                            print '<div id="googleloginsidebar_topbar" data-social="google">'.esc_html__('Login with Google','wpestate').'</div>';
                        }
                        if($yahoo_status=='yes'){
                            print '<div id="yahoologinsidebar_topbar" data-social="yahoo">'.esc_html__('Login with Yahoo','wpestate').'</div>';
                        } 
                        ?>
                    </div>    
               </div>

                <h3  id="register-div-title-topbar"><?php esc_html_e('Register','wpestate');?></h3>
                <div class="login_form" id="register-div-topbar">
                    <?php  
                    $enable_user_pass_status= esc_html ( get_option('wp_estate_enable_user_pass','') );
                    if($enable_user_pass_status != 'yes'){  ?>
                        <p id="reg_passmail_topbar"><?php esc_html_e('A password will be e-mailed to you','wpestate');?></p>
                    <?php } ?>
                    <div class="loginalert" id="register_message_area_topbar" ></div>
                    <input type="text" name="user_login_register" id="user_login_register_topbar" class="form-control" placeholder="<?php esc_html_e('Username','wpestate');?>"/>
                    <input type="text" name="user_email_register" id="user_email_register_topbar" class="form-control" placeholder="<?php esc_html_e('Email','wpestate');?>"  />

                    <?php
                  
                    if($enable_user_pass_status == 'yes'){
                        print ' <input type="password" name="user_password" id="user_password_topbar" class="form-control" placeholder="'.esc_html__('Password','wpestate').'"/>
                        <input type="password" name="user_password_retype" id="user_password_topbar_retype" class="form-control" placeholder="'.esc_html__('Retype Password','wpestate').'"  />
                        ';
                    }
                    ?>
                    <div class="login-links">
                        <input type="checkbox" name="terms" id="user_terms_register_topbar" />
                        <label id="user_terms_register_topbar_label" for="user_terms_register_topbar"><?php esc_html_e('I agree with ','wpestate');?><a href="<?php print esc_url(wpestate_get_terms_links());?> " target="_blank" id="user_terms_register_topbar_link"><?php esc_html_e('terms & conditions','wpestate');?></a> </label>
                    </div>

                    <?php
                    if(get_option('wp_estate_use_captcha','')=='yes'){
                        print '<div id="top_register_menu" style="float:left;transform:scale(0.75);-webkit-transform:scale(0.75);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';
                    }
                    ?>

                     <a href="#" id="widget_login_topbar"><?php esc_html_e('Back to Login','wpestate');?></a>                       
                 
                    <input type="hidden" id="security-register-topbar" name="security-register-topbar" value="<?php  echo estate_create_onetime_nonce( 'register_ajax_nonce_topbar' );?>">
                    <button class="wpresidence_button" id="wp-submit-register_topbar" ><?php esc_html_e('Register','wpestate');?></button>
                      
                </div>

                <h3   id="forgot-div-title-topbar"><?php esc_html_e('Reset Password','wpestate');?></h3>
                <div class="login_form" id="forgot-pass-div">
                    <div class="loginalert" id="forgot_pass_area_topbar"></div>
                    <div class="loginrow">
                            <input type="text" class="form-control" name="forgot_email" id="forgot_email_topbar" placeholder="<?php esc_html_e('Enter Your Email Address','wpestate');?>" size="20" />
                    </div>
                    <?php echo wp_nonce_field( 'forgot_ajax_nonce-topbar', 'security-forgot-topbar',true,false );?>  
                    <input type="hidden" class="postid" value="<?php echo intval($post_id);?>">    
                    <button class="wpresidence_button" id="wp-forgot-but-topbar" name="forgot" ><?php esc_html_e('Reset Password','wpestate');?></button>
                    <div class="login-links shortlog">
                    <a href="#" id="return_login_topbar"><?php esc_html_e('Return to Login','wpestate');?></a>
                    </div>
                </div>


            </div>
        </div>
    </div>
<?php }?>