<?php
define('RC_TEMPLATE_DIR_URI',get_stylesheet_directory_uri());	
define('RC_TEMPLATE_DIR',get_stylesheet_directory());

add_action( 'wp_enqueue_scripts', 'rockers_theme_css',999);
function rockers_theme_css() {
    wp_enqueue_style( 'rockers-parent-style', ST_TEMPLATE_DIR_URI . '/style.css' );
	wp_enqueue_style('bootstrap', ST_TEMPLATE_DIR . '/css/bootstrap.css');
    wp_enqueue_style('rockers-child-style',RC_TEMPLATE_DIR_URI . '/style.css',array('parent-style'));
	wp_enqueue_style('default-style-css', RC_TEMPLATE_DIR_URI."/css/default.css" );
	wp_enqueue_style('media-responsive-css', RC_TEMPLATE_DIR_URI."/css/media-responsive.css" );
	wp_dequeue_style('default-css', ST_TEMPLATE_DIR_URI .'/css/default.css');
	
}

require( RC_TEMPLATE_DIR .'/functions/widgets/wdl_social_icon.php');
require( RC_TEMPLATE_DIR .'/functions/widgets/wdl_header_topbar_info_ct_widget.php');
require( RC_TEMPLATE_DIR . '/functions/widgets/sidebars.php');



if ( ! function_exists( 'rockers_theme_setup' ) ) :

function rockers_theme_setup() {

//Load text domain for translation-ready
load_theme_textdomain( 'rockers', RC_TEMPLATE_DIR . '/languages' );

require( RC_TEMPLATE_DIR . '/functions/customizer/customizer_general_settings.php' );

if ( is_admin() ) {
				require RC_TEMPLATE_DIR . '/admin/admin-init.php';
			}

}
endif; 
add_action( 'after_setup_theme', 'rockers_theme_setup' );

add_action( 'admin_init', 'rockers_detect_button' );
	function rockers_detect_button() {
	wp_enqueue_style('rockers-info-button', RC_TEMPLATE_DIR_URI .'/css/import-button.css');
}

/**
 * Import options from SpicePress
 *
 */
function rockers_get_lite_options() {
	$spicepress_mods = get_option( 'theme_mods_spicepress' );
	if ( ! empty( $spicepress_mods ) ) {
		foreach ( $spicepress_mods as $spicepress_mod_k => $spicepress_mod_v ) {
			set_theme_mod( $spicepress_mod_k, $spicepress_mod_v );
		}
	}
}
add_action( 'after_switch_theme', 'rockers_get_lite_options' );



