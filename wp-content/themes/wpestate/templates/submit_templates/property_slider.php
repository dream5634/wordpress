<?php
global $option_slider;
?>

<div class="submit_container">  
<div class="submit_container_header"><?php esc_html_e('Slider Option','wpestate');?></div>

   <p class="col-md-12">
       <label for="prop_slider_type"><?php esc_html_e('Slider type ','wpestate');?></label>
       <select id="prop_slider_type" name="prop_slider_type" class="select-submit2">
           <?php print esc_html($option_slider);?>
       </select>
    </p>
</div>
