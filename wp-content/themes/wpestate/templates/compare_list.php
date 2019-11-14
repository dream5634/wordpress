<?php 
$compare_submit =   wpestate_get_compare_link();
global $leftcompare;
$left_class='';


?>
<!--Compare Starts here-->     
<div class="prop-compare <?php echo esc_html($left_class); ?>">
    <div id="compare_close"><i class="fa fa-times" aria-hidden="true"></i></div>
  
        <h4><?php _e('Compare','wpestate')?></h4>
        <button   id="submit_compare" class="wpresidence_button"> <?php esc_html_e('Compare','wpestate');?> </button>
        <?php
        $ajax_nonce = wp_create_nonce( "wpestate_submit_compare_nonce" );
        print'<input type="hidden" id="wpestate_submit_compare_nonce" value="'.esc_html($ajax_nonce).'" />    ';
        ?>
 
</div>    
<!--Compare Ends here-->  