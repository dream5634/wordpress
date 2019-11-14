<?php
$current_user           =   wp_get_current_user();
$userID                 =   $current_user->ID;
$user_login             =   $current_user->user_login;
$first_name             =   get_the_author_meta( 'first_name' , $userID );
$last_name              =   get_the_author_meta( 'last_name' , $userID );
$user_email             =   get_the_author_meta( 'user_email' , $userID );
$user_mobile            =   get_the_author_meta( 'mobile' , $userID );
$user_phone             =   get_the_author_meta( 'phone' , $userID );
$description            =   get_the_author_meta( 'description' , $userID );
$facebook               =   get_the_author_meta( 'facebook' , $userID );
$twitter                =   get_the_author_meta( 'twitter' , $userID );
$linkedin               =   get_the_author_meta( 'linkedin' , $userID );
$pinterest              =   get_the_author_meta( 'pinterest' , $userID );
$userinstagram          =   get_the_author_meta( 'instagram' , $userID );
$user_skype             =   get_the_author_meta( 'skype' , $userID );
$website                =   get_the_author_meta( 'website' , $userID );


$user_title             =   get_the_author_meta( 'title' , $userID );
$user_custom_picture    =   get_the_author_meta( 'custom_picture' , $userID );
$user_small_picture     =   get_the_author_meta( 'small_custom_picture' , $userID );
$image_id               =   get_the_author_meta( 'small_custom_picture',$userID); 
$about_me               =   get_the_author_meta( 'description' , $userID );
if($user_custom_picture==''){
    $user_custom_picture=get_template_directory_uri().'/img/default_user.png';
}
?>

<div class="col-md-12 user_profile_div"> 
    <div id="profile_message">
        </div> 
<div class="add-estate profile-page profile-onprofile row"> 
    
    <div class="col-md-4 profile_label">
        <div class="user_details_row"><?php esc_html_e('Photo','wpestate');?></div> 
        <div class="user_profile_explain"><?php esc_html_e('Upload your profile photo.','wpestate')?></div>
    </div>

    <div class="profile_div col-md-4" id="profile-div">
        <?php print '<img id="profile-image" src="'.esc_url($user_custom_picture).'" alt="'.esc_attr__('thumb','wpestate').'" data-profileurl="'.esc_attr($user_custom_picture).'" data-smallprofileurl="'.intval($image_id).'" >';
        ?>

        <div id="upload-container">                 
            <div id="aaiu-upload-container">                 

                <button id="aaiu-uploader" class="wpresidence_button wpresidence_success"><?php esc_html_e('Upload  profile image.','wpestate');?></button>
                <div id="aaiu-upload-imagelist">
                    <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                </div>
            </div>  
        </div>
        <span class="upload_explain"><?php esc_html_e('*minimum 500px x 500px','wpestate');?></span>                    
    </div>
</div>

<div class="add-estate profile-page profile-onprofile row"> 
    <div class="col-md-4 profile_label">
        <div class="user_details_row"><?php esc_html_e('User Details','wpestate');?></div> 
        <div class="user_profile_explain"><?php esc_html_e('Add your contact information.','wpestate')?></div>

    </div>
          
    <div class="col-md-4">
        <p>
            <label for="firstname"><?php esc_html_e('First Name','wpestate');?></label>
            <input type="text" id="firstname" class="form-control" value="<?php echo esc_html($first_name);?>"  name="firstname">
        </p>

        <p>
            <label for="secondname"><?php esc_html_e('Last Name','wpestate');?></label>
            <input type="text" id="secondname" class="form-control" value="<?php echo esc_html($last_name);?>"  name="firstname">
        </p>
        <p>
            <label for="useremail"><?php esc_html_e('Email','wpestate');?></label>
            <input type="text" id="useremail"  class="form-control" value="<?php echo esc_html($user_email);?>"  name="useremail">
        </p>
    </div>  

    <div class="col-md-4">
        <p>
            <label for="userphone"><?php esc_html_e('Phone', 'wpestate'); ?></label>
            <input type="text" id="userphone" class="form-control" value="<?php echo esc_html($user_phone); ?>"  name="userphone">
        </p>
        <p>
            <label for="usermobile"><?php esc_html_e('Mobile', 'wpestate'); ?></label>
            <input type="text" id="usermobile" class="form-control" value="<?php echo esc_html($user_mobile); ?>"  name="usermobile">
        </p>

        <p>
            <label for="userskype"><?php esc_html_e('Skype', 'wpestate'); ?></label>
            <input type="text" id="userskype" class="form-control" value="<?php echo esc_html($user_skype); ?>"  name="userskype">
        </p>
        <?php   wp_nonce_field( 'profile_ajax_nonce', 'security-profile' );   ?>
       
    </div>
