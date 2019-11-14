<div class="mobile_header">
    <div class="mobile-trigger"><i class=" fa fa-bars"></i></div>
    <div class="mobile-logo">
        <a href="<?php echo esc_url(home_url('','login'));?>">
        <?php
            $mobilelogo              =   esc_html( get_option('wp_estate_mobile_logo_image','') );
            if ( $mobilelogo!='' ){
               print '<img src="'.esc_url($mobilelogo).'" class="img-responsive retina_ready " alt="'.esc_attr__('logo','wpestate').'"/>';	
            } else {
               print '<img class="img-responsive retina_ready" src="'. get_template_directory_uri().'/img/logo_mobile.png" alt="'.esc_attr__('logo','wpestate').'"/>';
            }
        ?>
        </a>
    </div>  
    
    <?php 
    if(esc_html ( get_option('wp_estate_show_top_bar_user_login','') )=="yes"){
    ?>
        <div class="mobile-trigger-user">
            <?php
                $current_user               =   wp_get_current_user();
                if ( 0 != $current_user->ID  && is_user_logged_in() ) {
                    $user_custom_picture        =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
                    $user_small_picture_id      =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
                    if( $user_small_picture_id == '' ){

                        $user_small_picture[0]=get_template_directory_uri().'/img/default_user_small.png';
                    }else{
                        $user_small_picture=wp_get_attachment_image_src($user_small_picture_id,'user_thumb');

                    }
                    print '<div class="menu_user_picture" style="background-image: url('.esc_url($user_small_picture[0]).');"></div>';
                }else{
                    print ' <i class=" fa fa-cogs"></i>';
                }

            ?>
           
        </div>
    <?php } ?>
</div>