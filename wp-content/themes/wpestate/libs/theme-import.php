<?php
if( !function_exists('wpestate_new_import') ):
function wpestate_new_import() {  
    
    $allowed_html   =   array();  
    $active_tab = isset( $_GET[ 'tab' ] ) ? wp_kses( $_GET[ 'tab' ],$allowed_html ) : 'general_settings';  
    ?>

        
    <div class="wrap">
        <div class="container demo-wrapper">
            <div class="wrap-topbar"><span id="title_demo_import"><?php esc_html_e('WpEstate Demo Import','wpestate');?></span></div>
            <div id="upload-container">       
                    <span class="upload_details">
                        <?php 
                        if ( WP_MEMORY_LIMIT < 96 ) { 
                            print '<div class="error">
                                <p>'.esc_html__( 'Wordpress Memory Limit is set to ', 'wpestate' ).' '.WP_MEMORY_LIMIT.'. '.esc_html__( 'Because of that import functions may not work corectly. Recommended memory limit should be at least 96MB. Please refer to : ','wpestate').'<a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">'.esc_html__('Increasing memory allocated to PHP','wpestate').'</a></p>
                            </div>';
                        }
                        
                        $execution_time =intval ( ini_get('max_execution_time'));
                        if ( $execution_time < 180 ) { 
                            print '<div class="error">
                                <p>'.esc_html__( 'Your server maximum execution time is set to ', 'wpestate' ).' '.$execution_time.'. '.esc_html__( ' Because of that import functions may not work correctly. You should have at least 180 sec. Please address this item with your hosting provider.','wpestate').'</p>
                            </div>';
                        }
                    
                      
                          
                        $post_max_size =intval (wpestate_file_upload_max_size() );
                        if ( $post_max_size < 32000000  ) { 
                            print '<div class="error">
                                <p>'.esc_html__( 'Your server post_max_size is lower than 32M. Because of that import functions may not work correctly. Please correct this with your hosting provider.', 'wpestate' ).'</p>
                            </div>';
                        }
                        
                        $upload_max_filesize =intval( ini_get('upload_max_filesize'));
                    
                        if ( $upload_max_filesize < 32  ) { 
                            print '<div class="error">
                                <p>'.esc_html__( 'Your server upload_max_filesize is lower than 32M. Because of that import functions may not work correctly. Please correct this with your hosting provider.', 'wpestate' ).'</p>
                            </div>';
                        }
                      
                        
                        ?>
                        
                        <?php esc_html_e ('DO NOT import a demo if you already have content. All your settings will be replaced with demo settings.','wpestate');?></br>
                        <?php esc_html_e('We recommend importing only 1 demo zip for correct demo setup. If you wish a different demo, clear database and import the new demo after.','wpestate'); ?></br>
                        <?php esc_html_e('After you import the content you may need to edit certain pages and assign the correct category id for some shortcodes','wpestate'); ?>
                    </span>
                    <?php wpestate_get_list_of_templates();?>
                
                   
            </div>  
        
        <?php wp_nonce_field('wpestate_install_template_nonce', 'wpestate_install_template_security'); ?>
            
      
    </div>
</div>


<?php
}
endif; // end   wpestate_new_general_set  


add_action( 'wp_ajax_nopriv_wpestate_manage_demo_import', 'wpestate_manage_demo_import' );  
add_action( 'wp_ajax_wpestate_manage_demo_import', 'wpestate_manage_demo_import' );


    function wpestate_manage_demo_import(){
        
    
    }

    

function wpestate_get_list_of_templates() {
    $upload_dir = wp_upload_dir();

        
    $base_template_dir_scan     =   get_template_directory(). '/wpestate_templates/*';  
    $base_template_url          =   get_template_directory_uri(). '/wpestate_templates/';  
    $base_template_dir          =   get_template_directory(). '/wpestate_templates/';  
    
    $templates = array_filter(glob($base_template_dir_scan), 'is_dir');
        
        if ($templates) {
            foreach ($templates as $template) {
             
                echo '
                    <div class="template-item">
                   
                       
                        <div class="template-wrapper">
                            <img src="' . $base_template_url . basename($template) . '/preview.jpg" alt="' . basename($template) . '">
                            
                            <div class="activate_wrapper">
                                <div class="activate_template" data-baseid="'.basename($template).'">'.esc_html__('Install','wpestate').'</div>    
                              
                                <div class="importing_mess">'.esc_html__('click activate in order to import','wpestate').'</div>    
                            </div>
                        </div>
                        
                       
                    </div>';
            }
            $ajax_nonce = wp_create_nonce( "wpestate_activate_template_nonce" );
            print'<input type="hidden" id="wpestate_activate_template_nonce" value="'.esc_html($ajax_nonce).'" />    ';
        
        } 
        else {
            echo '<span class="import_mess">'.esc_html__('There are no imported templates','wpestate').'</span>';
        }
    }

 
?>