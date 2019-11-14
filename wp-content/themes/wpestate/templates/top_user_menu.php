<?php
$current_user               =   wp_get_current_user();
$user_custom_picture        =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
$user_small_picture_id      =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
if( $user_small_picture_id == '' ){
    $user_small_picture[0]=get_template_directory_uri().'/img/default_user_small.png';
}else{
    $user_small_picture=wp_get_attachment_image_src($user_small_picture_id,'agent_picture_thumb');
    
}

    if(is_user_logged_in()){ ?>   
        <div class="user_menu user_loged" id="user_menu_u">
         
            <span class="your_menu">
                <?php esc_html_e('Menu','wpestate');?>
            </span>    
            <div class="menu_user_picture" style="background-image: url('<?php echo esc_url($user_small_picture[0]); ?>');"></div>
        </div>     
    <?php 
    }else{ 
    ?>
    <div class="user_menu" id="user_menu_u">   
        <div class="submit_action">
            <?php esc_html_e('Submit Property','wpestate');?>
        </div>
    </div>     
<?php } ?>   
                  
    
        
        
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
    <ul id="user_menu_open" class="user_menu_topbar_open dropdown-menu menulist topmenux" role="menu" aria-labelledby="user_menu_trigger"> 
       <h5 class="user-login-title"><?php esc_html_e('User Account','wpestate');?></h5>
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
           
       
        <li role="presentation" class="divider"></li>
        <li role="presentation"><a href="<?php echo wp_logout_url( esc_url( home_url('/') ));?>" title="Logout" class="menulogout"><?php esc_html_e('Log Out','wpestate');?></a></li>
    </ul>
    
<?php }?>