</div>
                             
<div class="add-estate profile-page profile-onprofile row">       
    <div class="col-md-4 profile_label">
        <div class="user_details_row"><?php esc_html_e('User Details','wpestate');?></div> 
        <div class="user_profile_explain"><?php esc_html_e('Add your social media information.','wpestate')?></div>

    </div>
    <div class="col-md-4">
        <p>
            <label for="userfacebook"><?php esc_html_e('Facebook Url', 'wpestate'); ?></label>
            <input type="text" id="userfacebook" class="form-control" value="<?php echo esc_html($facebook); ?>"  name="userfacebook">
        </p>

        <p>
            <label for="usertwitter"><?php esc_html_e('Twitter Url', 'wpestate'); ?></label>
            <input type="text" id="usertwitter" class="form-control" value="<?php echo esc_html($twitter); ?>"  name="usertwitter">
        </p>

        <p>
            <label for="userlinkedin"><?php esc_html_e('Linkedin Url', 'wpestate'); ?></label>
            <input type="text" id="userlinkedin" class="form-control"  value="<?php echo esc_html($linkedin); ?>"  name="userlinkedin">
        </p>
    </div>
    <div class="col-md-4">
        <p>
            <label for="userinstagram"><?php esc_html_e('Instagram Url','wpestate');?></label>
            <input type="text" id="userinstagram" class="form-control" value="<?php echo esc_html($userinstagram);?>"  name="userinstagram">
        </p> 

        <p>
            <label for="userpinterest"><?php esc_html_e('Pinterest Url','wpestate');?></label>
            <input type="text" id="userpinterest" class="form-control" value="<?php echo esc_html($pinterest);?>"  name="userpinterest">
        </p> 

        <p>
            <label for="website"><?php esc_html_e('Website Url (without http)','wpestate');?></label>
            <input type="text" id="website" class="form-control" value="<?php echo esc_html($website);?>"  name="website">
        </p>
    </div> 
</div>

<div class="add-estate profile-page profile-onprofile row">
    <div class="col-md-4 profile_label">
        <div class="user_details_row"><?php esc_html_e('User Details','wpestate');?></div> 
        <div class="user_profile_explain"><?php esc_html_e('Add some information about yourself.','wpestate')?></div>
    </div>
    <div class="col-md-8">
         <p>
            <label for="usertitle"><?php esc_html_e('Title/Position','wpestate');?></label>
            <input type="text" id="usertitle" class="form-control" value="<?php echo esc_html($user_title);?>"  name="usertitle">
        </p>

         <p>
            <label for="about_me"><?php esc_html_e('About Me','wpestate');?></label>
            <textarea id="about_me" class="form-control" name="about_me"><?php echo esc_textarea($about_me);?></textarea>
        </p>
        <p class="fullp-button">
            <button class="wpresidence_button" id="update_profile"><?php esc_html_e('Update profile', 'wpestate'); ?></button>
        </p>
    </div>
    
            
</div>
      
<div class="add-estate profile-page profile-onprofile row"> 
    <div class="col-md-4 profile_label">
        <div class="change_pass"><?php esc_html_e('Change Password','wpestate');?></div> 
        <div class="pass_note"><?php esc_html_e('*After you change the password you will have to login again.','wpestate')?></div>

    </div>  
    <div class="col-md-8 dashboard_password">
        <p  class="col-md-12">
            <label for="oldpass"><?php esc_html_e('Old Password','wpestate');?></label>
            <input  id="oldpass" value=""  class="form-control" name="oldpass" type="password">
        </p>

        <p  class="col-md-6">
            <label for="newpass"><?php esc_html_e('New Password ','wpestate');?></label>
            <input  id="newpass" value="" class="form-control" name="newpass" type="password">
        </p>
        <p  class="col-md-6">
            <label for="renewpass"><?php esc_html_e('Confirm New Password','wpestate');?></label>
            <input id="renewpass" value=""  class="form-control" name="renewpass"type="password">
        </p>

        <?php   $ajax_nonce = wp_create_nonce( "wpestate_renew_pass_nonce" );
        print'<input type="hidden" id="wpestate_renew_pass_nonce" value="'.esc_html($ajax_nonce).'" />    ';  ?>
        <p class="fullp-button">
            <button class="wpresidence_button" id="change_pass"><?php esc_html_e('Reset Password','wpestate');?></button>

        </p>
    </div>
</div>   
</div>