<?php

/**
 * Function to add the style sheets
 */
function custom_theme_assets() {

	wp_enqueue_style( 'style', get_stylesheet_uri());
	wp_enqueue_style( 'bootstrap.min', get_template_directory_uri().'/assets/css/bootstrap.min.css');
	wp_enqueue_style( 'owl.carousel.min', get_template_directory_uri().'/assets/css/owl.carousel.min.css');
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri().'/assets/css/magnific-popup.css');
	wp_enqueue_style( 'font-awesome.min', get_template_directory_uri().'/assets/css/font-awesome.min.css');
	wp_enqueue_style( 'themify-icons', get_template_directory_uri().'/assets/css/themify-icons.css');
	wp_enqueue_style( 'nice-select', get_template_directory_uri().'/assets/css/nice-select.css');
	wp_enqueue_style( 'flaticon', get_template_directory_uri().'/assets/css/flaticon.css');
	wp_enqueue_style( 'gijgo', get_template_directory_uri().'/assets/css/gijgo.css');
	wp_enqueue_style( 'animate', get_template_directory_uri().'/assets/css/animate.css');
	wp_enqueue_style( 'slick', get_template_directory_uri().'/assets/css/slick.css');
	wp_enqueue_style( 'slicknav', get_template_directory_uri().'/assets/css/slicknav.css');

	//adding the js scripts
	wp_enqueue_script( 'modernizr-3.5.0.min', get_template_directory_uri().'/assets/js/vendor/modernizr-3.5.0.min.js');
	wp_enqueue_script( 'jquery-1.12.4.min', get_template_directory_uri().'/assets/js/vendor/jquery-1.12.4.min.js');
	wp_enqueue_script( 'popper.min', get_template_directory_uri().'/assets/js/popper.min.js');
	wp_enqueue_script( 'bootstrap.min', get_template_directory_uri().'/assets/js/bootstrap.min.js');
	wp_enqueue_script( 'owl.carousel.min', get_template_directory_uri().'/assets/js/owl.carousel.min.js');
	wp_enqueue_script( 'isotope.pkgd.min', get_template_directory_uri().'/assets/js/isotope.pkgd.min.js');
	wp_enqueue_script( 'ajax-form', get_template_directory_uri().'/assets/js/ajax-form.js');
	wp_enqueue_script( 'waypoints.min', get_template_directory_uri().'/assets/js/waypoints.min.js');
	wp_enqueue_script( 'jquery.counterup.min', get_template_directory_uri().'/assets/js/jquery.counterup.min.js');
	wp_enqueue_script( 'imagesloaded.pkgd.min', get_template_directory_uri().'/assets/js/imagesloaded.pkgd.min.js');
	wp_enqueue_script( 'scrollIt', get_template_directory_uri().'/assets/js/scrollIt.js');
	wp_enqueue_script( 'jquery.scrollUp.min', get_template_directory_uri().'/assets/js/jquery.scrollUp.min.js');
	wp_enqueue_script( 'wow.min', get_template_directory_uri().'/assets/js/wow.min.js');
	wp_enqueue_script( 'nice-select.min', get_template_directory_uri().'/assets/js/nice-select.min.js');
	wp_enqueue_script( 'jquery.slicknav.min', get_template_directory_uri().'/assets/js/jquery.slicknav.min.js');
	wp_enqueue_script( 'jquery.magnific-popup.min', get_template_directory_uri().'/assets/js/jquery.magnific-popup.min.js');
	wp_enqueue_script( 'plugins', get_template_directory_uri().'/assets/js/plugins.js');
	wp_enqueue_script( 'gijgo.min', get_template_directory_uri().'/assets/js/gijgo.min.js');
	wp_enqueue_script( 'slick.min', get_template_directory_uri().'/assets/js/slick.min.js');
	wp_enqueue_script( 'contact', get_template_directory_uri().'/assets/js/contact.js');
	wp_enqueue_script( 'jquery.ajaxchimp.min', get_template_directory_uri().'/assets/js/jquery.ajaxchimp.min.js');
	wp_enqueue_script( 'jquery.form', get_template_directory_uri().'/assets/js/jquery.form.js');
	wp_enqueue_script( 'jquery.validate.min', get_template_directory_uri().'/assets/js/jquery.validate.min.js');
	wp_enqueue_script( 'mail-script', get_template_directory_uri().'/assets/js/mail-script.js');
	wp_enqueue_script( 'main', get_template_directory_uri().'/assets/js/main.js');
	
}

add_action( 'wp_enqueue_scripts', 'custom_theme_assets' );


/*
 * Enable support for Post Thumbnails, custom logo on posts and pages.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */
add_theme_support( 'post-thumbnails' );
add_theme_support('custom-logo', array(
		'height' => 41,
		'width' => 138,
		'flex-height' => true ));

add_image_size( 'featured-large', 730, 365, true );
/**
 * Function to display logo image or the default image
 */
function travello_logo() {
	$logo = '';

	if(has_custom_logo())
	{
		$logo = get_custom_logo();
	}
	else
	{
		$logo = '<a href="'. get_site_url() .'" >'.get_bloginfo('name').'</a>';
	}

	echo $logo;
 }

/**
 * Registering menu for the theme
 */
function register_travello_menus() {
  register_nav_menus(
    array(
      'header-menu' => __( 'Header Menu' ),
      'extra-menu' => __( 'Extra Menu' )
     )
   );
 }
 add_action( 'init', 'register_travello_menus' );
/**
 * Adding hooks for login and logout link
 *
 */
function wp_logout_menu_filter_hook( $items, $args ) {

	if(is_user_logged_in())
	{
		$items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-14"><a href="'. wp_logout_url(get_permalink()) .'"> Logout </a></li>';
	}
	else
	{	
		$items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-14"><a href="'. wp_login_url(get_permalink()) .'"> Login</a></li>';
	}
	
	return $items;

}

add_filter( 'wp_nav_menu_items', 'wp_logout_menu_filter_hook', 10, 2);
