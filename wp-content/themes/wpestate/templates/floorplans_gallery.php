<?php
global $lightbox;
?>
<div class="lightbox_property_wrapper_floorplans">
    
    <div class="lightbox_property_wrapper_level2">
        
        <div class="lightbox_property_content row">
            <div class="lightbox_property_slider col-md-12">
                <?php echo '<div  id="owl-demo-floor" class="owl-carousel owl-theme">'.$lightbox.'</div>';//escaped above ?>
            </div>
        </div>
       
        <div class="lighbox-image-close-floor">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
    
    </div>
    
    <div class="lighbox_overlay"></div>    
</div>