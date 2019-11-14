<?php 
$current_user           =   wp_get_current_user();
$userID                 =   $current_user->ID;
$user_login             =   $current_user->user_login;  
$add_link               =   wpestate_get_dasboard_add_listing();
$dash_profile           =   wpestate_get_dashboard_profile_link();
$dash_favorite          =   wpestate_get_dashboard_favorites();
$dash_link              =   wpestate_get_dashboard_link();
$dash_searches          =   wpestate_get_searches_link();
$activeprofile          =   '';
$activedash             =   '';
$activeadd              =   '';
$activefav              =   '';
$activesearch           =   '';
$activeinvoices         =   '';
$user_pack              =   get_the_author_meta( 'package_id' , $userID );    
$clientId               =   esc_html( get_option('wp_estate_paypal_client_id','') );
$clientSecret           =   esc_html( get_option('wp_estate_paypal_client_secret','') );  
$user_registered        =   get_the_author_meta( 'user_registered' , $userID );
$user_package_activation=   get_the_author_meta( 'package_activation' , $userID );
$home_url               =   esc_url( home_url('/') );
$dash_invoices          =   wpestate_get_invoice_link();

if ( basename( get_page_template() ) == 'user_dashboard.php' ){
    $activedash  =   'user_tab_active';    
}else if ( basename( get_page_template() ) == 'user_dashboard_profile.php' ){
    $activeprofile   =   'user_tab_active';
}else if ( basename( get_page_template() ) == 'user_dashboard_favorite.php' ){
    $activefav   =   'user_tab_active';
}else if( basename( get_page_template() ) == 'user_dashboard_searches.php' ){
    $activesearch  =   'user_tab_active';
}else if( basename( get_page_template() ) == 'user_dashboard_invoices.php' ){
    $activeinvoices  =   'user_tab_active';
}
?>


<div class="user_tab_menu">

    <div class="user_dashboard_links">
        <?php if( $dash_profile!=$home_url ){ ?>
            <a href="<?php print esc_url($dash_profile);?>"  class="<?php print esc_html($activeprofile); ?>"> <?php esc_html_e('My Profile','wpestate');?></a>
        <?php } ?>
        <?php if( $dash_link!=$home_url ){ ?>
            <a href="<?php print esc_url($dash_link);?>"     class="<?php print esc_html($activedash); ?>"> <?php esc_html_e('My Properties List','wpestate');?></a>
        <?php } ?>
        <?php if( $dash_favorite!=$home_url ){ ?>
            <a href="<?php print esc_url($dash_favorite);?>" class="<?php print esc_html($activefav); ?>"> <?php esc_html_e('Favorites','wpestate');?></a>
        <?php } ?>
        <?php if( $dash_searches!=$home_url ){ ?>
            <a href="<?php print esc_url($dash_searches);?>" class="<?php print esc_html($activesearch); ?>"> <?php esc_html_e('Saved Searches','wpestate');?></a>
        <?php } 
        if( $dash_invoices!=$home_url ){ ?>
            <a href="<?php print esc_url($dash_invoices);?>" class="<?php print esc_html($activeinvoices); ?>"> <?php esc_html_e('My Invoices','wpestate');?></a>
        <?php } ?>
     
        <a href="<?php echo wp_logout_url( esc_url( home_url('/') ) );?>" title="Logout"> <?php esc_html_e('Log Out','wpestate');?></a>
    </div>
    
</div>