add_action('wp_head','rockers_custom_menu_breakpoint');
function rockers_custom_menu_breakpoint() {
	
$menu_breakpoint = get_theme_mod('menu_breakpoint', 1100);
?>
<style type="text/css">

.mobile-header { display: none; }
@media (max-width: <?php echo $menu_breakpoint; ?>px) {
	.desktop-header { display: none !important; }
	.mobile-header { display: block !important; }
	.mobile-header .navbar-classic { background-color: #fff !important; }
}

@media (min-width: <?php echo $menu_breakpoint; ?>px) {
	.navbar-transparent { background: transparent; padding-bottom: 0px; padding-top: 0px; margin: 0; }
	.navbar-custom .open > .dropdown-menu { visibility: visible; opacity: 1; }
	.navbar-right .dropdown-menu { right: auto; left: 0; }
	.navbar-classic .navbar-collapse { padding-right: 0; padding-left: 0; }
}
@media (min-width: <?php echo $menu_breakpoint+1; ?>px) {
	.navbar-custom .container-fluid {
		width: 970px;
		padding-right: 15px;
		padding-left: 15px;
		margin-right: auto;
		margin-left: auto;
	}
	.navbar-custom .dropdown-menu { position: absolute; display: block; visibility: hidden; opacity: 0; }
	.navbar-custom .container-fluid {
		width: 970px;
		padding-right: 15px;
		padding-left: 15px;
		margin-right: auto;
		margin-left: auto;
	}
}

/** BELOW MAX-WIDTH MEDIA QUERIES **/

@media (max-width: <?php echo $menu_breakpoint; ?>px) {
	/* Navbar */
	.navbar-custom .navbar-nav { letter-spacing: 0px; margin-top: 1px; margin-bottom: 0; }
	.navbar-custom .navbar-nav li { margin: 0 15px; padding: 0; }
	.navbar-custom .navbar-nav li > a { color: #bbb; padding: 12px 0px 12px 0px; }
	.navbar-custom .navbar-nav > li > a:focus,
	.navbar-custom .navbar-nav > li > a:hover {
		background: transparent;
		color: #fff;
	}
	.navbar-custom .dropdown-menu > li > a {
		display: block;
		clear: both;
		font-weight: normal;
	}
	.navbar-custom .dropdown-menu > li > a:hover, 
	.navbar-custom .dropdown-menu > li > a:focus {
		background-color: #21202F;
		color: #fff;
		padding: 12px 0px 12px 0px;
	}
	.navbar-custom .open .dropdown-menu {
		position: static;
		float: none;
		width: auto;
		margin-top: 0;
		background-color: transparent;
		border: 0;
		-webkit-box-shadow: none;
		box-shadow: none;
	}
	.navbar-custom .open .dropdown-menu > li > a {
		line-height: 20px;
	}
	.navbar-custom .open .dropdown-menu .dropdown-header,
	.navbar-custom .open .dropdown-menu > li > a {
		padding: 12px 0px;
	}
	.navbar-custom .open .dropdown-menu .dropdown-menu .dropdown-header,
	.navbar-custom .open .dropdown-menu .dropdown-menu > li > a {
		padding: 12px 0px;
	}
	.navbar-custom li a,
	.navbar-custom .dropdown-search {
		border-bottom: 1px dotted #4c4a5f !important;
	}
	.navbar-header { padding: 0px 15px; float: none; }
	.navbar-custom .navbar-brand { padding: 20px 50px 20px 0px; }
	.navbar-toggle { display: block; margin: 24px 0px 24px 0; padding: 9px 0px; }
	.site-branding-text { padding: 17px 50px 17px 15px; }
	.navbar-collapse { border-top: 1px solid transparent; box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1); }
	.navbar-collapse.collapse { display: none!important; }
	.navbar-custom .navbar-nav { background-color: #21202e; float: none!important; margin: 0px }
	.navbar-custom .navbar-nav > li { float: none; }
	.navbar-collapse.collapse.in { display: block!important; }
	.collapsing { overflow: hidden!important; }
	.navbar-collapse, 
	.navbar-collapse.collapse.in { 
		padding-right: 0px;
		padding-left: 0px;
	}  
	
}
@media (max-width: <?php echo $menu_breakpoint; ?>px) { 
	.navbar-custom .dropdown a > i.fa {
		font-size: 0.938rem;
		position: absolute;
		right: 0;
		margin-top: -6px;
		top: 50%;
		padding-left: 7px;
	}	
	/*Navbar Classic*/
	.navbar-classic .navbar-nav > .active > a, 
	.navbar-classic .navbar-nav > .active > a:hover, 
	.navbar-classic .navbar-nav > .active > a:focus {
		border-top: 0 none;
	}
}
@media (max-width: 768px) {
	.navbar-header { padding: 0 15px; }
	.navbar-collapse, 
	.navbar-collapse.collapse.in { padding-right: 15px; padding-left: 15px; }
	.navbar-custom .navbar-brand { padding: 20px 50px 20px 15px; }
	.navbar-toggle { display: block; margin: 24px 15px 24px 0; }
}
@media (max-width: 500px) { 
	.navbar-custom .navbar-brand { float: none; display: block; text-align: center; padding: 25px 15px 12px 15px; }
	.navbar-custom .navbar-brand.align-right { padding: 10px 15px 25px 15px; }
	.navbar-custom .navbar-brand img { margin: 0 auto; }
	.site-branding-text { padding: 17px 15px 17px 15px; float: none; text-align: center; }
	.navbar-toggle { float: none; margin: 10px auto 25px; }
	
}


@media (min-width: 100px) and (max-width: <?php echo $menu_breakpoint; ?>px) { 	
	/* Navbar Classic */
	.navbar-classic .navbar-nav > li > a:hover, 
	.navbar-classic .navbar-nav > li > a:focus {
		background-color: transparent;
		color: #fff;
	}
	.navbar-classic .navbar-nav > .open > a,
	.navbar-classic .navbar-nav > .open > a:hover,
	.navbar-classic .navbar-nav > .open > a:focus { 
		background-color: transparent; 
		color: #ce1b28; 
	}
	.navbar-classic.navbar .navbar-nav > .active > a, 
	.navbar-classic.navbar .navbar-nav > .active > a:hover, 
	.navbar-classic.navbar .navbar-nav > .active > a:focus {
		background-color: transparent;
		color: #ce1b28;
		border-top: none;
	}
	
}

@media (min-width: <?php echo $menu_breakpoint; ?>px) and (max-width: <?php echo $menu_breakpoint+1; ?>px) { 
	/*Navbar Classic*/
	.navbar-classic .navbar-nav > li > a { padding: 20px 25px; }
	.navbar-classic .navbar-nav .cart-header { margin: 18px 12px 18px 20px; }
}


@media (min-width: 1200px) {
	.navbar-custom .container-fluid {
		width: 1170px;
		padding-right: 15px;
		padding-left: 15px;
		margin-right: auto;
		margin-left: auto;
	}
}

/* Navbar Classic Css---------------------------------------------------------------------------------------------------- */ 
@media only screen and (max-width: 767px) and (min-width: 200px){
.slide-text-bg1 {
    width: 100%;
}
}

</style>
<?php } ?>


