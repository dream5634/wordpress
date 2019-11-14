<?php





if (!function_exists('wpestate_select_email_type')):
    function wpestate_select_email_type($user_email,$type,$arguments){
        $value          =   get_option('wp_estate_'.$type,'');
        $value_subject  =   get_option('wp_estate_subject_'.$type,'');  
            
        if (function_exists('icl_translate') ){
            $value          =  icl_translate('wpestate','wp_estate_email_'.$value, $value ) ;
            $value_subject  =  icl_translate('wpestate','wp_estate_email_subject_'.$value_subject, $value_subject ) ;
        }

        wpestate_emails_filter_replace($user_email,$value,$value_subject,$arguments);
    }
endif;


if( !function_exists('wpestate_emails_filter_replace')):
    function  wpestate_emails_filter_replace($user_email,$message,$subject,$arguments){
        $arguments ['website_url'] = get_option('siteurl');
        $arguments ['website_name'] = get_option('blogname');       
        $arguments ['user_email'] = $user_email;     
        $user= get_user_by('email',$user_email);
        $arguments ['username'] = $user-> user_login;
        
        foreach($arguments as $key_arg=>$arg_val){
            $subject = str_replace('%'.$key_arg, $arg_val, $subject);
            $message = str_replace('%'.$key_arg, $arg_val, $message);
        }
        if(function_exists('wpestate_send_emails')){
        wpestate_send_emails($user_email, $subject, $message );    
        }
    }
endif;








if( !function_exists('wpestate_email_management') ):
    function wpestate_email_management(){
        print '<div class="wpestate-tab-container">';
        print '<h1 class="wpestate-tabh1">'.esc_html__('Email Management','wpestate').'</h1>';
        print '<a href="http://help.wpresidence.net/#" target="_blank" class="help_link">'.esc_html__('help','wpestate').'</a>';


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
            'downgrade_warning'         =>  esc_html__('Downgrade Warning','wpestate'),
            'free_listing_expired'      =>  esc_html__('Free Listing Expired','wpestate'),
            'new_listing_submission'    =>  esc_html__('New Listing Submission','wpestate'),
            'listing_edit'              =>  esc_html__('Listing Edit','wpestate'),
            'recurring_payment'         =>  esc_html__('Recurring Payment','wpestate'),
            'membership_activated'      =>  esc_html__('Membership Activated','wpestate'),
            'agent_update_profile'      =>  esc_html__('Update Profile','wpestate'),
           
           
        );
        
        
        print '<div class="email_row">'.esc_html__('Global variables: %website_url as website url,%website_name as website name, %user_email as user_email, %username as username','wpestate').'</div>';
        
        
        foreach ($emails as $key=>$label ){

            print '<div class="email_row">';
            $value          = stripslashes( get_option('wp_estate_'.$key,'') );
            $value_subject  = stripslashes( get_option('wp_estate_subject_'.$key,'') );

            print '<label for="subject_'.$key.'">'.esc_html__('Subject for','wpestate').' '.$label.'</label>';
            print '<input type="text" name="subject_'.$key.'" value="'.$value_subject.'" />';

            print '<label for="'.$key.'">'.esc_html__('Content for','wpestate').' '.$label.'</label>';
            print '<textarea rows="10" name="'.$key.'">'.$value.'</textarea>';
            print '<div class="extra_exp"> '.wpestate_emails_extra_details($key).'</div>';
            print '</div>';

        }

        print'<p class="submit">
               <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__('Save Changes','wpestate').'" />
            </p>';

        print '</div>';   
    }
endif;


if( !function_exists('wpestate_emails_extra_details') ):
    function wpestate_emails_extra_details($type){
        $return_string='';
        switch ($type) {
            case "new_user":
                    $return_string=esc_html__('%user_login_register as new username, %user_pass_register as user password, %user_email_register as new user email' ,'wpestate');
                    break;
                
            case "admin_new_user":
                    $return_string=esc_html__('%user_login_register as new username and %user_email_register as new user email' ,'wpestate');
                    break;
                
            case "password_reset_request":
                    $return_string=esc_html__('%reset_link as reset link','wpestate');
                    break;
                
            case "password_reseted":
                    $return_string=esc_html__('%user_pass as user password','wpestate');
                    break;
                
            case "purchase_activated":
                    $return_string='';
                    break;
                
            case "approved_listing":
                    $return_string=esc_html__('* you can use %post_id as listing id, %property_url as property url and %property_title as property name','wpestate');
                    break;

            case "new_wire_transfer":
                    $return_string=  esc_html__('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpestate');
                    break;
            
            case "admin_new_wire_transfer":
                    $return_string=  esc_html__('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpestate');
                    break;    
                
            case "admin_expired_listing":
                    $return_string=  esc_html__('* you can use %submission_title as property title number, %submission_url as property submission url','wpestate');
                    break;  
                
            case "matching_submissions":
                    $return_string=  esc_html__('* you can use %matching_submissions as matching submissions list','wpestate');
                    break;
                
            case "paid_submissions":  
                    $return_string= '';
                    break;
                
            case  "featured_submission":
                    $return_string=  '';
                    break;

            case "account_downgraded":   
                    $return_string=  '';
                    break;
                
            case "free_listing_expired":
                    $return_string=  esc_html__('* you can use %expired_listing_url as expired listing url and %expired_listing_name as expired listing name','wpestate');
                    break;
                
            case "new_listing_submission":
                    $return_string=  esc_html__('* you can use %new_listing_title as new listing title and %new_listing_url as new listing url','wpestate');
                    break;
                
            case "listing_edit":
                    $return_string=  esc_html__('* you can use %editing_listing_title as editing listing title and %editing_listing_url as editing listing url','wpestate');
                    break;
                
            case "recurring_payment":  
                    $return_string=  esc_html__('* you can use %recurring_pack_name as recurring packacge name and %merchant as merchant name','wpestate');
                    break;
                
            case "membership_activated":  
                    $return_string=  '';
                    break;    
            case "agent_update_profile":  
                    $return_string=  esc_html__('* you can use %user_profile as user name and %user_email_profile as email','wpestate');
                    break;
        
                
                
        }
        return $return_string;
    }
endif;