<?php
if( !function_exists('wpestate_new_general_set') ):
function wpestate_new_general_set() {  
    if ( ! empty( $_POST ) ) {

       
       if (  ! isset( $_POST['wpestate_setup_nonce_field'] ) || ! wp_verify_nonce( $_POST['wpestate_setup_nonce_field'], 'wpestate_setup_nonce' ) 
        ) {
           esc_html_e('Sorry, your nonce did not verify.','wpestate');
           exit;
           
        } 

        $allowed_html   =   array();
        
        // cusotm fields
        if( isset( $_POST['add_field_name'] ) ){
            $new_custom=array();  
            foreach( $_POST['add_field_name'] as $key=>$value ){
                $temp_array=array();
                $temp_array[0]=$value;
                $temp_array[1]= wp_kses( $_POST['add_field_label'][sanitize_key($key)] ,$allowed_html);
                $temp_array[2]= wp_kses( $_POST['add_field_type'][sanitize_key($key)] ,$allowed_html);
                $temp_array[3]= wp_kses ( $_POST['add_field_order'][sanitize_key($key)],$allowed_html);
                $temp_array[4]=  ( $_POST['add_dropdown_order'][sanitize_key($key)]);
                $new_custom[]=$temp_array;
            }

          
            usort($new_custom,"wpestate_sorting_function");
            update_option( 'wp_estate_custom_fields', $new_custom );   
        }
       
       // multiple currencies
        if( isset( $_POST['add_curr_name'] ) ){
            foreach( $_POST['add_curr_name'] as $key=>$value ){
                $temp_array=array();
                $temp_array[0]=$value;
                $temp_array[1]= wp_kses( $_POST['add_curr_label'][sanitize_key($key)] ,$allowed_html);
                $temp_array[2]= wp_kses( $_POST['add_curr_value'][sanitize_key($key)] ,$allowed_html);
                $temp_array[3]= wp_kses( $_POST['add_curr_order'][sanitize_key($key)] ,$allowed_html);
                $new_custom_cur[]=$temp_array;
            }
            
            update_option( 'wp_estate_multi_curr', $new_custom_cur );   

       }else{
           
       }

       
       

        if( isset( $_POST['theme_slider'] ) ){
            update_option( 'wp_estate_theme_slider', true);  
        }
        
       
        $permission_array=array(
            'add_field_name',
            'add_field_label',
            'add_field_type',
            'add_field_order',
            'adv_search_how',
            'adv_search_what',
            'adv_search_label',
        );
        
        $tags_array=array(
            'co_address',
            'direct_payment_details',
            'new_user',
            'admin_new_user',
            'purchase_activated',
            'password_reset_request',
            'password_reseted',
            'purchase_activated',
            'approved_listing',
            'new_wire_transfer',
            'admin_new_wire_transfer',
            'admin_expired_listing',
            'matching_submissions',
            'paid_submissions',
            'featured_submission',
            'account_downgraded',
            'membership_cancelled',
            'downgrade_warning',
            'free_listing_expired',
            'new_listing_submission' ,
            'listing_edit',
            'recurring_payment',
            'subject_new_user',
            'subject_admin_new_user',
            'subject_purchase_activated',
            'subject_password_reset_request',
            'subject_password_reseted',
            'subject_purchase_activated',
            'subject_approved_listing',
            'subject_new_wire_transfer',
            'subject_admin_new_wire_transfer',
            'subject_admin_expired_listing',
            'subject_matching_submissions',
            'subject_paid_submissions',
            'subject_featured_submission',
            'subject_account_downgraded',
            'subject_membership_cancelled',
            'subject_downgrade_warning',
            'subject_free_listing_expired',
            'subject_new_listing_submission' ,
            'subject_listing_edit',
            'subject_recurring_payment'
        );
        
         foreach($_POST as $variable=>$value){	
            if ($variable!='submit'){
                if (!in_array($variable, $permission_array)){
                    $variable   =   sanitize_key($variable);
                    if( in_array($variable, $tags_array) ){
                        $allowed_html_br=array(
                                'br' => array(),
                                'em' => array(),
                                'strong' => array()
                        );
                        $postmeta   =   wp_kses($value,$allowed_html_br);
                    }else{
                        $postmeta   =   wp_kses($value,$allowed_html);
                    
                    }   
                    update_option( wpestate_limit64('wp_estate_'.$variable), $postmeta );                
                }else{
                
                    update_option( 'wp_estate_'.$variable, $value );
                }	
            }	
        }
        
        if(isset($_POST['copyright_message'])){
             $allowed_html_link=array(
                                'a' => array(
                                'href' => array(),
                                'title' => array()
                            ),
                            'br' => array(),
                            'em' => array(),
                            'strong' => array(),
                        );
            $value=wp_kses($_POST['copyright_message'],$allowed_html_link);
             update_option( 'wp_estate_copyright_message', $value );
        }
        
        
        if( isset($_POST['is_custom']) && $_POST['is_custom']== 1 && !isset($_POST['add_field_name']) ){
            update_option( 'wp_estate_custom_fields', '' ); 
        }
        
        if( isset($_POST['is_custom_cur']) && $_POST['is_custom_cur']== 1 && !isset($_POST['add_curr_name']) ){
            update_option( 'wp_estate_multi_curr', '' );
        }
    
        if (isset($_POST['show_save_search'])){
            $allowed_html=array();
            $show_save_search= wp_kses( $_POST['show_save_search'],$allowed_html );
            $search_alert= wp_kses( $_POST['search_alert'],$allowed_html );
            wp_estate_schedule_email_events( $show_save_search,$search_alert);
          
        }
        
       
        if ( isset( $_POST['paid_submission']) ){
            if ( $_POST['paid_submission']=='membership'){
                wp_estate_schedule_user_check();  
            }else{
                wp_clear_scheduled_hook('wpestate_check_for_users_event');
            }
        }
        
        if ( isset($_POST['auto_curency']) ){
            if( $_POST['auto_curency']=='yes' ){
                wp_estate_enable_load_exchange();
            }else{
                wp_clear_scheduled_hook('wpestate_load_exchange_action');
            }
        }
        
        if(isset($_POST['url_rewrites'])){
            flush_rewrite_rules();
        }
        
 
        if ( isset( $_POST['is_submit_page'] ) && $_POST['is_submit_page']== 1 ){
            
            if( !isset($_POST['mandatory_page_fields'])){
                update_option('wp_estate_mandatory_page_fields','');
            } 
            if( !isset($_POST['submission_page_fields'])){
                update_option('wp_estate_submission_page_fields','');
            }             
            
        }
       
       
        
}
    

    
$allowed_html   =   array();  
$active_tab = isset( $_GET[ 'tab' ] ) ? wp_kses( $_GET[ 'tab' ],$allowed_html ) : 'general_settings';  


print '<div class="wrap">';
    print '<div class="wrap-topbar">';
        
        $hidden_tab='none';
        if(isset($_POST['hidden_tab'])) {
            $hidden_tab= esc_attr( $_POST['hidden_tab'] );
        }
        
        $hidden_sidebar='none';
        if(isset($_POST['hidden_sidebar'])) {
            $hidden_sidebar= esc_attr( $_POST['hidden_sidebar'] );
        }
        
        print '<input type="hidden" id="hidden_tab" name="hidden_tab" value="'.$hidden_tab.'">';        
        print '<input type="hidden" id="hidden_sidebar"  name="hidden_sidebar" value="'.$hidden_sidebar.'">';
        
        print   '<div id="general_settings" data-menu="general_settings_sidebar" class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/general.png'.'" alt="'.esc_attr__('general','wpestate').'">'.esc_html__('General','wpestate').'
                </div>';
        
        print   '<div id="social_contact" data-menu="social_contact_sidebar" class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/contact.png'.'" alt="'.esc_attr__('social','wpestate').'">'.esc_html__('Social','wpestate').'
                </div>';
        
        print   '<div id="map_settings" data-menu="map_settings_sidebar" class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/map.png'.'" alt="'.esc_attr__('map','wpestate').'">'.esc_html__('Map','wpestate').'
                </div>';
         
        print   '<div id="design_settings" data-menu="design_settings_sidebar" class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/design.png'.'" alt="'.esc_attr__('design','wpestate').'">'.esc_html__('Design','wpestate').'
                </div>';
        
        print   '<div id="advanced_settings" data-menu="advanced_settings_sidebar" class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/advanced.png'.'" alt="'.esc_attr__('advanced','wpestate').'">'.esc_html__('Advanced','wpestate').'
                </div>';
            
        print   '<div id="membership_settings" data-menu="membership_settings_sidebar"  class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/membership.png'.'" alt="'.esc_attr__('membership','wpestate').'">'.esc_html__('Membership','wpestate').'
                </div>';
        
        print   '<div id="advanced_search_settings" data-menu="advanced_search_settings_sidebar"  class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/search.png'.'" alt="'.esc_attr__('search','wpestate').'">'.esc_html__('Search','wpestate').'
                </div>';
        
        print   '<div id="help_custom" data-menu="help_custom_sidebar"  class="admin_top_bar_button"> 
                    <img src="'.get_template_directory_uri().'/img/admin/help.png'.'" alt="'.esc_attr__('help','wpestate').'">'.esc_html__('Help','wpestate').'
                </div>';
        
        print '<div class="theme_details">'. wp_get_theme().'</div>';
        
    print '</div>';


    print '
    <div id="wpestate_sidebar_menu">
        <div id="general_settings_sidebar" class="theme_options_sidebar">
            <ul>
                <li data-optiontab="global_settings_tab" class="selected_option">'.esc_html__('Global Theme Settings','wpestate').'</li>
                <li data-optiontab="appearance_options_tab"   class="">'.esc_html__('Appearance','wpestate').'</li>
                <li data-optiontab="logos_favicon_tab"   class="">'.esc_html__('Logos','wpestate').'</li>
                <li data-optiontab="header_settings_tab"   class="">'.esc_html__('Header','wpestate').'</li>
                <li data-optiontab="footer_settings_tab"   class="">'.esc_html__('Footer','wpestate').'</li>
                <li data-optiontab="price_curency_tab"   class="">'.esc_html__('Price & Currency','wpestate').'</li>
                <li data-optiontab="custom_fields_tab"   class="">'.esc_html__('Custom Fields','wpestate').'</li>
                <li data-optiontab="ammenities_features_tab"   class="">'.esc_html__('Features & Amenities','wpestate').'</li>
                <li data-optiontab="listing_labels_tab"   class="">'.esc_html__('Listings Labels','wpestate').'</li>   
                <li data-optiontab="theme_slider_tab"   class="">'.esc_html__('Theme Slider','wpestate').'</li>   
                <li data-optiontab="property_rewrite_page_tab" class="">'.esc_html__('Edit Agent and Page links','wpestate').'</li>
            </ul>
        </div>
        
        <div id="social_contact_sidebar" class="theme_options_sidebar" style="display:none;">
            <ul>
                <li data-optiontab="contact_details_tab" class="">'.esc_html__('Contact Details','wpestate').'</li>
                <li data-optiontab="social_accounts_tab" class="">'.esc_html__('Social Accounts','wpestate').'</li>
                <li data-optiontab="contact7_tab" class="">'.esc_html__('Contact 7 Settings','wpestate').'</li>
            </ul>
        </div>
        

        <div id="map_settings_sidebar" class="theme_options_sidebar" style="display:none;">
            <ul>
                <li data-optiontab="general_map_tab" class="">'.esc_html__('Map Settings','wpestate').'</li>
                <li data-optiontab="pin_management_tab" class="">'.esc_html__('Pins Management','wpestate').'</li>
                <li data-optiontab="generare_pins_tab" class="">'.esc_html__('Generate Pins','wpestate').'</li>
            </ul>
        </div>
        

        <div id="design_settings_sidebar" class="theme_options_sidebar" style="display:none;">
            <ul>
                <li data-optiontab="general_design_settings_tab" class="">'.esc_html__('General Design Settings','wpestate').'</li>
                <li data-optiontab="property_page_tab" class="">'.esc_html__('Property Page','wpestate').'</li>
                <li data-optiontab="custom_colors_tab" class="">'.esc_html__('Custom Colors','wpestate').'</li>
                <li data-optiontab="custom_fonts_tab" class="">'.esc_html__('Fonts','wpestate').'</li>
                <li data-optiontab="mainmenu_design_elements_tab" class="">'.esc_html__('Main Menu Design','wpestate').'</li>
                <li data-optiontab="widget_design_elements_tab" class="">'.esc_html__('Sidebar Widget Design','wpestate').'</li>
                <li data-optiontab="print_page_tab" class="">'.esc_html__('Property Print Page Design','wpestate').'</li>
                <li data-optiontab="wpestate_user_dashboard_design_tab" class="">'.esc_html__('User Dashboard Design','wpestate').'</li>
             
            </ul>
        </div>
        
        <div id="advanced_search_settings_sidebar" class="theme_options_sidebar" style="display:none;">
            <ul>
                <li data-optiontab="advanced_search_settings_tab" class="">'.esc_html__('Advanced Search Settings','wpestate').'</li>
                <li data-optiontab="advanced_search_form_tab" class="">'.esc_html__('Advanced Search Form','wpestate').'</li>
            </ul>
        </div>
        
        <div id="membership_settings_sidebar" class="theme_options_sidebar" style="display:none;">
            <ul>
                <li data-optiontab="membership_settings_tab" class="">'.esc_html__('Membership Settings','wpestate').'</li>
                <li data-optiontab="property_submission_page_tab" class="">'.esc_html__('Property Submission Page','wpestate').'</li>
                <li data-optiontab="paypal_settings_tab" class="">'.esc_html__('Paypal Settings','wpestate').'</li>
                <li data-optiontab="stripe_settings_tab" class="">'.esc_html__('Stripe Settings','wpestate').'</li>
            </ul>
        </div>

 
        <div id="advanced_settings_sidebar" class="theme_options_sidebar" style="display:none;">
             <ul>
                <li data-optiontab="email_management_tab" class="selected_option">'.esc_html__('Email Management','wpestate').'</li>
                <li data-optiontab="speed_management_tab" class="selected_option">'.esc_html__('Site Speed','wpestate').'</li>
                <li data-optiontab="export_settings_tab" class="selected_option">'.esc_html__('Export Options','wpestate').'</li>
                <li data-optiontab="import_settings_tab" class="selected_option">'.esc_html__('Import Options','wpestate').'</li>
                <li data-optiontab="recaptcha_tab" class="selected_option">'.esc_html__('reCaptcha settings','wpestate').'</li>
                <li data-optiontab="yelp_tab" class="selected_option">'.esc_html__('Yelp settings','wpestate').'</li>
                <li data-optiontab="optima_express_tab" class="selected_option">'.esc_html__('Optima Express  settings','wpestate').'</li>
            </ul>
        </div>
        
        <div id="help_custom_sidebar" class="theme_options_sidebar" style="display:none;">
             <ul>
                <li data-optiontab="help_custom_tab" class="selected_option">'.esc_html__('Help & Custom','wpestate').'</li>
            </ul>
        </div>
    </div>';
    
    

    print ' <div id="wpestate_wrapper_admin_menu"> 
                <div id="general_settings_sidebar_tab" class="theme_options_wrapper_tab">
                    <form method="post" action="" >
                        ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="global_settings_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('General Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';    
                            wpestate_new_theme_admin_general_settings();
                        print '        
                        </div>
                    </form>

                    <form method="post" action="">
                    ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="appearance_options_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Appearance','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_appeareance();
                    print '        
                    </div>
                    </form>

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="logos_favicon_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Logos & Favicon','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_theme_admin_logos_favicon();
                    print '        
                    </div>
                    </form>

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="header_settings_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Header Settings','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_header_settings();
                    print '        
                    </div>
                    </form>

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="footer_settings_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Footer Settings','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_footer_settings();
                    print '        
                    </div>
                    </form>

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="price_curency_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Price & Currency','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_price_currency();
                    print '        
                    </div>
                    </form>

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="custom_fields_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Custom Fields','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_custom_fields();
                    print '        
                    </div>
                    </form>
                    
                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="ammenities_features_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Features & Amenities','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_ammenities_features();
                    print '        
                    </div>
                    </form>

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="listing_labels_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Listings Labels','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_listing_labels();
                    print '        
                    </div>
                    </form>
                    

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="property_rewrite_page_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Property and Agent Links','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_property_links();
                    print '        
                    </div>
                    </form>
                    

                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="theme_slider_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Theme Slider ','wpestate').'</div>
                        <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                        <div class="theme_option_separator"></div>';
                        wpestate_new_theme_slider();
                    print '        
                    </div>
                    </form>
 
                </div>
                
                <div id="social_contact_sidebar_tab" class="theme_options_wrapper_tab" style="display:none">
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="contact_details_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Contact Details','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_theme_contact_details();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="social_accounts_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Social Accounts ','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_theme_social_accounts();
                        print '        
                        </div>
                        </form>

                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="contact7_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Contact 7 Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_contact7();
                        print '        
                        </div>
                        </form>
                </div> 




                <div id="map_settings_sidebar_tab" class="theme_options_wrapper_tab" style="display:none">
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="general_map_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Map  Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_map_details();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="pin_management_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Pin Management','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_pin_management();
                        print '        
                        </div>
                        </form>

                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="generare_pins_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Generate Pins','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_generate_pins();
                        print '        
                        </div>
                        </form>
                </div> 




                
                <div id="design_settings_sidebar_tab" class="theme_options_wrapper_tab" style="display:none">
                


                       

                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="property_page_tab" class="theme_options_tab" style="display:none;" >
                            <div class="wpestate_replace_h1">'.esc_html__('Property Page Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_property_page_details();
                        print '        
                        </div>
                        </form>
                        
                        
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="mainmenu_design_elements_tab" class="theme_options_tab" style="display:none;" >
                            <div class="wpestate_replace_h1">'.esc_html__('Main Menu Design Tab','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_main_menu_design();
                        print '        
                        </div>
                        </form>


                      
                        
                      
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="print_page_tab" class="theme_options_tab" style="display:none;" >
                            <div class="wpestate_replace_h1">'.esc_html__('Print Page Design','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_print_page_design();
                        print '        
                        </div>
                        </form>
                        

                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="wpestate_user_dashboard_design_tab" class="theme_options_tab" style="display:none;" >
                            <div class="wpestate_replace_h1">' .esc_html__('User Dashboard Design', 'wpestate') . '</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="' . esc_html__('Save Changes', 'wpestate') . '" />
                            <div class="theme_option_separator"></div>';
                            wpestate_user_dashboard_design();
                        print '        
                        </div>
                        </form>


                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="widget_design_elements_tab" class="theme_options_tab" style="display:none;" >
                            <div class="wpestate_replace_h1">'.esc_html__('Sidebar Widget Tab','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_widget_design_elements_details();
                        print '        
                        </div>
                        </form>



                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="general_design_settings_tab" class="theme_options_tab" style="display:none;" >
                            <div class="wpestate_replace_h1">'.esc_html__('General Design Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_general_design_settings();
                        print '        
                        </div>
                        </form>
                        


                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="custom_colors_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Custom Colors Settings','wpestate').'</div>
                            <span class="header_explanation">'.esc_html__('***Please understand that we cannot add here color controls for all theme elements & details. Doing that will result in a overcrowded and useless interface. These small details need to be addressed via custom css code','wpestate').'</span>    
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_custom_colors();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="custom_fonts_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Custom Fonts','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_custom_fonts();
                        print '        
                        </div>
                        </form>


                </div> 
                
                <div id="advanced_search_settings_sidebar_tab" class="theme_options_wrapper_tab"  style="display:none">
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="advanced_search_settings_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Advanced Search Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_advanced_search_settings();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="advanced_search_form_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Advanced Search Form','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_advanced_search_form();
                        print '        
                        </div>
                        </form>



                       
                </div> 
                


                <div id="membership_settings_sidebar_tab" class="theme_options_wrapper_tab" style="display:none">
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="membership_settings_tab" class="theme_options_tab"  style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Membership Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_membership_settings();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="paypal_settings_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('PaypPal Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_paypal_settings();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="stripe_settings_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Stripe Settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_stripe_settings();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="property_submission_page_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Property Submission Page','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_property_submission_tab();
                        print '        
                        </div>
                        </form>





                </div> 
                
             

                <div id="advanced_settings_sidebar_tab" class="theme_options_wrapper_tab" style="display:none">
                
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="email_management_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Email Management','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_email_management();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="speed_management_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Site Speed','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_site_speed();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="export_settings_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Export Options','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_new_export_settings();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="import_settings_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Import Options','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            if(function_exists('wpestate_new_import_options_tab')){
                                 wpestate_new_import_options_tab();
                            }
                        print '        
                        </div>
                        </form>

                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="recaptcha_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('reCaptcha settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            estate_recaptcha_settings();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="yelp_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Yelp settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            estate_yelp_settings();
                        print '        
                        </div>
                        </form>
                        
                        <form method="post" action="">
                         ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                        <div id="optima_express_tab" class="theme_options_tab" style="display:none;">
                            <div class="wpestate_replace_h1">'.esc_html__('Optima Express settings','wpestate').'</div>
                            <input type="submit" name="submit"  class="new_admin_submit new_admin_submit_right" value="'.esc_html__('Save Changes','wpestate').'" />
                            <div class="theme_option_separator"></div>';
                            wpestate_optima_express_settings();
                        print '        
                        </div>
                        </form>
                        


     
                </div> 


                <div id="help_custom_sidebar_tab" class="theme_options_wrapper_tab">
                    <form method="post" action="">
                     ';wp_nonce_field( 'wpestate_setup_nonce', 'wpestate_setup_nonce_field' );print'
                    <div id="help_custom_tab" class="theme_options_tab" style="display:none;">
                        <div class="wpestate_replace_h1">'.esc_html__('Help&Custom','wpestate').'</div>
                        <div class="theme_option_separator"></div>';
                        wpestate_new_help_custom();
                    print '        
                    </div>
                    </form>
                </div>


           </div>';

print '</div>';



 
   
}
endif; // end   wpestate_new_general_set  


if( !function_exists('wpestate_generate_file_pins') ):
function   wpestate_generate_file_pins(){
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__('Generate pins','wpestate').'</div>';
     print '<a href="http://help.wpresidence.net/#!/googlemaps" target="_blank" class="help_link">'.esc_html__('help','wpestate').'</a>';
  
    print '<table class="form-table">   <tr valign="top">
           <td>';  
          
  
    
    print '</td>
           </tr></table>';
    print '</div>';   
}
endif;


if( !function_exists('wpestate_show_advanced_search_options') ):

function  wpestate_show_advanced_search_options($i,$adv_search_what){
    $return_string='';

    $curent_value='';
    if(isset($adv_search_what[$i])){
        $curent_value=$adv_search_what[$i];        
    }
    
   // $curent_value=$adv_search_what[$i];
    $admin_submission_array=array('types',
                                  'categories',
                                  'county / state',
                                  'cities',
                                  'areas',
                                  'property price',
                                  'property size',
                                  'property lot size',
                                  'property rooms',
                                  'property bedrooms',
                                  'property bathrooms',
                                  'property address',                               
                                  'property zip',
                                  'property country',
                                  'property status',
                                  'property id',
                                  'keyword'
                                );
    
    foreach($admin_submission_array as $value){

        $return_string.='<option value="'.$value.'" '; 
        if($curent_value==$value){
             $return_string.= ' selected="selected" ';
        }
        $return_string.= '>'.$value.'</option>';    
    }
    
    $i=0;
    $custom_fields = get_option( 'wp_estate_custom_fields', true); 
    if( !empty($custom_fields)){  
        while($i< count($custom_fields) ){          
            $name =   $custom_fields[$i][0];
            $type =   $custom_fields[$i][1];
            $slug =   str_replace(' ','-',$name);

            $return_string.='<option value="'.$slug.'" '; 
            if($curent_value==$slug){
               $return_string.= ' selected="selected" ';
            }
            $return_string.= '>'.$name.'</option>';    
            $i++;  
        }
    }  
    $slug='none';
    $name='none';
    $return_string.='<option value="'.$slug.'" '; 
    if($curent_value==$slug){
        $return_string.= ' selected="selected" ';
    }
    $return_string.= '>'.$name.'</option>';    

       
    return $return_string;
}
endif; // end   wpestate_show_advanced_search_options  



if( !function_exists('wpestate_show_advanced_search_how') ):
function  wpestate_show_advanced_search_how($i,$adv_search_how){
    $return_string='';
    $curent_value='';
    if (isset($adv_search_how[$i])){
         $curent_value=$adv_search_how[$i];
    }
   
    
    
    $admin_submission_how_array=array('equal',
                                      'greater',
                                      'smaller',
                                      'like',
                                      'date bigger',
                                      'date smaller');
    
    foreach($admin_submission_how_array as $value){
        $return_string.='<option value="'.$value.'" '; 
        if($curent_value==$value){
             $return_string.= ' selected="selected" ';
        }
        $return_string.= '>'.$value.'</option>';    
    }
    return $return_string;
}
endif; // end   wpestate_show_advanced_search_how  





if( !function_exists('wpestate_dropdowns_theme_admin') ):
    function wpestate_dropdowns_theme_admin($array_values,$option_name,$pre=''){
        
        $dropdown_return    =   '';
        $option_value       =   esc_html ( get_option('wp_estate_'.$option_name,'') );
        foreach($array_values as $value){
            $dropdown_return.='<option value="'.$value.'"';
              if ( $option_value == $value ){
                $dropdown_return.='selected="selected"';
            }
            $dropdown_return.='>'.$pre.$value.'</option>';
        }
        
        return $dropdown_return;
        
    }
endif;




if( !function_exists('wpestate_dropdowns_theme_admin_with_key') ):
    function wpestate_dropdowns_theme_admin_with_key($array_values,$option_name){
        
        $dropdown_return    =   '';
        $option_value       =   esc_html ( get_option('wp_estate_'.$option_name,'') );
        foreach($array_values as $key=>$value){
            $dropdown_return.='<option value="'.$key.'"';
              if ( $option_value == $key ){
                $dropdown_return.='selected="selected"';
            }
            $dropdown_return.='>'.$value.'</option>';
        }
        
        return $dropdown_return;
        
    }
