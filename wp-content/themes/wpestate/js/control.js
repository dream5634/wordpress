/*global $, jQuery, ajaxcalls_vars, document, control_vars, window,Modernizr,wpestate_map_shortcode_function,map,google*/
var width,height,prop_title_zone_top,prop_title_zone_top_off,is_pop;
width   = jQuery(window).width();
height  = jQuery(window).height();
is_pop  =0;


prop_title_zone_top_off=8000;
if(jQuery('.listing-content').length  > 0){
    prop_title_zone_top     =   jQuery('.listing-content').offset();
    prop_title_zone_top_off =   prop_title_zone_top.top;
 }

jQuery(window).scroll(function () {
    "use strict";
    var switch_logo;
    var scroll                  = jQuery(window).scrollTop();
  
    if ( scroll >prop_title_zone_top_off  ) {
        if ( !Modernizr.mq('only all and (max-width: 1180px)')) {
            jQuery(".prop_title_zone_menu").addClass('prop_title_zone_menu_fixed');
            setTimeout(function(){ 
                var header_height=jQuery('.header_wrapper_inside').height();
                if(jQuery('#'+'wpadminbar').length  > 0){
           
                    header_height=header_height+32;
                }
                
                jQuery(".prop_title_zone_menu").css('top',header_height+"px");
            }, 300); 
                
        }
    }else {
        jQuery(".prop_title_zone_menu").removeClass('prop_title_zone_menu_fixed');
        jQuery(".prop_title_zone_menu").removeAttr('style');
    }
    
  
        
    if (scroll >=100) {
 
        if (!Modernizr.mq('only all and (max-width: 1024px)')) {        
            jQuery(".master_header").addClass("master_header_sticky");
            jQuery('.logo').addClass('miclogo');
            switch_logo = jQuery('.header_wrapper_inside').attr('data-sticky-logo');
           
            if( switch_logo!=='' ){
                jQuery('#logo_image').attr('src',switch_logo);
            }

            if( !jQuery(".header_wrapper").hasClass('header_type4') ){
                jQuery(".header_wrapper").addClass("navbar-fixed-top");
                jQuery(".header_wrapper").addClass("customnav");
            }
            //jQuery('.barlogo').show();
            jQuery('.user_menu_topbar_open').hide();
            //jQuery('.navicon-button').removeClass('opensvg');
        }
        jQuery('.contact-box').addClass('islive');
        jQuery('.backtop').addClass('islive');
    } else {
        jQuery(".master_header").removeAttr('style');
        jQuery(".master_header").removeClass("master_header_sticky");
        jQuery(".header_wrapper").removeClass("navbar-fixed-top");
        jQuery(".header_wrapper").removeClass("customnav");
        jQuery('.contact-box ').removeClass('islive');
        jQuery('.backtop').removeClass('islive');
        jQuery('.contactformwrapper').addClass('hidden');
        jQuery('.barlogo').hide();
        jQuery('.user_menu_topbar_open').hide();
        jQuery('.logo').removeClass('miclogo');
        switch_logo = jQuery('.header_wrapper_inside').attr('data-sticky-logo');
        if( switch_logo!=='' ){
            switch_logo = jQuery('.header_wrapper_inside').attr('data-logo');
            jQuery('#logo_image').attr('src',switch_logo);
        }
    }
});



jQuery(window).resize(function() {
    "use strict";    
    // check because crome mobile trigger resize event on  scroll
    if(jQuery(window).width() != width ){
        jQuery('#mobile_menu').hide('10');
    }
    wpestate_half_map_responsive();
   
});


function wpestate_half_map_responsive(){
      "use strict";
    if (Modernizr.mq('only screen and (min-width: 640px)') && Modernizr.mq('only screen and (max-width: 1025px)')) {
        var half_map_header = jQuery('.master_header ').height();
    }
    
}



Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&'+control_vars.price_separator);
};


function wpestate_lazy_load_carousel_property_unit(){
     "use strict";
    jQuery('.property_unit_carousel img').each(function(event){
          var new_source='';
          new_source=jQuery(this).attr('data-lazy-load-src');
          if(typeof (new_source)!=='undefined' && new_source!==''){
              jQuery(this).attr('src',new_source);
          }
      });
}
 //recaptha
    
    
    var widgetId1,widgetId2,widgetId3,widgetId4;
  
    var wpestate_onloadCallback = function() {
        
        // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
        // The id of the reCAPTCHA widget is assigned to 'widgetId1'.
        
        if(  document.getElementById('top_register_menu') ){
            widgetId1 = grecaptcha.render('top_register_menu', {
                'sitekey' : control_vars.captchakey,
                'theme' : 'light'
            });
        }
        
        if(  document.getElementById('mobile_register_menu') ){
            widgetId2 = grecaptcha.render('mobile_register_menu', {
                'sitekey' : control_vars.captchakey,
                'theme' : 'light'
            });
        }
        
        
        if(  document.getElementById('widget_register_menu') ){
            widgetId3 = grecaptcha.render('widget_register_menu', {
                'sitekey' : control_vars.captchakey,
                'theme' : 'light'
            });
        }
    
        if(  document.getElementById('shortcode_register_menu') ){
            widgetId4 = grecaptcha.render('shortcode_register_menu', {
                'sitekey' : control_vars.captchakey,
                'theme' : 'light'
            });
        }
        

    };
    
   
function wpestate_half_map_height(){
     "use strict";
    var wpadminbar_h                =   jQuery('#wpadminbar').height();
    var master_header_h             =   jQuery('.master_header').height();
    var half_map_search_wrapper_h   =   jQuery('.half_map_search_wrapper').height();
    if ( Modernizr.mq('only all and (max-width: 770px)')) {
        half_map_search_wrapper_h=0;

    }
    var move_height= wpadminbar_h + master_header_h + half_map_search_wrapper_h+42;
    if ( Modernizr.mq('only all and (max-width: 770px)')) {
    move_height=0;
    }
  

    jQuery('#google_map_prop_list_sidebar, #google_map_prop_list_wrapper').css('top',move_height+'px');
}
      
      
      
      
      
      
function wpestate_check_in_viewport(){
     "use strict";
    jQuery('#listing_ajax_container .listing_wrapper').each(function(index){
        jQuery(this).delay(600*index).addClass('onScreen');   
    });
}      
      
      
jQuery(document).ready(function ($) {
   "use strict";
    var screen_width,screen_height,map_tab;
    
    $.datepicker.setDefaults( $.datepicker.regional[control_vars.datepick_lang] );

    estate_start_lightbox();
    estate_start_lightbox_floorplans();
    estate_sidebar_slider_carousel();
    wpestate_compare_action();

    wpestate_check_in_viewport();
    wpestate_half_map_height();
    setTimeout(function() {     
        $('.property_listing').matchHeight({
            byRow: true,
            property: 'height',
            target: null,
            remove: false
            }); 
    }, 300);  
    
    
    
    $('#open_packages').on('click',function(){
      
        $('.pack_description_row').slideToggle();
    });
    
    
    
    
    $('.theme_slider_2 .prop_new_details,.nav-next-wrapper,.nav-prev-wrapper').on('click',function(){
       
        var new_link;
        new_link =  $(this).attr('data-href');
        window.open (new_link,'_self',false);
    });    
        
    setTimeout(function() {   wpresidence_list_view_arrange(); }, 300);    
   
    ////////// adv serach 6
    $('.adv6_tab_head').on('click',function(){
      
        var tab_controls;
        $('.adv_search_tab_item').removeClass('active');
        $(this).parent().addClass('active');      
        tab_controls = $(this).attr('aria-controls');
        $('.adv6_price_low').removeClass('price_active');
        $('.adv6_price_max').removeClass('price_active');
         
        $('#'+tab_controls).find('.adv6_price_low').addClass('price_active');  
        $('#'+tab_controls).find('.adv6_price_max').addClass('price_active');
        
    });
    
    
    
    
    if( $('.header_wrapper').hasClass('header_type2')  ){
        var mega_menu_width=$('.header_wrapper_inside').width()-90;
        $('#access ul li.with-megamenu>ul.sub-menu, #access ul li.with-megamenu:hover>ul.sub-menu').css('width',mega_menu_width+'px');
    }
      
      
    $('.prop_title_zone_menu_container a, .my_listings_act a').on('click',function () {
    
        var target;
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            target = $(this.hash);
            if (target.selector === '#carousel-control-theme-next' || target.selector === '#carousel-control-theme-prev' || target.selector === '#carousel-listing' || target.selector === '#carousel-example-generic' || target.selector === '#post_carusel_right') {
                return;
            }
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 140
                }, 1000);
                return false;
            }
        }
    });  
   ////////// header type 3

      
    ////////// map shortcode
    map_tab=0;
    $('#propmaptrigger').on('click',function(){

        if(map_tab===0){
             setTimeout(function(){    
                wpestate_map_shortcode_function();
            },300);
            map_tab=1;
            google.maps.event.trigger(map, 'resize');
        }
    });
    
    $('.shtabmap,.shacctab,#1462452287029-32936ca6-a1d5,#1462968545691-6400f415-3d1e').on('click',function(){   
        if(map_tab===0){
            setTimeout(function(){    
                wpestate_map_shortcode_function();
            },300);
            map_tab=1;
        }
    });
    
    
    $('.testimonial-slider-container').each(function(){
      
        var items   = 1;
        var auto    = parseInt(  $(this).attr('data-auto') );
        
        if( $(this).find('.testimonial-container').hasClass('type_class_3') ){
            $(this).addClass('testimonial-slider-container_type3');
        }
        
        
        
        if (auto === 0 ){
        
            $(this).slick({
                infinite: true,
                slidesToShow: items,
                slidesToScroll: 1,
                dots: true,
       
                responsive: [
                    {
                     breakpoint:1025,
                     settings: {
                       slidesToShow: 1,
                       slidesToScroll: 1
                     }
                   },
                    {
                      breakpoint: 480,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    }
                ]
            });
            if(control_vars.is_rtl==='1'){
                  $(this).slick('slickSetOption','rtl',true,true);
            }
        }else{
            
            $(this).slick({
                infinite: true,
                slidesToShow: items,
                slidesToScroll: 1,
                dots: true,
                autoplay: true,
                autoplaySpeed: auto,
          
                 responsive: [
                    {
                     breakpoint:1025,
                     settings: {
                       slidesToShow: 1,
                       slidesToScroll: 1
                     }
                   },
                    {
                      breakpoint: 480,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    }
                ]
            });
            if(control_vars.is_rtl==='1'){
                  $(this).slick('slickSetOption','rtl',true,true);
            }
        }
    });


    $('.theme_slider_2').each(function(){
        var items   = 3;
        var auto    = parseInt(  $(this).attr('data-auto') );
        
        if (auto === 0 ){
        
            $(this).slick({
                infinite: true,
                slidesToShow: 1,
                centerMode: true,   
                centerPadding: '20%',
                slidesToScroll: 1,
                speed:700,
               
                dots: false,
             
                responsive: [
                    {
                    breakpoint:1025,
                    settings: {
                       slidesToShow: 1,
                       slidesToScroll: 1
                     }
                   }
                    
                ]
            });
            if(control_vars.is_rtl==='1'){
                  $(this).slick('slickSetOption','rtl',true,true);
            }
            
        }else{
            
            $(this).slick({
                infinite: true,
                slidesToShow: 1,
                centerMode: true,   
                centerPadding: '20%',
                slidesToScroll: 1,
                speed:700,
                dots: false,
                autoplay: true,
                autoplaySpeed: auto,
                
                 responsive: [
                    {
                        breakpoint:1025,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                ]
            });
            if(control_vars.is_rtl==='1'){
                  $(this).slick('slickSetOption','rtl',true,true);
            }
        }
    });

    $('.shortcode_slider_list').each(function(){
        var items   = $(this).attr('data-items-per-row');
        var auto    = parseInt(  $(this).attr('data-auto') );
        
        
        if($(this).hasClass('slider_type2')){
             if (auto === 0 ){
                var slick=$(this).slick({
                    infinite: true,
                    slidesToShow: items,
                    slidesToScroll: 1,
                    dots: true,

                });
                if(control_vars.is_rtl==='1'){
                      $(this).slick('slickSetOption','rtl',true,true);
                }
            }else{
            
                var slick= $(this).slick({
                    infinite: true,
                    slidesToShow: items,
                    slidesToScroll: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: auto,

                });
                if(control_vars.is_rtl==='1'){
                    $(this).slick('slickSetOption','rtl',true,true);
                }
            }
        }else{
            if (auto === 0 ){
                var slick=$(this).slick({
                    infinite: true,
                    slidesToShow: items,
                    slidesToScroll: 1,
                    dots: true,

                    responsive: [
                        {
                         breakpoint:1025,
                         settings: {
                           slidesToShow: 2,
                           slidesToScroll: 1
                         }
                       },
                        {
                          breakpoint: 480,
                          settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                          }
                        }
                    ]
                });
                if(control_vars.is_rtl==='1'){
                      $(this).slick('slickSetOption','rtl',true,true);
                }
            }else{
            
                var slick= $(this).slick({
                    infinite: true,
                    slidesToShow: items,
                    slidesToScroll: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: auto,

                     responsive: [
                        {
                         breakpoint:1025,
                         settings: {
                           slidesToShow: 2,
                           slidesToScroll: 1
                         }
                       },
                        {
                          breakpoint: 480,
                          settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                          }
                        }
                    ]
                });
                if(control_vars.is_rtl==='1'){
                    $(this).slick('slickSetOption','rtl',true,true);
                }
            }
        }
        
        
    });
     
     $('.slider_container').css('overflow','initial');
  

    
    $(window).bind("load", function() {
        wpestate_lazy_load_carousel_property_unit();
    });

    wpestate_half_map_responsive();
  

    $('.show_stats').on('click',function(event){
        event.preventDefault();
        var parent,listing_id;
        parent = $(this).parent().parent().parent();
        listing_id = $(this).attr('data-listingid');
        
        if( parent.find('.statistics_wrapper').hasClass('is_slide')  ){
               parent.find('.statistics_wrapper').slideUp().removeClass('is_slide'); 
        }else{
            parent.find('.statistics_wrapper').slideDown().addClass('is_slide'); 
            wpestate_load_stats(listing_id);
        }
        
    });
   
     $('.tabs_stats,#1462452319500-8587db8d-e959,#1462968563400-b8613baa-7092').on('click',function(){
       var parent,listing_id;
       listing_id = $(this).attr('data-listingid');
       if(typeof(listing_id)==='undefined'){
           listing_id =  $('.estate_property_first_row').attr('data-prp-listingid');
       }
       
    
       wpestate_load_stats_tabs(listing_id);
    });
    
  
    
    ////////////////////////////////////////////////////////////////////////////
    //new retina script
    ////////////////////////////////////////////////////////////////////////////
        
        $('.retina_ready').dense();
        var image_unnit = $('<div data-1x="'+control_vars.path+'/css/css-images/unit.png" data-2x="'+control_vars.path+'/css/css-images/unit_2x.png" />').dense('getImageAttribute');
      //  $('.property_marker, .inforoom, .infobath, .infobath, .infosize').css('background-image', 'url(' + image_unnit + ')').css('background-size','210px 38px');
        
        var image_unnit = $('<div data-1x="'+control_vars.path+'/css/css-images/unitshare.png" data-2x="'+control_vars.path+'/css/css-images/unitshare_2x.png" />').dense('getImageAttribute');
        //$('.share_list').css('background-image', 'url(' + image_unnit + ')').css('background-size','36px 12px');;

        /*var image_unnit = $('<div data-1x="'+control_vars.path+'/css/css-images/unit.png" data-2x="'+control_vars.path+'/css/css-images/unit_2x.png" />').dense('getImageAttribute');
        $('.inforoom').css('background-image', 'url(' + image_unnit + ')');

        var image_unnit = $('<div data-1x="'+control_vars.path+'/css/css-images/unit.png" data-2x="'+control_vars.path+'/css/css-images/unit_2x.png" />').dense('getImageAttribute');
        $('.infobath').css('background-image', 'url(' + image_unnit + ')');

        var image_unnit = $('<div data-1x="'+control_vars.path+'/css/css-images/unit.png" data-2x="'+control_vars.path+'/css/css-images/unit_2x.png" />').dense('getImageAttribute');
        $('.infosize').css('background-image', 'url(' + image_unnit + ')');
        */
    ////////////////////////////////////////////////////////////////////////////
    //invoice filters
    ////////////////////////////////////////////////////////////////////////////
     

   $(function() {
        jQuery("#invoice_start_date,#invoice_end_date").datepicker({
            dateFormat : "yy-mm-dd",
        }).datepicker('widget').wrap('<div class="ll-skin-melon"/>');
    });
    /* 
    jQuery("#invoice_end_date").datepicker({
        dateFormat : "yy-mm-dd",
      
    }, jQuery.datepicker.regional[control_vars.datepick_lang]).datepicker('widget').wrap('<div class="ll-skin-melon"/>');
    
    */
    
    $('#invoice_start_date, #invoice_end_date, #invoice_type ,#invoice_status ').change(function(){
        filter_invoices();
    });
   
    ////////////////////////////////////////////////////////////////////////////
    //new mobile menu 1.10 
    ////////////////////////////////////////////////////////////////////////////

    $('.all-elements').animate({
            minHeight: 100+'%'
    });
    
    $('.header-tip').addClass('hide-header-tip');
    
    var vc_size;
    var var_parents=new Array();
    var var_parents_back=new Array();
    
    
    
    $('.mobile-trigger').on('click',function() {
        if(  $('#all_wrapper').hasClass('moved_mobile') ){
            wpestate_close_mobile_menu();
        }else{
           
           
            $('#all_wrapper').css('-webkit-transform','translate(266px, 0px)');
            $('#all_wrapper').css('-moz-transform','translate(266px, 0px)');
            $('#all_wrapper').css('-ms-transform','translate(266px, 0px)');
            $('#all_wrapper').css('-o-transform','translate(266px, 0px)');
            $('.page-template-property_list_half #all_wrapper').css('height','100%');
            $('#all_wrapper').addClass('moved_mobile');
            
            $('.mobilewrapper-user').hide();
            $('.mobilewrapper').show();
            $('.mobilewrapper').css('-webkit-transform','translate(0px, 0px)'); 
            $('.mobilewrapper').css('-moz-transform','translate(0px, 0px)');  
            $('.mobilewrapper').css('-ms-transform','translate(0px, 0px)');  
            $('.mobilewrapper').css(' -o-transform','translate(0px, 0px)');  
            $('body').css('overflow-x','hidden');
             
        }
    });
     
     
    $('.mobile-trigger-user').on('click',function () {
        if ($('#all_wrapper').hasClass('moved_mobile_user')) {
            wpestate_close_mobile_user_menu();
        } else {
            $('#all_wrapper').css('-webkit-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-moz-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-ms-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-o-transform', 'translate(-265px, 0px)');
            $('.page-template-property_list_half #all_wrapper').css('height','100%');
            $('#all_wrapper').addClass('moved_mobile_user');
          
            $('.mobilewrapper-user').show();
            $('.mobilewrapper').hide();
            $('.mobilewrapper-user').css('-webkit-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css('-moz-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css('-ms-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css(' -o-transform', 'translate(0px, 0px)');
        }
    });
     
    $('.mobilemenu-close-user').on('click',function(){
        wpestate_close_mobile_user_menu();
    });
    
    
    $('.mobilemenu-close').on('click',function() {
        wpestate_close_mobile_menu();        
    });
    
    function wpestate_close_mobile_user_menu(){
        $('#all_wrapper').css('-webkit-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-moz-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-ms-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-o-transform', 'translate(0px, 0px)');
        $('#all_wrapper').removeClass('moved_mobile_user');


        $('.mobilewrapper-user').hide();
        $('.mobilewrapper').hide();
        $('.mobilewrapper-user').css('-webkit-transform', 'translate(265px, 0px)');
        $('.mobilewrapper-user').css('-moz-transform', 'translate(265px, 0px)');
        $('.mobilewrapper-user').css('-ms-transform', 'translate(265px, 0px)');
        $('.mobilewrapper-user').css('-o-transform', 'translate(265px, 0px)');
    }
    
    function wpestate_close_mobile_menu(){
    
          
            
        $('#all_wrapper').css('-webkit-transform','translate(0px, 0px)');  
        $('#all_wrapper').css('-moz-transform','translate(0px, 0px)'); 
        $('#all_wrapper').css('-ms-transform','translate(0px, 0px)'); 
        $('#all_wrapper').css('-o-transform','translate(0px, 0px)'); 
        $('#all_wrapper').removeClass('moved_mobile');    
        
        $('.mobilewrapper').hide();
        $('.mobilewrapper-user').hide();
        $('.mobilewrapper').css('-webkit-transform','translate(-265px, 0px)'); 
        $('.mobilewrapper').css('-moz-transform','translate(-265px, 0px)');
        $('.mobilewrapper').css('-ms-transform','translate(-265px, 0px)');
        $('.mobilewrapper').css('-o-transform','translate(-265px, 0px)');
     
       
    }
    
    
    $('#menu-main-menu li').on('click',function(event ){
        event.stopPropagation();

        var selected;
        selected = $(this).find('.sub-menu:first');
        selected.slideToggle();

    });

        
    
    ////////////////////////////////////////////////////////////////////////////
    // multiple cur set cookige
    ////////////////////////////////////////////////////////////////////////////
    
    $('.list_sidebar_currency li').on('click',function(){
        var ajaxurl,data,pos,symbol,coef,curpos;
        data=$(this).attr('data-value');
        pos=$(this).attr('data-pos');
        symbol=$(this).attr('data-symbol');
        coef=$(this).attr('data-coef');
        curpos=$(this).attr('data-curpos');
        var nonce = jQuery('#wpestate_curency_change_nonce').val();
        ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'    :   'wpestate_set_cookie_multiple_curr',
                'curr'      :   data,
                'pos'       :   pos,
                'symbol'    :   symbol,
                'coef'      :   coef,
                'curpos'    :   curpos,
                'security'  :   nonce
            },
            success: function (data) {     
         
               location.reload();
            },
            error: function (errorThrown) {}
        });//end ajax     
        
    });
    
    
    
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////
    // map control
    ////////////////////////////////////////////////////////////////////////////
    $('#map-view').on('click',function(event){
        $('.map-type').fadeIn(400);
    });
    
    $('.map-type').on('click',function(){
        var map_type;
        $('.map-type').hide();
        map_type=$(this).attr('id');
        wpestate_change_map_type(map_type);
        
    });

    ////////////////////////////////////////////////////////////////////////////
    // listing map actions
    ////////////////////////////////////////////////////////////////////////////
   
    if (typeof wpestate_enable_half_map_pin_action == 'function'){
        wpestate_enable_half_map_pin_action();
    }
    ////////////////////////////////////////////////////////////////////////////
    /// direct pay
    ////////////////////////////////////////////////////////////////////////////
    
    $('.perpack').on('click',function(){
        var direct_pay_modal, selected_pack,selected_prop,include_feat,attr;
        selected_prop   =   $(this).attr('data-listing');
        
        var price_pack  =   $(this).parent().parent().find('.submit-price-total').text();
     
     
        if (control_vars.where_curency === 'after'){
            price_pack = price_pack +' '+control_vars.submission_curency;
        }else{
            price_pack = control_vars.submission_curency+' '+price_pack;
        }
        
        price_pack=control_vars.direct_price+': '+price_pack;
        
        
        include_feat=' data-include-feat="0" ';
        $('#send_direct_bill').attr('data-include-feat',0);
        $('#send_direct_bill').attr('data-listing',selected_prop);
         
        if ( $(this).parent().find('.extra_featured').attr('checked') ){
            include_feat=' data-include-feat="1" ';
            $('#send_direct_bill').attr('data-include-feat',1);
        }

        attr = $(this).attr('data-isupgrade');
        if (typeof attr !== typeof undefined && attr !== false) {
            include_feat=' data-include-feat="1" ';
            $('#send_direct_bill').attr('data-include-feat',1);
        }


        window.scrollTo(0, 0);
        direct_pay_modal='<div class="modal fade" id="direct_pay_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'+control_vars.direct_title+'</h4><div class="modal-body listing-submit"><span class="to_be_paid">'+price_pack+'</span><span>'+control_vars.direct_pay+'</span><div id="send_direct_bill" '+include_feat+' data-listing="'+selected_prop+'">'+control_vars.send_invoice+'</div></div></div></div></div></div>';
        jQuery('body').append(direct_pay_modal);
        jQuery('#direct_pay_modal').modal();
        wpestate_enable_direct_pay_perlisting();
        
          $('#direct_pay_modal').on('hidden.bs.modal', function (e) {
               $('#direct_pay_modal').remove();
        });
        
    });
    
    
    $('#direct_pay').on('click',function(){
        var direct_pay_modal, selected_pack,selected_prop,include_feat,attr, price_pack;

       // selected_pack=$('#pack_select').val();
       // var price_pack  =   $('#pack_select option:selected').attr('data-price');
        var packName = jQuery('.package_selected .pack-listing-title').text();
        selected_pack = jQuery('.package_selected .pack-listing-title').attr('data-packid');
        var price_pack = jQuery('.package_selected .pack-listing-title').attr('data-packprice');
    
     
        if (control_vars.where_curency === 'after'){
            price_pack = price_pack +' '+control_vars.submission_curency;
        }else{
            price_pack = control_vars.submission_curency+' '+price_pack;
        }
        
        price_pack=control_vars.direct_price+': '+price_pack;
        
        if(selected_pack!==''){
            window.scrollTo(0, 0);
            direct_pay_modal='<div class="modal fade" id="direct_pay_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'+control_vars.direct_title+'</h4><div class="modal-body listing-submit"><span class="to_be_paid">'+price_pack+'</span><span>'+control_vars.direct_pay+'</span><div id="send_direct_bill" data-pack="'+selected_pack+'">'+control_vars.send_invoice+'</div></div></div></div></div></div>';
            jQuery('body').append(direct_pay_modal);
            jQuery('#direct_pay_modal').modal();
            wpestate_new_enable_direct_pay();
        }
        
        $('#direct_pay_modal').on('hidden.bs.modal', function (e) {
               $('#direct_pay_modal').remove();
        });
         
    });
        
        
     
        
    function  wpestate_enable_direct_pay_perlisting(){
        jQuery('#send_direct_bill').unbind('click');
        jQuery('#send_direct_bill').on('click',function(){
            jQuery('#send_direct_bill').unbind('click');
            var selected_pack,ajaxurl,include_feat;
           
            selected_pack   =   jQuery(this).attr('data-listing');
            include_feat    =   jQuery(this).attr('data-include-feat');
            ajaxurl         =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
            
            var nonce = jQuery('#wpestate_payments_nonce').val();
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_direct_pay_pack_per_listing',
                    'selected_pack'     :   selected_pack,
                    'include_feat'      :   include_feat,
                    'security'          :   nonce
                },
                success: function (data) {
                    jQuery('#send_direct_bill').hide();
                    jQuery('#direct_pay_modal .listing-submit span:nth-child(2)').empty().html(control_vars.direct_thx);
                },
                error: function (errorThrown) {}
            });//end ajax  

        });
         
    }    
        
        
    function wpestate_new_enable_direct_pay(){
        jQuery('#send_direct_bill').on('click',function(){
            jQuery('#send_direct_bill').unbind('click');
            var selected_pack,ajaxurl;
            selected_pack=jQuery(this).attr('data-pack');
            ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
            var nonce = jQuery('#wpestate_payments_nonce').val();
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_direct_pay_pack',
                    'selected_pack'     :   selected_pack,
                    'security'          :   nonce,
                },
                success: function (data) {     
                    jQuery('#send_direct_bill').hide();
                    jQuery('#direct_pay_modal .listing-submit span:nth-child(2)').empty().html(control_vars.direct_thx);
                  
                },
                error: function (errorThrown) {}
            });//end ajax  

 
    
    
        });
        
    }    
     
  
    
    ////////////////////////////////////////////////////////////////////////////
    /// stripe
    ////////////////////////////////////////////////////////////////////////////
    $('#pack_select').change(function(){
        var stripe_pack_id,stripe_ammount,the_pick;
        $( "#pack_select option:selected" ).each(function() {
            stripe_pack_id=$(this).val();
            stripe_ammount=parseFloat( $(this).attr('data-price'))*100;
            the_pick=$(this).attr('data-pick');
        });
    
        $('#pack_id').val(stripe_pack_id);
        $('#pay_ammout').val(stripe_ammount);
        $('#stripe_form').attr('data-amount',stripe_ammount);
        
        $('.stripe_buttons').each(function(){
            $(this).hide();
            if( $(this).attr('id') === the_pick){
                 $(this).show();
            }
        });

    });
    
      $('#pack_recuring').on('click',function () {
        if( $(this).attr('checked') ) {
            $('#stripe_form').append('<input type="hidden" name="stripe_recuring" id="stripe_recuring" value="1">');
        }else{
            $('#stripe_recuring').remove();
        }
    });
    
    ////////////////////////////////////////////////////////////////////////////
    /// floor plans
    ////////////////////////////////////////////////////////////////////////////
    
   

    $('.deleter_floor').on('click',function(){
        $(this).parent().remove();
    });
    // on submit
    
    
    
    
    
    
    
    
    
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////
    /// slider price 
    ////////////////////////////////////////////////////////////////////////////
    
    var price_low_val= parseInt( $('#price_low').val() );
    var price_max_val= parseInt( $('#price_max').val() );
 
    function wpestate_getCookie(cname) {
       var name = cname + "=";
       var ca = document.cookie.split(';');
       for(var i=0; i<ca.length; i++) {
           var c = ca[i];
           while (c.charAt(0)==' ') c = c.substring(1);
           if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
       }
       return "";
   }


 
    var my_custom_curr_symbol  =   decodeURI ( wpestate_getCookie('my_custom_curr_symbol') );
    var my_custom_curr_coef    =   parseFloat( wpestate_getCookie('my_custom_curr_coef'));
    var my_custom_curr_pos     =   parseFloat( wpestate_getCookie('my_custom_curr_pos'));
    var my_custom_curr_cur_post=   wpestate_getCookie('my_custom_curr_cur_post');
    var slider_counter = 0;
   
    wpestate_enable_slider('slider_price', 'price_low', 'price_max', 'amount', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef);
    $( "#slider_price" ).slider({
        stop: function( event, ui ) {
            if (typeof (wpestate_show_pins) !== "undefined") {   
                first_time_wpestate_show_inpage_ajax_half=1;
                wpestate_show_pins(); 
            }
        }
    });
    wpestate_enable_slider('slider_price_sh', 'price_low_sh', 'price_max_sh', 'amount_sh', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef); 
    wpestate_enable_slider('slider_price_widget', 'price_low_widget', 'price_max_widget', 'amount_wd', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef);
    wpestate_enable_slider('slider_price_mobile', 'price_low_mobile', 'price_max_mobile', 'amount_mobile', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef);


    if(control_vars.adv6_taxonomy_term!==''){
        control_vars.adv6_taxonomy_term.forEach(wpestate_advtabs_function);
    }
    
    function wpestate_advtabs_function(item){
       wpestate_enable_slider_tab(control_vars.adv6_min_price[slider_counter],control_vars.adv6_max_price[slider_counter],'slider_price_'+item, 'price_low_'+item, 'price_max_'+item, 'amount_'+item, my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef,control_vars.adv6_min_price[slider_counter],control_vars.adv6_max_price[slider_counter]);
       slider_counter++;
  
        $( '#slider_price_'+item ).slider({
            stop: function( event, ui ) {
                if (typeof (wpestate_show_pins) !== "undefined") {   
                    first_time_wpestate_show_inpage_ajax_half=1;
                    wpestate_show_pins(); 
                }
            }
        });
    }
   
   
   
   
   
   
    function wpestate_replace_plus(string){
        return string.replace("+"," ");
    }
  function wpestate_enable_slider_tab(slider_min,slider_max,slider_name, price_low, price_max, amount, my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef) {
     
        var price_low_val, price_max_val, temp_min, temp_max, slider_min, slider_max;
        price_low_val = parseFloat(jQuery('#'+price_low).val());
        price_max_val = parseFloat(jQuery('#'+price_max).val());

  
        slider_min = parseInt(slider_min,10);
        slider_max = parseInt(slider_max,10);
        
        if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
            slider_min =slider_min *my_custom_curr_coef;
            slider_max =slider_max *my_custom_curr_coef;
        }

        jQuery("#" + slider_name).slider({
            range: true,
            min: parseFloat(slider_min),
            max: parseFloat(slider_max),
            values: [price_low_val, price_max_val ],
            slide: function (event, ui) {

                if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
                    jQuery("#" + price_low).val(ui.values[0]);
                    jQuery("#" + price_max).val(ui.values[1]);
                    
                    jQuery("#price_low").val(ui.values[0]);
                    jQuery("#price_max").val(ui.values[1]);
                    
                    
                    temp_min= ui.values[0] ;
                    temp_max= ui.values[1];

                    if (my_custom_curr_cur_post === 'before') {
                        jQuery("#" + amount).text( wpestate_replace_plus( decodeURIComponent ( my_custom_curr_symbol ) ) + " " + temp_min.format() + " " + control_vars.to + " " + wpestate_replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) )+ " " + temp_max.format());
                    } else {
                        jQuery("#" + amount).text(temp_min.format() + " " + wpestate_replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) )+ " " + control_vars.to + " " + temp_max.format() + " " + wpestate_replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) );
                    }
                } else {
                    jQuery("#" + price_low).val(ui.values[0]);
                    jQuery("#" + price_max).val(ui.values[1]);
                     
                    jQuery("#price_low").val(ui.values[0]);
                    jQuery("#price_max").val(ui.values[1]);

                    if (control_vars.where_curency === 'before') {
                        jQuery("#" + amount).text( wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + ui.values[0].format() + " " + control_vars.to + " " +  wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + ui.values[1].format());
                    } else {
                        jQuery("#" + amount).text(ui.values[0].format() + " " + wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + control_vars.to + " " + ui.values[1].format() + " " + wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) );
                    }
                }
            }
        });
    }

    function wpestate_enable_slider(slider_name, price_low, price_max, amount, my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef) {
      
        var price_low_val, price_max_val, temp_min, temp_max, slider_min, slider_max;
        price_low_val = parseInt(jQuery('#'+price_low).val(), 10);
        price_max_val = parseInt(jQuery('#'+price_max).val(), 10);

  
        slider_min = control_vars.slider_min;
        slider_max = control_vars.slider_max;
        if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
            slider_min =slider_min *my_custom_curr_coef;
            slider_max =slider_max *my_custom_curr_coef;
        }
        
        jQuery("#" + slider_name).slider({
            range: true,
            min: parseFloat(slider_min),
            max: parseFloat(slider_max),
            values: [price_low_val, price_max_val ],
            slide: function (event, ui) {

                if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
                    jQuery("#" + price_low).val(ui.values[0]);
                    jQuery("#" + price_max).val(ui.values[1]);

                    temp_min= ui.values[0] ;
                    temp_max= ui.values[1];

                    if (my_custom_curr_cur_post === 'before') {
                        jQuery("#" + amount).text( wpestate_replace_plus( decodeURIComponent ( my_custom_curr_symbol ) ) + " " + temp_min.format() + " " + control_vars.to + " " + wpestate_replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) )+ " " + temp_max.format());
                    } else {
                        jQuery("#" + amount).text(temp_min.format() + " " + wpestate_replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) )+ " " + control_vars.to + " " + temp_max.format() + " " + wpestate_replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) );
                    }
                } else {
                    jQuery("#" + price_low).val(ui.values[0]);
                    jQuery("#" + price_max).val(ui.values[1]);

                    if (control_vars.where_curency === 'before') {
                        jQuery("#" + amount).text( wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + ui.values[0].format() + " " + control_vars.to + " " +  wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + ui.values[1].format());
                    } else {
                        jQuery("#" + amount).text(ui.values[0].format() + " " + wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + control_vars.to + " " + ui.values[1].format() + " " + wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) );
                    }
                }
            }
        });
    }


    
    ////////////////////////////////////////////////////////////////////////////
    /// print property page
    ////////////////////////////////////////////////////////////////////////////
      
    $('#print_page').on('click',function(event){
        var prop_id, myWindow, ajaxurl;
        ajaxurl      =   control_vars.admin_url+'admin-ajax.php'; 
        event.preventDefault();
   
        prop_id=$(this).attr('data-propid');
     
        myWindow=window.open('','Print Me','width=700 ,height=842');
        var nonce = jQuery('#wpestate_print_page_nonce').val();
        
        $.ajax({    
                type: 'POST',
                url: ajaxurl, 
            data: {
                'action'        :   'wpestate_ajax_create_print',
                'propid'        :   prop_id, 
                'security'      :   nonce
            },
            success:function(data) {  
                myWindow.document.write(data); 
                myWindow.document.close();
                myWindow.focus();
            },
            error: function(errorThrown){
            }

        });//end ajax  var ajaxurl      =   control_vars.admin_url+'admin-ajax.php';     
    });
    
        

    ////////////////////////////////////////////////////////////////////////////
    /// save search actions
    ////////////////////////////////////////////////////////////////////////////
    
    
    $('#save_search_button').on('click',function(){
        var nonce, search, search_name, parent, ajaxurl,meta;
        search_name     =   jQuery('#search_name').val();
        search          =   jQuery('#search_args').val();
        meta            =   jQuery('#meta_args').val();
       
        ajaxurl         =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
        
        jQuery('#save_search_notice').html('saving...');
        var nonce = jQuery('#wpestate_save_search_nonce').val();
        
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'        :   'wpestate_save_search_function',
                'search_name'   :   search_name,
                'search'        :   search,
                'meta'          :   meta,
                'security'         :   nonce
            },
            success: function (data) {
               
                jQuery('#save_search_notice').html(data);
                jQuery('#search_name').val('');
            },
            error: function (errorThrown) {
            }
        });
        
    });
    
    
    $('.delete_search').on('click',function(event){
        var  search_id, parent, ajaxurl,confirmtext;
        confirmtext = control_vars.deleteconfirm;
        
        if (confirm(confirmtext)) {       
            event.preventDefault();
            ajaxurl         =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
            search_id       =   $(this).attr('data-searchid');
            parent          =   $(this).parent();
            $(this).html(control_vars.deleting);
            var nonce = jQuery('#wpestate_delete_nonce').val();
             
          
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'        :   'wpestate_delete_search',
                    'search_id'     :   search_id,
                    'security'      :   nonce
                },
                success: function (data) {
                  
                    if (data==='deleted'){
                        parent.remove();
                    }

                },
                error: function (errorThrown) {
                }
            });
            
       
        }
        
    });
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////
    $('.adv_handler').on('click',function(event){
        event.preventDefault();
        $('.adv_search_hidden_fields').slideToggle();
        
    });
    
    $('#adv_extended_options_text_adv ').on('click',function(){
        $('.adv-search-1.adv_extended_class').css('height','auto');
        $('.adv_extended_class .adv1-holder').css('height','auto');
        $(this).parent().find('.adv_extended_options_text').hide();
        $(this).parent().find('.extended_search_check_wrapper').show();
        $(this).parent().find('.adv_extended_close_button').show();
        
        var  new_margin = parseInt( $(this).parent().find('.extended_search_check_wrapper').height(),10);
        new_margin = new_margin-7;
        if(new_margin<19){
            new_margin=19;
        }
        
        $('#google_map_prop_list_sidebar, #google_map_prop_list_wrapper').css('margin-top',new_margin+'px');
    });
    
    $('.adv_extended_close_button').on('click',function(){
        $(this).parent().parent().find('.extended_search_check_wrapper').hide();
        $(this).hide();
        $(this).parent().parent().find('.adv_extended_options_text').show();
        $('.adv-search-1.adv_extended_class').removeAttr('style');
        $('.adv_extended_class .adv1-holder').removeAttr('style');
         $('#google_map_prop_list_sidebar, #google_map_prop_list_wrapper').css('margin-top','0px');
    });
    
    
    //////////////////////////////////////////////////////////////
    
    $('#adv_extended_options_text_widget').on('click',function(){
      
        $(this).parent().find('.adv_extended_options_text').hide();
        $(this).parent().find('.extended_search_check_wrapper').slideDown();
        $(this).parent().find('#adv_extended_close_widget').show();
    });
    
    $('#adv_extended_close_widget').on('click',function(){
        $(this).parent().parent().find('.extended_search_check_wrapper').slideUp();
        $(this).hide();
        $(this).parent().parent().find('.adv_extended_options_text').show();
    });
    
    ////////////////////////////////////////////////////////////////////////////////
       $('#adv_extended_options_text_short').on('click',function(){     
        $(this).parent().find('.adv_extended_options_text').hide();
        $(this).parent().find('.extended_search_check_wrapper').slideDown();
        $(this).parent().find('#adv_extended_close_short').show();
    });
    
    $('#adv_extended_close_short').on('click',function(){
        $(this).parent().parent().find('.extended_search_check_wrapper').slideUp();
        $(this).hide();
        $(this).parent().parent().find('.adv_extended_options_text').show();
    });
    
    
    /////////////////////////////////////////////////////////////////////////////////////
    $('#adv_extended_options_text_mobile').on('click',function(){      
        $(this).parent().find('.adv_extended_options_text').hide();
        $(this).parent().find('.extended_search_check_wrapper').slideDown();
        $(this).parent().find('#adv_extended_close_mobile').show();
    });
    
    $('#adv_extended_close_mobile').on('click',function(){
        $(this).parent().parent().find('.extended_search_check_wrapper').slideUp();
        $(this).hide();
        $(this).parent().parent().find('.adv_extended_options_text').show();
    });
    /////////////////////////////////////////////////////////////////////////////////////////
    
    
   
   
  
    
    $('#login_user_topbar,#login_pwd_topbar').on('focus', function(e) {
       $('#user_menu_open').addClass('iosfixed');
    });
     
     
     
    $('#estate-carousel .slider-content h3 a,#estate-carousel .slider-content .read_more ').on('click',function(){
        var new_link;
        new_link =  $(this).attr('href');
        window.open (new_link,'_self',false);
    });
     
     
    ////////////////////////////////////////////////////////////////////////////////////////////
    ///city-area-selection
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    wpestate_filter_city_area('filter_city','filter_area');
    wpestate_filter_city_area('sidebar-adv-search-city','sidebar-adv-search-area');
    wpestate_filter_city_area('adv-search-city ','adv-search-area');
    wpestate_filter_city_area('half-adv-search-city ','half-adv-search-area');
    wpestate_filter_city_area('shortcode-adv-search-city','shortcode-adv-search-area');
    wpestate_filter_city_area('mobile-adv-search-city','mobile-adv-search-area');

  
        
   
    
    
    var all_browsers_stuff;
    
    $('#property_city_submit').change(function(){
        var city_value, area_value;
        city_value=$(this).val();
  
        all_browsers_stuff=$('#property_area_submit_hidden').html();
        $('#property_area_submit').empty().append(all_browsers_stuff);
        $('#property_area_submit option').each(function(){
            area_value=$(this).attr('data-parentcity');
          
            if( city_value ===area_value || area_value==='all'){
              //  $(this).show();        
            }else{
                //$(this).hide();
                 $(this).remove();
            }
        });
    });
    
     
    ////////////////////////////////////////////////////////////////////////////////////////////
    ///mobile
    ///////////////////////////////////////////////////////////////////////////////////////////


    $('#adv-search-header-mobile').on('click',function(){
        $('#adv-search-mobile').fadeToggle('300');
    });


    ////////////////////////////////////////////////////////////////////////////////////////////
    ///navigational links
    ///////////////////////////////////////////////////////////////////////////////////////////

    $('.nav-prev,.nav-next ').on('click',function(event){
        event.preventDefault();
        var link = $(this).find('a').attr('href');
        window.open (link,'_self',false);
    });

    ////////////////////////////////////////////////////////////////////////////////////////////
    /// featured agent
    ///////////////////////////////////////////////////////////////////////////////////////////
 
  
    $('.featured_agent_details_wrapper, .agent-listing-img-wrapper').on('click',function(){
        var newl= $( this ).attr('data-link');
        window.open (newl,'_self',false);
    });  
    
    $('.see_my_list_featured').on('click',function(event){
            event.stopPropagation();
    });
  
    ////////////////////////////////////////////////////////////////////////////////////////////
    /// featuerd property
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    $('.featured_cover').on('click',function(){
        var newl= $( this ).attr('data-link');
        window.open (newl,'_self',false);
    }); 


  
        
    jQuery(".agent_face").on("hover", function(e) {

        if (e.type === "mouseenter") { 
            $(this).find('.agent_face_details').fadeIn('500');
        } else if (e.type === "mouseleave") { 
            $(this).find('.agent_face_details').fadeOut('500');
        }

    });
    
    ////////////////////////////////////////////////////////////////////////////////////////////
    /// listings unit navigation
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('.property_listing, .places_cover,.agent_unit, .blog_unit , .featured_widget_image').on('click',function(){
        var link;
        link = $(this).attr('data-link'); 

        window.open(link, '_self');
    });

   

    $('.share_unit').on('click',function(event){
        event.stopPropagation();
    });

    $('.related_blog_unit_image').on('click',function(){
         var link;
        link = $(this).attr('data-related-link'); 
        window.open(link, '_self');
    });

    ////////////////////////////////////////////////////////////////////////////////////////////
    /// user menu
    ///////////////////////////////////////////////////////////////////////////////////////////

    $('#modal_login_wpestate_close').on('click',function(){
        $('#modal_login_wpestate').fadeOut(300);
    });
    
    $('#user_menu_u').on('click',function(event){
        
        if( $('#user_menu_open').is(":visible")){
            $('#user_menu_open').removeClass('iosfixed').fadeOut(400); 
          
        }else{
            if( $(this).hasClass('.user_loged') ){
                $('#user_menu_open').fadeIn(400); 
            }else{
                $('#modal_login_wpestate').fadeIn(300);
                $('#user_menu_open').fadeIn(400); 
            }
           
        }     
        event.stopPropagation();
    });
    

    $(document).on('click',function(event) {
       
        var clicka  =   event.target.id;
        var clicka2 =   $(event.target).attr('share_unit');
        
        if ( !$('#'+clicka).parents('.topmenux').length) {
           // $('#user_menu_open').removeClass('iosfixed').hide(400); 
            $('#user_menu_u .navicon-button').removeClass('open');
        }
      
        $('.share_unit').hide();
     
        
        if (event.target.id == "header_type3_wrapper" || $(event.target).parents("#header_type3_wrapper").size()) { 
         
        } else { 
            var css_right   = parseFloat( $('.header_type3_menu_sidebar').css('right') );
            var css_left    = parseFloat( $('.header_type3_menu_sidebar').css('left') );
            
       
            
          //  if (  $('.header_type3_menu_sidebar').hasClass('sidebaropen')  ) {
            
            if(css_right===0 || css_left===0 ){
                $('.header_type3_menu_sidebar.header_left.sidebaropen').css("right","-300px");
                $('.header_type3_menu_sidebar.header_right.sidebaropen').css("left","-300px");
                $('.container.main_wrapper.has_header_type3').css("padding","0px");
                $('.master_header').removeAttr('style');
               
            }
        }
    });
    
    
    $('#header_type3_trigger').on('click',function(event){
   
        event.preventDefault(); 
        if ( !$('.container').hasClass('is_boxed') ){
            if( $('.header_type3_menu_sidebar').hasClass('header_left') ){
                $(".header_type3_menu_sidebar").css("right","0px");
                $(".container.main_wrapper ").css("padding-right","300px");
                $(".master_header").css("right","150px");
            }else{
                $(".header_type3_menu_sidebar").css("left","0px");
                $(".container.main_wrapper ").css("padding-left","300px");
                $(".master_header").css("left","150px");
            }
            $(".header_type3_menu_sidebar").addClass("sidebaropen");
        }else{
             if( $('.header_type3_menu_sidebar').hasClass('header_left') ){
                $(".header_type3_menu_sidebar").css("right","0px");
              
            }else{
                $(".header_type3_menu_sidebar").css("left","0px");
              
            }
            $(".header_type3_menu_sidebar").addClass("sidebaropen");
        }
    });
      
      
    ////////////////////////////////////////////////////////////////////////////////////////////
    /// new controls for upload pictures
    ///////////////////////////////////////////////////////////////////////////////////////////

    jQuery('#imagelist i.fa-trash-o').on('click',function(){
          var curent='';  
          jQuery(this).parent().remove();

          jQuery('#imagelist .uploaded_images').each(function(){
             curent=curent+','+jQuery(this).attr('data-imageid'); 
          });
          jQuery('#attachid').val(curent); 

      });

    jQuery('#imagelist img').dblclick(function(){

        jQuery('#imagelist .uploaded_images .thumber').each(function(){
            jQuery(this).remove();
        });

        jQuery(this).parent().append('<i class="fa thumber fa-star"></i>');
        jQuery('#attachthumb').val(   jQuery(this).parent().attr('data-imageid') );
    });   

    
  
    
    
    $('#switch').on('click',function () {
        $('.main_wrapper').toggleClass('wide');
    });


    $('#accordion_prop_addr, #accordion_prop_details, #accordion_prop_features').on('shown.bs.collapse', function () {
        $(this).find('h4').removeClass('carusel_closed');
    });
    
    $('#accordion_prop_addr, #accordion_prop_details, #accordion_prop_features').on('hidden.bs.collapse', function () {
        $(this).find('h4').addClass('carusel_closed');
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////  
    //////// advanced search filters
    ////////////////////////////////////////////////////////////////////////////////////////////    
 
    var elems = ['.search_wrapper' , '#advanced_search_shortcode', '#advanced_search_shortcode_2', '.adv-search-mobile','.advanced_search_sidebar'];
 
    $.each( elems, function( i, elem ) {
      
        $(elem+' li').on('click',function (event) {
            event.preventDefault();
            var pick, value, parent,parent_replace;
            
            parent_replace='.filter_menu_trigger';
            if(elem === '.advanced_search_sidebar'){
                parent_replace='.sidebar_filter_menu';      
            }
            
            pick = $(this).text();
            value = $(this).attr('data-value');
            parent = $(this).parent().parent();  
            parent.find(parent_replace).text(pick).append('<span class="caret caret_filter"></span>').attr('data-value',value);
           parent.find('input').val(value);    
        });
    });
 
    
 
 
    jQuery('.search_wrapper li, .extended_search_check_wrapper input[type="checkbox"]').on('click',function () {
         if (typeof (wpestate_show_pins) !== "undefined") {    
            first_time_wpestate_show_inpage_ajax_half=1;
            wpestate_show_pins(); 
        }
    });

    jQuery('#adv_location,.search_wrapper input[type=text]').change(function () {        
        if (typeof (wpestate_show_pins) !== "undefined") {   
            first_time_wpestate_show_inpage_ajax_half=1;
            wpestate_show_pins(); 
        }
       
    });
    
   

  

    $('#showinpage').on('click',function (event) {
        event.preventDefault();
        wpestate_show_inpage_ajax();       
    });
    
    
    function wpestate_show_inpage_ajax(){
        if( $('#gmap-full').hasClass('spanselected')){
            $('#gmap-full').trigger('click');
        }
 
        if(mapfunctions_vars.custom_search==='yes'){
            wpestate_custom_search_start_filtering_ajax(1);
        }else{
            wpestate_start_filtering_ajax(1);  
        } 
    }
    

    /// ******************** end check
    ///////////////////////////////////////////////////////////////////////////////////////////  
    //////// advanced search filters
    ////////////////////////////////////////////////////////////////////////////////////////////    

  
  
    
    ///////////////////////////////////////////////////////////////////////////////////////////  
    //////// full screen map
    ////////////////////////////////////////////////////////////////////////////////////////////    
    var wrap_h;
    var map_h;
    
    $('#gmap-full').on('click',function(){

      
        if(  $('#gmap_wrapper').hasClass('fullmap') ){    
            $('#google_map_prop_list_wrapper').removeClass('fullhalf');

            $('#gmap_wrapper').removeClass('fullmap').css('height',wrap_h+'px');
            $('#googleMap').removeClass('fullmap').css('height',map_h+'px');
            $('.master_header ').removeClass('header_full_map');
            $('#search_wrapper').removeClass('fullscreen_search');
            $('#search_wrapper').removeClass('fullscreen_search_open');
            $('.nav_wrapper').removeClass('hidden');
             if(  !$('#google_map_prop_list_wrapper').length ){
                 $('.content_wrapper').show();
             }
            $('body,html').animate({
                 scrollTop: 0
            }, "slow");
            $('#openmap').show();
            $(this).empty().append('<i class="fa fa-arrows-alt"></i>'+control_vars.fullscreen).removeClass('spanselected');

            $('#google_map_prop_list_wrapper').removeClass('fullscreen');
            $('#google_map_prop_list_sidebar').removeClass('fullscreen');
        }else{
            $('#gmap_wrapper,#googleMap').css('height','100%').addClass('fullmap');
  
            $('#google_map_prop_list_wrapper').addClass('fullscreen');
            $('#google_map_prop_list_sidebar').addClass('fullscreen');




            $('#google_map_prop_list_wrapper').addClass('fullhalf');


            wrap_h=$('#gmap_wrapper').outerHeight();
            map_h=$('#googleMap').outerHeight();
          
            $('.master_header ').addClass('header_full_map');


            $('#search_wrapper').addClass('fullscreen_search');
            $('.nav_wrapper').addClass('hidden');
            if(  !$('#google_map_prop_list_wrapper').length ){
                $('.content_wrapper').hide();
            }

            $('#openmap').hide();
            $(this).empty().append('<i class="fa fa-square-o"></i>'+control_vars.default).addClass('spanselected');
setTimeout(function(){google.maps.event.trigger(map, 'resize'); }, 600);   
        }
        
            
      
        google.maps.event.addListenerOnce(map, 'idle', function() {
  
            setTimeout(function(){google.maps.event.trigger(map, 'resize'); }, 600);        
        });
      
    });
  
    
    $('#street-view').on('click',function(){
         toggleStreetView();
    });
    
    $('#carousel-listing').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots:false,
        fade: true,
        asNavFor: '#carousel-listing-nav'
    });
    
    
    if(    $('#carousel-listing-nav').hasClass('carouselvertical-nav') ){
      
        $('#carousel-listing-nav').slick({
          slidesToShow: 6,
            slidesToScroll: 1,
            asNavFor: '#carousel-listing',
            dots: false,
            arrows: false,
            centerMode: false,
            focusOnSelect: true,
            verticalSwiping: true,
            vertical:true
//          rows:6,
//          slidesPerRow:1,
    });
    }else{
        $('#carousel-listing-nav').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            asNavFor: '#carousel-listing',
            dots: false,
            arrows: false,
            centerMode: false,
            focusOnSelect: true,
    });
    }
    
 
    
    
    
    
    if(control_vars.is_rtl==='1'){
        $('#carousel-listing').slick('slickSetOption','rtl',true,true);
        $('#carousel-listing-nav').slick('slickSetOption','rtl',true,true);
    }

  
    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////   tool tips on prop unit
    ///////////////////////////////////////////////////////////////////////////////////////////	       
  
    jQuery(".share_list, .icon-fav, .compare-action, .dashboad-tooltip,#slider_enable_map ,#slider_enable_slider,#slider_enable_street,#slider_enable_street_sh").on("hover", function(e) {

        if (e.type === "mouseenter") { 
            $( this ).tooltip('show') ;
        } else if (e.type === "mouseleave") { 
              $( this ).tooltip('hide');
        }

    });
        
        
     $('.share_list').on('click',function(event){
        event.stopPropagation();
        var sharediv=$(this).parent().find('.share_unit');
        sharediv.toggle();
        $(this).toggleClass('share_on');
     });
    

    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////   back to top
    ///////////////////////////////////////////////////////////////////////////////////////////	       
           
         
     $('.backtop').on('click',function(event){
         event.preventDefault();
  
         $('body,html').animate({
                scrollTop: 0
          }, "slow");

     }) ;
         
    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////    footer contact
    ///////////////////////////////////////////////////////////////////////////////////////////	       
         
    $('.contact-box ').on('click',function(event){
        event.preventDefault();
        $('.contactformwrapper').toggleClass('hidden');
        wpestate_contact_footer_starter();
    });
         
   
         
    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////    add pretty photo
    ///////////////////////////////////////////////////////////////////////////////////////////	
    
 

    //$(" a[data-pretty='prettyPhoto']").prettyPhoto();
    //$("a[rel^='prettyPhoto']").prettyPhoto();
    $("a[rel^='prettyPhoto']").on('click',function(event){
       event.preventDefault(); 
    });
    /*$("a[rel^='prettyPhoto']").fancybox({
		prevEffect	: 'none',
		nextEffect	: 'none',
		helpers	: {
			title	: {
				type: 'inside'
			},
			thumbs	: {
				width	: 100,
				height	: 100
			}
		}
    });*/
    


    var mediaQuery = 'has_pretty_photo';
    if (Modernizr.mq('only screen and (max-width: 600px)') || Modernizr.mq('only screen and (max-height: 520px)')) {
        mediaQuery = 'no_pretty_photo';
       //$("a[data-pretty^='prettyPhoto']").unbind('click');
        $("a[rel^='prettyPhoto']").unbind('click');
    }

    //   pretty photo on / off
    mediaQuery = 'has_pretty_photo';

    if ((Modernizr.mq('only screen and (max-width: 600px)') || Modernizr.mq('only screen and (max-height: 520px)')) && mediaQuery === 'has_pretty_photo') {
       // jQuery("a[data-pretty='prettyPhoto']").unbind('click');
         jQuery("a[rel^='prettyPhoto']").unbind('click');
        mediaQuery = 'no_pretty_photo';
    } else if (!Modernizr.mq('only screen and (max-width: 600px)') && !Modernizr.mq('only screen and (max-height: 520px)') && mediaQuery === 'no_pretty_photo') {
        //$("a[data-pretty='prettyPhoto']").prettyPhoto();
          $("a[rel^='prettyPhoto']").prettyPhoto();
        mediaQuery = 'has_pretty_photo';
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////   widget morgage calculator
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('#morg_compute').on('click',function() {
        
        var intPayPer  = 0;
        var intMthPay  = 0;
        var intMthInt  = 0;
        var intPerFin  = 0;
        var intAmtFin  = 0;
        var intIntRate = 0;
        var intAnnCost = 0;
        var intVal     = 0;
        var salePrice  = 0;

        salePrice = $('#sale_price').val();
        intPerFin = $('#percent_down').val() / 100;

        intAmtFin = salePrice - salePrice * intPerFin;
        intPayPer =  parseInt ($('#term_years').val(),10) * 12;
        intIntRate = parseFloat ($('#interest_rate').val(),10);
        intMthInt = intIntRate / (12 * 100);
        intVal = wpestate_raisePower(1 + intMthInt, -intPayPer);
        intMthPay = intAmtFin * (intMthInt / (1 - intVal));
        intAnnCost = intMthPay * 12;

        $('#am_fin').html("<strong>"+control_vars.morg1+"</strong><br> " + (Math.round(intAmtFin * 100)) / 100 + " ");
        $('#morgage_pay').html("<strong>"+control_vars.morg2+"</strong><br> " + (Math.round(intMthPay * 100)) / 100 + " ");
        $('#anual_pay').html("<strong>"+control_vars.morg3+"</strong><br> " + (Math.round(intAnnCost * 100)) / 100 + " ");
        $('#morg_results').show();
        $('.mortgage_calculator_div').css('height',532+'px');
    });



    ///////////////////////////////////////////////////////////////////////////////////////////
    /////// Search widget
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('#searchform input').focus(function(){
      $(this).val(''); 
    }).blur(function(){

    });
   
     /////////////////////////////////////////////////////////////////////////////////////////
     ////// idx widget 
     /////////////////////////////////////////////////////////////////////////////////////////
     
     $('.dsidx-controls a').on('click',function(){
         sizeContent();         
     });
     
   
     ///////////////////////////////////////////////////////////////////////////////////////
     ////// Geolocation
     /////////////////////////////////////////////////////////////////////////////////////////
     
        
    jQuery("#geolocation-button").on("hover", function(e) {

        if (e.type === "mouseenter") { 
            $('#tooltip-geolocation').fadeIn();
            $('.tooltip').fadeOut("fast");
        } else if (e.type === "mouseleave") { 
            $('#tooltip-geolocation').fadeOut();
        }

    });

    ////////////////////////////////////////////////////////////////////////////////////////////
    /// adding total for featured listings  
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('.extra_featured').change(function(){
       var parent= $(this).parent();
       var price_regular  = parseFloat( parent.find('.submit-price-no').text(),10 );
       var price_featured = parseFloat( parent.find('.submit-price-featured').text(),10 );
       var total= price_regular+price_featured;

       if( $(this).is(':checked') ){
            parent.find('.submit-price-total').text(total);
            parent.find('#stripe_form_featured').show();
            parent.find('#stripe_form_simple').hide();
       }else{
           //substract from total
            parent.find('.submit-price-total').text(price_regular);
            parent.find('#stripe_form_featured').hide();
            parent.find('#stripe_form_simple').show();
       }
    });
  
  
     ///////////////////////////////////////////////////////////////////////////////////////////
    ///////  resise colums on compare page
    ///////////////////////////////////////////////////////////////////////////////////////////

    $('.compare_wrapper').each(function() {
        var cols = $(this).find('.compare_item_head').length;
        $(this).addClass('compar-' + cols);
    });
    
    /////////////////////////////////////////////////////////////////////////////////////////
    /////// grid to list view
    ///////////////////////////////////////////////////////////////////////////////////////////


    $('.col-md-12.listing_wrapper .property_unit_custom_element.image').each(function(){
       $(this).parent().addClass('wrap_custom_image'); 
    });


    $('#list_view').on('click',function(){
        $(this).toggleClass('icon_selected');
        $('#listing_ajax_container').addClass('ajax12');
        $('#grid_view').toggleClass('icon_selected');
        
         
        $('#listing_ajax_container .listing_wrapper').hide().removeClass('col-md-4').removeClass('col-md-3').addClass('col-md-12').fadeIn(400) ;
       
        $('.the_grid_view').fadeOut(10,function() {
            $('.the_list_view:not(.half_map_list_view)').fadeIn(300);
           
        });    
         
        // custom unit code 
        $('#listing_ajax_container .col-md-12.listing_wrapper .property_unit_custom_element.image').each(function(){
            $(this).parent().addClass('wrap_custom_image'); 
        });
      
        jQuery('.listing_wrapper.col-md-12  > .property_listing').matchHeight();
        wpresidence_list_view_arrange();
     });
     
     $('#grid_view').on('click',function(){
        var class_type;
        class_type = $('#listing_ajax_container .listing_wrapper:first-of-type').attr('data-org');
        $(this).toggleClass('icon_selected');
        $('#listing_ajax_container').removeClass('ajax12');
        $('#list_view').toggleClass('icon_selected');
        $('#listing_ajax_container .listing_wrapper ').hide().removeClass('col-md-12').addClass('col-md-'+class_type).fadeIn(400); 
        $('#listing_ajax_container .the_list_view').fadeOut(10,function(){
             $('.the_grid_view').fadeIn(300);
        });     
        // custom unit code 
        // custom unit code 
        $('#listing_ajax_container .wrap_custom_image').each(function(){
            $(this).removeClass('wrap_custom_image'); 
            jQuery('.property_listing_custom_design').css('padding-left','0px'); 
        });
       
         setTimeout(function() {   jQuery('.property_listing').matchHeight(); }, 300);    
     });
     
     
     
    function  wpresidence_list_view_arrange(){
        var wrap_image = parseInt( jQuery('.wrap_custom_image').width());
      
        if(wrap_image!=0){
           jQuery('.col-md-12>.property_listing_custom_design').css('padding-left',wrap_image); 
        }
    }
     
     
   
    
     /////////////////////////////////////////////////////////////////////////////////////////
     ////// form upload
     /////////////////////////////////////////////////////////////////////////////////////////
       
    $('#form_submit_2,#form_submit_1 ').on('click',function(){
        var loading_modal;
        window.scrollTo(0, 0);
        loading_modal='<div class="modal fade" id="loadingmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body listing-submit"><span>'+control_vars.addprop+'</div></div></div></div></div>';
        
        jQuery('body').append(loading_modal);
        jQuery('#loadingmodal').modal();
    });
       
       
       $('#add-new-image').on('click',function(){
           $('<p><label for="file">New Image:</label><input type="file" name="upload_attachment[]" id="file_featured"></p> ').appendTo('#files_area');
       });
       
       
       
       $('.delete_image').on('click',function(){
          var image_id=$(this).attr('data-imageid'); 
          
          var curent=$('#images_todelete').val(); 
        if(curent===''){
                 curent=image_id;
           }else{
                 curent=curent+','+image_id;
           }
         
          $('#images_todelete').val(curent) ;     
          $(this).parent().remove();              
      });
  
     /////////////////////////////////////////////////////////////////////////////////////////
     ////// mouse over map tooltip
     /////////////////////////////////////////////////////////////////////////////////////////
       
    $('#googleMap').bind('mousemove', function(e){
       $('.tooltip').css({'top':e.pageY,'left':e.pageX, 'z-index':'1'});
    });

    setTimeout(function(){  $('.tooltip').fadeOut("fast");},10000);
});