endif;






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Membership Settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_theme_admin_membershipsettings') ):
function wpestate_theme_admin_membershipsettings(){
    $price_submission               =   floatval( get_option('wp_estate_price_submission','') );
    $price_featured_submission      =   floatval( get_option('wp_estate_price_featured_submission','') );    
    $paypal_client_id               =   esc_html( get_option('wp_estate_paypal_client_id','') );
    $paypal_client_secret           =   esc_html( get_option('wp_estate_paypal_client_secret','') );
    $paypal_rec_email               =   esc_html( get_option('wp_estate_paypal_rec_email','') );
    $free_feat_list                 =   esc_html( get_option('wp_estate_free_feat_list','') );
    $free_mem_list                  =   esc_html( get_option('wp_estate_free_mem_list','') );
    $cache_array                    =   array('yes','no');  
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );
    
    $args=array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
    );
     $direct_payment_details         =   wp_kses( get_option('wp_estate_direct_payment_details','') ,$args);
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $free_mem_list_unl='';
    if ( intval( get_option('wp_estate_free_mem_list_unl', '' ) ) == 1){
        $free_mem_list_unl=' checked="checked" ';  
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    $paypal_array                   =   array( 'sandbox','live' );
    $paypal_api_select              =   wpestate_dropdowns_theme_admin($paypal_array,'paypal_api');

    $submission_curency_array       =   array(get_option('wp_estate_submission_curency_custom',''),'USD','EUR','AUD','BRL','CAD','CZK','DKK','HKD','HUF','ILS','INR','JPY','MYR','MXN','NOK','NZD','PHP','PLN','GBP','SGD','SEK','CHF','TWD','THB','TRY');
    $submission_curency_symbol      =   wpestate_dropdowns_theme_admin($submission_curency_array,'submission_curency');
    
    $paypal_array                   =   array('no','per listing','membership');
    $paid_submission_symbol         =   wpestate_dropdowns_theme_admin($paypal_array,'paid_submission');
    $admin_submission_symbol        =   wpestate_dropdowns_theme_admin($cache_array,'admin_submission');
    $user_agent_symbol              =   wpestate_dropdowns_theme_admin($cache_array,'user_agent');
    $enable_paypal_symbol           =   wpestate_dropdowns_theme_admin($cache_array,'enable_paypal');
    $enable_stripe_symbol           =   wpestate_dropdowns_theme_admin($cache_array,'enable_stripe');
    $enable_direct_pay_symbol       =   wpestate_dropdowns_theme_admin($cache_array,'enable_direct_pay');
   
    
    
    $free_feat_list_expiration= intval ( get_option('wp_estate_free_feat_list_expiration','') );
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__('Membership & Payment Settings','wpestate').'</div>';  
    print '<a href="http://help.wpresidence.net/#!/freesubmission" target="_blank" class="help_link">'.esc_html__('help','wpestate').'</a>';
  
    print '
        <table class="form-table">
        
        <tr valign="top">
            <th scope="row"><label for="admin_submission">'.esc_html__('Submited Listings should be approved by admin?','wpestate').'</label></th>
           
            <td> <select id="admin_submission" name="admin_submission">
                    '.$admin_submission_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="user_agent">'.esc_html__('Front end registred users should be saved as agents?','wpestate').'</label></th>
           
            <td> <select id="user_agent" name="user_agent">
                    '.$user_agent_symbol.'
		 </select>
            </td>
        </tr>
        

         <tr valign="top">
            <th scope="row"><label for="paid_submission">'.esc_html__('Enable Paid Submission ?','wpestate').'</label></th>
           
            <td> <select id="paid_submission" name="paid_submission">
                    '.$paid_submission_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="enable_paypal">'.esc_html__('Enable Paypal?','wpestate').'</label></th>
           
            <td> <select id="enable_paypal" name="enable_paypal">
                    '.$enable_paypal_symbol.'
		 </select>
            </td>
        </tr>

     
        
        <tr valign="top">
            <th scope="row"><label for="enable_stripe">'.esc_html__('Enable Stripe?','wpestate').'</label></th>
           
            <td> <select id="enable_stripe" name="enable_stripe">
                    '.$enable_stripe_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="enable_direct_pay">'.esc_html__('Enable Direct Payment / Wire Payment?','wpestate').'</label></th>
           
            <td> <select id="enable_direct_pay" name="enable_direct_pay">
                    '.$enable_direct_pay_symbol.'
		 </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="submission_curency">'.esc_html__('Currency For Paid Submission','wpestate').'</label></th>
            <td>
                <select id="submission_curency" name="submission_curency">
                    '.$submission_curency_symbol.'
                </select> 
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="submission_curency_custom">'.esc_html__('Custom Currency Symbol - *select it from the list above after you add it.','wpestate').'</label></th>
            <td>
               <input type="text" id="submission_curency_custom" name="submission_curency_custom" class="regular-text"  value="'.get_option('wp_estate_submission_curency_custom','').'"/>
            </td>
        </tr>

         <tr valign="top">
            <th scope="row"><label for="paypal_client_id">'.esc_html__('Paypal Client id','wpestate').'</label></th>
            <td><input  type="text" id="paypal_client_id" name="paypal_client_id" class="regular-text"  value="'.$paypal_client_id.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="paypal_client_secret ">'.esc_html__('Paypal Client Secret Key ','wpestate').'</label></th>
            <td><input  type="text" id="paypal_client_secret" name="paypal_client_secret"  class="regular-text" value="'.$paypal_client_secret.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="paypal_api">'.esc_html__('Paypal & Stripe Api ','wpestate').'</label></th>
            <td>
              <select id="paypal_api" name="paypal_api">
                    '.$paypal_api_select.'
                </select>
            </td>
        </tr>
        
       
        
        <tr valign="top">
            <th scope="row"><label for="paypal_rec_email">'.esc_html__('Paypal receiving email','wpestate').'</label></th>
            <td><input  type="text" id="paypal_rec_email" name="paypal_rec_email"  class="regular-text" value="'.$paypal_rec_email.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="stripe_secret_key">'.esc_html__('Stripe Secret Key','wpestate').'</label></th>
            <td><input  type="text" id="stripe_secret_key" name="stripe_secret_key"  class="regular-text" value="'.$stripe_secret_key.'"/> </td>
        </tr>
       
        <tr valign="top">
            <th scope="row"><label for="stripe_publishable_key">'.esc_html__('Stripe Publishable Key','wpestate').'</label></th>
            <td><input  type="text" id="stripe_publishable_key" name="stripe_publishable_key" class="regular-text" value="'.$stripe_publishable_key.'"/> </td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="direct_payment_details">'.esc_html__('Wire instructions for direct payment','wpestate').'</label></th>
            <td><textarea id="direct_payment_details" name="direct_payment_details"  style="width:325px;" class="regular-text" >'.$direct_payment_details.'</textarea> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="price_submission">'.esc_html__('Price Per Submission (for "per listing" mode)','wpestate').'</label></th>
           <td><input  type="text" id="price_submission" name="price_submission"  value="'.$price_submission.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="price_featured_submission">'.esc_html__('Price to make the listing featured (for "per listing" mode)','wpestate').'</label></th>
           <td><input  type="text" id="price_featured_submission" name="price_featured_submission"  value="'.$price_featured_submission.'"/> </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="free_mem_list">'.esc_html__('Free Membership - no of listings (for "membership" mode)','wpestate').' </label></th>
            <td>
                <input  type="text" id="free_mem_list" name="free_mem_list" style="margin-right:20px;"  value="'.$free_mem_list.'"/> 
       
                <input type="hidden" name="free_mem_list_unl" value="">
                <input type="checkbox"  id="free_mem_list_unl" name="free_mem_list_unl" value="1" '.$free_mem_list_unl.' />
                <label for="free_mem_list_unl">'.esc_html__('Unlimited listings ?','wpestate').'</label>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="free_feat_list">'.esc_html__('Free Membership - no of featured listings (for "membership" mode)','wpestate').' </label></th>
            <td>
                <input  type="text" id="free_feat_list" name="free_feat_list" style="margin-right:20px;"    value="'.$free_feat_list.'"/>
              
            </td>
        </tr>
        
  
        <tr valign="top">
            <th scope="row"><label for="free_feat_list_expiration">'.esc_html__('Free Membership Listings - no of days until a free listing will expire. *Starts from the moment the property is published on the website. (for "membership" mode) ','wpestate').' </label></th>
            <td>
                <input  type="text" id="free_feat_list_expiration" name="free_feat_list_expiration" style="margin-right:20px;"    value="'.$free_feat_list_expiration.'"/>
              
            </td>
        </tr>

        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary" value="'.esc_html__('Save Changes','wpestate').'" />
        </p>  
    ';
    print '</div>';
}
endif; // end   wpestate_theme_admin_membershipsettings  




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Map Settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  General Settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Social $  Contact
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_theme_admin_social') ):
function wpestate_theme_admin_social(){
    $fax_ac                     =   esc_html (  stripslashes( get_option('wp_estate_fax_ac','') ) );
    $skype_ac                   =   esc_html (  stripslashes( get_option('wp_estate_skype_ac','') ) );
    $telephone_no               =   esc_html (  stripslashes( get_option('wp_estate_telephone_no','') ) );
    $mobile_no                  =   esc_html (  stripslashes( get_option('wp_estate_mobile_no','') ) );
    $company_name               =   esc_html (  stripslashes(get_option('wp_estate_company_name','') ) );
    $email_adr                  =   esc_html ( get_option('wp_estate_email_adr','') );
    $duplicate_email_adr        =   esc_html ( get_option('wp_estate_duplicate_email_adr','') );   
    $co_address                 =   esc_html ( stripslashes( get_option('wp_estate_co_address','') ) );
    $facebook_link              =   esc_html ( get_option('wp_estate_facebook_link','') );
    $twitter_link               =   esc_html ( get_option('wp_estate_twitter_link','') );
    $google_link                =   esc_html ( get_option('wp_estate_google_link','') );
    $linkedin_link              =   esc_html ( get_option('wp_estate_linkedin_link','') );
    $pinterest_link             =   esc_html ( get_option('wp_estate_pinterest_link','') );  
    $instagram_link             =   esc_html ( get_option('wp_estate_instagram_link','') );  
    $twitter_consumer_key       =   esc_html ( get_option('wp_estate_twitter_consumer_key','') );
    $twitter_consumer_secret    =   esc_html ( get_option('wp_estate_twitter_consumer_secret','') );
    $twitter_access_token       =   esc_html ( get_option('wp_estate_twitter_access_token','') );
    $twitter_access_secret      =   esc_html ( get_option('wp_estate_twitter_access_secret','') );
    $twitter_cache_time         =   intval   ( get_option('wp_estate_twitter_cache_time','') );
    $zillow_api_key             =   esc_html ( get_option('wp_estate_zillow_api_key','') );
    $facebook_api               =   esc_html ( get_option('wp_estate_facebook_api','') );
    $facebook_secret            =   esc_html ( get_option('wp_estate_facebook_secret','') );
    $company_contact_image      =   esc_html( get_option('wp_estate_company_contact_image','') );
    $google_oauth_api           =   esc_html ( get_option('wp_estate_google_oauth_api','') );
    $google_oauth_client_secret =   esc_html ( get_option('wp_estate_google_oauth_client_secret','') );
    $google_api_key             =   esc_html ( get_option('wp_estate_google_api_key','') );
    
    
    $social_array               =   array('no','yes');
   
    $facebook_login_select      = wpestate_dropdowns_theme_admin($social_array,'facebook_login');
    $google_login_select        = wpestate_dropdowns_theme_admin($social_array,'google_login');
    $yahoo_login_select         = wpestate_dropdowns_theme_admin($social_array,'yahoo_login');
    $contact_form_7_contact     = stripslashes( esc_html( get_option('wp_estate_contact_form_7_contact','') ) );
    $contact_form_7_agent       = stripslashes( esc_html( get_option('wp_estate_contact_form_7_agent','') ) );
    
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">Social</div>';
    print '<a href="http://help.wpresidence.net/#!/social" target="_blank" class="help_link">'.esc_html__('help','wpestate').'</a>';
   
    print '<table class="form-table">     
        <tr valign="top">
            <th scope="row"><label for="company_contact_image">'.esc_html__('Image for Contact Page','wpestate').'</label></th>
            <td>
	        <input id="company_contact_image" type="text" size="36" name="company_contact_image" value="'.$company_contact_image.'" />
		<input id="company_contact_image_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Image','wpestate').'" />
            </td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="company_name">'.esc_html__('Company Name','wpestate').'</label></th>
            <td>  <input id="company_name" type="text" size="36" name="company_name" value="'.$company_name.'" /></td>
        </tr>   
        
    	<tr valign="top">
            <th scope="row"><label for="email_adr">'.esc_html__('Email','wpestate').'</label></th>
            <td>  <input id="email_adr" type="text" size="36" name="email_adr" value="'.$email_adr.'" /></td>
        </tr>    
        
        <tr valign="top">
            <th scope="row"><label for="duplicate_email_adr">'.esc_html__('Send all contact emails to:','wpestate').'</label></th>
            <td>  <input id="duplicate_email_adr" type="text" size="36" name="duplicate_email_adr" value="'.$duplicate_email_adr.'" /></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="telephone_no">'.esc_html__('Telephone','wpestate').'</label></th>
            <td>  <input id="telephone_no" type="text" size="36" name="telephone_no" value="'.$telephone_no.'" /></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="mobile_no">'.esc_html__('Mobile','wpestate').'</label></th>
            <td>  <input id="mobile_no" type="text" size="36" name="mobile_no" value="'.$mobile_no.'" /></td>
        </tr> 
        
         <tr valign="top">
            <th scope="row"><label for="fax_ac">'.esc_html__('Fax','wpestate').'</label></th>
            <td>  <input id="fax_ac" type="text" size="36" name="fax_ac" value="'.$fax_ac.'" /></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="skype_ac">'.esc_html__('Skype','wpestate').'</label></th>
            <td>  <input id="skype_ac" type="text" size="36" name="skype_ac" value="'.$skype_ac.'" /></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="co_address">'.esc_html__('Address','wpestate').'</label></th>
            <td><textarea cols="57" rows="2" name="co_address" id="co_address">'.$co_address.'</textarea></td>
        </tr> 
        
        <tr valign="top">
            <th scope="row"><label for="facebook_link">'.esc_html__('Facebook Link','wpestate').'</label></th>
            <td>  <input id="facebook_link" type="text" size="36" name="facebook_link" value="'.$facebook_link.'" /></td>
        </tr>        
        
        <tr valign="top">
            <th scope="row"><label for="twitter_link">'.esc_html__('Twitter Page Link','wpestate').'</label></th>
            <td>  <input id="twitter_link" type="text" size="36" name="twitter_link" value="'.$twitter_link.'" /></td>
        </tr>
         
        <tr valign="top">
            <th scope="row"><label for="google_link">'.esc_html__('Google+ Link','wpestate').'</label></th>
            <td>  <input id="google_link" type="text" size="36" name="google_link" value="'.$google_link.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="pinterest_link">'.esc_html__('Pinterest Link','wpestate').'</label></th>
            <td>  <input id="pinterest_link" type="text" size="36" name="pinterest_link" value="'.$pinterest_link.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="linkedin_link">'.esc_html__('Linkedin Link','wpestate').'</label></th>
            <td>  <input id="linkedin_link" type="text" size="36" name="linkedin_link" value="'.$linkedin_link.'" /></td>
        </tr>
        

        <tr valign="top">
            <th scope="row"><label for="twitter_consumer_key">'.esc_html__('Twitter Consumer Key','wpestate').'</label></th>
            <td>  <input id="twitter_consumer_key" type="text" size="36" name="twitter_consumer_key" value="'.$twitter_consumer_key.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="twitter_consumer_secret">'.esc_html__('Twitter Consumer Secret','wpestate').'</label></th>
            <td>  <input id="twitter_consumer_secret" type="text" size="36" name="twitter_consumer_secret" value="'.$twitter_consumer_secret.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="twitter_access_token">'.esc_html__('Twitter Access Token','wpestate').'</label></th>
            <td>  <input id="twitter_account" type="text" size="36" name="twitter_access_token" value="'.$twitter_access_token.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="twitter_access_secret">'.esc_html__('Twitter Access Token Secret','wpestate').'</label></th>
            <td>  <input id="twitter_access_secret" type="text" size="36" name="twitter_access_secret" value="'.$twitter_access_secret.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="twitter_cache_time">'.esc_html__('Twitter Cache Time in hours','wpestate').'</label></th>
            <td>  <input id="twitter_cache_time" type="text" size="36" name="twitter_cache_time" value="'.$twitter_cache_time.'" /></td>
        </tr>
         
        <tr valign="top">
            <th scope="row"><label for="facebook_api">'.esc_html__('Facebook Api Key (for Facebook login)','wpestate').'</label></th>
            <td>  <input id="facebook_api" type="text" size="36" name="facebook_api" value="'.$facebook_api.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="facebook_secret">'.esc_html__('Facebook secret code (for Facebook login) ','wpestate').'</label></th>
            <td>  <input id="facebook_secret" type="text" size="36" name="facebook_secret" value="'.$facebook_secret.'" /></td>
        </tr>
       
        <tr valign="top">
            <th scope="row"><label for="google_oauth_api">'.esc_html__('Google OAuth client id (for Google login)','wpestate').'</label></th>
            <td>  <input id="google_oauth_api" type="text" size="36" name="google_oauth_api" value="'.$google_oauth_api.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="google_oauth_client_secret">'.esc_html__('Google Client Secret (for Google login)','wpestate').'</label></th>
            <td>  <input id="google_oauth_client_secret" type="text" size="36" name="google_oauth_client_secret" value="'.$google_oauth_client_secret.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="google_api_key">'.esc_html__('Google Api key (for Google login)','wpestate').'</label></th>
            <td>  <input id="google_api_key" type="text" size="36" name="google_api_key" value="'.$google_api_key.'" /></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="facebook_login">'.esc_html__('Allow login via Facebook ? ','wpestate').'</label></th>
            <td> <select id="facebook_login" name="facebook_login">
                    '.$facebook_login_select.'
                </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="google_login">'.esc_html__('Allow login via Google ?','wpestate').' </label></th>
            <td> <select id="google_login" name="google_login">
                    '.$google_login_select.'
                </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="yahoo_login">'.esc_html__('Allow login via Yahoo ? ','wpestate').'</label></th>
            <td> <select id="yahoo_login" name="yahoo_login">
                    '.$yahoo_login_select.'
                </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="contact_form_7_agent">'.esc_html__('Contact form 7 code for agent (ex: [contact-form-7 id="2725" title="contact me"])','wpestate').'</label></th>
            <td> 
                <input type="text" size="36" id="contact_form_7_agent" name="contact_form_7_agent" value="'.$contact_form_7_agent.'" />
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><label for="contact_form_7_contact">'.esc_html__('Contact form 7 code for contact page template (ex: [contact-form-7 id="2725" title="contact me"])','wpestate').'</label></th>
            <td> 
                 <input type="text" size="36" id="contact_form_7_contact" name="contact_form_7_contact" value="'.$contact_form_7_contact.'" />
            </td>
        </tr>
        

    </table>
    <p class="submit">
      <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__('Save Changes','wpestate').'" />
    </p>';
print '</div>';
}
endif; // end   wpestate_theme_admin_social  








/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  help and custom
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_theme_admin_help') ):
function wpestate_theme_admin_help(){
    print '<div class="wpestate-tab-container">';
    print '<h1 class="wpestate-tabh1">'.esc_html__('Help','wpestate').'</div>';
    print '<table class="form-table">  
 
        <tr valign="top">
            <td> '.esc_html__('For theme help please check http://help.wpresidence.net/. If your question is not here, please go to http://support.wpestate.org, create an account and post a ticket. The registration is simple and as soon as you send a ticket we are notified. We usually answer in the next 24h (except weekends). Please use this system and not the email. It will help us answer your questions much faster. Thank you!','wpestate').'
            </td>             
        </tr>
        
        <tr valign="top">
            <td> '.esc_html__('For custom work on this theme please go to  <a href="http://support.wpestate.org/" target="_blank">http://support.wpestate.org</a>, create a ticket with your request and we will offer a free quote.','wpestate').'
            </td>             
        </tr>
        
        <tr valign="top">
            <td> '.esc_html__('For help files please go to  <a href="http://help.wpresidence.net/">http://help.wpresidence.net</a>.','wpestate').'
            </td>             
        </tr>
        
         
        <tr valign="top">
            <td>  '.esc_html__('Subscribe to our mailing list in order to receive news about new features and theme upgrades. <a href="http://eepurl.com/CP5U5">Subscribe Here!</a>','wpestate').'
            </td>             
        </tr>
        </table>
        
      ';
    print '</div>';
}
endif; // end   wpestate_theme_admin_help  



if( !function_exists('wpestate_general_country_list') ):
    function wpestate_general_country_list($selected){
        $countries = array(esc_html__("Afghanistan","wpestate"),
            esc_html__("Albania","wpestate"),
            esc_html__("Algeria","wpestate"),
            esc_html__("American Samoa","wpestate"),
            esc_html__("Andorra","wpestate"),
            esc_html__("Angola","wpestate"),
            esc_html__("Anguilla","wpestate"),
            esc_html__("Antarctica","wpestate"),
            esc_html__("Antigua and Barbuda","wpestate"),
            esc_html__("Argentina","wpestate"),
            esc_html__("Armenia","wpestate"),
            esc_html__("Aruba","wpestate"),
            esc_html__("Australia","wpestate"),
            esc_html__("Austria","wpestate"),
            esc_html__("Azerbaijan","wpestate"),
            esc_html__("Bahamas","wpestate"),
            esc_html__("Bahrain","wpestate"),
            esc_html__("Bangladesh","wpestate"),
            esc_html__("Barbados","wpestate"),
            esc_html__("Belarus","wpestate"),
            esc_html__("Belgium","wpestate"),
            esc_html__("Belize","wpestate"),
            esc_html__("Benin","wpestate"),
            esc_html__("Bermuda","wpestate"),
            esc_html__("Bhutan","wpestate"),
            esc_html__("Bolivia","wpestate"),
            esc_html__("Bosnia and Herzegowina","wpestate"),
            esc_html__("Botswana","wpestate"),
            esc_html__("Bouvet Island","wpestate"),
            esc_html__("Brazil","wpestate"),
            esc_html__("British Indian Ocean Territory","wpestate"),
            esc_html__("Brunei Darussalam","wpestate"),
            esc_html__("Bulgaria","wpestate"),
            esc_html__("Burkina Faso","wpestate"),
            esc_html__("Burundi","wpestate"),
            esc_html__("Cambodia","wpestate"),
            esc_html__("Cameroon","wpestate"),
            esc_html__("Canada","wpestate"),
            esc_html__("Cape Verde","wpestate"),
            esc_html__("Cayman Islands","wpestate"),
            esc_html__("Central African Republic","wpestate"),
            esc_html__("Chad","wpestate"),
            esc_html__("Chile","wpestate"),
            esc_html__("China","wpestate"),
            esc_html__("Christmas Island","wpestate"),
            esc_html__("Cocos (Keeling) Islands","wpestate"),
            esc_html__("Colombia","wpestate"),
            esc_html__("Comoros","wpestate"),
            esc_html__("Congo","wpestate"),
            esc_html__("Congo, the Democratic Republic of the","wpestate"),
            esc_html__("Cook Islands","wpestate"),
            esc_html__("Costa Rica","wpestate"),
            esc_html__("Cote d'Ivoire","wpestate"),
            esc_html__("Croatia (Hrvatska)","wpestate"),
            esc_html__("Cuba","wpestate"),
            esc_html__("Cyprus","wpestate"),
            esc_html__("Czech Republic","wpestate"),
            esc_html__("Denmark","wpestate"),
            esc_html__("Djibouti","wpestate"),
            esc_html__("Dominica","wpestate"),
            esc_html__("Dominican Republic","wpestate"),
            esc_html__("East Timor","wpestate"),
            esc_html__("Ecuador","wpestate"),
            esc_html__("Egypt","wpestate"),
            esc_html__("El Salvador","wpestate"),
            esc_html__("Equatorial Guinea","wpestate"),
            esc_html__("Eritrea","wpestate"),
            esc_html__("Estonia","wpestate"),
            esc_html__("Ethiopia","wpestate"),
            esc_html__("Falkland Islands (Malvinas)","wpestate"),
            esc_html__("Faroe Islands","wpestate"),
            esc_html__("Fiji","wpestate"),
            esc_html__("Finland","wpestate"),
            esc_html__("France","wpestate"),
            esc_html__("France Metropolitan","wpestate"),
            esc_html__("French Guiana","wpestate"),
            esc_html__("French Polynesia","wpestate"),
            esc_html__("French Southern Territories","wpestate"),
            esc_html__("Gabon","wpestate"),
            esc_html__("Gambia","wpestate"),
            esc_html__("Georgia","wpestate"),
            esc_html__("Germany","wpestate"),
            esc_html__("Ghana","wpestate"),
            esc_html__("Gibraltar","wpestate"),
            esc_html__("Greece","wpestate"),
            esc_html__("Greenland","wpestate"),
            esc_html__("Grenada","wpestate"),
            esc_html__("Guadeloupe","wpestate"),
            esc_html__("Guam","wpestate"),
            esc_html__("Guatemala","wpestate"),
            esc_html__("Guinea","wpestate"),
            esc_html__("Guinea-Bissau","wpestate"),
            esc_html__("Guyana","wpestate"),
            esc_html__("Haiti","wpestate"),
            esc_html__("Heard and Mc Donald Islands","wpestate"),
            esc_html__("Holy See (Vatican City State)","wpestate"),
            esc_html__("Honduras","wpestate"),
            esc_html__("Hong Kong","wpestate"),
            esc_html__("Hungary","wpestate"),
            esc_html__("Iceland","wpestate"),
            esc_html__("India","wpestate"),
            esc_html__("Indonesia","wpestate"),
            esc_html__("Iran (Islamic Republic of)","wpestate"),
            esc_html__("Iraq","wpestate"),
            esc_html__("Ireland","wpestate"),
            esc_html__("Israel","wpestate"),
            esc_html__("Italy","wpestate"),
            esc_html__("Jamaica","wpestate"),
            esc_html__("Japan","wpestate"),
            esc_html__("Jordan","wpestate"),
            esc_html__("Kazakhstan","wpestate"),
            esc_html__("Kenya","wpestate"),
            esc_html__("Kiribati","wpestate"),
            esc_html__("Korea, Democratic People's Republic of","wpestate"),
            esc_html__("Korea, Republic of","wpestate"),
            esc_html__("Kuwait","wpestate"),
            esc_html__("Kyrgyzstan","wpestate"),
            esc_html__("Lao, People's Democratic Republic","wpestate"),
            esc_html__("Latvia","wpestate"),
            esc_html__("Lebanon","wpestate"),
            esc_html__("Lesotho","wpestate"),
            esc_html__("Liberia","wpestate"),
            esc_html__("Libyan Arab Jamahiriya","wpestate"),
            esc_html__("Liechtenstein","wpestate"),
            esc_html__("Lithuania","wpestate"),
            esc_html__("Luxembourg","wpestate"),
            esc_html__("Macau","wpestate"),
            esc_html__("Macedonia (FYROM)","wpestate"),
            esc_html__("Madagascar","wpestate"),
            esc_html__("Malawi","wpestate"),
            esc_html__("Malaysia","wpestate"),
            esc_html__("Maldives","wpestate"),
            esc_html__("Mali","wpestate"),
            esc_html__("Malta","wpestate"),
            esc_html__("Marshall Islands","wpestate"),
            esc_html__("Martinique","wpestate"),
            esc_html__("Mauritania","wpestate"),
            esc_html__("Mauritius","wpestate"),
            esc_html__("Mayotte","wpestate"),
            esc_html__("Mexico","wpestate"),
            esc_html__("Micronesia, Federated States of","wpestate"),
            esc_html__("Moldova, Republic of","wpestate"),
            esc_html__("Monaco","wpestate"),
            esc_html__("Mongolia","wpestate"),
            esc_html__("Montserrat","wpestate"),
            esc_html__("Morocco","wpestate"),
            esc_html__("Mozambique","wpestate"),
            esc_html__("Montenegro","wpestate"),
            esc_html__("Myanmar","wpestate"),
            esc_html__("Namibia","wpestate"),
            esc_html__("Nauru","wpestate"),
            esc_html__("Nepal","wpestate"),
            esc_html__("Netherlands","wpestate"),
            esc_html__("Netherlands Antilles","wpestate"),
            esc_html__("New Caledonia","wpestate"),
            esc_html__("New Zealand","wpestate"),
            esc_html__("Nicaragua","wpestate"),
            esc_html__("Niger","wpestate"),
            esc_html__("Nigeria","wpestate"),
            esc_html__("Niue","wpestate"),
            esc_html__("Norfolk Island","wpestate"),
            esc_html__("Northern Mariana Islands","wpestate"),
            esc_html__("Norway","wpestate"),
            esc_html__("Oman","wpestate"),
            esc_html__("Pakistan","wpestate"),
            esc_html__("Palau","wpestate"),
            esc_html__("Panama","wpestate"),
            esc_html__("Papua New Guinea","wpestate"),
            esc_html__("Paraguay","wpestate"),
            esc_html__("Peru","wpestate"),
            esc_html__("Philippines","wpestate"),
            esc_html__("Pitcairn","wpestate"),
            esc_html__("Poland","wpestate"),
            esc_html__("Portugal","wpestate"),
            esc_html__("Puerto Rico","wpestate"),
            esc_html__("Qatar","wpestate"),
            esc_html__("Reunion","wpestate"),
            esc_html__("Romania","wpestate"),
            esc_html__("Russian Federation","wpestate"),
            esc_html__("Rwanda","wpestate"),
            esc_html__("Saint Kitts and Nevis","wpestate"),
            esc_html__("Saint Martin","wpestate"),
            esc_html__("Saint Lucia","wpestate"),
            esc_html__("Saint Vincent and the Grenadines","wpestate"),
            esc_html__("Samoa","wpestate"),
            esc_html__("San Marino","wpestate"),
            esc_html__("Sao Tome and Principe","wpestate"),
            esc_html__("Saudi Arabia","wpestate"),
            esc_html__("Senegal","wpestate"),
            esc_html__("Seychelles","wpestate"),
            esc_html__("Serbia","wpestate"),
            esc_html__("Sierra Leone","wpestate"),
            esc_html__("Singapore","wpestate"),
            esc_html__("Slovakia (Slovak Republic)","wpestate"),
            esc_html__("Slovenia","wpestate"),
            esc_html__("Solomon Islands","wpestate"),
            esc_html__("Somalia","wpestate"),
            esc_html__("South Africa","wpestate"),
            esc_html__("South Georgia and the South Sandwich Islands","wpestate"),
            esc_html__("Spain","wpestate"),
            esc_html__("Sri Lanka","wpestate"),
            esc_html__("St. Helena","wpestate"),
            esc_html__("St. Pierre and Miquelon","wpestate"),
            esc_html__("Sudan","wpestate"),
            esc_html__("Suriname","wpestate"),
            esc_html__("Svalbard and Jan Mayen Islands","wpestate"),
            esc_html__("Swaziland","wpestate"),
            esc_html__("Sweden","wpestate"),
            esc_html__("Switzerland","wpestate"),
            esc_html__("Syrian Arab Republic","wpestate"),
            esc_html__("Taiwan, Province of China","wpestate"),
            esc_html__("Tajikistan","wpestate"),
            esc_html__("Tanzania, United Republic of","wpestate"),
            esc_html__("Thailand","wpestate"),
            esc_html__("Togo","wpestate"),
            esc_html__("Tokelau","wpestate"),
            esc_html__("Tonga","wpestate"),
            esc_html__("Trinidad and Tobago","wpestate"),
            esc_html__("Tunisia","wpestate"),
            esc_html__("Turkey","wpestate"),
            esc_html__("Turkmenistan","wpestate"),
            esc_html__("Turks and Caicos Islands","wpestate"),
            esc_html__("Tuvalu","wpestate"),
            esc_html__("Uganda","wpestate"),
            esc_html__("Ukraine","wpestate"),
            esc_html__("United Arab Emirates","wpestate"),
            esc_html__("United Kingdom","wpestate"),
            esc_html__("United States","wpestate"),
            esc_html__("United States Minor Outlying Islands","wpestate"),
            esc_html__("Uruguay","wpestate"),
            esc_html__("Uzbekistan","wpestate"),
            esc_html__("Vanuatu","wpestate"),
            esc_html__("Venezuela","wpestate"),
            esc_html__("Vietnam","wpestate"),
            esc_html__("Virgin Islands (British)","wpestate"),
            esc_html__("Virgin Islands (U.S.)","wpestate"),
            esc_html__("Wallis and Futuna Islands","wpestate"),
            esc_html__("Western Sahara","wpestate"),
            esc_html__("Yemen","wpestate"),
            esc_html__("Zambia","wpestate"),
            esc_html__("Zimbabwe","wpestate"));
        $country_select='<select id="general_country" style="width: 200px;" name="general_country">';

        foreach($countries as $country){
            $country_select.='<option value="'.$country.'"';  
            if($selected==$country){
                $country_select.='selected="selected"';
            }
            $country_select.='>'.$country.'</option>';
        }

        $country_select.='</select>';
        return $country_select;
    }
endif; // end   wpestate_general_country_list  


function wpestate_sorting_function($a, $b) {
    return $a[3] - $b[3];
};

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Wpestate Price settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////






if( !function_exists('wpestate_new_theme_admin_general_settings') ):
function wpestate_new_theme_admin_general_settings(){
    
    $cache_array                    =   array('yes','no');
    $social_array                   =   array('no','yes');
    
    $general_country                =   esc_html( get_option('wp_estate_general_country') );
    $measure_sys='';
    $measure_array=array( esc_html__('feet','wpestate')     =>esc_html__('ft','wpestate'),
                          esc_html__('meters','wpestate')   =>esc_html__('m','wpestate') 
                        );
    
    $measure_array_status= esc_html( get_option('wp_estate_measure_sys','') );

    foreach($measure_array as $key => $value){
            $measure_sys.='<option value="'.$value.'"';
            if ($measure_array_status==$value){
                $measure_sys.=' selected="selected" ';
            }
            $measure_sys.='>'.esc_html__('square','wpestate').' '.$key.' - '.$value.'<sup>2</sup></option>';
    }

    
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Country','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Select default country','wpestate').'</div>    
        '.wpestate_general_country_list($general_country).'
    </div>';
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Measurement Unit','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Select the measurement unit you will use on the website','wpestate').'</div>    
            <select id="measure_sys" name="measure_sys">
                '.$measure_sys.'
            </select>
    </div>';
    
    
    $enable_autocomplete_symbol = wpestate_dropdowns_theme_admin($cache_array,'enable_autocomplete');
      
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Enable Autocomplete in Front End Submission Form','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('If yes, the address field in front end submission form will use Google Places autocomplete.','wpestate').'</div>    
            <select id="enable_autocomplete" name="enable_autocomplete">
                '.$enable_autocomplete_symbol.'
            </select>
    </div>';
    
    
    $enable_user_pass_symbol    = wpestate_dropdowns_theme_admin($cache_array,'enable_user_pass');

    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Users can type the password on registration form','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('If no, users will get the auto generated password via email','wpestate').'</div>    
            <select id="enable_user_pass" name="enable_user_pass">
                '.$enable_user_pass_symbol.'
            </select>
    </div>';
    
    
    $date_languages=array(  'xx'=> 'default',
                            'af'=>'Afrikaans',
                            'ar'=>'Arabic',
                            'ar-DZ' =>'Algerian',
                            'az'=>'Azerbaijani',
                            'be'=>'Belarusian',
                            'bg'=>'Bulgarian',
                            'bs'=>'Bosnian',
                            'ca'=>'Catalan',
                            'cs'=>'Czech',
                            'cy-GB'=>'Welsh/UK',
                            'da'=>'Danish',
                            'de'=>'German',
                            'el'=>'Greek',
                            'en-AU'=>'English/Australia',
                            'en-GB'=>'English/UK',
                            'en-NZ'=>'English/New Zealand',
                            'eo'=>'Esperanto',
                            'es'=>'Spanish',
                            'et'=>'Estonian',
                            'eu'=>'Karrikas-ek',
                            'fa'=>'Persian',
                            'fi'=>'Finnish',
                            'fo'=>'Faroese',
                            'fr'=>'French',
                            'fr-CA'=>'Canadian-French',
                            'fr-CH'=>'Swiss-French',
                            'gl'=>'Galician',
                            'he'=>'Hebrew',
                            'hi'=>'Hindi',
                            'hr'=>'Croatian',
                            'hu'=>'Hungarian',
                            'hy'=>'Armenian',
                            'id'=>'Indonesian',
                            'ic'=>'Icelandic',
                            'it'=>'Italian',
                            'it-CH'=>'Italian-CH',
                            'ja'=>'Japanese',
                            'ka'=>'Georgian',
                            'kk'=>'Kazakh',
                            'km'=>'Khmer',
                            'ko'=>'Korean',
                            'ky'=>'Kyrgyz',
                            'lb'=>'Luxembourgish',
                            'lt'=>'Lithuanian',
                            'lv'=>'Latvian',
                            'mk'=>'Macedonian',
                            'ml'=>'Malayalam',
                            'ms'=>'Malaysian',
                            'nb'=>'Norwegian',
                            'nl'=>'Dutch',
                            'nl-BE'=>'Dutch-Belgium',
                            'nn'=>'Norwegian-Nynorsk',
                            'no'=>'Norwegian',
                            'pl'=>'Polish',
                            'pt'=>'Portuguese',
                            'pt-BR'=>'Brazilian',
                            'rm'=>'Romansh',
                            'ro'=>'Romanian',
                            'ru'=>'Russian',
                            'sk'=>'Slovak',
                            'sl'=>'Slovenian',
                            'sq'=>'Albanian',
                            'sr'=>'Serbian',
                            'sr-SR'=>'Serbian-i18n',
                            'sv'=>'Swedish',
                            'ta'=>'Tamil',
                            'th'=>'Thai',
                            'tj'=>'Tajiki',
                            'tr'=>'Turkish',
                            'uk'=>'Ukrainian',
                            'vi'=>'Vietnamese',
                            'zh-CN'=>'Chinese',
                            'zh-HK'=>'Chinese-Hong-Kong',
                            'zh-TW'=>'Chinese Taiwan',
        );  

    
    $date_lang_symbol =  wpestate_dropdowns_theme_admin_with_key($date_languages,'date_lang');
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Language for datepicker','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('This applies for the calendar field type available for properties.','wpestate').'</div>    
        <select id="date_lang" name="date_lang">
            '.$date_lang_symbol.'
         </select>
    </div>';
      
    $google_analytics_code          =   esc_html ( get_option('wp_estate_google_analytics_code','') );
  
    
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Google Analytics Tracking id','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Google Analytics Tracking id (ex UA-41924406-1)','wpestate').'</div>    
        <input  name="google_analytics_code" id="google_analytics_code" value="'.$google_analytics_code.'"></input>
    </div>';
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
   
}
endif; // end   wpestate_theme_admin_general_settings  