////////////////// END ready

function wpestate_filter_city_area(selected_city,selected_area){
       
    jQuery('#'+selected_city+' li').on('click',function(event){
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city   = String( jQuery(this).attr('data-value2') ).toLowerCase();       

        jQuery('#'+selected_area+' li').each(function(){
            is_city = String ( jQuery(this).attr('data-parentcity') ).toLowerCase();
            is_city = is_city.replace(" ","-");
            area_value   = String ( jQuery(this).attr('data-value') ).toLowerCase();    
            if(is_city === value_city || value_city === 'all' ){
                jQuery(this).show();
            }else{
                jQuery(this).hide();
            }
        });
    });
}
    




 function wpestate_raisePower(x, y) {
        return Math.pow(x, y);
} 
    
function wpestate_shortcode_google_map_load(containermap, lat, long, mapid){
    "use strict";    
  
    var myCenter = new google.maps.LatLng(lat, long);
    var mapOptions = {
             flat:false,
             noClear:false,
             zoom: 15,
             scrollwheel: false,
             draggable: true,
             center: myCenter,
             mapTypeId: google.maps.MapTypeId.ROADMAP,
             streetViewControl:false,
             mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP]
            },
            disableDefaultUI: true
           };
           
    map = new google.maps.Map(document.getElementById(mapid), mapOptions);
    google.maps.visualRefresh = true;
    
    var marker=new google.maps.Marker({
       position: myCenter,
             map: map
    });

    marker.setMap(map);

}





function filter_invoices(){
    "use strict";
    var ajaxurl, start_date, end_date, type, status;
    start_date  = jQuery('#invoice_start_date').val();
    end_date    = jQuery('#invoice_end_date').val();
    type        = jQuery('#invoice_type').val();
    status      = jQuery('#invoice_status').val();
    
    var nonce = jQuery('#wpestate_invoices_nonce').val();
    ajaxurl         =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        dataType: 'json',
        data: {
            'action'        :   'wpestate_ajax_filter_invoices',
            'start_date'    :   start_date,
            'end_date'      :   end_date,
            'type'          :   type,
            'status'        :   status,
            'security'      :   nonce
        },
        success: function (data) {
         
            jQuery('#container-invoices').empty().append(data.results);
            jQuery('#invoice_confirmed').empty().append(data.invoice_confirmed);
            //enable_invoice_actions();
    
        },
        
        error: function (errorThrown) {
       
        }
    });//end ajax
}
 
 
function estate_sidebar_slider_carousel(){
    var owl = jQuery("#owl-featured-slider").data('owlCarousel');
    jQuery(".owl-featured-slider").owlCarousel({
         rtl:true,
         navigation : true, // Show next and prev buttons
         slideSpeed : 300,
         paginationSpeed : 400,
         singleItem:true,
         navigationText : ["<div class='nextleft'><i class='demo-icon icon-left-open-big'></i></div>","<div class='nextright'><i class='demo-icon icon-right-open-big'></i></div>"],

     });
      
} 