if( !function_exists('wpestate_new_theme_admin_logos_favicon') ):
function wpestate_new_theme_admin_logos_favicon(){
    $cache_array                    =   array('yes','no');
    $social_array                   =   array('no','yes');
    $logo_image                     =   esc_html( get_option('wp_estate_logo_image','') );
    //$footer_logo_image              =   esc_html( get_option('wp_estate_footer_logo_image','') );
    $mobile_logo_image              =   esc_html( get_option('wp_estate_mobile_logo_image','') );
    $favicon_image                  =   esc_html( get_option('wp_estate_favicon_image','') );
    
    
  
    
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Your Favicon','wpestate').'</div>
        
       '.esc_html__('Please update your favicon via Appearance - Customise - Site Identity ','wpestate').'
    </div>';   
     
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('To add Retina logo versions, create retina logo, add _2x at the end of name of the original file (for ex logo_2x.jpg) and upload it in the same uploads folder as the non retina logo.','wpestate').'</div>
    </div>';
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Your Logo','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('We will use the image at the uploaded size. So make sure it fits your design. If you add images directly into the input fields (without Upload button) please use the full image path. For ex: http://www.wpresidence..... . If you use the "upload"  button use also "Insert into Post" button from the pop up window.','wpestate').'</div>    
            <input id="logo_image" type="text" size="36" name="logo_image" value="'.$logo_image.'" />
            <input id="logo_image_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Logo','wpestate').'" /></br>
            '.'
       
    </div>';
    $stikcy_logo_image    =   esc_html( get_option('wp_estate_stikcy_logo_image','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Your Sticky Logo','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('If you add images directly into the input fields (without Upload button) please use the full image path. For ex: http://www.wpresidence..... . If you use the "upload"  button use also "Insert into Post" button from the pop up window.','wpestate').'</div>    
            <input id="stikcy_logo_image" type="text" size="36" name="stikcy_logo_image" value="'.$stikcy_logo_image.'" />
            <input id="stikcy_logo_image_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Logo','wpestate').'" /></br>
            '.'
       
    </div>';
    
    $transparent_logo_image    =   esc_html( get_option('wp_estate_transparent_logo_image','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Your Transparent Header Logo','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('If you add images directly into the input fields (without Upload button) please use the full image path. For ex: http://www.wpresidence..... . If you use the "upload"  button use also "Insert into Post" button from the pop up window.','wpestate').'</div>    
            <input id="transparent_logo_image" type="text" size="36" name="transparent_logo_image" value="'.$transparent_logo_image.'" />
            <input id="transparent_logo_image_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Logo','wpestate').'" /></br>
            '.'
       
    </div>';
     
     
    $logo_margin                =   intval( get_option('wp_estate_logo_margin','') ); 
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Margin Top for logo','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Add logo margin top number.','wpestate').'</div>    
        <input type="text" id="logo_margin" name="logo_margin" value="'.$logo_margin.'"> 
    </div>';
        
     /* 
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Retina Logo','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Retina ready logo (add _2x after the name. For ex logo_2x.jpg) ','wpestate').'</div>    
            <input id="footer_logo_image" type="text" size="36" name="footer_logo_image" value="'.$footer_logo_image.'" />
            <input id="footer_logo_image_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Logo','wpestate').'" />
       
    </div>';
     */
      
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Mobile/Tablets Logo','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Upload mobile logo in jpg or png format.','wpestate').'</div>    
            <input id="mobile_logo_image" type="text" size="36" name="mobile_logo_image" value="'.$mobile_logo_image.'" />
            <input id="mobile_logo_image_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Logo','wpestate').'" />
       
    </div>';
      
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
       
}
endif;


if( !function_exists('wpestate_new_header_settings') ):
function wpestate_new_header_settings(){
    $cache_array                =   array('yes','no');
     
    $show_top_bar_user_menu_symbol      = wpestate_dropdowns_theme_admin($cache_array,'show_top_bar_user_menu');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show top bar widget menu ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable top bar widget area.','wpestate').'</div>    
       <select id="show_top_bar_user_menu" name="show_top_bar_user_menu">
            '.$show_top_bar_user_menu_symbol.'
        </select>
    </div>';
    
    
    $show_top_bar_user_login_symbol     = wpestate_dropdowns_theme_admin($cache_array,'show_top_bar_user_login');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show user login menu in header ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable user login menu in header.','wpestate').'</div>    
        <select id="show_top_bar_user_login" name="show_top_bar_user_login">
            '.$show_top_bar_user_login_symbol.'
        </select>
    </div>';
    
          
    $cache_array                =   array('no','yes');
    $header_transparent_select  =   wpestate_dropdowns_theme_admin($cache_array,'header_transparent');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Global transparent header?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable the use of transparent header globally.','wpestate').'</div>    
        <select id="header_transparent" name="header_transparent">
            '.$header_transparent_select.'
        </select>
    </div>';
    
    
    $header_array_logo  =   array(
                            'type1',
                            'type2',
                           
                        );
    $logo_header_select   = wpestate_dropdowns_theme_admin($header_array_logo,'logo_header_type');

    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Header Type?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select header type.','wpestate').'</div>    
        <select id="logo_header_type" name="logo_header_type">
            '.$logo_header_select.'
        </select>
    </div>';
    
    
    $header_array_logo_align  =   array(
                            'left',
                            'center',
                            'right',
                        );
 
    
    $logo_header_align_select   = wpestate_dropdowns_theme_admin($header_array_logo_align,'logo_header_align');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Header Align(Logo Position)?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select header alignment.','wpestate').'</div>    
        <select id="logo_header_align" name="logo_header_align">
            '.$logo_header_align_select.'
        </select>
    </div>';
           
    
  
    
    
    
    $cache_array                =   array('no','yes');
    $wide_header_select  =   wpestate_dropdowns_theme_admin($cache_array,'wide_header');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Wide Header ? ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('make the header 100%.','wpestate').'</div>    
        <select id="wide_header" name="wide_header">
            '.$wide_header_select.'
        </select>
    </div>';
    
    
    $header_array   =   array(
                            'none',
                            'image',
                            'theme slider',
                            'revolution slider',
                            'google map',
                            'gallery'
                            );
    $header_select   = wpestate_dropdowns_theme_admin_with_key($header_array,'header_type');

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Media Header Type?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select what media header to use globally.','wpestate').'</div>    
        <select id="header_type" name="header_type">
            '.$header_select.'
        </select>
    </div>';
       
    
    
   
          
   
    $global_revolution_slider   =   get_option('wp_estate_global_revolution_slider','');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Global Revolution Slider','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If media header is set to Revolution Slider, type the slider name and save.','wpestate').'</div>    
        <input type="text" id="global_revolution_slider" name="global_revolution_slider" value="'.$global_revolution_slider.'">   
    </div>';
    
    
    $global_header              =   get_option('wp_estate_global_header','');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Global Header Static Image','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If media header is set to image, add the image below. ','wpestate').'</div>    
        <input id="global_header" type="text" size="36" name="global_header" value="'.$global_header.'" />
        <input id="global_header_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Header Image','wpestate').'" />
    </div>';
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
       
}
endif;



if( !function_exists('wpestate_new_footer_settings') ):
function wpestate_new_footer_settings(){
    //wide_footer
    //show_footer
    //show_footer_copy
    //footer_type
    
    
    $cache_array                =   array('yes','no');
    $show_footer_select  =   wpestate_dropdowns_theme_admin($cache_array,'show_footer');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Footer ? ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show Footer ?','wpestate').'</div>    
        <select id="show_footer" name="show_footer">
            '.$show_footer_select.'
        </select>
    </div>';
    
    $show_show_footer_copy_select  =   wpestate_dropdowns_theme_admin($cache_array,'show_footer_copy');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Footer Copyright Area? ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show Footer Copyright Area?.','wpestate').'</div>    
        <select id="show_footer_copy" name="show_footer_copy">
            '.$show_show_footer_copy_select.'
        </select>
    </div>';
    
    $copyright_message          =   esc_html (stripslashes( get_option('wp_estate_copyright_message','') ) );   
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Copyright Message','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Type here the copyright message that will appear in footer.','wpestate').'</div>    
        <textarea cols="57" rows="2" id="copyright_message" name="copyright_message">'.$copyright_message.'</textarea></td>  
    </div>';
    
    $footer_background          =   get_option('wp_estate_footer_background','');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Background for Footer','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Insert background footer image below.','wpestate').'</div>    
        <input id="footer_background" type="text" size="36" name="footer_background" value="'.$footer_background.'" />
        <input id="footer_background_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Background Image for Footer','wpestate').'" />
                 
    </div>';
    

    $repeat_array=array('repeat','repeat x','repeat y','no repeat');
    $repeat_footer_back_symbol  = wpestate_dropdowns_theme_admin($repeat_array,'repeat_footer_back');

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Repeat Footer background ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set repeat options for background footer image.','wpestate').'</div>    
        <select id="repeat_footer_back" name="repeat_footer_back">
            '.$repeat_footer_back_symbol.'
        </select>     
    </div>';
    
    

    
    $cache_array                =   array('no','yes');
    $wide_footer_select  =   wpestate_dropdowns_theme_admin($cache_array,'wide_footer');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Wide Footer ? ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('make the footer 100%.','wpestate').'</div>    
        <select id="wide_footer" name="wide_footer">
            '.$wide_footer_select.'
        </select>
    </div>';
    
    
    
    
    $wide_array=array(
        "1"  =>     esc_html__("4 equal columns","wpestate"),
        "2"  =>     esc_html__("3 equal columns","wpestate"),
        "3"  =>     esc_html__("2 equal columns","wpestate"),
        "4"  =>     esc_html__("100% width column","wpestate"),
        "5"  =>     esc_html__("3 columns: 1/2 + 1/4 + 1/4","wpestate"),
        "6"  =>     esc_html__("3 columns: 1/4 + 1/2 + 1/4","wpestate"),
        "7"  =>     esc_html__("3 columns: 1/4 + 1/4 + 1/2","wpestate"),
        "8"  =>     esc_html__("2 columns: 2/3 + 1/3","wpestate"),
        "9"  =>     esc_html__("2 columns: 1/3 + 2/3","wpestate"),
        );
    
    
    
    $footer_type_symbol   = wpestate_dropdowns_theme_admin_with_key($wide_array,'footer_type');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Footer Type','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Footer Type','wpestate').'</div>    
        <select id="footer_type" name="footer_type">
            '.$footer_type_symbol.'
        </select>
    </div>';
    
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
       
}
endif;




if( !function_exists('wpestate_new_export_settings') ):
function  wpestate_new_export_settings(){
          
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Export Theme Options','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Export Theme Options ','wpestate').'</div>    
            <textarea  rows="15" style="width:100%;" id="export_theme_options" onclick="this.focus();this.select()" name="export_theme_options">';
        if(function_exists('wpestate_export_theme_options')){
            print wpestate_export_theme_options();
        }
    print '</textarea>
       
    </div>';
   
}
endif;




if( !function_exists('wpestate_new_theme_contact_details') ):
function  wpestate_new_theme_contact_details (){
    
    $company_contact_image      =   esc_html( get_option('wp_estate_company_contact_image','') );
       
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Image for Contact Page','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Add the image for the contact page contact area. Minim 350px wide for a nice design. ','wpestate').'</div>    
        <input id="company_contact_image" type="text" size="36" name="company_contact_image" value="'.$company_contact_image.'" />
        <input id="company_contact_image_button" type="button"  class="upload_button button" value="'.esc_html__('Upload Image','wpestate').'" />
    </div>';
    
    
    $company_name               =   esc_html ( stripslashes(get_option('wp_estate_company_name','') ) );
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Company Name','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Company name for contact page','wpestate').'</div>    
        <input id="company_name" type="text" size="36" name="company_name" value="'.$company_name.'" />
    </div>';
             
    $email_adr                  =   esc_html ( get_option('wp_estate_email_adr','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Email','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('company email','wpestate').'</div>    
      <input id="email_adr" type="text" size="36" name="email_adr" value="'.$email_adr.'" />
    </div>';
    
    
    $duplicate_email_adr        =   esc_html ( get_option('wp_estate_duplicate_email_adr','') );   
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Duplicate Email','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Send all contact emails to','wpestate').'</div>    
      <input id="duplicate_email_adr" type="text" size="36" name="duplicate_email_adr" value="'.$duplicate_email_adr.'" />
    </div>';
    
    $telephone_no               =   esc_html ( get_option('wp_estate_telephone_no','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Telephone','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Company phone number.','wpestate').'</div>    
    <input id="telephone_no" type="text" size="36" name="telephone_no" value="'.$telephone_no.'" />
    </div>';
     
    $mobile_no                  =   esc_html ( get_option('wp_estate_mobile_no','') );
     print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Mobile','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('company mobile','wpestate').'</div>    
    <input id="mobile_no" type="text" size="36" name="mobile_no" value="'.$mobile_no.'" />
    </div>';
     
    $fax_ac                     =   esc_html ( get_option('wp_estate_fax_ac','') );
     print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Fax','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('company fax','wpestate').'</div>    
    <input id="fax_ac" type="text" size="36" name="fax_ac" value="'.$fax_ac.'" />
    </div>';
     
    $skype_ac                   =   esc_html ( get_option('wp_estate_skype_ac','') );
     print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Skype','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Company skype','wpestate').'</div>    
    <input id="skype_ac" type="text" size="36" name="skype_ac" value="'.$skype_ac.'" />
    </div>';
    
    $co_address                 =   esc_html ( stripslashes( get_option('wp_estate_co_address','') ) );
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Company Address','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Type company address','wpestate').'</div>    
        <textarea cols="57" rows="2" name="co_address" id="co_address">'.$co_address.'</textarea>
    </div>';
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
    
}
endif;


if( !function_exists('wpestate_new_theme_social_accounts') ):
function wpestate_new_theme_social_accounts(){
    
    $facebook_link              =   esc_html ( get_option('wp_estate_facebook_link','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Facebook Link','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Facebook page url, with https://','wpestate').'</div>    
        <input id="facebook_link" type="text" size="36" name="facebook_link" value="'.$facebook_link.'" />
    </div>';
    
      
    $twitter_link               =   esc_html ( get_option('wp_estate_twitter_link','') );
      print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Twitter page link','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Twitter page link, with https://','wpestate').'</div>    
       <input id="twitter_link" type="text" size="36" name="twitter_link" value="'.$twitter_link.'" />
    </div>';
      
      
    $google_link                =   esc_html ( get_option('wp_estate_google_link','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Google+ Link','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Google+ page link, with https://','wpestate').'</div>    
       <input id="google_link" type="text" size="36" name="google_link" value="'.$google_link.'" />
    </div>';
      
    $linkedin_link              =   esc_html ( get_option('wp_estate_linkedin_link','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Linkedin Link','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__(' Linkedin page link, with https://','wpestate').'</div>    
        <input id="linkedin_link" type="text" size="36" name="linkedin_link" value="'.$linkedin_link.'" />
    </div>';
      
    $pinterest_link             =   esc_html ( get_option('wp_estate_pinterest_link','') );  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Pinterest Link','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Pinterest page link, with https://','wpestate').'</div>    
        <input id="pinterest_link" type="text" size="36" name="pinterest_link" value="'.$pinterest_link.'" />
    </div>';
      
    $instagram_link             =   esc_html ( get_option('wp_estate_instagram_link','') );  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Instagram Link','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Instagram page link, with https://','wpestate').'</div>    
        <input id="instagram_link" type="text" size="36" name="instagram_link" value="'.$instagram_link.'" />
    </div>';  
      
    $twitter_consumer_key       =   esc_html ( get_option('wp_estate_twitter_consumer_key','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Twitter consumer_key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Twitter consumer_key is required for theme Twitter widget.','wpestate').'</div>    
        <input id="twitter_consumer_key" type="text" size="36" name="twitter_consumer_key" value="'.$twitter_consumer_key.'" />
    </div>';
      
    $twitter_consumer_secret    =   esc_html ( get_option('wp_estate_twitter_consumer_secret','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Twitter Consumer Secret','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Twitter Consumer Secret is required for theme Twitter widget.','wpestate').'</div>    
        <input id="twitter_consumer_secret" type="text" size="36" name="twitter_consumer_secret" value="'.$twitter_consumer_secret.'" />
    </div>';
      
    $twitter_access_token       =   esc_html ( get_option('wp_estate_twitter_access_token','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Twitter Access Token','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Twitter Access Token is required for theme Twitter widget.','wpestate').'</div>    
        <input id="twitter_account" type="text" size="36" name="twitter_access_token" value="'.$twitter_access_token.'" />
    </div>';
      
    $twitter_access_secret      =   esc_html ( get_option('wp_estate_twitter_access_secret','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Twitter Access Secret','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Twitter Access Secret is required for theme Twitter widget.','wpestate').'</div>    
        <input id="twitter_access_secret" type="text" size="36" name="twitter_access_secret" value="'.$twitter_access_secret.'" />
    </div>';
      
    
    $twitter_cache_time         =   intval   ( get_option('wp_estate_twitter_cache_time','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Twitter Cache Time','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Twitter Cache Time','wpestate').'</div>    
       <input id="twitter_cache_time" type="text" size="36" name="twitter_cache_time" value="'.$twitter_cache_time.'" />
    </div>';
      
      
  
      
      
    
    $facebook_api               =   esc_html ( get_option('wp_estate_facebook_api','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Facebook Api key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Facebook Api key is required for Facebook login.','wpestate').'</div>    
        <input id="facebook_api" type="text" size="36" name="facebook_api" value="'.$facebook_api.'" />
    </div>';
      
    
    $facebook_secret            =   esc_html ( get_option('wp_estate_facebook_secret','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Facebook Secret','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Facebook Secret is required for Facebook login.','wpestate').'</div>    
        <input id="facebook_secret" type="text" size="36" name="facebook_secret" value="'.$facebook_secret.'" />
    </div>';
      
    
    $google_oauth_api           =   esc_html ( get_option('wp_estate_google_oauth_api','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Google Oauth Api','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Google Oauth Api is required for Google Login','wpestate').'</div>    
        <input id="google_oauth_api" type="text" size="36" name="google_oauth_api" value="'.$google_oauth_api.'" />
    </div>';
      
    $google_oauth_client_secret =   esc_html ( get_option('wp_estate_google_oauth_client_secret','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.__('Google Oauth Client Secret','wpestate').'</div>
    <div class="option_row_explain">'.__('Google Oauth Client Secret is required for Google Login.','wpestate').'</div>    
        <input id="google_oauth_client_secret" type="text" size="36" name="google_oauth_client_secret" value="'.$google_oauth_client_secret.'" />
    </div>';
      
    $google_api_key             =   esc_html ( get_option('wp_estate_google_api_key','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Google api key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Google api key is required for Google Login.','wpestate').'</div>    
        <input id="google_api_key" type="text" size="36" name="google_api_key" value="'.$google_api_key.'" />
    </div>';
      
    
    $social_array               =   array('no','yes');
   
    $facebook_login_select      = wpestate_dropdowns_theme_admin($social_array,'facebook_login');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Allow login via Facebook ? ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable Facebook login. ','wpestate').'</div>    
        <select id="facebook_login" name="facebook_login">
            '.$facebook_login_select.'
        </select>
    </div>';
      
      
    $google_login_select        = wpestate_dropdowns_theme_admin($social_array,'google_login');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Allow login via Google ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable Google login.','wpestate').'</div>    
        <select id="google_login" name="google_login">
            '.$google_login_select.'
        </select>
    </div>';
      
    $yahoo_login_select         = wpestate_dropdowns_theme_admin($social_array,'yahoo_login');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Allow login via Yahoo ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable Yahoo login.','wpestate').'</div>    
        <select id="yahoo_login" name="yahoo_login">
            '.$yahoo_login_select.'
        </select>
    </div>';
    
          
    $zillow_api_key             =   esc_html ( get_option('wp_estate_zillow_api_key','') );  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Zillow api key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Zillow api key is required for Zillow Widget.','wpestate').'</div>    
        <input id="zillow_api_key" type="text" size="36" name="zillow_api_key" value="'.$zillow_api_key.'" />
    </div>';
      
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;


if( !function_exists('wpestate_new_contact7') ):
function wpestate_new_contact7(){
    
    $contact_form_7_contact     = stripslashes( esc_html( get_option('wp_estate_contact_form_7_contact','') ) );
    $contact_form_7_agent       = stripslashes( esc_html( get_option('wp_estate_contact_form_7_agent','') ) );
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Contact form 7 code for agent','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Contact form 7 code for agent (ex: [contact-form-7 id="2725" title="contact me"])','wpestate').'</div>    
         <input type="text" size="36" id="contact_form_7_agent" name="contact_form_7_agent" value="'.$contact_form_7_agent.'" />
    </div>';
    
      
  
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Contact form 7 code for contact page','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Contact form 7 code for contact page template (ex: [contact-form-7 id="2725" title="contact me"])','wpestate').'</div>    
         <input type="text" size="36" id="contact_form_7_contact" name="contact_form_7_contact" value="'.$contact_form_7_contact.'" />
    </div>';
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;

if( !function_exists('wpestate_new_appeareance') ):
function wpestate_new_appeareance(){
    $cache_array                =   array('yes','no');
  
    $wide_array=array(
            "1"  =>  esc_html__("wide","wpestate"),
            "2"  =>  esc_html__("boxed","wpestate")
         );
    $wide_status_symbol   = wpestate_dropdowns_theme_admin_with_key($wide_array,'wide_status');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Wide or Boxed?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Choose the theme layout: wide or boxed.','wpestate').'</div>    
        <select id="wide_status" name="wide_status">
            '.$wide_status_symbol.'
        </select>
    </div>';


        
  


    
    $prop_no                    =   intval   ( get_option('wp_estate_prop_no','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Properties List - Properties number per page','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set how many properties to show per page in lists.','wpestate').'</div>    
        <input type="text" id="prop_no" name="prop_no" value="'.$prop_no.'"> 
    </div>';
    
    $prop_image_number                   =   intval   ( get_option('wp_estate_prop_image_number','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Maximum no of images per property (only front-end upload)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('The maximum no of images an user can upload on front end. Use 0 for unlimited','wpestate').'</div>    
        <input type="text" id="prop_no" name="prop_image_number" value="'.$prop_image_number.'"> 
    </div>';
    
    $prop_list_slider = array( 
                "0"  =>  esc_html__("no ","wpestate"),
                "1"  =>  esc_html__("yes","wpestate")
                );
    $prop_unit_slider_symbol = wpestate_dropdowns_theme_admin_with_key($prop_list_slider,'prop_list_slider');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Slider in Property Unit','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable / Disable the image slider in property unit (used in lists).','wpestate').'</div>    
        <select id="prop_list_slider" name="prop_list_slider">
            '.$prop_unit_slider_symbol.'
        </select>  
    </div>';
    
    
    $show_empty_city_status_symbol      = wpestate_dropdowns_theme_admin($cache_array,'show_empty_city');  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Cities and Areas with 0 properties in advanced search','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable listing empty city or area categories in dropdowns.','wpestate').'</div>    
        <select id="show_empty_city" name="show_empty_city">
            '.$show_empty_city_status_symbol.'
        </select>
    </div>';
    
    
    
    $blog_sidebar_array=array('no sidebar','right','left');
    $agent_sidebar_pos_select     = wpestate_dropdowns_theme_admin($blog_sidebar_array,'agent_sidebar');

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Agent Sidebar Position','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Where to show the sidebar in agent page.','wpestate').'</div>    
       <select id="agent_sidebar" name="agent_sidebar">
            '.$agent_sidebar_pos_select.'
        </select>
    </div>';
    
    
    $agent_sidebar_name          =   esc_html ( get_option('wp_estate_agent_sidebar_name','') );
    $agent_sidebar_name_select='';
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
        $agent_sidebar_name_select.='<option value="'.($sidebar['id'] ).'"';
            if($agent_sidebar_name==$sidebar['id']){ 
                $agent_sidebar_name_select.=' selected="selected"';
            }
        $agent_sidebar_name_select.=' >'.ucwords($sidebar['name']).'</option>';
    } 
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Agent page Sidebar','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('What sidebar to show in agent page.','wpestate').'</div>    
       <select id="agent_sidebar_name" name="agent_sidebar_name">
            '.$agent_sidebar_name_select.'
        </select>
    </div>';
    
    
    $blog_sidebar_select ='';
    $blog_sidebar= esc_html ( get_option('wp_estate_blog_sidebar','') );
    $blog_sidebar_array=array('no sidebar','right','left');

    foreach($blog_sidebar_array as $value){
            $blog_sidebar_select.='<option value="'.$value.'"';
            if ($blog_sidebar==$value){
                    $blog_sidebar_select.='selected="selected"';
            }
            $blog_sidebar_select.='>'.$value.'</option>';
    }
    
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Blog Category/Archive Sidebar Position','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Where to show the sidebar for blog category/archive list.','wpestate').'</div>    
        <select id="blog_sidebar" name="blog_sidebar">
            '.$blog_sidebar_select.'
        </select>
    </div>';
    
    
    
    $blog_sidebar_name          =   esc_html ( get_option('wp_estate_blog_sidebar_name','') );
   
    $blog_sidebar_name_select='';
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
        $blog_sidebar_name_select.='<option value="'.($sidebar['id'] ).'"';
            if($blog_sidebar_name==$sidebar['id']){ 
               $blog_sidebar_name_select.=' selected="selected"';
            }
        $blog_sidebar_name_select.=' >'.ucwords($sidebar['name']).'</option>';
    } 
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Blog Category/Archive Sidebar','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('What sidebar to show for blog category/archive list.','wpestate').'</div>    
        <select id="blog_sidebar_name" name="blog_sidebar_name">
            '.$blog_sidebar_name_select.'
        </select>
    </div>';
    
    
    $blog_unit_array    =   array(
                        'grid'    =>esc_html__('grid','wpestate'),
                        'list'      => esc_html__('list','wpestate')
                        );
    
    $blog_unit_select = wpestate_dropdowns_theme_admin_with_key($blog_unit_array,'blog_unit');
      
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Blog Category/Archive List type','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select list or grid style for Blog Category/Archive list type.','wpestate').'</div>    
        <select id="blog_unit" name="blog_unit">
            '.$blog_unit_select.'
        </select>
    </div>';
    
    
    
      
    $prop_list_array=array(
               "1"  =>  esc_html__("standard ","wpestate"),
               "2"  =>  esc_html__("half map","wpestate")
            );
    $property_list_type_symbol   = wpestate_dropdowns_theme_admin_with_key($prop_list_array,'property_list_type');
   
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property List Type for Taxonomy pages','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select standard or half map style for property taxonomies pages.','wpestate').'</div>    
        <select id="property_list_type" name="property_list_type">
            '.$property_list_type_symbol.'
        </select>
    </div>';
    
    
    
   
    $property_list_type_symbol_adv   = wpestate_dropdowns_theme_admin_with_key($prop_list_array,'property_list_type_adv');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property List Type for Advanced Search','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select standard or half map style for advanced search results page.','wpestate').'</div>    
        <select id="property_list_type_adv" name="property_list_type_adv">
            '.$property_list_type_symbol_adv.'
        </select>
    </div>';
    
    
    
    
    $prop_unit_array    =   array(
                                'grid'    =>esc_html__('grid','wpestate'),
                                'list'      => esc_html__('list','wpestate')
                            );
    $prop_unit_select_view   = wpestate_dropdowns_theme_admin_with_key($prop_unit_array,'prop_unit');
    

    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property List display(*global option)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select grid or list style for properties list pages.','wpestate').'</div>    
        <select id="prop_unit" name="prop_unit">
            '.$prop_unit_select_view.'
        </select>
    </div>';
    
      
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;


if( !function_exists('wpestate_new_property_page_details') ):
function wpestate_new_property_page_details(){
    $sidebar_agent                  =   array('yes','no');
    $slider_type                    =   array('vertical','horizontal','full width header','gallery');
    $social_array                   =   array('no','yes');
    $content_type                   =   array('accordion','tabs');
   
    
    $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page_property_design.php'
            ));

 
    $global_prpg_slider_type_symbol                             =   wpestate_dropdowns_theme_admin($slider_type,'global_prpg_slider_type');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Slider Type','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('What property slider type to show on property page.','wpestate').'</div>    
        <select id="global_prpg_slider_type" name="global_prpg_slider_type">
            '.$global_prpg_slider_type_symbol.'
        </select> 
    </div>';
    
    $blog_sidebar_array=array('no sidebar','right','left');
    $property_sidebar_pos_select     = wpestate_dropdowns_theme_admin($blog_sidebar_array,'property_sidebar');

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property Sidebar Position','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Where to show the sidebar in property page.','wpestate').'</div>    
       <select id="property_sidebar" name="property_sidebar">
            '.$property_sidebar_pos_select.'
        </select>
    </div>';
    
    
    $property_sidebar_name          =   esc_html ( get_option('wp_estate_property_sidebar_name','') );
    $property_sidebar_name_select='';
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
        $property_sidebar_name_select.='<option value="'.($sidebar['id'] ).'"';
            if($property_sidebar_name==$sidebar['id']){ 
                $property_sidebar_name_select.=' selected="selected"';
            }
        $property_sidebar_name_select.=' >'.ucwords($sidebar['name']).'</option>';
    } 
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property page Sidebar','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('What sidebar to show in property page.','wpestate').'</div>    
       <select id="property_sidebar_name" name="property_sidebar_name">
            '.$property_sidebar_name_select.'
        </select>
    </div>';
    
    
    
    
 
    
    
    $global_prpg_content_type_symbol                            =   wpestate_dropdowns_theme_admin($content_type,'global_prpg_content_type');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Content as ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select tabs or accordion style for property info.','wpestate').'</div>    
        <select id="global_prpg_content_type" name="global_prpg_content_type">
            '.$global_prpg_content_type_symbol.'
        </select> 
    </div>';
    
    $walkscore_api                                              =   esc_html ( get_option('wp_estate_walkscore_api','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Walkscore APi Key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Walkscore info doesn\'t show if you don\'t add the API.','wpestate').'</div>    
        <input type="text" name="walkscore_api" id="walkscore_api" value="'.$walkscore_api.'"> 
    </div>';
    
    
    $show_graph_prop_page                                       =   wpestate_dropdowns_theme_admin($social_array,'show_graph_prop_page');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Graph on Property Page','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable the display of number of view by day graphic.','wpestate').'</div>    
        <select id="show_graph_prop_page" name="show_graph_prop_page">
            '.$show_graph_prop_page.'
        </select> 
    </div>';
    
    $show_lightbox_contact                                       =   wpestate_dropdowns_theme_admin($social_array,'show_lightbox_contact');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Contact Form on lightbox','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable the contact form on lightbox.','wpestate').'</div>    
        <select id="show_lightbox_contact" name="show_lightbox_contact">
            '.$show_lightbox_contact.'
        </select> 
    </div>';
    
    $crop_images_lightbox                                       =   wpestate_dropdowns_theme_admin($social_array,'crop_images_lightbox');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Crop Images on lightbox','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Images will have the same size. If set to no you will need to make sure that images are about the same size','wpestate').'</div>    
        <select id="crop_images_lightbox" name="crop_images_lightbox">
            '.$crop_images_lightbox.'
        </select> 
    </div>';
   
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  
}
endif;


if( !function_exists('wpestate_general_design_settings') ):
function wpestate_general_design_settings(){
    
    $main_grid_content_width                                              =   esc_html ( get_option('wp_estate_main_grid_content_width','') );
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Main Grid Width in px','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('This option defines the main content width. Default value is 1200px','wpestate').'</div>    
        <input type="text" name="main_grid_content_width" id="main_grid_content_width" value="'.$main_grid_content_width.'"> 
    </div>';
    
    $main_content_width                                              =   esc_html ( get_option('wp_estate_main_content_width','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Content Width (In Percent)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Using this option you can define the width of the content in percent.Sidebar will occupy the rest of the main content space.','wpestate').'</div>    
        <input type="text" name="main_content_width" id="main_content_width" value="'.$main_content_width.'"> 
    </div>';
    
    $header_height                                              =   esc_html ( get_option('wp_estate_header_height','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Header Height','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Header Height in px','wpestate').'</div>    
        <input type="text" name="header_height" id="header_height" value="'.$header_height.'"> 
    </div>';
    
    $sticky_header_height                                              =   esc_html ( get_option('wp_estate_sticky_header_height','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Sticky Header Height','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Sticky Header Height in px','wpestate').'</div>    
        <input type="text" name="sticky_header_height" id="sticky_header_height" value="'.$sticky_header_height.'"> 
    </div>';
      
    
    
    $border_bottom_header                                              =   esc_html ( get_option('wp_estate_border_bottom_header','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Border Bottom Header Height','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Border Bottom Header Height in px','wpestate').'</div>    
        <input type="text" name="border_bottom_header" id="border_bottom_header" value="'.$border_bottom_header.'"> 
    </div>';
    
    $sticky_border_bottom_header                                            =   esc_html ( get_option('wp_estate_sticky_border_bottom_header','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Border Bottom Sticky Header Height','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Border Bottom Sticky Header Height px','wpestate').'</div>    
        <input type="text" name="sticky_border_bottom_header" id="sticky_border_bottom_header" value="'.$sticky_border_bottom_header.'"> 
    </div>';
    
        
    $border_bottom_header_color             =  esc_html ( get_option('wp_estate_border_bottom_header_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Header Border Bottom Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Header Border Bottom Color','wpestate').'</div>    
        <input type="text" name="border_bottom_header_color" value="'.$border_bottom_header_color.'" maxlength="7" class="inptxt" />
        <div id="border_bottom_header_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$border_bottom_header_color.';"></div></div>
    </div>';
     
        
    $border_bottom_header_sticky_color             =  esc_html ( get_option('wp_estate_border_bottom_header_sticky_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Sticky Header Border Bottom Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Sticky Header Border Bottom Color','wpestate').'</div>    
        <input type="text" name="border_bottom_header_sticky_color" value="'.$border_bottom_header_sticky_color.'" maxlength="7" class="inptxt" />
        <div id="border_bottom_header_sticky_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$border_bottom_header_sticky_color.';"></div></div>
    </div>';
     
    $wp_estate_contentarea_internal_padding_top          = get_option('wp_estate_contentarea_internal_padding_top','');
    $wp_estate_contentarea_internal_padding_left         = get_option('wp_estate_contentarea_internal_padding_left','');
    $wp_estate_contentarea_internal_padding_bottom       = get_option('wp_estate_contentarea_internal_padding_bottom','');
    $wp_estate_contentarea_internal_padding_right        = get_option('wp_estate_contentarea_internal_padding_right','');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Content Area Internal Padding','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Content Area Internal Padding (top,left,bottom,right) ','wpestate').'</div>    
        <input  style="width:100px;min-width:100px;" type="text" id="wp_estate_contentarea_internal_padding_top" name="contentarea_internal_padding_top"  value="'.$wp_estate_contentarea_internal_padding_top.'"/> 
        <input  style="width:100px;min-width:100px;" type="text" id="wp_estate_contentarea_internal_padding_left" name="contentarea_internal_padding_left"  value="'.$wp_estate_contentarea_internal_padding_left.'"/> 
        <input  style="width:100px;min-width:100px;" type="text" id="wp_estate_contentarea_internal_padding_bottom" name="contentarea_internal_padding_bottom"  value="'.$wp_estate_contentarea_internal_padding_bottom.'"/> 
        <input  style="width:100px;min-width:100px;" type="text" id="wp_estate_contentarea_internal_padding_right" name="contentarea_internal_padding_right"  value="'.$wp_estate_contentarea_internal_padding_right.'"/> 
    </div>';
    
    
    $wp_estate_content_area_back_color             =  esc_html ( get_option('wp_estate_content_area_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Content Area Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Content Area Background Color','wpestate').'</div>    
        <input type="text" name="content_area_back_color" value="'.$wp_estate_content_area_back_color.'" maxlength="7" class="inptxt" />
        <div id="content_area_back_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$wp_estate_content_area_back_color.';"></div></div>
    </div>';
    
    $yesno=array('yes','no');
    $enable_show_breadcrumbs           =   wpestate_dropdowns_theme_admin($yesno,'show_breadcrumbs');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Breadcrumbs','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show Breadcrumbs?','wpestate').'</div>    
        <select id="show_breadcrumbs" name="show_breadcrumbs">
            '.$enable_show_breadcrumbs.'
        </select> 
    </div>';
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>'; 
    
}
endif;



if( !function_exists('wpestate_new_price_currency') ):
function wpestate_new_price_currency(){
    
     
    $custom_fields = get_option( 'wp_estate_multi_curr', true);     
    $current_fields='';
    
    $currency_symbol                =   esc_html( get_option('wp_estate_currency_symbol') );
    
    $where_currency_symbol_array    =   array('before','after');
    $where_currency_symbol          =   wpestate_dropdowns_theme_admin($where_currency_symbol_array,'where_currency_symbol');
   
    $enable_auto_symbol_array       =   array('yes','no');
    $enable_auto_symbol             =   wpestate_dropdowns_theme_admin($enable_auto_symbol_array,'auto_curency');
    
    
    $i=0;
    if( !empty($custom_fields)){    
        while($i< count($custom_fields) ){
            $current_fields.='
                <div class=field_row>
                <div    class="field_item"><strong>'.esc_html__('Currency Symbol','wpestate').'</strong></br><input   type="text" name="add_curr_name[]"   value="'.$custom_fields[$i][0].'"  ></div>
                <div    class="field_item"><strong>'.esc_html__('Currency Label','wpestate').'</strong></br><input  type="text" name="add_curr_label[]"   value="'.$custom_fields[$i][1].'"  ></div>
                <div    class="field_item"><strong>'.esc_html__('Currency Value','wpestate').'</strong></br><input  type="text" name="add_curr_value[]"   value="'.$custom_fields[$i][2].'"  ></div>
                <div    class="field_item"><strong>'.esc_html__('Currency Position','wpestate').'</strong></br><input  type="text" name="add_curr_order[]"   value="'.$custom_fields[$i][3].'"  ></div>
                
                <a class="deletefieldlink" href="#">'.esc_html__('delete','wpestate').'</a>
            </div>';    
            $i++;
        }
    }
    
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Price - thousands separator','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set the thousand separator for price numbers.','wpestate').'</div>    
        <input type="text" name="prices_th_separator" id="prices_th_separator" value="'.  stripslashes ( get_option('wp_estate_prices_th_separator','') ).'"> 
    </div>';
 
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Currency symbol','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set currency symbol for property price.','wpestate').'</div>    
        <input  type="text" id="currency_symbol" name="currency_symbol"  value="'.$currency_symbol.'"/>
    </div>';
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Currency label - will appear on front end','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set the currency label for multi-currency widget dropdown.','wpestate').'</div>    
        <input  type="text" id="currency_label_main"  name="currency_label_main"   value="'. get_option('wp_estate_currency_label_main','').'" size="40"/>
    </div>';
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Where to show the currency symbol?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set the position for the currency symbol.','wpestate').'</div>    
        <select id="where_currency_symbol" name="where_currency_symbol">
            '.$where_currency_symbol.'
        </select> 
    </div>';

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Enable auto loading of exchange rates from Yahoo(1 time per day)?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Symbol must be set according to international standards. Complete list is here http://www.xe.com/iso4217.php.','wpestate').'</div>    
        <select id="auto_curency" name="auto_curency">
            '.$enable_auto_symbol.'
        </select> 
    </div>';

 
    print'<div class="estate_option_row"> 
        <h3 style="margin-left:10px;width:100%;float:left;">'.esc_html__('Add Currencies for Multi Currency Widget.','wpestate').'</h3>
     
        <div id="custom_fields">
             '.$current_fields.'
            <input type="hidden" name="is_custom_cur" value="1">   
        </div>

     
        <div class="add_curency">
            <div class="cur_explanations">'.esc_html__('Currency','wpestate').'</div>
            <input  type="text" id="currency_name"  name="currency_name"   value=""/>
        
            <div class="cur_explanations">'.esc_html__('Currency label - will appear on front end','wpestate').'</div>
            <input  type="text" id="currency_label"  name="currency_label"   value="" />   

            <div class="cur_explanations">'.esc_html__('Currency value compared with the base currency value.','wpestate').'</div>
            <input  type="text" id="currency_value"  name="currency_value"   value="" />
           
            <div class="cur_explanations">'.esc_html__('Show currency before or after price - in front pages','wpestate').'</div>
                <select id="where_cur" name="where_cur"  >
                    <option value="before"> before </option>
                    <option value="after">  after </option>
                </select>
        </div>
                     
         <a href="#" id="add_curency">'.esc_html__(' click to add currency','wpestate').'</a><br>
     </div> ';
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;

if( !function_exists('wpestate_new_map_details') ):
function wpestate_new_map_details(){
    $cache_array                    =   array('yes','no');
    $cache_array2                   =   array('no','yes');
    $show_filter_map_symbol         =   wpestate_dropdowns_theme_admin($cache_array,'show_filter_map');
    $home_small_map_symbol          =   wpestate_dropdowns_theme_admin($cache_array,'home_small_map');
    $show_adv_search_symbol_map_close   =   wpestate_dropdowns_theme_admin($cache_array,'show_adv_search_map_close');
    
    
    $path=estate_get_pin_file_path(); 
   
    if ( file_exists ($path) && is_writable ($path) ){
    }else{
        print ' <div class="notice_file">'.esc_html__('the file Google map does NOT exist or is NOT writable','wpestate').'</div>';
    }
    
    $readsys_symbol                 =   wpestate_dropdowns_theme_admin($cache_array,'readsys');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use file reading for pins? ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Use file reading for pins? (*recommended for over 200 listings. Read the manual for diffrences between file and mysql reading)','wpestate').'</div>    
        <select id="readsys" name="readsys">
            '.$readsys_symbol.'
        </select>
    </div>';
       
    
     
    $map_max_pins                 =   intval( get_option('wp_estate_map_max_pins') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Maximum number of pins to show on the map. ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('A high number will increase the response time and server load. Use a number that works for your current hosting situation. Put -1 for all pins.','wpestate').'</div>    
        <input  type="text" id="map_max_pins" name="map_max_pins" class="regular-text" value="'.$map_max_pins.'"/>
  
    </div>';
    
    $ssl_map_symbol                 =   wpestate_dropdowns_theme_admin($cache_array,'ssl_map');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Google maps with SSL ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set to Yes if you use SSL.','wpestate').'</div>    
        <select id="ssl_map" name="ssl_map">
            '.$ssl_map_symbol.'
        </select>
    </div>';  

    $api_key                        =   esc_html( get_option('wp_estate_api_key') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Google Maps API KEY','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('The Google Maps JavaScript API v3 REQUIRES an API key to function correctly. Get an APIs Console key and post the code in Theme Options. You can get it from ','wpestate').'<a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">here</a></div>    
        <input  type="text" id="api_key" name="api_key" class="regular-text" value="'.$api_key.'"/>
    </div>'; 

    $general_latitude               =   esc_html( get_option('wp_estate_general_latitude') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Starting Point Latitude','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Applies for global header media with google maps. Add only numbers (ex: 40.577906).','wpestate').'</div>    
    <input  type="text" id="general_latitude"  name="general_latitude"   value="'.$general_latitude.'"/>
    </div>'; 
    
    $general_longitude              =   esc_html( get_option('wp_estate_general_longitude') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Starting Point Longitude','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Applies for global header media with google maps. Add only numbers (ex: -74.155058).','wpestate').'</div>    
    <input  type="text" id="general_longitude" name="general_longitude"  value="'.$general_longitude.'"/>
    </div>'; 
       
    $default_map_zoom               =   intval   ( get_option('wp_estate_default_map_zoom','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Default Map zoom (1 to 20)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Applies for global header media with google maps, except advanced search results, properties list and taxonomies pages.','wpestate').'</div>    
    <input type="text" id="default_map_zoom" name="default_map_zoom" value="'.$default_map_zoom.'">   
    </div>'; 
    
    $map_types = array('SATELLITE','HYBRID','TERRAIN','ROADMAP');
    $default_map_type_symbol               =   wpestate_dropdowns_theme_admin($map_types,'default_map_type');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Map Type','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('The type selected applies for Google Maps header. ','wpestate').'</div>    
        <select id="default_map_type" name="default_map_type">
            '.$default_map_type_symbol.'
        </select> 
    </div>'; 
        
    $cache_symbol                   =   wpestate_dropdowns_theme_admin($cache_array,'cache');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Cache for Google maps ?(*cache will renew itself every 3h)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If set to yes, new property pins will update on the map every 3 hours.','wpestate').'</div>    
        <select id="cache" name="cache">
            '.$cache_symbol.'
        </select>
    </div>'; 

    $pin_cluster_symbol             =   wpestate_dropdowns_theme_admin($cache_array,'pin_cluster');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Pin Cluster on map','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If yes, it groups nearby pins in cluster.','wpestate').'</div>    
        <select id="pin_cluster" name="pin_cluster">
            '.$pin_cluster_symbol.'
        </select>
    </div>'; 
       
    $zoom_cluster                   =   esc_html ( get_option('wp_estate_zoom_cluster ','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Maximum zoom level for Cloud Cluster to appear','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Pin cluster disappears when map zoom is less than the value set in here. ','wpestate').'</div>    
        <input id="zoom_cluster" type="text" size="36" name="zoom_cluster" value="'.$zoom_cluster.'" />
    </div>'; 
    
    $hq_latitude                    =   esc_html ( get_option('wp_estate_hq_latitude') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Contact Page - Company HQ Latitude','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set company pin location for contact page template. Latitude must be a number (ex: 40.577906).','wpestate').'</div>    
        <input  type="text" id="hq_latitude"  name="hq_latitude"   value="'.$hq_latitude.'"/>
    </div>'; 
        
    $hq_longitude                   =   esc_html ( get_option('wp_estate_hq_longitude') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Contact Page - Company HQ Longitude','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Set company pin location for contact page template. Longitude must be a number (ex: -74.155058).','wpestate').'</div>    
        <input  type="text" id="hq_longitude" name="hq_longitude"  value="'.$hq_longitude.'"/>
    </div>';   
        
    
    $idx_symbol             =   wpestate_dropdowns_theme_admin($cache_array,'idx_enable');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Enable dsIDXpress to use the map','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable only if you activate dsIDXpres optional plugin. See help for details.','wpestate').'</div>    
        <select id="idx_enable" name="idx_enable">
            '.$idx_symbol.'
        </select>
    </div>';     
    
    
    $geolocation_radius         =   esc_html ( get_option('wp_estate_geolocation_radius','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Geolocation Circle over map (in meters)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Controls circle radius value for user geolocation pin. Type only numbers (ex: 400).','wpestate').'</div>    
       <input id="geolocation_radius" type="text" size="36" name="geolocation_radius" value="'.$geolocation_radius.'" />
    </div>'; 
       
    $min_height                     =   intval   ( get_option('wp_estate_min_height','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Height of the Google Map ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Applies for header google maps when set as global header media type.','wpestate').'</div>    
       <input id="min_height" type="text" size="36" name="min_height" value="'.$min_height.'" />
    </div>';  
      
 
     
    
    $show_g_search_symbol               =   wpestate_dropdowns_theme_admin($cache_array2,'show_g_search');

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Google Search over Map?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable the Google Maps search bar.','wpestate').'</div>    
        <select id="show_g_search" name="show_g_search">
            '.$show_g_search_symbol.'
        </select>
    </div>'; 
     
    $map_style  =   esc_html ( get_option('wp_estate_map_style','') );    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Style for Google Map. Use https://snazzymaps.com/ to create styles','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Copy/paste below the custom map style code.','wpestate').'</div>    
        <textarea id="map_style" style="width:100%;height:350px;" name="map_style">'.stripslashes($map_style).'</textarea>
    </div>'; 

     print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';

}   
endif;






    
if( !function_exists('wpestate_new_custom_colors') ):      
function wpestate_new_custom_colors(){
    $menu_items_color               =  esc_html ( get_option('wp_estate_menu_items_color','') );
    $agent_color                    =  esc_html ( get_option('wp_estate_agent_color','') );
    $color_scheme_array=array('no','yes');
    $color_scheme_select   = wpestate_dropdowns_theme_admin($color_scheme_array,'color_scheme');
    /*
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Custom Colors ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('You must set YES and save for your custom colors to apply.','wpestate').'</div>    
        <select id="color_scheme" name="color_scheme">
            '.$color_scheme_select.'
         </select>
    </div>'; 
       */
    $main_color                     =  esc_html ( get_option('wp_estate_main_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Main Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Main Color','wpestate').'</div>    
        <input type="text" name="main_color" maxlength="7" class="inptxt " value="'.$main_color.'"/>
        <div id="main_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$main_color.';"  ></div></div>
    </div>';   
    
    $background_color               =  esc_html ( get_option('wp_estate_background_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Background Color','wpestate').'</div>    
        <input type="text" name="background_color" maxlength="7" class="inptxt " value="'.$background_color.'"/>
        <div id="background_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$background_color.';"  ></div></div>
    </div>'; 

    $content_back_color             =  esc_html ( get_option('wp_estate_content_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Content Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Content Background Color','wpestate').'</div>    
        <input type="text" name="content_back_color" value="'.$content_back_color.'" maxlength="7" class="inptxt" />
        <div id="content_back_color" class="colorpickerHolder" ><div class="sqcolor"  style="background-color:#'.$content_back_color.';" ></div></div>
    </div>'; 
        
    $breadcrumbs_font_color         =  esc_html ( get_option('wp_estate_breadcrumbs_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Breadcrumbs, Meta and Second Line Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Breadcrumbs, Meta and Second Line Font Color','wpestate').'</div>    
        <input type="text" name="breadcrumbs_font_color" value="'.$breadcrumbs_font_color.'" maxlength="7" class="inptxt" />
        <div id="breadcrumbs_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$breadcrumbs_font_color.';" ></div></div>
    </div>';  
    
    
    $font_color                     =  esc_html ( get_option('wp_estate_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Font Color','wpestate').'</div>    
        <input type="text" name="font_color" value="'.$font_color.'" maxlength="7" class="inptxt" />
        <div id="font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$font_color.';" ></div></div>
    </div>';
       
    $link_color                     =  esc_html ( get_option('wp_estate_link_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Link Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Link Color','wpestate').'</div>    
        <input type="text" name="link_color" value="'.$link_color.'" maxlength="7" class="inptxt" />
        <div id="link_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$link_color.';" ></div></div>
    </div>';
  
    $headings_color                 =  esc_html ( get_option('wp_estate_headings_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Headings Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Headings Color','wpestate').'</div>    
        <input type="text" name="headings_color" value="'.$headings_color.'" maxlength="7" class="inptxt" />
        <div id="headings_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$headings_color.';" ></div></div>
    </div>';
        
        
    $footer_back_color              =  esc_html ( get_option('wp_estate_footer_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Footer Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Footer Background Color','wpestate').'</div>    
        <input type="text" name="footer_back_color" value="'.$footer_back_color.'" maxlength="7" class="inptxt" />
        <div id="footer_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$footer_back_color.';" ></div></div>
    </div>';
        
    $footer_font_color              =  esc_html ( get_option('wp_estate_footer_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Footer Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Footer Font Color','wpestate').'</div>    
        <input type="text" name="footer_font_color" value="'.$footer_font_color.'" maxlength="7" class="inptxt" />
        <div id="footer_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$footer_font_color.';" ></div></div>
    </div>';
        
    $footer_copy_color              =  esc_html ( get_option('wp_estate_footer_copy_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Footer Copyright Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Footer Copyright Font Color','wpestate').'</div>    
        <input type="text" name="footer_copy_color" value="'.$footer_copy_color.'" maxlength="7" class="inptxt" />
        <div id="footer_copy_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$footer_copy_color.';" ></div></div>
    </div>';
        
  
    $footer_copy_back_color              =  esc_html ( get_option('wp_estate_footer_copy_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Footer Copyright Area Background Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Footer Copyright Area Background Font Color','wpestate').'</div>    
        <input type="text" name="footer_copy_back_color" value="'.$footer_copy_back_color.'" maxlength="7" class="inptxt" />
        <div id="footer_copy_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$footer_copy_back_color.';" ></div></div>
    </div>';
         
      
    $header_color                   =  esc_html ( get_option('wp_estate_header_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Header Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Header Background Color','wpestate').'</div>    
        <input type="text" name="header_color" value="'.$header_color.'" maxlength="7" class="inptxt" />
        <div id="header_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$header_color.';" ></div></div>
    </div>';
        
    
   
    $top_bar_back                   =  esc_html ( get_option('wp_estate_top_bar_back','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Top Bar Background Color (Header Widget Menu)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Top Bar Background Color (Header Widget Menu)','wpestate').'</div>    
        <input type="text" name="top_bar_back" value="'.$top_bar_back.'" maxlength="7" class="inptxt" />
        <div id="top_bar_back" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$top_bar_back.';"></div></div>
    </div>';
     
    $top_bar_font                   =  esc_html ( get_option('wp_estate_top_bar_font','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Top Bar Font Color (Header Widget Menu)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Top Bar Font Color (Header Widget Menu)','wpestate').'</div>    
        <input type="text" name="top_bar_font" value="'.$top_bar_font.'" maxlength="7" class="inptxt" />
        <div id="top_bar_font" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$top_bar_font.';"></div></div>
    </div>';
          
  
        
   
        
    $box_content_back_color         =  esc_html ( get_option('wp_estate_box_content_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Boxed Content Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Boxed Content Background Color','wpestate').'</div>    
        <input type="text" name="box_content_back_color" value="'.$box_content_back_color.'" maxlength="7" class="inptxt" />
        <div id="box_content_back_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$box_content_back_color.';"></div></div>
    </div>';
        
    $box_content_border_color       =  esc_html ( get_option('wp_estate_box_content_border_color','') );
     print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Border Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Border Color','wpestate').'</div>    
        <input type="text" name="box_content_border_color" value="'.$box_content_border_color.'" maxlength="7" class="inptxt" />
        <div id="box_content_border_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$box_content_border_color.';"></div></div>
    </div>';
       
    $hover_button_color             =  esc_html ( get_option('wp_estate_hover_button_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Hover Button Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Hover Button Color','wpestate').'</div>    
        <input type="text" name="hover_button_color" value="'.$hover_button_color.'" maxlength="7" class="inptxt" />
        <div id="hover_button_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$hover_button_color.';"></div></div>
    </div>';
     
    $map_controls_back            =  esc_html ( get_option('wp_estate_map_controls_back','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Map Controls Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Map Controls Background Color','wpestate').'</div>    
        <input type="text" name="map_controls_back" value="'.$map_controls_back.'" maxlength="7" class="inptxt" />
        <div id="map_controls_back" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$map_controls_back.';"></div></div>
    </div>';
    
    $map_controls_font_color            =  esc_html ( get_option('wp_estate_map_controls_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Map Controls Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Map Controls Font Color','wpestate').'</div>    
        <input type="text" name="map_controls_font_color" value="'.$map_controls_font_color.'" maxlength="7" class="inptxt" />
        <div id="map_controls_font_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$map_controls_font_color.';"></div></div>
    </div>';
     
       
    $custom_css                     =  esc_html ( stripslashes( get_option('wp_estate_custom_css','') ) );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Custom Css','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Overwrite theme css using custom css.','wpestate').'</div>    
        <textarea cols="57" rows="15" name="custom_css" id="custom_css">'.$custom_css.'</textarea>
    </div>';
        
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  
     
}
endif;


if( !function_exists('wpestate_new_custom_fonts') ):    
function   wpestate_new_custom_fonts(){
   /*   $google_fonts_array = array(                          
                                                            "Abel" => "Abel",
                                                            "Abril Fatface" => "Abril Fatface",
                                                            "Aclonica" => "Aclonica",
                                                            "Acme" => "Acme",
                                                            "Actor" => "Actor",
                                                            "Adamina" => "Adamina",
                                                            "Advent Pro" => "Advent Pro",
                                                            "Aguafina Script" => "Aguafina Script",
                                                            "Aladin" => "Aladin",
                                                            "Aldrich" => "Aldrich",
                                                            "Alegreya" => "Alegreya",
                                                            "Alegreya SC" => "Alegreya SC",
                                                            "Alex Brush" => "Alex Brush",
                                                            "Alfa Slab One" => "Alfa Slab One",
                                                            "Alice" => "Alice",
                                                            "Alike" => "Alike",
                                                            "Alike Angular" => "Alike Angular",
                                                            "Allan" => "Allan",
                                                            "Allerta" => "Allerta",
                                                            "Allerta Stencil" => "Allerta Stencil",
                                                            "Allura" => "Allura",
                                                            "Almendra" => "Almendra",
                                                            "Almendra SC" => "Almendra SC",
                                                            "Amaranth" => "Amaranth",
                                                            "Amatic SC" => "Amatic SC",
                                                            "Amethysta" => "Amethysta",
                                                            "Andada" => "Andada",
                                                            "Andika" => "Andika",
                                                            "Angkor" => "Angkor",
                                                            "Annie Use Your Telescope" => "Annie Use Your Telescope",
                                                            "Anonymous Pro" => "Anonymous Pro",
                                                            "Antic" => "Antic",
                                                            "Antic Didone" => "Antic Didone",
                                                            "Antic Slab" => "Antic Slab",
                                                            "Anton" => "Anton",
                                                            "Arapey" => "Arapey",
                                                            "Arbutus" => "Arbutus",
                                                            "Architects Daughter" => "Architects Daughter",
                                                            "Arimo" => "Arimo",
                                                            "Arizonia" => "Arizonia",
                                                            "Armata" => "Armata",
                                                            "Artifika" => "Artifika",
                                                            "Arvo" => "Arvo",
                                                            "Asap" => "Asap",
                                                            "Asset" => "Asset",
                                                            "Astloch" => "Astloch",
                                                            "Asul" => "Asul",
                                                            "Atomic Age" => "Atomic Age",
                                                            "Aubrey" => "Aubrey",
                                                            "Audiowide" => "Audiowide",
                                                            "Average" => "Average",
                                                            "Averia Gruesa Libre" => "Averia Gruesa Libre",
                                                            "Averia Libre" => "Averia Libre",
                                                            "Averia Sans Libre" => "Averia Sans Libre",
                                                            "Averia Serif Libre" => "Averia Serif Libre",
                                                            "Bad Script" => "Bad Script",
                                                            "Balthazar" => "Balthazar",
                                                            "Bangers" => "Bangers",
                                                            "Basic" => "Basic",
                                                            "Battambang" => "Battambang",
                                                            "Baumans" => "Baumans",
                                                            "Bayon" => "Bayon",
                                                            "Belgrano" => "Belgrano",
                                                            "Belleza" => "Belleza",
                                                            "Bentham" => "Bentham",
                                                            "Berkshire Swash" => "Berkshire Swash",
                                                            "Bevan" => "Bevan",
                                                            "Bigshot One" => "Bigshot One",
                                                            "Bilbo" => "Bilbo",
                                                            "Bilbo Swash Caps" => "Bilbo Swash Caps",
                                                            "Bitter" => "Bitter",
                                                            "Black Ops One" => "Black Ops One",
                                                            "Bokor" => "Bokor",
                                                            "Bonbon" => "Bonbon",
                                                            "Boogaloo" => "Boogaloo",
                                                            "Bowlby One" => "Bowlby One",
                                                            "Bowlby One SC" => "Bowlby One SC",
                                                            "Brawler" => "Brawler",
                                                            "Bree Serif" => "Bree Serif",
                                                            "Bubblegum Sans" => "Bubblegum Sans",
                                                            "Buda" => "Buda",
                                                            "Buenard" => "Buenard",
                                                            "Butcherman" => "Butcherman",
                                                            "Butterfly Kids" => "Butterfly Kids",
                                                            "Cabin" => "Cabin",
                                                            "Cabin Condensed" => "Cabin Condensed",
                                                            "Cabin Sketch" => "Cabin Sketch",
                                                            "Caesar Dressing" => "Caesar Dressing",
                                                            "Cagliostro" => "Cagliostro",
                                                            "Calligraffitti" => "Calligraffitti",
                                                            "Cambo" => "Cambo",
                                                            "Candal" => "Candal",
                                                            "Cantarell" => "Cantarell",
                                                            "Cantata One" => "Cantata One",
                                                            "Cardo" => "Cardo",
                                                            "Carme" => "Carme",
                                                            "Carter One" => "Carter One",
                                                            "Caudex" => "Caudex",
                                                            "Cedarville Cursive" => "Cedarville Cursive",
                                                            "Ceviche One" => "Ceviche One",
                                                            "Changa One" => "Changa One",
                                                            "Chango" => "Chango",
                                                            "Chau Philomene One" => "Chau Philomene One",
                                                            "Chelsea Market" => "Chelsea Market",
                                                            "Chenla" => "Chenla",
                                                            "Cherry Cream Soda" => "Cherry Cream Soda",
                                                            "Chewy" => "Chewy",
                                                            "Chicle" => "Chicle",
                                                            "Chivo" => "Chivo",
                                                            "Coda" => "Coda",
                                                            "Coda Caption" => "Coda Caption",
                                                            "Codystar" => "Codystar",
                                                            "Comfortaa" => "Comfortaa",
                                                            "Coming Soon" => "Coming Soon",
                                                            "Concert One" => "Concert One",
                                                            "Condiment" => "Condiment",
                                                            "Content" => "Content",
                                                            "Contrail One" => "Contrail One",
                                                            "Convergence" => "Convergence",
                                                            "Cookie" => "Cookie",
                                                            "Copse" => "Copse",
                                                            "Corben" => "Corben",
                                                            "Cousine" => "Cousine",
                                                            "Coustard" => "Coustard",
                                                            "Covered By Your Grace" => "Covered By Your Grace",
                                                            "Crafty Girls" => "Crafty Girls",
                                                            "Creepster" => "Creepster",
                                                            "Crete Round" => "Crete Round",
                                                            "Crimson Text" => "Crimson Text",
                                                            "Crushed" => "Crushed",
                                                            "Cuprum" => "Cuprum",
                                                            "Cutive" => "Cutive",
                                                            "Damion" => "Damion",
                                                            "Dancing Script" => "Dancing Script",
                                                            "Dangrek" => "Dangrek",
                                                            "Dawning of a New Day" => "Dawning of a New Day",
                                                            "Days One" => "Days One",
                                                            "Delius" => "Delius",
                                                            "Delius Swash Caps" => "Delius Swash Caps",
                                                            "Delius Unicase" => "Delius Unicase",
                                                            "Della Respira" => "Della Respira",
                                                            "Devonshire" => "Devonshire",
                                                            "Didact Gothic" => "Didact Gothic",
                                                            "Diplomata" => "Diplomata",
                                                            "Diplomata SC" => "Diplomata SC",
                                                            "Doppio One" => "Doppio One",
                                                            "Dorsa" => "Dorsa",
                                                            "Dosis" => "Dosis",
                                                            "Dr Sugiyama" => "Dr Sugiyama",
                                                            "Droid Sans" => "Droid Sans",
                                                            "Droid Sans Mono" => "Droid Sans Mono",
                                                            "Droid Serif" => "Droid Serif",
                                                            "Duru Sans" => "Duru Sans",
                                                            "Dynalight" => "Dynalight",
                                                            "EB Garamond" => "EB Garamond",
                                                            "Eater" => "Eater",
                                                            "Economica" => "Economica",
                                                            "Electrolize" => "Electrolize",
                                                            "Emblema One" => "Emblema One",
                                                            "Emilys Candy" => "Emilys Candy",
                                                            "Engagement" => "Engagement",
                                                            "Enriqueta" => "Enriqueta",
                                                            "Erica One" => "Erica One",
                                                            "Esteban" => "Esteban",
                                                            "Euphoria Script" => "Euphoria Script",
                                                            "Ewert" => "Ewert",
                                                            "Exo" => "Exo",
                                                            "Expletus Sans" => "Expletus Sans",
                                                            "Fanwood Text" => "Fanwood Text",
                                                            "Fascinate" => "Fascinate",
                                                            "Fascinate Inline" => "Fascinate Inline",
                                                            "Federant" => "Federant",
                                                            "Federo" => "Federo",
                                                            "Felipa" => "Felipa",
                                                            "Fjord One" => "Fjord One",
                                                            "Flamenco" => "Flamenco",
                                                            "Flavors" => "Flavors",
                                                            "Fondamento" => "Fondamento",
                                                            "Fontdiner Swanky" => "Fontdiner Swanky",
                                                            "Forum" => "Forum",
                                                            "Francois One" => "Francois One",
                                                            "Fredericka the Great" => "Fredericka the Great",
                                                            "Fredoka One" => "Fredoka One",
                                                            "Freehand" => "Freehand",
                                                            "Fresca" => "Fresca",
                                                            "Frijole" => "Frijole",
                                                            "Fugaz One" => "Fugaz One",
                                                            "GFS Didot" => "GFS Didot",
                                                            "GFS Neohellenic" => "GFS Neohellenic",
                                                            "Galdeano" => "Galdeano",
                                                            "Gentium Basic" => "Gentium Basic",
                                                            "Gentium Book Basic" => "Gentium Book Basic",
                                                            "Geo" => "Geo",
                                                            "Geostar" => "Geostar",
                                                            "Geostar Fill" => "Geostar Fill",
                                                            "Germania One" => "Germania One",
                                                            "Give You Glory" => "Give You Glory",
                                                            "Glass Antiqua" => "Glass Antiqua",
                                                            "Glegoo" => "Glegoo",
                                                            "Gloria Hallelujah" => "Gloria Hallelujah",
                                                            "Goblin One" => "Goblin One",
                                                            "Gochi Hand" => "Gochi Hand",
                                                            "Gorditas" => "Gorditas",
                                                            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
                                                            "Graduate" => "Graduate",
                                                            "Gravitas One" => "Gravitas One",
                                                            "Great Vibes" => "Great Vibes",
                                                            "Gruppo" => "Gruppo",
                                                            "Gudea" => "Gudea",
                                                            "Habibi" => "Habibi",
                                                            "Hammersmith One" => "Hammersmith One",
                                                            "Handlee" => "Handlee",
                                                            "Hanuman" => "Hanuman",
                                                            "Happy Monkey" => "Happy Monkey",
                                                            "Henny Penny" => "Henny Penny",
                                                            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
                                                            "Holtwood One SC" => "Holtwood One SC",
                                                            "Homemade Apple" => "Homemade Apple",
                                                            "Homenaje" => "Homenaje",
                                                            "IM Fell DW Pica" => "IM Fell DW Pica",
                                                            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
                                                            "IM Fell Double Pica" => "IM Fell Double Pica",
                                                            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
                                                            "IM Fell English" => "IM Fell English",
                                                            "IM Fell English SC" => "IM Fell English SC",
                                                            "IM Fell French Canon" => "IM Fell French Canon",
                                                            "IM Fell French Canon SC" => "IM Fell French Canon SC",
                                                            "IM Fell Great Primer" => "IM Fell Great Primer",
                                                            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
                                                            "Iceberg" => "Iceberg",
                                                            "Iceland" => "Iceland",
                                                            "Imprima" => "Imprima",
                                                            "Inconsolata" => "Inconsolata",
                                                            "Inder" => "Inder",
                                                            "Indie Flower" => "Indie Flower",
                                                            "Inika" => "Inika",
                                                            "Irish Grover" => "Irish Grover",
                                                            "Istok Web" => "Istok Web",
                                                            "Italiana" => "Italiana",
                                                            "Italianno" => "Italianno",
                                                            "Jim Nightshade" => "Jim Nightshade",
                                                            "Jockey One" => "Jockey One",
                                                            "Jolly Lodger" => "Jolly Lodger",
                                                            "Josefin Sans" => "Josefin Sans",
                                                            "Josefin Slab" => "Josefin Slab",
                                                            "Judson" => "Judson",
                                                            "Julee" => "Julee",
                                                            "Junge" => "Junge",
                                                            "Jura" => "Jura",
                                                            "Just Another Hand" => "Just Another Hand",
                                                            "Just Me Again Down Here" => "Just Me Again Down Here",
                                                            "Kameron" => "Kameron",
                                                            "Karla" => "Karla",
                                                            "Kaushan Script" => "Kaushan Script",
                                                            "Kelly Slab" => "Kelly Slab",
                                                            "Kenia" => "Kenia",
                                                            "Khmer" => "Khmer",
                                                            "Knewave" => "Knewave",
                                                            "Kotta One" => "Kotta One",
                                                            "Koulen" => "Koulen",
                                                            "Kranky" => "Kranky",
                                                            "Kreon" => "Kreon",
                                                            "Kristi" => "Kristi",
                                                            "Krona One" => "Krona One",
                                                            "La Belle Aurore" => "La Belle Aurore",
                                                            "Lancelot" => "Lancelot",
                                                            "Lato" => "Lato",
                                                            "League Script" => "League Script",
                                                            "Leckerli One" => "Leckerli One",
                                                            "Ledger" => "Ledger",
                                                            "Lekton" => "Lekton",
                                                            "Lemon" => "Lemon",
                                                            "Lilita One" => "Lilita One",
                                                            "Limelight" => "Limelight",
                                                            "Linden Hill" => "Linden Hill",
                                                            "Lobster" => "Lobster",
                                                            "Lobster Two" => "Lobster Two",
                                                            "Londrina Outline" => "Londrina Outline",
                                                            "Londrina Shadow" => "Londrina Shadow",
                                                            "Londrina Sketch" => "Londrina Sketch",
                                                            "Londrina Solid" => "Londrina Solid",
                                                            "Lora" => "Lora",
                                                            "Love Ya Like A Sister" => "Love Ya Like A Sister",
                                                            "Loved by the King" => "Loved by the King",
                                                            "Lovers Quarrel" => "Lovers Quarrel",
                                                            "Luckiest Guy" => "Luckiest Guy",
                                                            "Lusitana" => "Lusitana",
                                                            "Lustria" => "Lustria",
                                                            "Macondo" => "Macondo",
                                                            "Macondo Swash Caps" => "Macondo Swash Caps",
                                                            "Magra" => "Magra",
                                                            "Maiden Orange" => "Maiden Orange",
                                                            "Mako" => "Mako",
                                                            "Marck Script" => "Marck Script",
                                                            "Marko One" => "Marko One",
                                                            "Marmelad" => "Marmelad",
                                                            "Marvel" => "Marvel",
                                                            "Mate" => "Mate",
                                                            "Mate SC" => "Mate SC",
                                                            "Maven Pro" => "Maven Pro",
                                                            "Meddon" => "Meddon",
                                                            "MedievalSharp" => "MedievalSharp",
                                                            "Medula One" => "Medula One",
                                                            "Megrim" => "Megrim",
                                                            "Merienda One" => "Merienda One",
                                                            "Merriweather" => "Merriweather",
                                                            "Metal" => "Metal",
                                                            "Metamorphous" => "Metamorphous",
                                                            "Metrophobic" => "Metrophobic",
                                                            "Michroma" => "Michroma",
                                                            "Miltonian" => "Miltonian",
                                                            "Miltonian Tattoo" => "Miltonian Tattoo",
                                                            "Miniver" => "Miniver",
                                                            "Miss Fajardose" => "Miss Fajardose",
                                                            "Modern Antiqua" => "Modern Antiqua",
                                                            "Molengo" => "Molengo",
                                                            "Monofett" => "Monofett",
                                                            "Monoton" => "Monoton",
                                                            "Monsieur La Doulaise" => "Monsieur La Doulaise",
                                                            "Montaga" => "Montaga",
                                                            "Montez" => "Montez",
                                                            "Montserrat" => "Montserrat",
                                                            "Moul" => "Moul",
                                                            "Moulpali" => "Moulpali",
                                                            "Mountains of Christmas" => "Mountains of Christmas",
                                                            "Mr Bedfort" => "Mr Bedfort",
                                                            "Mr Dafoe" => "Mr Dafoe",
                                                            "Mr De Haviland" => "Mr De Haviland",
                                                            "Mrs Saint Delafield" => "Mrs Saint Delafield",
                                                            "Mrs Sheppards" => "Mrs Sheppards",
                                                            "Muli" => "Muli",
                                                            "Mystery Quest" => "Mystery Quest",
                                                            "Neucha" => "Neucha",
                                                            "Neuton" => "Neuton",
                                                            "News Cycle" => "News Cycle",
                                                            "Niconne" => "Niconne",
                                                            "Nixie One" => "Nixie One",
                                                            "Nobile" => "Nobile",
                                                            "Nokora" => "Nokora",
                                                            "Norican" => "Norican",
                                                            "Nosifer" => "Nosifer",
                                                            "Nothing You Could Do" => "Nothing You Could Do",
                                                            "Noticia Text" => "Noticia Text",
                                                            "Nova Cut" => "Nova Cut",
                                                            "Nova Flat" => "Nova Flat",
                                                            "Nova Mono" => "Nova Mono",
                                                            "Nova Oval" => "Nova Oval",
                                                            "Nova Round" => "Nova Round",
                                                            "Nova Script" => "Nova Script",
                                                            "Nova Slim" => "Nova Slim",
                                                            "Nova Square" => "Nova Square",
                                                            "Numans" => "Numans",
                                                            "Nunito" => "Nunito",
                                                            "Odor Mean Chey" => "Odor Mean Chey",
                                                            "Old Standard TT" => "Old Standard TT",
                                                            "Oldenburg" => "Oldenburg",
                                                            "Oleo Script" => "Oleo Script",
                                                            "Open Sans" => "Open Sans",
                                                            "Open Sans Condensed" => "Open Sans Condensed",
                                                            "Orbitron" => "Orbitron",
                                                            "Original Surfer" => "Original Surfer",
                                                            "Oswald" => "Oswald",
                                                            "Over the Rainbow" => "Over the Rainbow",
                                                            "Overlock" => "Overlock",
                                                            "Overlock SC" => "Overlock SC",
                                                            "Ovo" => "Ovo",
                                                            "Oxygen" => "Oxygen",
                                                            "PT Mono" => "PT Mono",
                                                            "PT Sans" => "PT Sans",
                                                            "PT Sans Caption" => "PT Sans Caption",
                                                            "PT Sans Narrow" => "PT Sans Narrow",
                                                            "PT Serif" => "PT Serif",
                                                            "PT Serif Caption" => "PT Serif Caption",
                                                            "Pacifico" => "Pacifico",
                                                            "Parisienne" => "Parisienne",
                                                            "Passero One" => "Passero One",
                                                            "Passion One" => "Passion One",
                                                            "Patrick Hand" => "Patrick Hand",
                                                            "Patua One" => "Patua One",
                                                            "Paytone One" => "Paytone One",
                                                            "Permanent Marker" => "Permanent Marker",
                                                            "Petrona" => "Petrona",
                                                            "Philosopher" => "Philosopher",
                                                            "Piedra" => "Piedra",
                                                            "Pinyon Script" => "Pinyon Script",
                                                            "Plaster" => "Plaster",
                                                            "Play" => "Play",
                                                            "Playball" => "Playball",
                                                            "Playfair Display" => "Playfair Display",
                                                            "Podkova" => "Podkova",
                                                            "Poiret One" => "Poiret One",
                                                            "Poller One" => "Poller One",
                                                            "Poly" => "Poly",
                                                            "Pompiere" => "Pompiere",
                                                            "Pontano Sans" => "Pontano Sans",
                                                            "Port Lligat Sans" => "Port Lligat Sans",
                                                            "Port Lligat Slab" => "Port Lligat Slab",
                                                            "Prata" => "Prata",
                                                            "Preahvihear" => "Preahvihear",
                                                            "Press Start 2P" => "Press Start 2P",
                                                            "Princess Sofia" => "Princess Sofia",
                                                            "Prociono" => "Prociono",
                                                            "Prosto One" => "Prosto One",
                                                            "Puritan" => "Puritan",
                                                            "Quantico" => "Quantico",
                                                            "Quattrocento" => "Quattrocento",
                                                            "Quattrocento Sans" => "Quattrocento Sans",
                                                            "Questrial" => "Questrial",
                                                            "Quicksand" => "Quicksand",
                                                            "Qwigley" => "Qwigley",
                                                            "Radley" => "Radley",
                                                            "Raleway" => "Raleway",
                                                            "Rammetto One" => "Rammetto One",
                                                            "Rancho" => "Rancho",
                                                            "Rationale" => "Rationale",
                                                            "Redressed" => "Redressed",
                                                            "Reenie Beanie" => "Reenie Beanie",
                                                            "Revalia" => "Revalia",
                                                            "Ribeye" => "Ribeye",
                                                            "Ribeye Marrow" => "Ribeye Marrow",
                                                            "Righteous" => "Righteous",
                                                            "Rochester" => "Rochester",
                                                            "Rock Salt" => "Rock Salt",
                                                            "Rokkitt" => "Rokkitt",
                                                            "Ropa Sans" => "Ropa Sans",
                                                            "Rosario" => "Rosario",
                                                            "Rosarivo" => "Rosarivo",
                                                            "Rouge Script" => "Rouge Script",
                                                            "Ruda" => "Ruda",
                                                            "Ruge Boogie" => "Ruge Boogie",
                                                            "Ruluko" => "Ruluko",
                                                            "Ruslan Display" => "Ruslan Display",
                                                            "Russo One" => "Russo One",
                                                            "Ruthie" => "Ruthie",
                                                            "Sail" => "Sail",
                                                            "Salsa" => "Salsa",
                                                            "Sancreek" => "Sancreek",
                                                            "Sansita One" => "Sansita One",
                                                            "Sarina" => "Sarina",
                                                            "Satisfy" => "Satisfy",
                                                            "Schoolbell" => "Schoolbell",
                                                            "Seaweed Script" => "Seaweed Script",
                                                            "Sevillana" => "Sevillana",
                                                            "Shadows Into Light" => "Shadows Into Light",
                                                            "Shadows Into Light Two" => "Shadows Into Light Two",
                                                            "Shanti" => "Shanti",
                                                            "Share" => "Share",
                                                            "Shojumaru" => "Shojumaru",
                                                            "Short Stack" => "Short Stack",
                                                            "Siemreap" => "Siemreap",
                                                            "Sigmar One" => "Sigmar One",
                                                            "Signika" => "Signika",
                                                            "Signika Negative" => "Signika Negative",
                                                            "Simonetta" => "Simonetta",
                                                            "Sirin Stencil" => "Sirin Stencil",
                                                            "Six Caps" => "Six Caps",
                                                            "Slackey" => "Slackey",
                                                            "Smokum" => "Smokum",
                                                            "Smythe" => "Smythe",
                                                            "Sniglet" => "Sniglet",
                                                            "Snippet" => "Snippet",
                                                            "Sofia" => "Sofia",
                                                            "Sonsie One" => "Sonsie One",
                                                            "Sorts Mill Goudy" => "Sorts Mill Goudy",
                                                            "Special Elite" => "Special Elite",
                                                            "Spicy Rice" => "Spicy Rice",
                                                            "Spinnaker" => "Spinnaker",
                                                            "Spirax" => "Spirax",
                                                            "Squada One" => "Squada One",
                                                            "Stardos Stencil" => "Stardos Stencil",
                                                            "Stint Ultra Condensed" => "Stint Ultra Condensed",
                                                            "Stint Ultra Expanded" => "Stint Ultra Expanded",
                                                            "Stoke" => "Stoke",
                                                            "Sue Ellen Francisco" => "Sue Ellen Francisco",
                                                            "Sunshiney" => "Sunshiney",
                                                            "Supermercado One" => "Supermercado One",
                                                            "Suwannaphum" => "Suwannaphum",
                                                            "Swanky and Moo Moo" => "Swanky and Moo Moo",
                                                            "Syncopate" => "Syncopate",
                                                            "Tangerine" => "Tangerine",
                                                            "Taprom" => "Taprom",
                                                            "Telex" => "Telex",
                                                            "Tenor Sans" => "Tenor Sans",
                                                            "The Girl Next Door" => "The Girl Next Door",
                                                            "Tienne" => "Tienne",
                                                            "Tinos" => "Tinos",
                                                            "Titan One" => "Titan One",
                                                            "Trade Winds" => "Trade Winds",
                                                            "Trocchi" => "Trocchi",
                                                            "Trochut" => "Trochut",
                                                            "Trykker" => "Trykker",
                                                            "Tulpen One" => "Tulpen One",
                                                            "Ubuntu" => "Ubuntu",
                                                            "Ubuntu Condensed" => "Ubuntu Condensed",
                                                            "Ubuntu Mono" => "Ubuntu Mono",
                                                            "Ultra" => "Ultra",
                                                            "Uncial Antiqua" => "Uncial Antiqua",
                                                            "UnifrakturCook" => "UnifrakturCook",
                                                            "UnifrakturMaguntia" => "UnifrakturMaguntia",
                                                            "Unkempt" => "Unkempt",
                                                            "Unlock" => "Unlock",
                                                            "Unna" => "Unna",
                                                            "VT323" => "VT323",
                                                            "Varela" => "Varela",
                                                            "Varela Round" => "Varela Round",
                                                            "Vast Shadow" => "Vast Shadow",
                                                            "Vibur" => "Vibur",
                                                            "Vidaloka" => "Vidaloka",
                                                            "Viga" => "Viga",
                                                            "Voces" => "Voces",
                                                            "Volkhov" => "Volkhov",
                                                            "Vollkorn" => "Vollkorn",
                                                            "Voltaire" => "Voltaire",
                                                            "Waiting for the Sunrise" => "Waiting for the Sunrise",
                                                            "Wallpoet" => "Wallpoet",
                                                            "Walter Turncoat" => "Walter Turncoat",
                                                            "Wellfleet" => "Wellfleet",
                                                            "Wire One" => "Wire One",
                                                            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
                                                            "Yellowtail" => "Yellowtail",
                                                            "Yeseva One" => "Yeseva One",
                                                            "Yesteryear" => "Yesteryear",
                                                            "Zeyada" => "Zeyada",
                                                    ); */
$google_fonts_array = array(
                'ABeeZee',
                'Abel',
                'Abril+Fatface',
                'Aclonica',
                'Acme',
                'Actor',
                'Adamina',
                'Advent+Pro',
                'Aguafina+Script',
                'Akronim',
                'Aladin',
                'Aldrich',
                'Alef',
                'Alegreya',
                'Alegreya+Sans',
                'Alegreya+Sans+SC',
                'Alegreya+SC',
                'Alex+Brush',
                'Alfa+Slab+One',
                'Alice',
                'Alike',
                'Alike+Angular',
                'Allan',
                'Allerta',
                'Allerta+Stencil',
                'Allura',
                'Almendra',
                'Almendra+Display',
                'Almendra+SC',
                'Amarante',
                'Amaranth',
                'Amatic+SC',
                'Amethysta',
                'Amiri',
                'Amita',
                'Anaheim',
                'Andada',
                'Andika',
                'Angkor',
                'Annie+Use+Your+Telescope',
                'Anonymous+Pro',
                'Antic',
                'Antic+Didone',
                'Antic+Slab',
                'Anton',
                'Arapey',
                'Arbutus',
                'Arbutus+Slab',
                'Architects+Daughter',
                'Archivo+Black',
                'Archivo+Narrow',
                'Arimo',
                'Arizonia',
                'Armata',
                'Artifika',
                'Arvo',
                'Arya',
                'Asap',
                'Asar',
                'Asset',
                'Astloch',
                'Asul',
                'Atomic+Age',
                'Aubrey',
                'Audiowide',
                'Autour+One',
                'Average',
                'Average+Sans',
                'Averia+Gruesa+Libre',
                'Averia+Libre',
                'Averia+Sans+Libre',
                'Averia+Serif+Libre',
                'Bad+Script',
                'Balthazar',
                'Bangers',
                'Basic',
                'Battambang',
                'Baumans',
                'Bayon',
                'Belgrano',
                'Belleza',
                'BenchNine',
                'Bentham',
                'Berkshire+Swash',
                'Bevan',
                'Bigelow+Rules',
                'Bigshot+One',
                'Bilbo',
                'Bilbo+Swash+Caps',
                'Biryani',
                'Bitter',
                'Black+Ops+One',
                'Bokor',
                'Bonbon',
                'Boogaloo',
                'Bowlby+One',
                'Bowlby+One+SC',
                'Brawler',
                'Bree+Serif',
                'Bubblegum+Sans',
                'Bubbler+One',
                'Buda',
                'Buenard',
                'Butcherman',
                'Butterfly+Kids',
                'Cabin',
                'Cabin+Condensed',
                'Cabin+Sketch',
                'Caesar+Dressing',
                'Cagliostro',
                'Calligraffitti',
                'Cambay',
                'Cambo',
                'Candal',
                'Cantarell',
                'Cantata+One',
                'Cantora+One',
                'Capriola',
                'Cardo',
                'Carme',
                'Carrois+Gothic',
                'Carrois+Gothic+SC',
                'Carter+One',
                'Catamaran',
                'Caudex',
                'Cedarville+Cursive',
                'Ceviche+One',
                'Changa+One',
                'Chango',
                'Chau+Philomene+One',
                'Chela+One',
                'Chelsea+Market',
                'Chenla',
                'Cherry+Cream+Soda',
                'Cherry+Swash',
                'Chewy',
                'Chicle',
                'Chivo',
                'Chonburi',
                'Cinzel',
                'Cinzel+Decorative',
                'Clicker+Script',
                'Coda',
                'Coda+Caption',
                'Codystar',
                'Combo',
                'Comfortaa',
                'Coming+Soon',
                'Concert+One',
                'Condiment',
                'Content',
                'Contrail+One',
                'Convergence',
                'Cookie',
                'Copse',
                'Corben',
                'Courgette',
                'Cousine',
                'Coustard',
                'Covered+By+Your+Grace',
                'Crafty+Girls',
                'Creepster',
                'Crete+Round',
                'Crimson+Text',
                'Croissant+One',
                'Crushed',
                'Cuprum',
                'Cutive',
                'Cutive+Mono',
                'Damion',
                'Dancing+Script',
                'Dangrek',
                'Dawning+of+a+New+Day',
                'Days+One',
                'Dekko',
                'Delius',
                'Delius+Swash+Caps',
                'Delius+Unicase',
                'Della+Respira',
                'Denk+One',
                'Devonshire',
                'Dhurjati',
                'Didact+Gothic',
                'Diplomata',
                'Diplomata+SC',
                'Domine',
                'Donegal+One',
                'Doppio+One',
                'Dorsa',
                'Dosis',
                'Dr+Sugiyama',
                'Droid+Sans',
                'Droid+Sans+Mono',
                'Droid+Serif',
                'Duru+Sans',
                'Dynalight',
                'Eagle+Lake',
                'Eater',
                'EB+Garamond',
                'Economica',
                'Eczar',
                'Ek+Mukta',
                'Electrolize',
                'Elsie',
                'Elsie+Swash+Caps',
                'Emblema+One',
                'Emilys+Candy',
                'Engagement',
                'Englebert',
                'Enriqueta',
                'Erica+One',
                'Esteban',
                'Euphoria+Script',
                'Ewert',
                'Exo',
                'Exo+2',
                'Expletus+Sans',
                'Fanwood+Text',
                'Fascinate',
                'Fascinate+Inline',
                'Faster+One',
                'Fasthand',
                'Fauna+One',
                'Federant',
                'Federo',
                'Felipa',
                'Fenix',
                'Finger+Paint',
                'Fira+Mono',
                'Fira+Sans',
                'Fjalla+One',
                'Fjord+One',
                'Flamenco',
                'Flavors',
                'Fondamento',
                'Fontdiner+Swanky',
                'Forum',
                'Francois+One',
                'Freckle+Face',
                'Fredericka+the+Great',
                'Fredoka+One',
                'Freehand',
                'Fresca',
                'Frijole',
                'Fruktur',
                'Fugaz+One',
                'Gabriela',
                'Gafata',
                'Galdeano',
                'Galindo',
                'Gentium+Basic',
                'Gentium+Book+Basic',
                'Geo',
                'Geostar',
                'Geostar+Fill',
                'Germania+One',
                'GFS+Didot',
                'GFS+Neohellenic',
                'Gidugu',
                'Gilda+Display',
                'Give+You+Glory',
                'Glass+Antiqua',
                'Glegoo',
                'Gloria+Hallelujah',
                'Goblin+One',
                'Gochi+Hand',
                'Gorditas',
                'Goudy+Bookletter+1911',
                'Graduate',
                'Grand+Hotel',
                'Gravitas+One',
                'Great+Vibes',
                'Griffy',
                'Gruppo',
                'Gudea',
                'Gurajada',
                'Habibi',
                'Halant',
                'Hammersmith+One',
                'Hanalei',
                'Hanalei+Fill',
                'Handlee',
                'Hanuman',
                'Happy+Monkey',
                'Headland+One',
                'Henny+Penny',
                'Herr+Von+Muellerhoff',
                'Hind',
                'Holtwood+One+SC',
                'Homemade+Apple',
                'Homenaje',
                'Iceberg',
                'Iceland',
                'IM+Fell+Double+Pica',
                'IM+Fell+Double+Pica+SC',
                'IM+Fell+DW+Pica',
                'IM+Fell+DW+Pica+SC',
                'IM+Fell+English',
                'IM+Fell+English+SC',
                'IM+Fell+French+Canon',
                'IM+Fell+French+Canon+SC',
                'IM+Fell+Great+Primer',
                'IM+Fell+Great+Primer+SC',
                'Imprima',
                'Inconsolata',
                'Inder',
                'Indie+Flower',
                'Inika',
                'Inknut+Antiqua',
                'Irish+Grover',
                'Istok+Web',
                'Italiana',
                'Italianno',
                'Itim',
                'Jacques+Francois',
                'Jacques+Francois+Shadow',
                'Jaldi',
                'Jim+Nightshade',
                'Jockey+One',
                'Jolly+Lodger',
                'Josefin+Sans',
                'Josefin+Slab',
                'Joti+One',
                'Judson',
                'Julee',
                'Julius+Sans+One',
                'Junge',
                'Jura',
                'Just+Another+Hand',
                'Just+Me+Again+Down+Here',
                'Kadwa',
                'Kalam',
                'Kameron',
                'Kantumruy',
                'Karla',
                'Karma',
                'Kaushan+Script',
                'Kavoon',
                'Kdam+Thmor',
                'Keania+One',
                'Kelly+Slab',
                'Kenia',
                'Khand',
                'Khmer',
                'Khula',
                'Kite+One',
                'Knewave',
                'Kotta+One',
                'Koulen',
                'Kranky',
                'Kreon',
                'Kristi',
                'Krona+One',
                'Kurale',
                'La+Belle+Aurore',
                'Laila',
                'Lakki+Reddy',
                'Lancelot',
                'Lateef',
                'Lato',
                'League+Script',
                'Leckerli+One',
                'Ledger',
                'Lekton',
                'Lemon',
                'Libre+Baskerville',
                'Life+Savers',
                'Lilita+One',
                'Lily+Script+One',
                'Limelight',
                'Linden+Hill',
                'Lobster',
                'Lobster+Two',
                'Londrina+Outline',
                'Londrina+Shadow',
                'Londrina+Sketch',
                'Londrina+Solid',
                'Lora',
                'Love+Ya+Like+A+Sister',
                'Loved+by+the+King',
                'Lovers+Quarrel',
                'Luckiest+Guy',
                'Lusitana',
                'Lustria',
                'Macondo',
                'Macondo+Swash+Caps',
                'Magra',
                'Maiden+Orange',
                'Mako',
                'Mallanna',
                'Mandali',
                'Marcellus',
                'Marcellus+SC',
                'Marck+Script',
                'Margarine',
                'Marko+One',
                'Marmelad',
                'Martel',
                'Martel+Sans',
                'Marvel',
                'Mate',
                'Mate+SC',
                'Maven+Pro',
                'McLaren',
                'Meddon',
                'MedievalSharp',
                'Medula+One',
                'Megrim',
                'Meie+Script',
                'Merienda',
                'Merienda+One',
                'Merriweather',
                'Merriweather+Sans',
                'Metal',
                'Metal+Mania',
                'Metamorphous',
                'Metrophobic',
                'Michroma',
                'Milonga',
                'Miltonian',
                'Miltonian+Tattoo',
                'Miniver',
                'Miss+Fajardose',
                'Modak',
                'Modern+Antiqua',
                'Molengo',
                'Molle',
                'Monda',
                'Monofett',
                'Monoton',
                'Monsieur+La+Doulaise',
                'Montaga',
                'Montez',
                'Montserrat',
                'Montserrat+Alternates',
                'Montserrat+Subrayada',
                'Moul',
                'Moulpali',
                'Mountains+of+Christmas',
                'Mouse+Memoirs',
                'Mr+Bedfort',
                'Mr+Dafoe',
                'Mr+De+Haviland',
                'Mrs+Saint+Delafield',
                'Mrs+Sheppards',
                'Muli',
                'Mystery+Quest',
                'Neucha',
                'Neuton',
                'New+Rocker',
                'News+Cycle',
                'Niconne',
                'Nixie+One',
                'Nobile',
                'Nokora',
                'Norican',
                'Nosifer',
                'Nothing+You+Could+Do',
                'Noticia+Text',
                'Noto+Sans',
                'Noto+Serif',
                'Nova+Cut',
                'Nova+Flat',
                'Nova+Mono',
                'Nova+Oval',
                'Nova+Round',
                'Nova+Script',
                'Nova+Slim',
                'Nova+Square',
                'NTR',
                'Numans',
                'Nunito',
                'Odor+Mean+Chey',
                'Offside',
                'Old+Standard+TT',
                'Oldenburg',
                'Oleo+Script',
                'Oleo+Script+Swash+Caps',
                'Open+Sans',
                'Open+Sans+Condensed',
                'Oranienbaum',
                'Orbitron',
                'Oregano',
                'Orienta',
                'Original+Surfer',
                'Oswald',
                'Over+the+Rainbow',
                'Overlock',
                'Overlock+SC',
                'Ovo',
                'Oxygen',
                'Oxygen+Mono',
                'Pacifico',
                'Palanquin',
                'Palanquin+Dark',
                'Paprika',
                'Parisienne',
                'Passero+One',
                'Passion+One',
                'Pathway+Gothic+One',
                'Patrick+Hand',
                'Patrick+Hand+SC',
                'Patua+One',
                'Paytone+One',
                'Peddana',
                'Peralta',
                'Permanent+Marker',
                'Petit+Formal+Script',
                'Petrona',
                'Philosopher',
                'Piedra',
                'Pinyon+Script',
                'Pirata+One',
                'Plaster',
                'Play',
                'Playball',
                'Playfair+Display',
                'Playfair+Display+SC',
                'Podkova',
                'Poiret+One',
                'Poller+One',
                'Poly',
                'Pompiere',
                'Pontano+Sans',
                'Poppins',
                'Port+Lligat+Sans',
                'Port+Lligat+Slab',
                'Pragati+Narrow',
                'Prata',
                'Preahvihear',
                'Press+Start+2P',
                'Princess+Sofia',
                'Prociono',
                'Prosto+One',
                'PT+Mono',
                'PT+Sans',
                'PT+Sans+Caption',
                'PT+Sans+Narrow',
                'PT+Serif',
                'PT+Serif+Caption',
                'Puritan',
                'Purple+Purse',
                'Quando',
                'Quantico',
                'Quattrocento',
                'Quattrocento+Sans',
                'Questrial',
                'Quicksand',
                'Quintessential',
                'Qwigley',
                'Racing+Sans+One',
                'Radley',
                'Rajdhani',
                'Raleway',
                'Raleway+Dots',
                'Ramabhadra',
                'Ramaraja',
                'Rambla',
                'Rammetto+One',
                'Ranchers',
                'Rancho',
                'Ranga',
                'Rationale',
                'Ravi+Prakash',
                'Redressed',
                'Reenie+Beanie',
                'Revalia',
                'Rhodium+Libre',
                'Ribeye',
                'Ribeye+Marrow',
                'Righteous',
                'Risque',
                'Roboto',
                'Roboto+Condensed',
                'Roboto+Mono',
                'Roboto+Slab',
                'Rochester',
                'Rock+Salt',
                'Rokkitt',
                'Romanesco',
                'Ropa+Sans',
                'Rosario',
                'Rosarivo',
                'Rouge+Script',
                'Rozha+One',
                'Rubik',
                'Rubik+Mono+One',
                'Rubik+One',
                'Ruda',
                'Rufina',
                'Ruge+Boogie',
                'Ruluko',
                'Rum+Raisin',
                'Ruslan+Display',
                'Russo+One',
                'Ruthie',
                'Rye',
                'Sacramento',
                'Sahitya',
                'Sail',
                'Salsa',
                'Sanchez',
                'Sancreek',
                'Sansita+One',
                'Sarala',
                'Sarina',
                'Sarpanch',
                'Satisfy',
                'Scada',
                'Scheherazade',
                'Schoolbell',
                'Seaweed+Script',
                'Sevillana',
                'Seymour+One',
                'Shadows+Into+Light',
                'Shadows+Into+Light+Two',
                'Shanti',
                'Share',
                'Share+Tech',
                'Share+Tech+Mono',
                'Shojumaru',
                'Short+Stack',
                'Siemreap',
                'Sigmar+One',
                'Signika',
                'Signika+Negative',
                'Simonetta',
                'Sintony',
                'Sirin+Stencil',
                'Six+Caps',
                'Skranji',
                'Slabo+13px',
                'Slabo+27px',
                'Slackey',
                'Smokum',
                'Smythe',
                'Sniglet',
                'Snippet',
                'Snowburst+One',
                'Sofadi+One',
                'Sofia',
                'Sonsie+One',
                'Sorts+Mill+Goudy',
                'Source+Code+Pro',
                'Source+Sans+Pro',
                'Source+Serif+Pro',
                'Special+Elite',
                'Spicy+Rice',
                'Spinnaker',
                'Spirax',
                'Squada+One',
                'Sree+Krushnadevaraya',
                'Stalemate',
                'Stalinist+One',
                'Stardos+Stencil',
                'Stint+Ultra+Condensed',
                'Stint+Ultra+Expanded',
                'Stoke',
                'Strait',
                'Sue+Ellen+Francisco',
                'Sumana',
                'Sunshiney',
                'Supermercado+One',
                'Sura',
                'Suranna',
                'Suravaram',
                'Suwannaphum',
                'Swanky+and+Moo+Moo',
                'Syncopate',
                'Tangerine',
                'Taprom',
                'Tauri',
                'Teko',
                'Telex',
                'Tenali+Ramakrishna',
                'Tenor+Sans',
                'Text+Me+One',
                'The+Girl+Next+Door',
                'Tienne',
                'Tillana',
                'Timmana',
                'Tinos',
                'Titan+One',
                'Titillium+Web',
                'Trade+Winds',
                'Trocchi',
                'Trochut',
                'Trykker',
                'Tulpen+One',
                'Ubuntu',
                'Ubuntu+Condensed',
                'Ubuntu+Mono',
                'Ultra',
                'Uncial+Antiqua',
                'Underdog',
                'Unica+One',
                'UnifrakturCook',
                'UnifrakturMaguntia',
                'Unkempt',
                'Unlock',
                'Unna',
                'Vampiro+One',
                'Varela',
                'Varela+Round',
                'Vast+Shadow',
                'Vesper+Libre',
                'Vibur',
                'Vidaloka',
                'Viga',
                'Voces',
                'Volkhov',
                'Vollkorn',
                'Voltaire',
                'VT323',
                'Waiting+for+the+Sunrise',
                'Wallpoet',
                'Walter+Turncoat',
                'Warnes',
                'Wellfleet',
                'Wendy+One',
                'Wire+One',
                'Work+Sans',
                'Yanone+Kaffeesatz',
                'Yantramanav',
                'Yellowtail',
                'Yeseva+One',
                'Yesteryear',
                'Zeyada'
            );
    $font_select='';
    /*foreach($google_fonts_array as $key=>$value){
        $font_select.='<option value="'.$key.'">'.$value.'</option>';
    }
    */
    foreach($google_fonts_array as $value){
        $font_select.='<option value="'.$value.'">'.str_replace('+',' ',$value).'</option>';
    }
    
    
    $general_font_select='';
    $general_font= esc_html ( get_option('wp_estate_general_font','') );
    if($general_font!='x'){
        $general_font_select='<option value="'.$general_font.'">'.$general_font.'</option>';
    }
    /*
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Main Font','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Replace theme font with another Google Font from the list below.','wpestate').'</div>    
        <select id="general_font" name="general_font">
            '.$general_font_select.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> 
    </div>';
    

    
    $headings_font_subset   =   esc_html ( get_option('wp_estate_headings_font_subset','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Font subset','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Specify font subset(s) if you don\'t use latin language.','wpestate').'</div>    
        <input type="text" id="headings_font_subset" name="headings_font_subset" value="'.$headings_font_subset.'">  
    </div>';
    */
    
    
    
    $font_weight_array = array( 
            "normal"=>"Normal",
            "bold"=>"Bold",
            "bolder"=>"Bolder",
            "lighter"=>"Lighter",
            "100"=>"100",
            "200"=>"200",
            "300"=>"300",
            "400"=>"400",
            "500"=>"500",
            "600"=>"600",
            "700"=>"700",
            "800"=>"800",
            "900"=>"900",
            "initial"=> "Initial"    
    );

    $font_weight='';
    foreach($font_weight_array as $key=>$value){
        $font_weight.='<option value="'.$key.'">'.$value.'</option>';
    }
   
    
    
    // H1 Typography
    $h1_fontfamily='';
    $h1_fontfamily= esc_html ( get_option('wp_estate_h1_fontfamily','') );
    if($h1_fontfamily!='x'){
        $h1_fontfamily='<option value="'.$h1_fontfamily.'">'.$h1_fontfamily.'</option>';
    }
    $h1_fontsize =   esc_html( get_option('wp_estate_h1_fontsize') );
    $h1_fontsubset =   esc_html( get_option('wp_estate_h1_fontsubset') );
    $h1_lineheight =   esc_html( get_option('wp_estate_h1_lineheight') ); 
    $h1_fontweight='';
    $h1_fontweight= esc_html ( get_option('wp_estate_h1_fontweight','') );
    if($h1_fontweight!='x'){
        $h1_fontweight='<option value="'.$h1_fontweight.'">'.$h1_fontweight.'</option>';
    }

//var_dump($h1_fontweight);
    // H2 Typography
    $h2_fontfamily='';
    $h2_fontfamily= esc_html ( get_option('wp_estate_h2_fontfamily','') );
    if($h2_fontfamily!='x'){
        $h2_fontfamily='<option value="'.$h2_fontfamily.'">'.$h2_fontfamily.'</option>';
    }
    $h2_fontsize =   esc_html( get_option('wp_estate_h2_fontsize') );
    $h2_fontsubset =   esc_html( get_option('wp_estate_h2_fontsubset') );
    $h2_lineheight =   esc_html( get_option('wp_estate_h2_lineheight')) ;
    $h2_fontweight='';
    $h2_fontweight= esc_html ( get_option('wp_estate_h2_fontweight','') );
    if($h2_fontweight!='x'){
        $h2_fontweight='<option value="'.$h2_fontweight.'">'.$h2_fontweight.'</option>';
    }

    // H3 Typography
    $h3_fontfamily='';
    $h3_fontfamily= esc_html ( get_option('wp_estate_h3_fontfamily','') );
    if($h3_fontfamily!='x'){
        $h3_fontfamily='<option value="'.$h3_fontfamily.'">'.$h3_fontfamily.'</option>';
    }
    $h3_fontsize =   esc_html( get_option('wp_estate_h3_fontsize') );
    $h3_fontsubset =   esc_html( get_option('wp_estate_h3_fontsubset') );
    $h3_lineheight =   esc_html( get_option('wp_estate_h3_lineheight') );
    $h3_fontweight='';
    $h3_fontweight= esc_html ( get_option('wp_estate_h3_fontweight','') );
    if($h3_fontweight!='x'){
        $h3_fontweight='<option value="'.$h3_fontweight.'">'.$h3_fontweight.'</option>';
    }

    // H4 Typography
    $h4_fontfamily='';
    $h4_fontfamily= esc_html ( get_option('wp_estate_h4_fontfamily','') );
    if($h4_fontfamily!='x'){
        $h4_fontfamily='<option value="'.$h4_fontfamily.'">'.$h4_fontfamily.'</option>';
    }
    $h4_fontsize =   esc_html( get_option('wp_estate_h4_fontsize') );
    $h4_fontsubset =   esc_html( get_option('wp_estate_h4_fontsubset') );
    $h4_lineheight =   esc_html( get_option('wp_estate_h4_lineheight') );
    $h4_fontweight='';
    $h4_fontweight= esc_html ( get_option('wp_estate_h4_fontweight','') );
    if($h4_fontweight!='x'){
        $h4_fontweight='<option value="'.$h4_fontweight.'">'.$h4_fontweight.'</option>';
    }


    // H5 Typography
    $h5_fontfamily='';
    $h5_fontfamily= esc_html ( get_option('wp_estate_h5_fontfamily','') );
    if($h5_fontfamily!='x'){
        $h5_fontfamily='<option value="'.$h5_fontfamily.'">'.$h5_fontfamily.'</option>';
    }
    $h5_fontsize =   esc_html( get_option('wp_estate_h5_fontsize') );
    $h5_fontsubset =   esc_html( get_option('wp_estate_h5_fontsubset') );
    $h5_lineheight =   esc_html( get_option('wp_estate_h5_lineheight') );
    $h5_fontweight='';
    $h5_fontweight= esc_html ( get_option('wp_estate_h5_fontweight','') );
    if($h5_fontweight!='x'){
        $h5_fontweight='<option value="'.$h5_fontweight.'">'.$h5_fontweight.'</option>';
    }

    // H6 Typography
    $h6_fontfamily='';
    $h6_fontfamily= esc_html ( get_option('wp_estate_h6_fontfamily','') );
    if($h6_fontfamily!='x'){
        $h6_fontfamily='<option value="'.$h6_fontfamily.'">'.$h6_fontfamily.'</option>';
    }
    $h6_fontsize =   esc_html( get_option('wp_estate_h6_fontsize') );
    $h6_fontsubset =   esc_html( get_option('wp_estate_h6_fontsubset') );
    $h6_lineheight =   esc_html( get_option('wp_estate_h6_lineheight') );
    $h6_fontweight='';
    $h6_fontweight= esc_html ( get_option('wp_estate_h6_fontweight','') );
    if($h6_fontweight!='x'){
        $h6_fontweight='<option value="'.$h6_fontweight.'">'.$h6_fontweight.'</option>';
    }

    // H6 Typography
    $p_fontfamily='';
    $p_fontfamily= esc_html ( get_option('wp_estate_p_fontfamily','') );
    if($p_fontfamily!='x'){
        $p_fontfamily='<option value="'.$p_fontfamily.'">'.$p_fontfamily.'</option>';
    }
    $p_fontsize =   esc_html( get_option('wp_estate_p_fontsize') );
    $p_fontsubset =   esc_html( get_option('wp_estate_p_fontsubset') );
    $p_lineheight =   esc_html( get_option('wp_estate_p_lineheight') );
    $p_fontweight='';
    $p_fontweight= esc_html ( get_option('wp_estate_p_fontweight','') );
    if($p_fontweight!='x'){
        $p_fontweight='<option value="'.$p_fontweight.'">'.$p_fontweight.'</option>';
    }

    // Menu Typography
    $menu_fontfamily='';
    $menu_fontfamily= esc_html ( get_option('wp_estate_menu_fontfamily','') );
    if($menu_fontfamily!='x'){
        $menu_fontfamily='<option value="'.$menu_fontfamily.'">'.$menu_fontfamily.'</option>';
    }
    $menu_fontsize =   esc_html( get_option('wp_estate_menu_fontsize') );
    $menu_fontsubset =   esc_html( get_option('wp_estate_menu_fontsubset') );
    $menu_lineheight =   esc_html( get_option('wp_estate_menu_lineheight') );
    $menu_fontweight='';
    $menu_fontweight= esc_html ( get_option('wp_estate_menu_fontweight','') );
    if($menu_fontweight!='x'){
        $menu_fontweight='<option value="'.$menu_fontweight.'">'.$menu_fontweight.'</option>';
    }

     // sidebar Typography
    $sidebar_fontfamily='';
    $sidebar_fontfamily= esc_html ( get_option('wp_estate_sidebar_fontfamily','') );
    if($sidebar_fontfamily!='x'){
        $sidebar_fontfamily='<option value="'.$sidebar_fontfamily.'">'.$sidebar_fontfamily.'</option>';
    }
    $sidebar_fontsize =   esc_html( get_option('wp_estate_sidebar_fontsize') );
    $sidebar_fontsubset =   esc_html( get_option('wp_estate_sidebar_fontsubset') );
    $sidebar_lineheight =   esc_html( get_option('wp_estate_sidebar_lineheight') );
    $sidebar_fontweight='';
    $sidebar_fontweight= esc_html ( get_option('wp_estate_sidebar_fontweight','') );
    if($sidebar_fontweight!='x'){
        $sidebar_fontweight='<option value="'.$sidebar_fontweight.'">'.$sidebar_fontweight.'</option>';
    }


     // footer Typography
    $footer_fontfamily='';
    $footer_fontfamily= esc_html ( get_option('wp_estate_footer_fontfamily','') );
    if($footer_fontfamily!='x'){
        $footer_fontfamily='<option value="'.$footer_fontfamily.'">'.$footer_fontfamily.'</option>';
    }
    $footer_fontsize =   esc_html( get_option('wp_estate_footer_fontsize') );
    $footer_fontsubset =   esc_html( get_option('wp_estate_footer_fontsubset') );
    $footer_lineheight =   esc_html( get_option('wp_estate_footer_lineheight') );
    $footer_fontweight='';
    $footer_fontweight= esc_html ( get_option('wp_estate_footer_fontweight','') );
    if($footer_fontweight!='x'){
        $footer_fontweight='<option value="'.$footer_fontweight.'">'.$footer_fontweight.'</option>';
    }



    print'<div class="estate_option_row">
    <table>
    <th style="text-align:left;"><div class="label_option_row">'.esc_html__('H1 Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="h1_fontfamily" name="h1_fontfamily">
            '.$h1_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
        <td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="h1_fontsubset" name="h1_fontsubset" value="'.$h1_fontsubset.'">
        </td>
        <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="h1_fontsize" name="h1_fontsize" value="'.$h1_fontsize.'" placeholder="in px"></td>
    </tr>
    <tr>
        <td>
        <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
             <input type="number" id="h1_lineheight" name="h1_lineheight" value="'.$h1_lineheight.'" placeholder="in px">
        </td>
        <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="h1_fontweight" name="h1_fontweight">
            '.$h1_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td>
    </tr>
</table>
</div>
<div class="estate_option_row">
<table>
    <th style="text-align:left;"><div class="label_option_row">'.esc_html__('H2 Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="h2_fontfamily" name="h2_fontfamily">
            '.$h2_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
        <td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="h2_fontsubset" name="h2_fontsubset" value="'.$h2_fontsubset.'">
        </td>
    <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="h2_fontsize" name="h2_fontsize" value="'.$h2_fontsize.'" placeholder="in px"></td>
    </tr>
    <tr><td>
    <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
         <input type="number" id="h2_lineheight" name="h2_lineheight" value="'.$h2_lineheight.'" placeholder="in px"></td>
         <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="h2_fontweight" name="h2_fontweight">
            '.$h2_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td>
    </tr>
</table>
</div>
<div class="estate_option_row">
<table>
    <th style="text-align:left;"><div class="label_option_row">'.esc_html__('H3 Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="h3_fontfamily" name="h3_fontfamily">
            '.$h3_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
<td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="h3_fontsubset" name="h3_fontsubset" value="'.$h3_fontsubset.'">
        </td>
    <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="h3_fontsize" name="h3_fontsize" value="'.$h3_fontsize.'" placeholder="in px"></td>
    </tr><td>
    <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
         <input type="number" id="h3_lineheight" name="h3_lineheight" value="'.$h3_lineheight.'" placeholder="in px"></td>
         </td>
        <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="h3_fontweight" name="h3_fontweight">
            '.$h3_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td>
    <tr>
</table>
</div>
<div class="estate_option_row">
<table>
   <th style="text-align:left;"><div class="label_option_row">'.esc_html__('H4 Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="h4_fontfamily" name="h4_fontfamily">
            '.$h4_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
        <td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="h4_fontsubset" name="h4_fontsubset" value="'.$h4_fontsubset.'">
        </td>
    <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="h4_fontsize" name="h4_fontsize" value="'.$h4_fontsize.'" placeholder="in px"></td>
         </tr><tr><td>
    <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
         <input type="number" id="h4_lineheight" name="h4_lineheight" value="'.$h4_lineheight.'" placeholder="in px"></td>
        <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="h4_fontweight" name="h4_fontweight">
            '.$h4_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td> 
    </tr>
</table>
</div>
<div class="estate_option_row">
<table>
    <th style="text-align:left;"><div class="label_option_row">'.esc_html__('H5 Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="h5_fontfamily" name="h5_fontfamily">
            '.$h5_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
        <td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="h5_fontsubset" name="h5_fontsubset" value="'.$h5_fontsubset.'">
        </td>
    <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="h5_fontsize" name="h5_fontsize" value="'.$h5_fontsize.'" placeholder="in px"></td>
         </tr><tr><td>
    <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
         <input type="number" id="h5_lineheight" name="h5_lineheight" value="'.$h5_lineheight.'" placeholder="in px"></td>
        <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="h5_fontweight" name="h5_fontweight">
            '.$h5_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td>
    </tr>
</table>
</div>
<div class="estate_option_row">
<table>
    <th style="text-align:left;"><div class="label_option_row">'.esc_html__('H6 Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="h6_fontfamily" name="h6_fontfamily">
            '.$h6_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
        <td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="h6_fontsubset" name="h6_fontsubset" value="'.$h6_fontsubset.'">
        </td>
    <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="h6_fontsize" name="h6_fontsize" value="'.$h6_fontsize.'" placeholder="in px"></td>
         </tr><tr><td>
    <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
         <input type="number" id="h6_lineheight" name="h6_lineheight" value="'.$h6_lineheight.'" placeholder="in px"></td>
         <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="h6_fontweight" name="h6_fontweight">
            '.$h6_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td>
    </tr>
</table>
</div>
<div class="estate_option_row">
<table>
    <th style="text-align:left;"><div class="label_option_row">'.esc_html__('Paragraph Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="p_fontfamily" name="p_fontfamily">
            '.$p_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
        <td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="p_fontsubset" name="p_fontsubset" value="'.$p_fontsubset.'">
        </td>
        <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="p_fontsize" name="p_fontsize" value="'.$p_fontsize.'" placeholder="in px"></td>
    </tr>
    <tr>
        <td>
        <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
             <input type="number" id="p_lineheight" name="p_lineheight" value="'.$p_lineheight.'" placeholder="in px">
        </td>
        <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="p_fontweight" name="p_fontweight">
            '.$p_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td>
    </tr>
</table>
</div>
<div class="estate_option_row">
<table>
    <th style="text-align:left;"><div class="label_option_row">'.esc_html__('Menu Font','wpestate').'</div><th>
    <tr><td><div class="option_row_explain">'.esc_html__('Font Family:','wpestate').'</div>    
        <select id="menu_fontfamily" name="menu_fontfamily">
            '.$menu_fontfamily.'
            <option value="">- original font -</option>
            '.$font_select.'                   
        </select> </td>
        <td>
        <div class="option_row_explain">'.esc_html__('Font Subset:','wpestate').'</div>    
             <input type="text" id="menu_fontsubset" name="menu_fontsubset" value="'.$menu_fontsubset.'">
        </td>
        <td>
    <div class="option_row_explain">'.esc_html__('Font Size:','wpestate').'</div>    
         <input type="number" id="menu_fontsize" name="menu_fontsize" value="'.$menu_fontsize.'" placeholder="in px"></td>
    </tr>
    <tr>
        <td>
        <div class="option_row_explain">'.esc_html__('Line Height:','wpestate').'</div>    
             <input type="number" id="menu_lineheight" name="menu_lineheight" value="'.$menu_lineheight.'" placeholder="in px">
        </td>
        <td><div class="option_row_explain">'.esc_html__('Font Weight:','wpestate').'</div>    
        <select id="menu_fontweight" name="menu_fontweight">
            '.$menu_fontweight.'
            <option value="">Original font weight</option>
            '.$font_weight.'                   
        </select> </td>
    </tr>
</table>
</div>';
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
     print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  
}
endif;











if( !function_exists('wpestate_new_membership_settings') ):    
function wpestate_new_membership_settings(){
    $free_mem_list                  =   esc_html( get_option('wp_estate_free_mem_list','') );
    $cache_array                    =   array('yes','no');  
   
    $paypal_array                   =   array('no','per listing','membership');
    $paid_submission_symbol         =   wpestate_dropdowns_theme_admin($paypal_array,'paid_submission');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Enable Paid Submission ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('No = submission is free. Paid listing = submission requires user to pay a fee for each listing. Membership = submission is based on user membership package.','wpestate').'</div>    
        <select id="paid_submission" name="paid_submission">
            '.$paid_submission_symbol.'
        </select>
    </div>';
    
    $paypal_array                   =   array( 'sandbox','live' );
    $paypal_api_select              =   wpestate_dropdowns_theme_admin($paypal_array,'paypal_api');  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Paypal & Stripe Api ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Sandbox = test API. LIVE = real payments API. Update PayPal and Stripe settings according to API type selection.','wpestate').'</div>    
        <select id="paypal_api" name="paypal_api">
            '.$paypal_api_select.'
        </select>
    </div>'; 
    
    $admin_submission_symbol        =   wpestate_dropdowns_theme_admin($cache_array,'admin_submission');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Submited Listings should be approved by admin?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If yes, admin publishes each property submitted in front end manually.','wpestate').'</div>    
        <select id="admin_submission" name="admin_submission">
            '.$admin_submission_symbol.'
        </select>
    </div>';
  
    $user_agent_symbol              =   wpestate_dropdowns_theme_admin($cache_array,'user_agent');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Front end registred users should be saved as agents?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If yes, new registered users will have an agent profile synced with user profile automatically. Applies only for front end registation.','wpestate').'</div>    
        <select id="user_agent" name="user_agent">
            '.$user_agent_symbol.'
        </select>
    </div>';
    



        
       
    $submission_curency_array       =   array(get_option('wp_estate_submission_curency_custom',''),'USD','EUR','AUD','BRL','CAD','CZK','DKK','HKD','HUF','ILS','INR','JPY','MYR','MXN','NOK','NZD','PHP','PLN','GBP','SGD','SEK','CHF','TWD','THB','TRY');
    $submission_curency_symbol      =   wpestate_dropdowns_theme_admin($submission_curency_array,'submission_curency');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Currency For Paid Submission','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('The currency in which payments are processed.','wpestate').'</div>    
        <select id="submission_curency" name="submission_curency">
            '.$submission_curency_symbol.'
        </select> 
    </div>'; 
       
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Custom Currency Symbol - *select it from the list above after you add it.','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Add your own currency for Wire payments. ','wpestate').'</div>    
        <input type="text" id="submission_curency_custom" name="submission_curency_custom" class="regular-text"  value="'.get_option('wp_estate_submission_curency_custom','').'"/>
    </div>'; 
      

      
   
      
   
      
    $enable_direct_pay_symbol       =   wpestate_dropdowns_theme_admin($cache_array,'enable_direct_pay');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Enable Direct Payment / Wire Payment?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable the wire payment option.','wpestate').'</div>    
        <select id="enable_direct_pay" name="enable_direct_pay">
            '.$enable_direct_pay_symbol.'
        </select>
    </div>'; 
    
    
    $args=array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
    );
    $direct_payment_details         =   wp_kses( get_option('wp_estate_direct_payment_details','') ,$args);
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Wire instructions for direct payment','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If wire payment is enabled, type the instructions below.','wpestate').'</div>    
        <textarea id="direct_payment_details" rows="5" style="width:700px;" name="direct_payment_details"   class="regular-text" >'.$direct_payment_details.'</textarea> 
    </div>';   
    
    $price_submission               =   floatval( get_option('wp_estate_price_submission','') );   
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Price Per Submission (for "per listing" mode)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Use .00 format for decimals (ex: 5.50). Do not set price as 0!','wpestate').'</div>    
       <input  type="text" id="price_submission" name="price_submission"  value="'.$price_submission.'"/> 
    </div>'; 
      
    
    $price_featured_submission      =   floatval( get_option('wp_estate_price_featured_submission','') ); 
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Price to make the listing featured (for "per listing" mode)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Use .00 format for decimals (ex: 1.50). Do not set price as 0!','wpestate').'</div>    
       <input  type="text" id="price_featured_submission" name="price_featured_submission"  value="'.$price_featured_submission.'"/>
    </div>'; 
       
    
    $free_mem_list_unl='';
    if ( intval( get_option('wp_estate_free_mem_list_unl', '' ) ) == 1){
        $free_mem_list_unl=' checked="checked" ';  
    }
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Free Membership - no of listings (for "membership" mode)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If you change this value, the new value applies for new registered users. Old value applies for older registered accounts.','wpestate').'</div>    
        <input  type="text" id="free_mem_list" name="free_mem_list" style="margin-right:20px;"  value="'.$free_mem_list.'"/> 
        <input type="hidden" name="free_mem_list_unl" value="">
        <input type="checkbox"  id="free_mem_list_unl" name="free_mem_list_unl" value="1" '.$free_mem_list_unl.' />
        <label for="free_mem_list_unl">'.esc_html__('Unlimited listings ?','wpestate').'</label>
    </div>'; 
     
    $free_feat_list                 =   esc_html( get_option('wp_estate_free_feat_list','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Free Membership - no of featured listings (for "membership" mode)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If you change this value, the new value applies for new registered users. Old value applies for older registered accounts.','wpestate').'</div>    
         <input  type="text" id="free_feat_list" name="free_feat_list" style="margin-right:20px;"    value="'.$free_feat_list.'"/>
    </div>';
        
    $free_feat_list_expiration= intval ( get_option('wp_estate_free_feat_list_expiration','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Free Membership Listings - no of days until a free listing will expire. *Starts from the moment the property is published on the website. (for "membership" mode) ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Option applies for each free published listing.','wpestate').'</div>    
        <input  type="text" id="free_feat_list_expiration" name="free_feat_list_expiration" style="margin-right:20px;"    value="'.$free_feat_list_expiration.'"/>
    </div>';
  
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  
     
}    
endif;



if( !function_exists('wpestate_new_stripe_settings') ):  
 function  wpestate_new_stripe_settings(){
    $cache_array                    =   array('yes','no');  
      
    $enable_stripe_symbol           =   wpestate_dropdowns_theme_admin($cache_array,'enable_stripe');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Enable Stripe?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('You can enable or disable Stripe payment buttons.','wpestate').'</div>    
        <select id="enable_stripe" name="enable_stripe">
            '.$enable_stripe_symbol.'
        </select>
    </div>';
    
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Stripe Secret Key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Info is taken from your account at https://dashboard.stripe.com/login','wpestate').'</div>    
       <input  type="text" id="stripe_secret_key" name="stripe_secret_key"  class="regular-text" value="'.$stripe_secret_key.'"/> 
    </div>';
        
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Stripe Publishable Key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Info is taken from your account at https://dashboard.stripe.com/login','wpestate').'</div>    
       <input  type="text" id="stripe_publishable_key" name="stripe_publishable_key" class="regular-text" value="'.$stripe_publishable_key.'"/>
    </div>';
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  
}
endif;
 
 
 
if( !function_exists('wpestate_new_paypal_settings') ):   
function     wpestate_new_paypal_settings(){
      $cache_array                    =   array('yes','no');  
    $enable_paypal_symbol           =   wpestate_dropdowns_theme_admin($cache_array,'enable_paypal');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Enable Paypal?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('You can enable or disable PayPal buttons.','wpestate').'</div>    
        <select id="enable_paypal" name="enable_paypal">
            '.$enable_paypal_symbol.'
        </select>
    </div>';
    
    $paypal_client_id               =   esc_html( get_option('wp_estate_paypal_client_id','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Paypal Client id','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('PayPal business account is required. Info is taken from https://developer.paypal.com/. See help.','wpestate').'</div>    
        <input  type="text" id="paypal_client_id" name="paypal_client_id" class="regular-text"  value="'.$paypal_client_id.'"/>
    </div>'; 
     
    $paypal_client_secret           =   esc_html( get_option('wp_estate_paypal_client_secret','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Paypal Client Secret Key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Info is taken from https://developer.paypal.com/ See help.','wpestate').'</div>    
        <input  type="text" id="paypal_client_secret" name="paypal_client_secret"  class="regular-text" value="'.$paypal_client_secret.'"/> 
    </div>'; 
    
   
  
    $paypal_rec_email               =   esc_html( get_option('wp_estate_paypal_rec_email','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Paypal receiving email','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Info is taken from https://www.paypal.com/ or http://sandbox.paypal.com/ See help.','wpestate').'</div>    
       <input  type="text" id="paypal_rec_email" name="paypal_rec_email"  class="regular-text" value="'.$paypal_rec_email.'"/>
    </div>';
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  
}
endif; 
   
   
   
   
   
   
   
if( !function_exists('wpestate_new_pin_management') ):   
function wpestate_new_pin_management(){  
    $pins       =   array();
    $taxonomy   =   'property_action_category';
    $tax_terms  =   get_terms($taxonomy,'hide_empty=0');

    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat,'hide_empty=0');

    // add only actions
    if(!empty($tax_terms)){
        foreach ($tax_terms as $tax_term) {
            if(isset($tax_term->slug)){
                $name                    =  sanitize_key ( wpestate_limit64('wp_estate_'.$tax_term->slug) );
                $limit54                 =  sanitize_key ( wpestate_limit54($tax_term->slug) );
                $pins[$limit54]          =  esc_html( get_option($name) );  
            }
        } 
    }

    // add only categories
    if(!empty($categories)){
        foreach ($categories as $categ) {
            if(isset($categ->slug)){
                $name                           =   sanitize_key( wpestate_limit64('wp_estate_'.$categ->slug));
                $limit54                        =   sanitize_key(wpestate_limit54($categ->slug));
                $pins[$limit54]                 =   esc_html( get_option($name) );
            }
        }
    }
    
    // add combinations
    if( !empty($categories) && !empty($tax_terms) ){
        foreach ($tax_terms as $tax_term) {
            foreach ($categories as $categ) {
                if( isset($categ->slug) && isset($tax_term->slug) ){
                    $limit54            =   sanitize_key ( wpestate_limit27($categ->slug).wpestate_limit27($tax_term->slug) );
                    $name               =   'wp_estate_'.$limit54;
                    $pins[$limit54]     =   esc_html( get_option($name) ) ;   
                }
            }
        }
    }

  
    $name='wp_estate_idxpin';
    $pins['idxpin']=esc_html( get_option($name) );  

    $name='wp_estate_userpin';
    $pins['userpin']=esc_html( get_option($name) );  
   
    $taxonomy = 'property_action_category';
    $tax_terms = get_terms($taxonomy,'hide_empty=0');

    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat,'hide_empty=0');

    print'<p class="admin-exp">'.esc_html__('Add new Google Maps pins for single actions / single categories. For speed reason, you MUST add pins if you change categories and actions names.','wpestate').'</p>';
    print '<p class="admin-exp" >'.esc_html__('If you add images directly into the input fields (without Upload button) please use the full image path. For ex: http://www.wpestate..... . If you use the "upload"  button use also "Insert into Post" button from the pop up window.','wpestate');
    print '<p class="admin-exp" >'.esc_html__('Pins retina version must be uploaded at the same time (same folder) as the original pin and the name of the retina file should be with_2x at the end.','wpestate').' <a href="http://helpv4.wpestatetheme.org/article/retina-pin-images/" target="_blank">'.esc_html__('For help go here!','wpestate').'</a>';
    $cache_array=array('no','yes');
    $use_price_pins                 =   wpestate_dropdowns_theme_admin($cache_array,'use_price_pins');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use price Pins ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Use price Pins ?','wpestate').'</div>    
        <select id="use_price_pins" name="use_price_pins">
            '.$use_price_pins.'
        </select>
    </div>';
    
    if(!empty($tax_terms)){
        foreach ($tax_terms as $tax_term) { 
                if(isset($tax_term->slug)){
                    $limit54   =  $post_name  =   sanitize_key(wpestate_limit54($tax_term->slug));
                    print'<div class="estate_option_row">
                    <div class="label_option_row">'.esc_html__('For action ','wpestate').'<strong>'.$tax_term->name.' </strong></div>
                    <div class="option_row_explain">'.esc_html__('Image size must be 44px x 48px. ','wpestate').'</div>    
                    <div class="option_row_explain">'.esc_html__('To customize price pins use this css class (the down arrow is made with :before)','wpestate').': .wpestate_marker.'.sanitize_title($tax_term->name).'</div>   
                        <input type="text"    class="pin-upload-form" size="36" name="'.$post_name.'" value="'.$pins[$limit54].'" />
                        <input type="button"  class="upload_button button pin-upload" value="'.esc_html__('Upload Pin','wpestate').'" />
                    </div>';
                }
        }
    }
     
    if(!empty($categories)){
        foreach ($categories as $categ) {  
            if( isset($categ->slug) ){
                $limit54   =   $post_name  =   sanitize_key(wpestate_limit54($categ->slug));
                print'<div class="estate_option_row">
                <div class="label_option_row">'.esc_html__('For category: ','wpestate').'<strong>'.$categ->name.' </strong>  </div>
                <div class="option_row_explain">'.esc_html__('To customize price pins use this css class (the down arrow is made with :before)','wpestate').': .wpestate_marker.'.sanitize_title($categ->name).'</div>    
                    <input type="text"    class="pin-upload-form" size="36" name="'.$post_name.'" value="'.$pins[$limit54].'" />
                    <input type="button"  class="upload_button button pin-upload" value="'.esc_html__('Upload Pin','wpestate').'"  />
                </div>';
             }

        }
    }
    
    
    print '<p class="admin-exp">'.esc_html__('Add new Google Maps pins for actions & categories combined (example: \'apartments in sales\')','wpestate').'</p>';  
    if( !empty($categ) && !empty($tax_term) ){
        foreach ($tax_terms as $tax_term) {

            foreach ($categories as $categ) {
                if( isset($categ->slug) && isset($tax_term->slug) ){
                    $limit54=sanitize_key(wpestate_limit27($categ->slug)).sanitize_key( wpestate_limit27($tax_term->slug) );

                    print'<div class="estate_option_row">
                    <div class="label_option_row">'.esc_html__('For action','wpestate').' <strong>'.$tax_term->name.'</strong>, '.esc_html__('category','wpestate').': <strong>'.$categ->name.'</strong>   </div>
                        <div class="option_row_explain">'.esc_html__('To customize price pins use this css class (the down arrow is made with :before)','wpestate').': .wpestate_marker.'.sanitize_title($tax_term->name).'.'.sanitize_title($categ->name).'</div>    

                    <div class="option_row_explain">'.esc_html__('Image size must be 44px x 48px.','wpestate').'  </div>    
                        <input id="'.$limit54.'" type="text" size="36" name="'. $limit54.'" value="'.$pins[$limit54].'" />
                        <input type="button"  class="upload_button button pin-upload" value="'.esc_html__('Upload Pin','wpestate').'" />
                    </div>';
                }

            }
        }
    }


    print'<div class="estate_option_row">
            <div class="label_option_row">'.esc_html__('For IDX (if plugin is enabled) ','wpestate').'</div>
            <div class="option_row_explain">'.esc_html__('For IDX (if plugin is enabled) ','wpestate').'</div>    
                <input id="idxpin" type="text" size="36" name="idxpin" value="'.$pins['idxpin'].'" />
                <input type="button"  class="upload_button button pin-upload" value="'.esc_html__('Upload Pin','wpestate').'" />
            </div>';
    
    
     print'<div class="estate_option_row">
            <div class="label_option_row">'.esc_html__('Userpin in geolocation','wpestate').'</div>
            <div class="option_row_explain">'.esc_html__('Userpin in geolocation','wpestate').'</div>    
                <input id="userpin" type="text" size="36" name="userpin" value="'.$pins['userpin'].'" />
                <input type="button"  class="upload_button button pin-upload" value="'.esc_html__('Upload Pin','wpestate').'" />
            </div>';
     
     print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>'; 
}
endif;



if( !function_exists('wpestate_new_advanced_search_form') ):   
function    wpestate_new_advanced_search_form(){
       
    $value_array=array('no','yes');
    $custom_advanced_search_select = wpestate_dropdowns_theme_admin($value_array,'custom_advanced_search');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Custom Fields For Advanced Search ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If yes, you can set your own custom fields in the  spots available. See help for correct setup.','wpestate').'</div>    
        <select id="custom_advanced_search" name="custom_advanced_search">
            '.$custom_advanced_search_select.'
        </select> 
    </div>';
  


    $use_float_search_form = wpestate_dropdowns_theme_admin($value_array,'use_float_search_form');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Float Search Form ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('The search form is "floating" over the media header and you are setting up the position ','wpestate').'</div>    
        <select id="use_float_search_form" name="use_float_search_form">
            '.$use_float_search_form.'
        </select> 
    </div>';
    

    
    
    $float_form_top            =    ( esc_html( get_option('wp_estate_float_form_top') ) );      
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Distance betwen search form and the top margin of the browser: Ex 200px or 20%','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Distance betwen search form and the top margin of the browser: Ex 200px or 20%.','wpestate').'</div>    
        <input  type="text" id="float_form_top"  name="float_form_top"   value="'.$float_form_top.'" size="40"/>
    </div>';
    
    $float_form_top_tax            =    ( esc_html( get_option('wp_estate_float_form_top_tax') ) );      
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Distance betwen search form and the top margin of the browser in px Ex 200px or 20%  - for taxonomy, category and archives pages','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Distance betwen search form and the top margin of the browser in px Ex 200px or 20% - for taxonomy, category and archives pages.','wpestate').'</div>    
        <input  type="text" id="float_form_top_tax"  name="float_form_top_tax"   value="'.$float_form_top_tax.'" size="40"/>
    </div>';
    
    
    $adv_search_fields_no             =    ( floatval( get_option('wp_estate_adv_search_fields_no') ) );      
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('No of Search fields','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('No of Search fields.','wpestate').'</div>    
        <input  type="text" id="adv_search_fields_no"  name="adv_search_fields_no"   value="'.$adv_search_fields_no.'" size="40"/>
    </div>';
    
    
   
    
    
    $adv_search_fields_no_per_row             =    ( floatval( get_option('wp_estate_search_fields_no_per_row') ) );      
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('No of Search fields per row','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('No of Search fields per row (Possible values: 1,2,3,4).Will not work for half map form!','wpestate').'</div>    
        <input  type="text" id="search_fields_no_per_row"  name="search_fields_no_per_row"   value="'.$adv_search_fields_no_per_row.'" size="40"/>
    </div>';
    
    $tax_array      =array( 'none'                      =>esc_html__('none','wpestate'),
                            'property_category'         =>esc_html__('Categories','wpestate'),
                            'property_action_category'  =>esc_html__('Action Categories','wpestate'),
                            'property_city'             =>esc_html__('City','wpestate'),
                            'property_area'             =>esc_html__('Area','wpestate'),
                            'property_county_state'     =>esc_html__('County State','wpestate'),
                            );
    
    $adv6_taxonomy_select   =   wpestate_dropdowns_theme_admin_with_key($tax_array,'adv6_taxonomy');
    $adv6_taxonomy          =   get_option('wp_estate_adv6_taxonomy');
    $adv6_taxonomy_terms    =   get_option('wp_estate_adv6_taxonomy_terms');     
    $adv6_max_price         =   get_option('wp_estate_adv6_max_price');     
    $adv6_min_price         =   get_option('wp_estate_adv6_min_price');     
 
        
    print'<div class="estate_option_row var_price_sliders">
    <div class="label_option_row">'.esc_html__('Select Taxonomy for tabs options in Advanced Search Type 6','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('This applies for  the search over media header.','wpestate').'</div>    
        <select id="adv6_taxonomy" name="adv6_taxonomy">
            '.$adv6_taxonomy_select.'
        </select>';
    
        $ajax_nonce = wp_create_nonce( "wpestate_adv6_taxonomy_nonce" );
        print'<input type="hidden" id="wpestate_adv6_taxonomy_nonce" value="'.esc_html($ajax_nonce).'" />    ';
        

        print'<select id="adv6_taxonomy_terms" name="adv6_taxonomy_terms[]" multiple="multiple" style="';
        if($adv6_taxonomy==''){print 'display:none;';}
        print 'height:200px;">'; 
        
        if($adv6_taxonomy !=='' ){
            $terms = get_terms( array(
                'taxonomy' => $adv6_taxonomy,
                'hide_empty' => false,
                'orderby'   =>'ID',
                'order'     =>'ASC'
            ) );
       
            foreach($terms as $term){
                if(isset($term->term_id)){
                    print '<option value="'.$term->term_id.'" ';
                    if(is_array($adv6_taxonomy_terms) && in_array($term->term_id, $adv6_taxonomy_terms) ){
                        print ' selected= "selected" ';
                    }

                    print' >'.$term->name.'</option>';
                }
            }      
    
        }
        
        
        print'</select>';

        print '<div style="margin-bottom:30px;"></div>';
        $i=0;
        
        if(is_array($adv6_taxonomy_terms)){

            print '<div class="label_option_row" style="margin-bottom:10px;">'.esc_html__('Price SLider values for advanced search with tabs','wpestate').'</div>';
            foreach ($adv6_taxonomy_terms as $term_id){
                $term = get_term( $term_id, $adv6_taxonomy);

                print '<div class="field_row">
                    <div class="field_item">'.esc_html__('Price Slider Values(min/max) for ','wpestate').$term->name.'</div>

                    <div class="field_item">
                       <input type="text" id="adv6_min_price" name="adv6_min_price[]" value="';

                        if( isset( $adv6_min_price[$i]) ){
                            echo esc_attr($adv6_min_price[$i]);
                        }
                        print'">
                    </div>

                    <div class="field_item">
                        <input type="text" id="adv6_max_price" name="adv6_max_price[]" value="';
                        if( isset( $adv6_max_price[$i]) ){
                            echo esc_attr($adv6_max_price[$i]);
                        }
                        print'">
                    </div>
                </div>';
                $i++;

            }
            
        }
       

print'</div>';

      print'<div class="" style="width:100%;float:left;margin-bottom:20px;"></div>';
    
    
    
    $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
    $adv_search_what    = get_option('wp_estate_adv_search_what','');
    $adv_search_how     = get_option('wp_estate_adv_search_how','');
    $adv_search_label   = get_option('wp_estate_adv_search_label','');

      
    print '<div class="estate_option_row">';
    print '<div id="custom_fields_search">';   
    print '<div class="field_row">
    <div class="field_item"><strong>'.esc_html__('Place in advanced search form','wpestate').'</strong></div>
    <div class="field_item"><strong>'.esc_html__('Search field','wpestate').'</strong></div>
    <div class="field_item"><strong>'.esc_html__('How it will compare','wpestate').'</strong></div>
    <div class="field_item"><strong>'.esc_html__('Label on Front end','wpestate').'</strong></div>
    </div>';
    
   
        
        
    $i=0;
    while( $i < $adv_search_fields_no ){
        $i++;
    
        print '<div class="field_row">
        <div class="field_item">'.esc_html__('Spot no ','wpestate').$i.'</div>
        
        <div class="field_item">
            <select id="adv_search_what'.$i.'" name="adv_search_what[]">';
                print   wpestate_show_advanced_search_options($i-1,$adv_search_what);
            print'</select>
        </div>
        
        <div class="field_item">
            <select id="adv_search_how'.$i.'" name="adv_search_how[]">';
                print  wpestate_show_advanced_search_how($i-1,$adv_search_how);
        
                $new_val=''; 
                if( isset($adv_search_label[$i-1]) ){
                    $new_val=$adv_search_label[$i-1]; 
                }
        print '</select>
        </div>
        
        <div class="field_item"><input type="text" id="adv_search_label'.$i.'" name="adv_search_label[]" value="'.$new_val.'"></div>
        </div>';

    }
    print'</div>';
    print'    
        <p style="margin-left:10px;">
         '.esc_html__('*Do not duplicate labels and make sure search fields do not contradict themselves','wpestate').'</br>
        '.esc_html__('*Labels will not apply for taxonomy dropdowns fields','wpestate').'</br>
      
        </p>';
    
    print'</div>';
        
    $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
    $feature_list_array =   explode( ',',$feature_list);
    foreach($feature_list_array as $checker => $value){
        $feature_list_array[$checker]= stripslashes($value);
    }
    
  
    $advanced_exteded =  get_option('wp_estate_advanced_exteded');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Amenities and Features for Advanced Search?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select which features and amenities show in search.','wpestate').'</div>';    
        
        print ' <p style="margin-left:10px;">  '.esc_html__('*Hold CTRL for multiple selection','wpestate').'</p>
        <input type="hidden" name="advanced_exteded[]" value="none">
        <select name="advanced_exteded[]" multiple="multiple" style="height:400px;">';
        foreach($feature_list_array as $checker => $value){
            $value          =   stripslashes($value);
            $post_var_name  =   str_replace(' ','_', trim($value) );
            
            
            print '<option value="'.$post_var_name.'"' ;
            if(is_array($advanced_exteded)){
                if( in_array ($post_var_name,$advanced_exteded) ){
                    print ' selected="selected" ';
                } 
            }
            
            print '>'.stripslashes($value).'</option>';                
        }
        print '</select>';
        
    print'</div>';
         
    
 
       
      
        
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  

       // wpestate_theme_admin_adv_search();
}
endif;



if( !function_exists('wpestate_new_advanced_search_settings') ):  
 function wpestate_new_advanced_search_settings(){
    $cache_array                    =   array('yes','no');
    
    $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
    $adv_search_what    = get_option('wp_estate_adv_search_what','');
    $adv_search_how     = get_option('wp_estate_adv_search_how','');
    $adv_search_label   = get_option('wp_estate_adv_search_label','');
    
    
    
    $value_array=array('no','yes');
    $search_array = array (1,2,3,4,5,6);
    $show_adv_search_type= wpestate_dropdowns_theme_admin($search_array,'adv_search_type',esc_html__('Type','wpestate').' ');
    
    
    $show_adv_search_general_select     = wpestate_dropdowns_theme_admin($cache_array,'show_adv_search_general');
    $show_adv_search_slider_select      = wpestate_dropdowns_theme_admin($cache_array,'show_adv_search_slider');
    $show_adv_search_visible_select     = wpestate_dropdowns_theme_admin($cache_array,'show_adv_search_visible');
    $show_adv_search_extended_select    = wpestate_dropdowns_theme_admin($cache_array,'show_adv_search_extended');
    $show_save_search_select            = wpestate_dropdowns_theme_admin($cache_array,'show_save_search');
    $show_slider_price_select           = wpestate_dropdowns_theme_admin($cache_array,'show_slider_price');
    $show_dropdowns_select              = wpestate_dropdowns_theme_admin($cache_array,'show_dropdowns');
    
    
    
    
    $period_array   =array( 0 =>esc_html__('daily','wpestate'),
                            1 =>esc_html__('weekly','wpestate') 
                            );
    
    $search_alert_select = wpestate_dropdowns_theme_admin_with_key($period_array,'search_alert');
    
 
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Advanced Search Type ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('This applies for  the search over header type.','wpestate').'</div>    
        <select id="adv_search_type" name="adv_search_type">
                    '.$show_adv_search_type.'
                </select> 
    </div>';
     
     
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Saved Search Feature ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If yes, user can save his searchs from Advanced Search Results, if he is logged in with a registered account.','wpestate').'</div>    
        <select id="show_save_search" name="show_save_search">
            '.$show_save_search_select.'
        </select> 
    </div>';


    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Send emails','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Send weekly or daily an email alert with new published properties matching user saved searches.','wpestate').'</div>    
        <select id="search_alert" name="search_alert">
            '.$search_alert_select.'
        </select>
    </div>';
       

        
     
  
 
 
     
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Amenities and Features fields?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select what features from Advanced Search Form. *for speed reasons, the "features checkboxes" will not filter the pins on the map','wpestate').'</div>    
        <select id="show_adv_search_extended" name="show_adv_search_extended">
            '.$show_adv_search_extended_select.'
        </select>
    </div>';
        
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Slider for Price?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('If no, price field can still be used in search and it will be input type.','wpestate').'</div>    
        <select id="show_slider_price" name="show_slider_price">
            '.$show_slider_price_select.'
        </select>
    </div>';
        
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Dropdowns for beds, bathrooms or rooms?(*only works with Custom Fields - YES)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Custom Fields are enabled and set from Advanced Search Form.','wpestate').'</div>    
        <select id="show_dropdowns" name="show_dropdowns">
            '.$show_dropdowns_select.'
        </select>
    </div>';
        
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Minimum and Maximum value for Price Slider','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Type only numbers!','wpestate').'</div>    
        <input type="text" name="show_slider_min_price"  class="inptxt " value="'.floatval(get_option('wp_estate_show_slider_min_price','')).'"/>
        -   
        <input type="text" name="show_slider_max_price"  class="inptxt " value="'.floatval(get_option('wp_estate_show_slider_max_price','')).'"/>
    </div>';
        


    $adv_back_color              =  esc_html ( get_option('wp_estate_adv_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Advanced Search Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Advanced Search Background Color','wpestate').'</div>    
        <input type="text" name="adv_back_color" value="'.$adv_back_color.'" maxlength="7" class="inptxt" />
        <div id="adv_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$adv_back_color.';" ></div></div>
    </div>';
        


    $adv_font_color              =  esc_html ( get_option('wp_estate_adv_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Advanced Search Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Advanced Search Font Color','wpestate').'</div>    
        <input type="text" name="adv_font_color" value="'.$adv_font_color.'" maxlength="7" class="inptxt" />
        <div id="adv_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$adv_font_color.';" ></div></div>
    </div>';
    
    
    
    $adv_search_back_color          =  esc_html ( get_option('wp_estate_adv_search_back_color ','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Map Advanced Search Button Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Map Advanced Search Button Background Color','wpestate').'</div>    
        <input type="text" name="adv_search_back_color" value="'.$adv_search_back_color.'" maxlength="7" class="inptxt" />
        <div id="adv_search_back_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$adv_search_back_color.';"></div></div>
    </div>';
    
    $adv_search_font_color          =  esc_html ( get_option('wp_estate_adv_search_font_color','') );  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Advanced Search Fields Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Advanced Search Fields Font Color','wpestate').'</div>    
        <input type="text" name="adv_search_font_color" value="'.$adv_search_font_color.'" maxlength="7" class="inptxt" />
        <div id="adv_search_font_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$adv_search_font_color.';"></div></div>
    </div>';
    
 
     
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';  
 } 
 endif;       
 
 
 
if( !function_exists('wpestate_new_custom_fields') ):   
function wpestate_new_custom_fields(){
   
    $custom_fields = get_option( 'wp_estate_custom_fields', true);     
    $current_fields='';

    
    $i=0;
    if( !empty($custom_fields)){    
        while($i< count($custom_fields) ){
            $current_fields.='
                <div class=field_row>
                <div    class="field_item"><strong>'.esc_html__('Field Name','wpestate').'</strong></br><input  type="text" name="add_field_name[]"   value="'.stripslashes( $custom_fields[$i][0] ).'"  ></div>
                <div    class="field_item"><strong>'.esc_html__('Field Label','wpestate').'</strong></br><input  type="text" name="add_field_label[]"   value="'.stripslashes( $custom_fields[$i][1]).'"  ></div>
                <div    class="field_item"><strong>'.esc_html__('Field Type','wpestate').'</strong></br>'.wpestate_fields_type_select($custom_fields[$i][2]).'</div>
                <div    class="field_item"><strong>'.esc_html__('Field Order','wpestate').'</strong></br><input  type="text" name="add_field_order[]" value="'.$custom_fields[$i][3].'"></div>     
                <div    class="field_item newfield"><strong>'.esc_html__('Dropdown values','wpestate').'</strong></br><textarea name="add_dropdown_order[]">';
                
                if( isset($custom_fields[$i][4])){
                    $current_fields.= $custom_fields[$i][4];
                }    
                $current_fields.='</textarea></div>     
             
                <a class="deletefieldlink" href="#">'.esc_html__('delete','wpestate').'</a>
            </div>';    
            $i++;
        }
    }
 

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Custom Fields list','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Add, edit or delete property custom fields.','wpestate').'</div>    
        <div id="custom_fields_wrapper">
        '.$current_fields.'
        <input type="hidden" name="is_custom" value="1">   
        </div>
    </div>';
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Add New Custom Field','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Fill the form in order to add a new custom field','wpestate').'</div>  
     
        <div class="add_curency">
            <div class="cur_explanations">'.esc_html__('Field name','wpestate').'</div>
            <input  type="text" id="field_name"  name="field_name"   value=""/>
            
            <div class="cur_explanations">'.esc_html__('Field Label','wpestate').'</div>
             <input  type="text" id="field_label"  name="field_label"   value="" />
            
            <div class="cur_explanations">'.esc_html__('Field Type','wpestate').'</div>
                <select id="field_type" name="field_type">
                    <option value="short text">short text</option>
                    <option value="long text">long text</option>
                    <option value="numeric">numeric</option>
                    <option value="date">date</option>
                    <option value="dropdown">dropdown</option>
                </select>
            

            <div class="cur_explanations">'.esc_html__(' Order in listing page','wpestate').'</div>
            <input  type="text" id="field_order"  name="field_order"   value="" />
                
            <div class="cur_explanations">'.esc_html__('Dropdown values separated by "," (only for dropdown field type)','wpestate').'</div>
            <textarea id="drodown_values"  name="drodown_values"  style="width:300px;"></textarea>
            
            </br>
            <a href="#" id="add_field">'.esc_html__(' click to add field','wpestate').'</a>
        </div>
        
        
    </div>'; 
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';   

}
endif;


if( !function_exists('wpestate_new_ammenities_features') ):   
function wpestate_new_ammenities_features(){
    $feature_list                           =   esc_html( get_option('wp_estate_feature_list') );
    $feature_list                           =   str_replace(', ',',&#13;&#10;',$feature_list);
    
    $cache_array=array('yes','no');
    $show_no_features_symbol =  wpestate_dropdowns_theme_admin($cache_array,'show_no_features');
    
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Add New Element in Features and Amenities','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Type and add a new item in features and amenities list.','wpestate').'</div>    
        <input  type="text" id="new_feature"  name="new_feature"   value="type here feature name.. " size="40"/><br>
        <a href="#" id="add_feature"> click to add feature </a><br>
    </div>';
  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Features and Amenities list','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('List of already added features and amenities.','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('After adding a new feature, make sure you select it to be part of the property submission form from Membership -> Property Submission Page.','wpestate').'</div> 
        <textarea id="feature_list" name="feature_list" rows="15" cols="42">'.stripslashes( $feature_list).'</textarea> 
    </div>';
      
     print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show the Features and Amenities that are not available','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show on property page the features and amenities that are not selected?','wpestate').'</div>    
        <select id="show_no_features" name="show_no_features">
            '.$show_no_features_symbol.'
        </select> 
    </div>';
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;

if( !function_exists('wpestate_new_listing_labels') ):   
function wpestate_new_property_links(){
  
 
    $rewrites =  get_option('wp_estate_url_rewrites');
 
    $links_to_rewrite = array(
        'property_page'         =>  array(
                                        $rewrites[0],
                                        esc_html__('Property Page','wpestate')
                                    ),
        
        'property_category'     =>  array(
                                         $rewrites[1],
                                        esc_html__('Property Categories Page','wpestate')
                                    ),
        
        'property_action_category'     =>  array(
                                        $rewrites[2],
                                        esc_html__('Property Action Category Page','wpestate')
                                    ),
        'property_city'     =>  array(
                                        $rewrites[3],
                                        esc_html__('Property City Page','wpestate')
                                    ),
        
        'property_area'     =>  array(
                                        $rewrites[4],
                                        esc_html__('Property Area Page','wpestate')
                                    ),
        
        'property_county_state'     =>  array(
                                         $rewrites[5],
                                        esc_html__('Property County/State Page','wpestate')
                                    ),
        'agent_page'     =>  array(
                                         $rewrites[6],
                                        esc_html__('Agent Page','wpestate')
                                    ),
        'agent_category'     =>  array(
                                         $rewrites[7],
                                        esc_html__('Agent Categories Page','wpestate')
                                    ),
        
        'agent_action_category'     =>  array(
                                        $rewrites[8],
                                        esc_html__('Agent Action Category Page','wpestate')
                                    ),
        'agent_city'     =>  array(
                                        $rewrites[9],
                                        esc_html__('Agent City Page','wpestate')
                                    ),
        
        'agent_area'     =>  array(
                                        $rewrites[10],
                                        esc_html__('Agent Area Page','wpestate')
                                    ),
        
        'agent_county_state'     =>  array(
                                         $rewrites[11],
                                        esc_html__('Agent County/State Page','wpestate')
                                    ),
    );
    
    $i=0;
      print'<div class="estate_option_row">'.esc_html__(' You cannot use special characters like "&". After changing the url you may need to wait for a few minutes until wordpress changes all the urls.','wpestate').'</div>';
    
    
    foreach ($links_to_rewrite as $key=>$value){
        print'<div class="estate_option_row">
        <div class="label_option_row">'.$value[1].'</div>
        <div class="option_row_explain">'.esc_html__('Custom link for ','wpestate').' '.$value[1].'</div>    
           '.esc_url( home_url('/') ) .'/ <input  type="text" id="'.$value[1].'"  name="url_rewrites[]"   value="'.$rewrites[$i].'"/> /....
        </div>';
        $i++;
    }
      
      
      
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;


if( !function_exists('wpestate_new_listing_labels') ):   
function wpestate_new_listing_labels(){
    $cache_array                            =   array('yes','no');
    $status_list                            =   esc_html(stripslashes( get_option('wp_estate_status_list') ) );
    $status_list                            =   str_replace(', ',',&#13;&#10;',$status_list);
    $property_adr_text                      =   stripslashes ( esc_html( get_option('wp_estate_property_adr_text') ) );
    $property_description_text              =   stripslashes ( esc_html( get_option('wp_estate_property_description_text') ) );
    $property_details_text                  =   stripslashes ( esc_html( get_option('wp_estate_property_details_text') ) );
    $property_features_text                 =   stripslashes ( esc_html( get_option('wp_estate_property_features_text') ) );
   
    $property_multi_text                      =   stripslashes ( esc_html( get_option('wp_estate_property_multi_text') ) );
    $property_multi_child_text                      =   stripslashes ( esc_html( get_option('wp_estate_property_multi_child_text') ) );
   
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Multi Unit Label','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__(' Custom title instead of Multi Unit label.','wpestate').'</div>    
        <input  type="text" id="property_multi_text"  name="property_multi_text"   value="'.$property_multi_text.'"/>
    </div>';
    
     print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Multi Unit Label (*for sub unit)','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__(' Custom title instead of Multi Unit label(*for sub unit).','wpestate').'</div>    
        <input  type="text" id="property_multi_child_text"  name="property_multi_child_text"   value="'.$property_multi_child_text.'"/>
    </div>';
     
 
        
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property Address Label','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__(' Custom title instead of Property Address label.','wpestate').'</div>    
        <input  type="text" id="property_adr_text"  name="property_adr_text"   value="'.$property_adr_text.'"/>
    </div>';
              
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property Features Label','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Update; Custom title instead of Features and Amenities label.','wpestate').'</div>    
        <input  type="text" id="property_features_text"  name="property_features_text"   value="'.$property_features_text.'" size="40"/>
    </div>';
                
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property Description Label','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Custom title instead of Description label.','wpestate').'</div>    
        <input  type="text" id="property_description_text"  name="property_description_text"   value="'.$property_description_text.'" size="40"/>
    </div>';

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property Details Label','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Custom title instead of Property Details label. ','wpestate').'</div>    
        <input  type="text" id="property_details_text"  name="property_details_text"   value="'.$property_details_text.'" size="40"/>
    </div>';

    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Property Status ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Property Status (* you may need to add new css classes - please see the help files) ','wpestate').'</div>    
        <input  type="text" id="new_status"  name="new_status"   value="'.esc_html__('type here the new status... ','wpestate').'"/></br>
        <a href="#new_status" id="add_status">'.esc_html__('click to add new status','wpestate').'</a><br>
        <textarea id="status_list" name="status_list" rows="7" style="width:300px;">'.$status_list.'</textarea>  
    </div>';
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif; 

if( !function_exists('wpestate_new_theme_slider') ):   
 function wpestate_new_theme_slider(){
    $theme_slider   =   get_option( 'wp_estate_theme_slider', true); 
    $slider_cycle   =   get_option( 'wp_estate_slider_cycle', true); 
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Select Properties','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select properties for slider - *hold CTRL for multiple select ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Due to speed reason we only show here the first 50 listings. If you want to add other listings into the theme slider please go and edit the property (in wordpress admin) and select "Property in theme Slider" in Property Details tab.','wpestate').'</div>';    
        
        $args = array(  'post_type'                 =>  'estate_property',
                        'post_status'               =>  'publish',
                        'paged'                     =>  1,
                        'posts_per_page'            =>  50,
                        'cache_results'             =>  false,
                        'update_post_meta_cache'    =>  false,
                        'update_post_term_cache'    =>  false,
                );

        $recent_posts = new WP_Query($args);
        print '<select name="theme_slider[]"  id="theme_slider"  multiple="multiple">';
        while ($recent_posts->have_posts()): $recent_posts->the_post();
             $theid=get_the_ID();
             print '<option value="'.$theid.'" ';
             if( is_array($theme_slider) && in_array($theid, $theme_slider) ){
                 print ' selected="selected" ';
             }
             print'>'.get_the_title().'</option>';
        endwhile;
        print '</select>';
     
    print '</div>';
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Number of milisecons before auto cycling an item','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Number of milisecons before auto cycling an item (5000=5sec).Put 0 if you don\'t want to autoslide. ','wpestate').'</div>    
        <input  type="text" id="slider_cycle" name="slider_cycle"  value="'.$slider_cycle.'"/> 
    </div>';
    
   
    $cache_array                    =   array('type1','type2');  
    $theme_slider_type_select       =   wpestate_dropdowns_theme_admin($cache_array,'theme_slider_type');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Design Type?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Select the design type.','wpestate').'</div>    
        <select id="theme_slider_type" name="theme_slider_type">
            '.$theme_slider_type_select.'
        </select>
    </div>';
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
     
 }
 endif;
 
 
 if( !function_exists('wpestate_new_email_management') ):   
 function wpestate_new_email_management(){
     
        $emails=array(
            'new_user'                  =>  esc_html__('New user  notification','wpestate'),
            'admin_new_user'            =>  esc_html__('New user admin notification','wpestate'),
            'purchase_activated'        =>  esc_html__('Purchase Activated','wpestate'),
            'password_reset_request'    =>  esc_html__('Password Reset Request','wpestate'),
            'password_reseted'          =>  esc_html__('Password Reseted','wpestate'),
            'purchase_activated'        =>  esc_html__('Purchase Activated','wpestate'),
            'approved_listing'          =>  esc_html__('Approved Listings','wpestate'),
            'new_wire_transfer'         =>  esc_html__('New wire Transfer','wpestate'),
            'admin_new_wire_transfer'   =>  esc_html__('Admin - New wire Transfer','wpestate'),
            'admin_expired_listing'     =>  esc_html__('Admin - Expired Listing','wpestate'),
            'matching_submissions'      =>  esc_html__('Matching Submissions','wpestate'),
            'paid_submissions'          =>  esc_html__('Paid Submission','wpestate'),
            'featured_submission'       =>  esc_html__('Featured Submission','wpestate'),
            'account_downgraded'        =>  esc_html__('Account Downgraded','wpestate'),
            'membership_cancelled'      =>  esc_html__('Membership Cancelled','wpestate'),
            'downgrade_warning'         =>  esc_html__('Membership Expiration Warning','wpestate'),
            'free_listing_expired'      =>  esc_html__('Free Listing Expired','wpestate'),
            'new_listing_submission'    =>  esc_html__('New Listing Submission','wpestate'),
            'listing_edit'              =>  esc_html__('Listing Edit','wpestate'),
            'recurring_payment'         =>  esc_html__('Recurring Payment','wpestate'),
            'membership_activated'      =>  esc_html__('Membership Activated','wpestate'),
            'agent_update_profile'      =>  esc_html__('Update Profile','wpestate'),
           
        );
        
        

            
        print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Global variables: %website_url as website url,%website_name as website name, %user_email as user_email, %username as username','wpestate').'</div>
        </div>';
        
        foreach ($emails as $key=>$label ){
            $value          = stripslashes( get_option('wp_estate_'.$key,'') );
            $value_subject  = stripslashes( get_option('wp_estate_subject_'.$key,'') );
              
            print'<div class="estate_option_row">
            <div class="label_option_row">'.esc_html__('Subject for','wpestate').' '.$label.'</div>
            <div class="option_row_explain">'.esc_html__('Email subject for','wpestate').' '.$label.'</div>
            <input type="text" style="width:100%" name="subject_'.$key.'" value="'.$value_subject.'" />
            </br>
            <div class="label_option_row">'.esc_html__('Content for','wpestate').' '.$label.'</div>
            <div class="option_row_explain">'.esc_html__('Email content for','wpestate').' '.$label.'</div>
            <textarea rows="10" style="width:100%" name="'.$key.'">'.$value.'</textarea>
            <div class="extra_exp_new"> '.wpestate_emails_extra_details($key).'</div>
            </div>';
    
         
        

        }
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
        
}
endif;


 if( !function_exists('wpestate_new_site_speed') ):   
function wpestate_new_site_speed(){
      
    $cache_array=array('no','yes');
    $mimify_css_js=  wpestate_dropdowns_theme_admin($cache_array,'use_mimify');
    
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Speed Advices','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('1. If you are NOT using "Ultimate Addons for Visual Composer" please disable it or just disable the modules you don\'t use. It will reduce the size of javascript files you are loading and increase the site speed!    ','wpestate').'</div>    
    <div class="option_row_explain">'.esc_html__('2. Use the EWWW Image Optimizer (or WP Smush IT) plugin to optimise images- optimised images increase the site speed.','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('3. Create a free account on Cloudflare (https://www.cloudflare.com/) and use this CDN.','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('4. If you are using custom categories make sure you are adding the custom pins images on Theme Options -> Map -> Pin Management. The site will get slow if it needs to look for images that don\'t exist.','wpestate').'</div>
    </div>';
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Minify css and js files','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('The system will use the minified versions of the css and js files','wpestate').'</div>    
        <select id="use_mimify" name="use_mimify">
            '.$mimify_css_js.'
        </select> 
    </div>';
    
    
    $remove_script_version=  wpestate_dropdowns_theme_admin($cache_array,'remove_script_version');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Remove script version','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('The system will remove the script version when it is included. This doest not actually improve speed, but improves test score on speed tools pages.','wpestate').'</div>    
        <select id="remove_script_version" name="remove_script_version">
            '.$remove_script_version.'
        </select> 
    </div>';
     
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Enable Browser Cache','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Add this code in your .httaces file(copy paste at the end). It will activate the browser cache and speed up your site.','wpestate').'</div>    
       <textarea rows="15" style="width:100%;" onclick="this.focus();this.select();">      
<IfModule mod_deflate.c>
# Insert filters
AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/xml
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/x-javascript
AddOutputFilterByType DEFLATE application/x-httpd-php
AddOutputFilterByType DEFLATE application/x-httpd-fastphp
AddOutputFilterByType DEFLATE image/svg+xml
# Drop problematic browsers
BrowserMatch ^Mozilla/4 gzip-only-text/html
BrowserMatch ^Mozilla/4\.0[678] no-gzip
BrowserMatch \bMSI[E] !no-gzip !gzip-only-text/html
# Make sure proxies dont deliver the wrong content
Header append Vary User-Agent env=!dont-vary
</IfModule>
## EXPIRES CACHING ##
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType image/jpg "access 1 year"
ExpiresByType image/jpeg "access 1 year"
ExpiresByType image/gif "access 1 year"
ExpiresByType image/png "access 1 year"
ExpiresByType text/css "access 1 month"
ExpiresByType text/html "access 1 month"
ExpiresByType application/pdf "access 1 month"
ExpiresByType text/x-javascript "access 1 month"
ExpiresByType application/x-shockwave-flash "access 1 month"
ExpiresByType image/x-icon "access 1 year"
ExpiresDefault "access 1 month"
</IfModule>
## EXPIRES CACHING ##
       </textarea>
    </div>';
     
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;









if( !function_exists('wpestate_new_generate_pins') ):   
 function wpestate_new_generate_pins(){
    
    if (isset($_POST['startgenerating']) == 1){
        //wpestate_generate_file_pins();
        if ( get_option('wp_estate_readsys','') =='yes' ){
        $path=estate_get_pin_file_path(); 
   
            if ( file_exists ($path) && is_writable ($path) ){
                wpestate_listing_pins_for_file();
                print'<div class="estate_option_row">
                <div class="label_option_row">'. esc_html__('File was generated','wpestate').'</div>
                </div>';
            }else{
                print'<div class="estate_option_row">
                <div class="label_option_row">'.esc_html__('the file Google map does NOT exist or is NOT writable','wpestate') .'</div>
                </div>';
            }
   
        }else{
          
            print'<div class="estate_option_row">
            <div class="label_option_row">'.  esc_html__('Pin Generation works only if the file reading option in Google Map setting is set to yes','wpestate').'</div>
            </div>';
    
        }
    
    
    }else{  
     
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Generate the pins','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Generate the pins for the read from file map option','wpestate').'</div>    
       
    <input type="hidden" name="startgenerating" value="1" />
        
    </div>';
    
    }
    
     print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Generate Pins','wpestate').'" />
    </div>';
    
}
endif;


if( !function_exists('wpestate_new_help_custom') ):   
function wpestate_new_help_custom(){
  
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Help and Custom','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Help and custom work','wpestate').'</div>    
       <p> '.esc_html__('For theme help please check helpv4.wpestatetheme.org. If your question is not here, please go to http://support.wpestate.org, create an account and post a ticket. The registration is simple and as soon as you send a ticket we are notified. We usually answer in the next 24h (except weekends). Please use this system and not the email. It will help us answer your questions much faster. Thank you!','wpestate').' </p>
       <p> '.esc_html__('For custom work on this theme please go to','wpestate').' <a href="http://support.wpestate.org/" target="_blank">http://support.wpestate.org</a>'. esc_html( ', create a ticket with your request and we will offer a free quote.','wpestate').' </p>
       <p> '.esc_html__('Subscribe to our mailing list in order to receive news about new features and theme upgrades. ','wpestate').' <a href="http://eepurl.com/CP5U5">Subscribe Here!</a></p>
                   
    </div>';
        
}
endif;



if(!function_exists('estate_yelp_settings')):
function estate_yelp_settings(){
    $yelp_client_id = get_option('wp_estate_yelp_client_id', '');
    $yelp_client_secret = get_option('wp_estate_yelp_client_secret', '');
    $yelp_results_no        = get_option('wp_estate_yelp_results_no','');
    $yelp_terms             = get_option('wp_estate_yelp_categories','');
    
    if(!is_array($yelp_terms)){
        $yelp_terms=array();
    }
    
   
    
    $yelp_terms_array = 
            array (
                'active'            =>  array( 'category' => esc_html__('Active Life','wpestate'),
                                                'category_sign' => 'fa fa-bicycle'),
                'arts'              =>  array( 'category' => esc_html__('Arts & Entertainment','wpestate'), 
                                               'category_sign' => 'fa fa-music') ,
                'auto'              =>  array( 'category' => esc_html__('Automotive','wpestate'), 
                                                'category_sign' => 'fa fa-car' ),
                'beautysvc'         =>  array( 'category' => esc_html__('Beauty & Spas','wpestate'), 
                                                'category_sign' => 'fa fa-female' ),
                'education'         => array(  'category' => esc_html__('Education','wpestate'),
                                                'category_sign' => 'fa fa-graduation-cap' ),
                'eventservices'     => array(  'category' => esc_html__('Event Planning & Services','wpestate'), 
                                                'category_sign' => 'fa fa-birthday-cake' ),
                'financialservices' => array(  'category' => esc_html__('Financial Services','wpestate'), 
                                                'category_sign' => 'fa fa-money' ),                
                'food'              => array(  'category' => esc_html__('Food','wpestate'), 
                                                'category_sign' => 'fa fa fa-cutlery' ),
                'health'            => array(  'category' => esc_html__('Health & Medical','wpestate'), 
                                                'category_sign' => 'fa fa-medkit' ),
                'homeservices'      => array(  'category' =>esc_html__('Home Services ','wpestate'), 
                                                'category_sign' => 'fa fa-wrench' ),
                'hotelstravel'      => array(  'category' => esc_html__('Hotels & Travel','wpestate'), 
                                                'category_sign' => 'fa fa-bed' ),
                'localflavor'       => array(  'category' => esc_html__('Local Flavor','wpestate'), 
                                                'category_sign' => 'fa fa-coffee' ),
                'localservices'     => array(  'category' => esc_html__('Local Services','wpestate'), 
                                                'category_sign' => 'fa fa-dot-circle-o' ),
                'massmedia'         => array(  'category' => esc_html__('Mass Media','wpestate'),
                                                'category_sign' => 'fa fa-television' ),
                'nightlife'         => array(  'category' => esc_html__('Nightlife','wpestate'),
                                                'category_sign' => 'fa fa-glass' ),
                'pets'              => array(  'category' => esc_html__('Pets','wpestate'),
                                                'category_sign' => 'fa fa-paw' ),
                'professional'      => array(  'category' => esc_html__('Professional Services','wpestate'), 
                                                'category_sign' => 'fa fa-suitcase' ),
                'publicservicesgovt'=> array(  'category' => esc_html__('Public Services & Government','wpestate'),
                                                'category_sign' => 'fa fa-university' ),
                'realestate'        => array(  'category' => esc_html__('Real Estate','wpestate'), 
                                                'category_sign' => 'fa fa-building-o' ),
                'religiousorgs'     => array(  'category' => esc_html__('Religious Organizations','wpestate'), 
                                                'category_sign' => 'fa fa-cloud' ),
                'restaurants'       => array(  'category' => esc_html__('Restaurants','wpestate'),
                                                'category_sign' => 'fa fa-cutlery' ),
                'shopping'          => array(  'category' => esc_html__('Shopping','wpestate'),
                                                'category_sign' => 'fa fa-shopping-bag' ),
                'transport'         => array(  'category' => esc_html__('Transportation','wpestate'),
                                                'category_sign' => 'fa fa-bus' )
    );
    print '<div class="estate_option_row">'.esc_html__('Please note that Yelp is not working for all countries. See here ','wpestate').'<a href="https://www.yelp.com/factsheet">https://www.yelp.com/factsheet</a>'.__(' the list of countries where Yelp is available.','wpestate').'</br></div>';
    
  
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Yelp Api Client','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Get this detail after you signup here ','wpestate').'<a target="_blank" href="https://www.yelp.com/developers/v3/manage_app">https://www.yelp.com/developers/v3/manage_app</a></div>    
        <input  type="text" id="yelp_client_id" name="yelp_client_id"  value="'.$yelp_client_id.'"/> 
    </div>';
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Yelp Api key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Get this detail after you signup here ','wpestate').'<a target="_blank" href="https://www.yelp.com/developers/v3/manage_app">https://www.yelp.com/developers/v3/manage_app</a></div>    
        <input  type="text" id="yelp_client_secret" name="yelp_client_secret"  value="'.$yelp_client_secret.'"/> 
    </div>'; 
    
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Yelp Categories ','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Yelp Categories to show on front page ','wpestate').'</div>    
        <select name="yelp_categories[]" style="height:400px;" id="yelp_categories" multiple>';
        foreach($yelp_terms_array as $key=>$term){
            print '<option value="'.$key.'" ' ;
            $keyx = array_search ($key,$yelp_terms) ;
            if( $keyx!==false ){
                print 'selected= "selected" ';
            }
            print'>'.$term['category'].'</option>';
        }
    print'</select>
    </div>';
    
       
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Yelp - no of results','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Yelp - no of results ','wpestate').'</div>    
        <input  type="text" id="yelp_results_no" name="yelp_results_no"  value="'.$yelp_results_no.'"/> 
    </div>';
    
    $cache_array=array('miles','kilometers');
    $yelp_dist_measure=  wpestate_dropdowns_theme_admin($cache_array,'yelp_dist_measure');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Yelp Distance Measurement Unit','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Yelp Distance Measurement Unit','wpestate').'</div>    
       <select id="yelp_dist_measure" name="yelp_dist_measure">
            '.$yelp_dist_measure.'
        </select> 
    </div>';
    
    
      print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
     
     
    
}endif;





if(!function_exists('estate_recaptcha_settings')):
function estate_recaptcha_settings(){
    $reCaptha_sitekey       = get_option('wp_estate_recaptha_sitekey','');
    $reCaptha_secretkey     = get_option('wp_estate_recaptha_secretkey','');
    $cache_array=array('no','yes');
    $use_captcha=  wpestate_dropdowns_theme_admin($cache_array,'use_captcha');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use reCaptcha on register ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('This helps preventing registration spam.','wpestate').'</div>    
        <select id="use_captcha" name="use_captcha">
            '.$use_captcha.'
        </select> 
    </div>';
    
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('reCaptha site key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Get this detail after you signup here ','wpestate').'<a target="_blank" href="https://www.google.com/recaptcha/intro/index.html">https://www.google.com/recaptcha/intro/index.html</a></div>    
        <input  type="text" id="recaptha_sitekey" name="recaptha_sitekey"  value="'.$reCaptha_sitekey.'"/> 
    </div>';
    
     print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('reCaptha secret key','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Get this detail after you signup here ','wpestate').'<a target="_blank" href="https://www.google.com/recaptcha/intro/index.html">https://www.google.com/recaptcha/intro/index.html</a></div>    
        <input  type="text" id="recaptha_secretkey" name="recaptha_secretkey"  value="'.$reCaptha_secretkey.'"/> 
    </div>';
     
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;



if(!function_exists('wpestate_optima_express_settings')):
function wpestate_optima_express_settings(){
   
    $cache_array=array('no','yes');
    $use_optima=  wpestate_dropdowns_theme_admin($cache_array,'use_optima');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Use Optima Express plugin (idx plugin by ihomefinder) - you will need to enable the plugin ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable compatibility mode with Optima Express plugin','wpestate').'</div>    
        <select id="use_optima" name="use_optima">
            '.$use_optima.'
        </select> 
    </div>';
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;



if (!function_exists('wpestate_new_widget_design_elements_details')):
function wpestate_new_widget_design_elements_details(){
    
 
    
    $cache_array                =   array('no','yes');
  
    $sidebar_heading_color          =  esc_html ( get_option('wp_estate_sidebar_heading_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Sidebar Heading Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Sidebar Heading Color','wpestate').'</div>    
        <input type="text" name="sidebar_heading_color" value="'.$sidebar_heading_color.'" maxlength="7" class="inptxt" />
        <div id="sidebar_heading_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$sidebar_heading_color.';"></div></div>
    </div>';
    
    $sidebar2_font_color            =  esc_html ( get_option('wp_estate_sidebar2_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Widgets Font color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Widgets Font color','wpestate').'</div>    
        <input type="text" name="sidebar2_font_color" value="'.$sidebar2_font_color.'" maxlength="7" class="inptxt" />
        <div id="sidebar2_font_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$sidebar2_font_color.';"></div></div>
    </div>';
  
    $sidebar_widget_color           =  esc_html ( get_option('wp_estate_sidebar_widget_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Sidebar Widget Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Sidebar Widget Background Color','wpestate').'</div>    
        <input type="text" name="sidebar_widget_color" value="'.$sidebar_widget_color.'" maxlength="7" class="inptxt" />
        <div id="sidebar_widget_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$sidebar_widget_color.';" ></div></div>
    </div>';
    
    $wp_estate_widget_sidebar_border_size      = get_option('wp_estate_widget_sidebar_border_size','');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Widget Border Size','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Widget Border Size','wpestate').'</div>    
        <input  type="text" id="widget_sidebar_border_size " name="widget_sidebar_border_size"  value="'.$wp_estate_widget_sidebar_border_size.'"/> 
    </div>';
    
    
    $widget_sidebar_border_color            =  esc_html ( get_option('wp_estate_widget_sidebar_border_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Widget Border Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Widget Border Color','wpestate').'</div>    
        <input type="text" name="widget_sidebar_border_color" value="'.$widget_sidebar_border_color.'" maxlength="7" class="inptxt" />
        <div id="widget_sidebar_border_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$widget_sidebar_border_color.';"></div></div>
    </div>';
   
    

       
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;




if( !function_exists('wpestate_new_main_menu_design')):
function wpestate_new_main_menu_design(){
 
    
    $menu_font_color                =  esc_html ( get_option('wp_estate_menu_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Top Menu Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Top Menu Font Color','wpestate').'</div>    
        <input type="text" name="menu_font_color" value="'.$menu_font_color.'"  maxlength="7" class="inptxt" />
        <div id="menu_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_font_color.';" ></div></div>
    </div>';
        
    $top_menu_hover_font_color                =  esc_html ( get_option('wp_estate_top_menu_hover_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Top Menu Hover Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Top Menu Hover Font Color','wpestate').'</div>    
        <input type="text" name="top_menu_hover_font_color" value="'.$top_menu_hover_font_color.'"  maxlength="7" class="inptxt" />
        <div id="top_menu_hover_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$top_menu_hover_font_color.';" ></div></div>
    </div>';
    
    $transparent_menu_font_color                =  esc_html ( get_option('wp_estate_transparent_menu_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Transparent Header - Top Menu Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Transparent Header - Top Menu Font Color','wpestate').'</div>    
        <input type="text" name="transparent_menu_font_color" value="'.$transparent_menu_font_color.'"  maxlength="7" class="inptxt" />
        <div id="transparent_menu_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$transparent_menu_font_color.';" ></div></div>
    </div>';
        
    
   
    
    $top_menu_hover_back_font_color                =  esc_html ( get_option('wp_estate_top_menu_hover_back_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Top Menu Hover Background Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Top Menu Hover Background Color (*applies on some hover types)','wpestate').'</div>    
        <input type="text" name="top_menu_hover_back_font_color" value="'.$top_menu_hover_back_font_color.'"  maxlength="7" class="inptxt" />
        <div id="top_menu_hover_back_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$top_menu_hover_back_font_color.';" ></div></div>
    </div>';
    
    $transparent_menu_hover_font_color               =  esc_html ( get_option('wp_estate_transparent_menu_hover_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Transparent Header - Top Menu Hover Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Transparent Header - Top Menu Hover Font Color','wpestate').'</div>    
        <input type="text" name="transparent_menu_hover_font_color" value="'.$transparent_menu_hover_font_color.'"  maxlength="7" class="inptxt" />
        <div id="transparent_menu_hover_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$transparent_menu_hover_font_color.';" ></div></div>
    </div>';
    
    
    $sticky_menu_font_color                =  esc_html ( get_option('wp_estate_sticky_menu_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Sticky Menu Font Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Sticky Menu Font Color','wpestate').'</div>    
        <input type="text" name="sticky_menu_font_color" value="'.$sticky_menu_font_color.'"  maxlength="7" class="inptxt" />
        <div id="sticky_menu_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$sticky_menu_font_color.';" ></div></div>
    </div>';
        
    
    $cache_array=array(1,2,3,4,5,6);
    $top_menu_hover_type=  wpestate_dropdowns_theme_admin($cache_array,'top_menu_hover_type');
    
    print'<div class="estate_option_row">
    <img  style="border:1px solid #FFE7E7;margin-bottom:10px;" src="'. get_template_directory_uri().'/img/menu_types.png" alt="logo"/>
                      
    <div class="label_option_row">'.esc_html__('Top Menu Hover Type','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Top Menu Hover Type','wpestate').'</div>    
        <select id="top_menu_hover_type" name="top_menu_hover_type">
            '.$top_menu_hover_type.'
        </select> 
    </div>';
    
    
    $menu_items_color        =  esc_html ( get_option('wp_estate_menu_items_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Menu Item Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Menu Item Color','wpestate').'</div>    
        <input type="text" name="menu_items_color" value="'.$menu_items_color.'"  maxlength="7" class="inptxt" />
        <div id="menu_items_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_items_color.';" ></div></div>
    </div>';
    
     
    $menu_item_back_color         =  esc_html ( get_option('wp_estate_menu_item_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Menu Item Back Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Menu Item  Back Color','wpestate').'</div>    
       <input type="text" name="menu_item_back_color" value="'.$menu_item_back_color.'"  maxlength="7" class="inptxt" />
        <div id="menu_item_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_item_back_color.';"></div></div>
    </div>';
    
    
    $menu_hover_back_color          =  esc_html ( get_option('wp_estate_menu_hover_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Menu Item Hover Back Color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Menu Item Hover Back Color','wpestate').'</div>    
       <input type="text" name="menu_hover_back_color" value="'.$menu_hover_back_color.'"  maxlength="7" class="inptxt" />
        <div id="menu_hover_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_hover_back_color.';"></div></div>
    </div>';
     
    
    $menu_hover_font_color          =  esc_html ( get_option('wp_estate_menu_hover_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Menu Item hover font color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Menu Item hover font color','wpestate').'</div>    
        <input type="text" name="menu_hover_font_color" value="'.$menu_hover_font_color.'" maxlength="7" class="inptxt" />
        <div id="menu_hover_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_hover_font_color.';" ></div></div>
    </div>';
    
    
    $wp_estate_top_menu_font_size     = get_option('wp_estate_top_menu_font_size','');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Top Menu Font Size','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Top Menu Font Size','wpestate').'</div>    
        <input  type="text" id="top_menu_font_size" name="top_menu_font_size"  value="'.$wp_estate_top_menu_font_size.'"/> 
    </div>';
    
    
    $wp_estate_menu_item_font_size     = get_option('wp_estate_menu_item_font_size','');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Menu Item Font Size','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Menu Item Font Size','wpestate').'</div>    
        <input  type="text" id="menu_item_font_size" name="menu_item_font_size"  value="'.$wp_estate_menu_item_font_size.'"/> 
    </div>';
    
    
    $menu_border_color          =  esc_html ( get_option('wp_estate_menu_border_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Menu border color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Menu border color','wpestate').'</div>    
        <input type="text" name="menu_border_color" value="'.$menu_border_color.'" maxlength="7" class="inptxt" />
        <div id="menu_border_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$menu_border_color.';" ></div></div>
    </div>';
    

    $mobile_header_background_color          =  esc_html ( get_option('wp_estate_mobile_header_background_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Mobile header background color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Mobile header background color','wpestate').'</div>    
        <input type="text" name="mobile_header_background_color" value="'.$mobile_header_background_color.'" maxlength="7" class="inptxt" />
        <div id="mobile_header_background_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$mobile_header_background_color.';" ></div></div>
    </div>';   
    
    
    $mobile_header_icon_color          =  esc_html ( get_option('wp_estate_mobile_header_icon_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Mobile header icon color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Mobile header icon color','wpestate').'</div>    
        <input type="text" name="mobile_header_icon_color" value="'.$mobile_header_icon_color.'" maxlength="7" class="inptxt" />
        <div id="mobile_header_icon_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$mobile_header_icon_color.';" ></div></div>
    </div>';  
    
    
    $mobile_menu_font_color          =  esc_html ( get_option('wp_estate_mobile_menu_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Mobile menu font color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Mobile menu font color','wpestate').'</div>    
        <input type="text" name="mobile_menu_font_color" value="'.$mobile_menu_font_color.'" maxlength="7" class="inptxt" />
        <div id="mobile_menu_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$mobile_menu_font_color.';" ></div></div>
    </div>'; 
    
    
    $mobile_menu_hover_font_color    =esc_html(get_option('wp_estate_mobile_menu_hover_font_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Mobile menu hover font color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Mobile menu hover font color','wpestate').'</div>    
        <input type="text" name="mobile_menu_hover_font_color" value="'.$mobile_menu_hover_font_color.'" maxlength="7" class="inptxt" />
        <div id="mobile_menu_hover_font_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$mobile_menu_hover_font_color.';" ></div></div>
    </div>';
    
    
    $mobile_item_hover_back_color         =  esc_html ( get_option('wp_estate_mobile_item_hover_back_color','') );
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Mobile menu item hover background color','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Mobile menu item hover background color','wpestate').'</div>    
        <input type="text" name="mobile_item_hover_back_color" value="'.$mobile_item_hover_back_color.'" maxlength="7" class="inptxt" />
        <div id="mobile_item_hover_back_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$mobile_item_hover_back_color.';" ></div></div>
    </div>'; 
    

    $mobile_menu_backgound_color = esc_html(get_option('wp_estate_mobile_menu_backgound_color', ''));
    print'<div class="estate_option_row">
    <div class="label_option_row">' . esc_html__('Mobile menu background color', 'wpestate') . '</div>
    <div class="option_row_explain">' . esc_html__('Mobile menu background color', 'wpestate') . '</div>    
        <input type="text" name="mobile_menu_backgound_color" value="' .$mobile_menu_backgound_color. '" maxlength="7" class="inptxt" />
        <div id="mobile_menu_backgound_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$mobile_menu_backgound_color. ';" ></div></div>
    </div>';

    $mobile_menu_border_color = esc_html(get_option('wp_estate_mobile_menu_border_color', ''));
    print'<div class="estate_option_row">
    <div class="label_option_row">' . esc_html__('Mobile menu item border color', 'wpestate') . '</div>
    <div class="option_row_explain">' . esc_html__('Mobile menu item border color', 'wpestate') . '</div>    
        <input type="text" name="mobile_menu_border_color" value="' . $mobile_menu_border_color . '" maxlength="7" class="inptxt" />
        <div id="mobile_menu_border_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#' .$mobile_menu_border_color . ';" ></div></div>
    </div>';
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;














if( !function_exists('wpestate_print_page_design')):
function wpestate_print_page_design(){
   
    
    $yesno=array('yes','no');
         
            
    $print_show_subunits           =   wpestate_dropdowns_theme_admin($yesno,'print_show_subunits');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show subunits section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show subunits section in print page?','wpestate').'</div>    
        <select id="print_show_subunits" name="print_show_subunits">
            '.$print_show_subunits.'
        </select> 
    </div>';
    
    $print_show_agent           =   wpestate_dropdowns_theme_admin($yesno,'print_show_agent');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show agent details section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show agent details section in print page?','wpestate').'</div>    
        <select id="print_show_agent" name="print_show_agent">
            '.$print_show_agent.'
        </select> 
    </div>';
    
    $print_show_description           =   wpestate_dropdowns_theme_admin($yesno,'print_show_description');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show description section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show description section in print page?','wpestate').'</div>    
        <select id="print_show_description" name="print_show_description">
            '.$print_show_description.'
        </select> 
    </div>';
    
    
    $print_show_adress           =   wpestate_dropdowns_theme_admin($yesno,'print_show_adress');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show address section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show address section in print page?','wpestate').'</div>    
        <select id="print_show_adress" name="print_show_adress">
            '.$print_show_adress.'
        </select> 
    </div>';
    
    
    $print_show_details           =   wpestate_dropdowns_theme_admin($yesno,'print_show_details');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show details section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show details section in print page?','wpestate').'</div>    
        <select id="print_show_details" name="print_show_details">
            '.$print_show_details.'
        </select> 
    </div>';
    
    $print_show_features           =   wpestate_dropdowns_theme_admin($yesno,'print_show_features');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show features & amenities section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show features & amenities section in print page?','wpestate').'</div>    
        <select id="print_show_features" name="print_show_features">
            '.$print_show_features.'
        </select> 
    </div>';
    
    
    $print_show_floor_plans           =   wpestate_dropdowns_theme_admin($yesno,'print_show_floor_plans');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show floor plans section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show floor plans section in print page?','wpestate').'</div>    
        <select id="print_show_floor_plans" name="print_show_floor_plans">
            '.$print_show_floor_plans.'
        </select> 
    </div>';
    
    $print_show_images           =   wpestate_dropdowns_theme_admin($yesno,'print_show_images');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show gallery section','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Show gallery section in print page?','wpestate').'</div>    
        <select id="print_show_images" name="print_show_images">
            '.$print_show_images.'
        </select> 
    </div>';
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;



if(!function_exists('wpestate_user_dashboard_design')):
function wpestate_user_dashboard_design(){ 
    
   $cache_array                =   array('yes','no');   
   $show_header_dashboard      = wpestate_dropdowns_theme_admin($cache_array,'show_header_dashboard');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Show Header in Dashboard ?','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Enable or disable header in dashboard. The header will always be wide & type1 !','wpestate').'</div>    
       <select id="show_header_dashboard" name="show_header_dashboard">
            '.$show_header_dashboard.'
        </select>
    </div>';
    
    $user_dashboard_menu_color  =  esc_html ( get_option('wp_estate_user_dashboard_menu_color','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('User Dashboard Menu Color','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('User Dashboard Menu Color','wpestate').'</div>    
            <input type="text" name="user_dashboard_menu_color" value="'.$user_dashboard_menu_color.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_menu_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_menu_color.';" ></div></div>
        </div>';
    
    
    $user_dashboard_menu_hover_color      =  esc_html ( get_option('wp_estate_user_dashboard_menu_hover_color','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('User Dashboard Menu Hover Color','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('User Dashboard Menu Hover Color','wpestate').'</div>    
            <input type="text" name="user_dashboard_menu_hover_color" value="'.$user_dashboard_menu_hover_color.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_menu_hover_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_menu_hover_color.';" ></div></div>
        </div>';  
    
    $user_dashboard_menu_color_hover  =  esc_html ( get_option('wp_estate_user_dashboard_menu_color_hover','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('User Dashboard Menu Item Background Color','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('User Dashboard Menu Item Background Color','wpestate').'</div>    
            <input type="text" name="user_dashboard_menu_color_hover" value="'.$user_dashboard_menu_color_hover.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_menu_color_hover" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_menu_color_hover.';" ></div></div>
        </div>';
    
    $user_dashboard_menu_back      =  esc_html ( get_option('wp_estate_user_dashboard_menu_back ','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('User Dashboard Menu Background','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('User Dashboard Menu Background','wpestate').'</div>    
            <input type="text" name="user_dashboard_menu_back" value="'.$user_dashboard_menu_back.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_menu_back" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_menu_back .';" ></div></div>
        </div>';
    
    
    $user_dashboard_package_back      =  esc_html ( get_option('wp_estate_user_dashboard_package_back ','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('User Dashboard Package Background','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('User Dashboard Package Background','wpestate').'</div>    
            <input type="text" name="user_dashboard_package_back" value="'.$user_dashboard_package_back.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_package_back" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_package_back .';" ></div></div>
        </div>';
    
    $user_dashboard_package_color     =  esc_html ( get_option('wp_estate_user_dashboard_package_color ','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('User Dashboard Package Color','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('User Dashboard Package Color','wpestate').'</div>    
            <input type="text" name="user_dashboard_package_color" value="'.$user_dashboard_package_color.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_package_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_package_color .';" ></div></div>
        </div>';
    
    
    $user_dashboard_buy_package     =  esc_html ( get_option('wp_estate_user_dashboard_buy_package','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Dashboard Buy Package Select Background','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Dashboard Package Selected','wpestate').'</div>    
            <input type="text" name="user_dashboard_buy_package" value="'.$user_dashboard_buy_package.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_buy_package" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_buy_package .';" ></div></div>
        </div>';
    
    $user_dashboard_package_select     =  esc_html ( get_option('wp_estate_user_dashboard_package_select','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Dashboard Package Select','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Dashboard Package Select','wpestate').'</div>    
            <input type="text" name="user_dashboard_package_select" value="'.$user_dashboard_package_select.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_package_select" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_package_select .';" ></div></div>
        </div>';
    
    $user_dashboard_content_back     =  esc_html ( get_option('wp_estate_user_dashboard_content_back ','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Content Background Color','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Content Background Color','wpestate').'</div>    
            <input type="text" name="user_dashboard_content_back" value="'.$user_dashboard_content_back.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_content_back" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_content_back .';" ></div></div>
        </div>';
    
    $user_dashboard_content_button_back     =  esc_html ( get_option('wp_estate_user_dashboard_content_button_back  ','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Content Button Background','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Content Button Background','wpestate').'</div>    
            <input type="text" name="user_dashboard_content_button_back" value="'.$user_dashboard_content_button_back.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_content_button_back" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_content_button_back .';" ></div></div>
        </div>';
    
    $user_dashboard_content_color     =  esc_html ( get_option('wp_estate_user_dashboard_content_color','') );
    print'<div class="estate_option_row">
        <div class="label_option_row">'.esc_html__('Content Text Color','wpestate').'</div>
        <div class="option_row_explain">'.esc_html__('Content Text Color','wpestate').'</div>    
            <input type="text" name="user_dashboard_content_color" value="'.$user_dashboard_content_color.'" maxlength="7" class="inptxt" />
            <div id="user_dashboard_content_color" class="colorpickerHolder" ><div class="sqcolor" style="background-color:#'.$user_dashboard_content_color .';" ></div></div>
        </div>';
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;










if(!function_exists('wpestate_new_property_submission_tab') ):
function wpestate_new_property_submission_tab(){
    
   
   
    $all_submission_fields  =   wpestate_return_all_fields();
    $all_mandatory_fields   =   wpestate_return_all_fields(1);
  
    
    $submission_page_fields =   ( get_option('wp_estate_submission_page_fields','') );
    if(is_array($submission_page_fields)){
        $submission_page_fields =   array_map("wpestate_strip_array",$submission_page_fields);
    }

       
   


    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Select the Fields for property submission.','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Use CTRL to select multiple fields for property submission page.','wpestate').'</div>    

        <select id="submission_page_fields" name="submission_page_fields[]" multiple="multiple" style="height:400px">';

        foreach ($all_submission_fields as $key=>$value){
            print '<option value="'.$key.'"';
            if (is_array($submission_page_fields) && in_array($key, $submission_page_fields) ){
                print ' selected="selected" ';
            }
            print '>'.$value.'</option>';
        }    

        print'
        </select>

    </div>';

        
    $mandatory_fields           =   ( get_option('wp_estate_mandatory_page_fields','') );
    
    if(is_array($mandatory_fields)){
        $mandatory_fields           =   array_map("wpestate_strip_array",$mandatory_fields);
    }
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.esc_html__('Select the Mandatory Fields for property submission.','wpestate').'</div>
    <div class="option_row_explain">'.esc_html__('Make sure the mandatory fields for property submission page are part of submit form (managed from the above setting). Use CTRL for multiple fields select..','wpestate').'</div>    

        <select id="mandatory_page_fields" name="mandatory_page_fields[]" multiple="multiple" style="height:400px">';

        foreach ($all_mandatory_fields as $key=>$value){
       
            print '<option value="'.stripslashes($key).'"';
            if (is_array($mandatory_fields) && in_array( addslashes($key), $mandatory_fields) ){
                print ' selected="selected" ';
            }
            print '>'.$value.'</option>';
        }    

        print'
        </select>

    </div>';
    
    
    print '<input type="hidden" value="1" name="is_submit_page"> <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.esc_html__('Save Changes','wpestate').'" />
    </div>';
}
endif;



?>