function estate_start_lightbox(){
        var jump_slide;
         jQuery("#owl-demo").owlCarousel({
            loop:true,
            navigation : true, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            items:1,
           
            navigationText : ["<div class='nextleft'><i class='demo-icon icon-left-open-big'></i></div>","<div class='nextright'><i class='demo-icon icon-right-open-big'></i></div>"],
            responsiveClass:true,
            responsive:false
        });
      
      
        jQuery('.lightbox_trigger').on('click',function(event){
          
            event.preventDefault();
            jQuery('.lightbox_property_wrapper').show();

        });
                
        jQuery('.lighbox-image-close').on('click',function(event){
            event.preventDefault();
            jQuery('.lightbox_property_wrapper').hide();
        }) ;
       
        
      
          
}
   
   
function estate_start_lightbox_floorplans(){
        var jump_slide;
      
        jQuery('.lightbox_trigger_floor').on('click',function(event){
            event.preventDefault();
            jQuery('.lightbox_property_wrapper_floorplans').show();           
        });
                
        jQuery('.lighbox-image-close-floor').on('click',function(event){
            event.preventDefault();
            jQuery('.lightbox_property_wrapper_floorplans').hide();
        });
       
        
       jQuery("#owl-demo-floor").owlCarousel({
            rtl:true,
            navigation : true, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,
            navigationText : ["<div class='nextleft'><i class='demo-icon icon-left-open-big'></i></div>","<div class='nextright'><i class='demo-icon icon-right-open-big'></i></div>"],
               
        });
      
          
}


function wpestate_compare_action(){
     ///////////////////////////////////////////////////////////////////////////////////////////
    ///////   compare action
    ///////////////////////////////////////////////////////////////////////////////////////////
    var already_in=[];
    jQuery('.compare-action').on('click',function(e) {
    
        e.preventDefault();
        e.stopPropagation();
        jQuery('.prop-compare').show();

        var post_id = jQuery(this).attr('data-pid');
        for(var i = 0; i < already_in.length; i++) {
            if(already_in[i] === post_id) {
                return;
            }
        }
        
        already_in.push(post_id);
      
        
        var post_image = jQuery(this).attr('data-pimage');

        var to_add = '<div class="items_compare" style="display:none;"><img src="' + post_image + '" class="img-responsive"><input type="hidden" class="comparevalues" value="' + post_id + '" name="selected_id[]" /></div>';
        jQuery('div.items_compare:first-child').css('background', 'red');
        if (parseInt(jQuery('.items_compare').length,10) > 3) {
            jQuery('.items_compare:first').remove();
        }
        jQuery('#submit_compare').before(to_add);
        
        
    
        jQuery('.items_compare').fadeIn(500);
    });

    jQuery('#submit_compare').on('click',function() {
        wpestate_start_loading_compare_modal();
    });
    
    jQuery('#compare_close').on('click',function() {
        jQuery('.prop-compare').hide();
    });
    
    
}
         
function  wpestate_start_loading_compare_modal(){

    var ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
    var submit_id='';
    
    jQuery('.comparevalues').each(function(){
       submit_id=submit_id+jQuery(this).val()+",";
    });

    var nonce = jQuery('#wpestate_submit_compare_nonce').val();

    jQuery('.prop-compare ').hide();
    
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
      
        data: {
            'action'            :   'wpestate_show_modal_compare',
            'submit_id'         :   submit_id,
            'security'          :   nonce
        },
        success: function (data) {  

            jQuery('#all_wrapper').append(data);
            jQuery('#compare_close_modal').on('click',function(){
                jQuery('#compare_modal_before,#compare_modal').remove();
            });
            
        },
        error: function (errorThrown) {
        
            
        }
    });//end ajax   

}   
