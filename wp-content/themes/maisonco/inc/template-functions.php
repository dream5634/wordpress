<?php
/**
 * Checks to see if we're on the homepage or not.
 */
if ( ! function_exists( 'maisonco_is_frontpage' ) ) {

	function maisonco_is_frontpage() {
		return ( is_front_page() || is_home() );
	}
}


if (!function_exists('maisonco_is_elementor_activated')) {
    function maisonco_is_elementor_activated() {
        return function_exists('elementor_load_plugin_textdomain');
    }
}

if ( ! function_exists( 'maisonco_page_enable_breadcrumb' ) ) {
	/**
	 * @return bool
	 */
	function maisonco_page_enable_breadcrumb() {

		if ( ! is_page() ) {
			return true;
		}

		$check = maisonco_get_metabox( get_the_ID(), 'osf_enable_breadcrumb', true );

		return $check;
	}
}


if ( ! function_exists( 'maisonco_get_query' ) ) {

	/**
	 * @param $args
	 *
	 * @return WP_Query
	 */
	function maisonco_get_query( $args ) {
		global $wp_query;
		$default  = array(
			'post_type' => 'post',
		);
		$args     = wp_parse_args( $args, $default );
		$wp_query = new WP_Query( $args );

		return $wp_query;
	}
}

if ( ! function_exists( 'maisonco_get_placeholder_image' ) ) {

	/**
	 * @return string
	 */
	function maisonco_get_placeholder_image() {
		return get_parent_theme_file_uri( '/assets/images/placeholder.png' );
	}

}

if ( ! function_exists( 'maisonco_is_osf_framework_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function maisonco_is_osf_framework_activated() {
		return class_exists( 'MaisonCoCore' ) ? true : false;
	}
}


if ( ! function_exists( 'maisonco_get_metabox' ) ) {

	/**
	 * @param int    $id
	 * @param string $key
	 * @param bool   $default
	 *
	 * @return bool|mixed
	 */
	function maisonco_get_metabox( $id, $key, $default = false ) {
		$value = get_post_meta( $id, $key, true );
		if ( $value === '' ) {
			return $default;
		} else {
			return $value;
		}
	}
}

if ( ! function_exists( 'maisonco_is_blog_archive' ) ) {
	function maisonco_is_blog_archive() {
		return ( is_home() && is_front_page() ) || is_category() || is_tag() || is_post_type_archive( 'post' );
	}
}

if ( ! function_exists( 'maisonco_get_excerpt' ) ) {
	function maisonco_get_excerpt( $excerpt_length = 55 ) {
		global $post;

		$text = $post->post_excerpt;
		if ( empty( $text ) ) {
			$text = $post->post_content;
		}

		$text = strip_shortcodes( $text );
		/** This filter is documented in wp-includes/post-template.php */
		$text = apply_filters( 'the_content', $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		/**
		 * Filters the string in the "more" link displayed after a trimmed excerpt.
		 *
		 * @since 2.9.0
		 *
		 * @param string $more_string The string shown within the more link.
		 */
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );

		return wp_trim_words( $text, $excerpt_length, $excerpt_more );
	}
}

if ( ! function_exists( 'maisonco_do_shortcode' ) ) {

	/**
	 * Call a shortcode function by tag name.
	 *
	 * @since  1.4.6
	 *
	 * @param string $tag     The shortcode whose function to call.
	 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
	 * @param array  $content The shortcode's content. Default is null (none).
	 *
	 * @return string|bool False on failure, the result of the shortcode on success.
	 */
	function maisonco_do_shortcode( $tag, array $atts = array(), $content = null ) {
		global $shortcode_tags;

		if ( ! isset( $shortcode_tags[ $tag ] ) ) {
			return false;
		}

		return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
	}
}

if ( ! function_exists( 'maisonco_nav_menu_social_icons' ) ) {

	/**
	 * Display SVG icons in social links menu.
	 *
	 * @param  string  $item_output The menu item output.
	 * @param  WP_Post $item        Menu item object.
	 * @param  int     $depth       Depth of the menu.
	 * @param  object  $args        wp_nav_menu() arguments.
	 *
	 * @return string  $item_output The menu item output with social icon.
	 */
	function maisonco_nav_menu_social_icons( $item_output, $item, $depth, $args ) {
		// Get supported social icons.
		$social_icons = maisonco_social_links_icons();

		// Change SVG icon inside social links menu if there is supported URL.
		if ( 'social' === $args->theme_location ) {
			foreach ( $social_icons as $attr => $value ) {
				if ( false !== strpos( $item_output, $attr ) ) {
					$item_output = str_replace( $args->link_after, '</span><i class="' . esc_attr( $value ) . '" aria-hidden="true"></i>', $item_output );
				}
			}
		}

		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'maisonco_nav_menu_social_icons', 10, 4 );

if ( ! function_exists( 'maisonco_social_links_icons' ) ) {

	/**
	 * Returns an array of supported social links (URL and icon name).
	 *
	 * @return array $social_links_icons
	 */
	function maisonco_social_links_icons() {
		// Supported social links icons.
		$social_links_icons = array(
			'behance.net'     => 'fa fa-behance',
			'codepen.io'      => 'fa fa-codepen',
			'deviantart.com'  => 'fa fa-deviantart',
			'digg.com'        => 'fa fa-digg',
			'dribbble.com'    => 'fa fa-dribbble',
			'dropbox.com'     => 'fa fa-dropbox',
			'facebook.com'    => 'fa fa-facebook',
			'flickr.com'      => 'fa fa-flickr',
			'foursquare.com'  => 'fa fa-foursquare',
			'plus.google.com' => 'fa fa-google-plus',
			'github.com'      => 'fa fa-github',
			'instagram.com'   => 'fa fa-instagram',
			'linkedin.com'    => 'fa fa-linkedin',
			'mailto:'         => 'fa fa-envelope-o',
			'medium.com'      => 'fa fa-medium',
			'pinterest.com'   => 'fa fa-pinterest-p',
			'getpocket.com'   => 'fa fa-get-pocket',
			'reddit.com'      => 'fa fa-reddit-alien',
			'skype.com'       => 'fa fa-skype',
			'skype:'          => 'fa fa-skype',
			'slideshare.net'  => 'fa fa-slideshare',
			'snapchat.com'    => 'fa fa-snapchat-ghost',
			'soundcloud.com'  => 'fa fa-soundcloud',
			'spotify.com'     => 'fa fa-spotify',
			'stumbleupon.com' => 'fa fa-stumbleupon',
			'tumblr.com'      => 'fa fa-tumblr',
			'twitch.tv'       => 'fa fa-twitch',
			'twitter.com'     => 'fa fa-twitter',
			'vimeo.com'       => 'fa fa-vimeo',
			'vine.co'         => 'fa fa-vine',
			'vk.com'          => 'fa fa-vk',
			'wordpress.org'   => 'fa fa-wordpress',
			'wordpress.com'   => 'fa fa-wordpress',
			'yelp.com'        => 'fa fa-yelp',
			'youtube.com'     => 'fa fa-youtube',
		);

		return apply_filters( 'maisonco_social_links_icons', $social_links_icons );
	}
}

if ( ! function_exists( 'maisonco_dropdown_icon_to_menu_link' ) ) {
	/**
	 * Add dropdown icon if menu item has children.
	 *
	 * @param  string $title The menu item's title.
	 * @param  object $item  The current menu item.
	 * @param  object $args  An array of wp_nav_menu() arguments.
	 * @param  int    $depth Depth of menu item. Used for padding.
	 *
	 * @return string $title The menu item's title with dropdown icon.
	 */
	function maisonco_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
		if ( 'top' === $args->theme_location ) {
			foreach ( $item->classes as $value ) {
				if ( 'menu-item-has-children' === $value || 'page_item_has_children' === $value ) {
					$title = $title . '<i class="fa fa-angle-down"></i>';
				}
			}
		}
		return $title;
	}
}
add_filter( 'nav_menu_item_title', 'maisonco_dropdown_icon_to_menu_link', 10, 4 );

if ( ! function_exists( 'maisonco_is_header_builder' ) ) {
	/**
	 * @return bool
	 */
	function maisonco_is_header_builder() {
		global $osf_header;
		if ( $osf_header && $osf_header instanceof WP_Post ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'maisonco_the_header_builder' ) ) {
	/**
	 * @return void
	 */
	function maisonco_the_header_builder() {
		echo maisonco_get_header_builder_html();
	}
}

if ( ! function_exists( 'maisonco_get_header_builder_html' ) ) {

	/**
	 * @return string
	 */
	function maisonco_get_header_builder_html() {
		$header_content = OSF_Header_builder::getInstance()->render();

		return $header_content;
	}
}


if ( ! function_exists( 'maisonco_is_footer_builder' ) ) {
	/**
	 * @return bool
	 */
	function maisonco_is_footer_builder() {
		global $osf_footer;
		if ( $osf_footer && $osf_footer instanceof WP_Post ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'maisonco_the_footer_builder' ) ) {
	/**
	 * @return void
	 */
	function maisonco_the_footer_builder() {
		echo maisonco_get_footer_builder_html();
	}
}


if ( ! function_exists( 'maisonco_get_footer_builder_html' ) ) {
	function maisonco_get_footer_builder_html() {
		$footer_content = '<div class="wrap"><div class="container">';
		$footer_content .= OSF_Footer_builder::getInstance()->render();
		$footer_content .= '</div></div>';

		return $footer_content;
	}
}
if ( ! function_exists( 'maisonco_license_get_option' ) ) {
	function maisonco_license_get_option( $key = '', $default = false ) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( 'opal-theme-license', $key, $default );
		}
		// Fallback to get_option if CMB2 is not loaded yet.
		$opts = get_option( 'opal-theme-license', $default );
		$val  = $default;
		if ( 'all' == $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}

		return $val;
	}
}


function maisonco_social_share() {
	if ( maisonco_is_osf_framework_activated() && get_theme_mod( 'osf_socials' ) ) {
		$template = WP_PLUGIN_DIR . '/maisonco-core/templates/socials.php';
		if ( file_exists( $template ) ) {
			require $template;
		}
	}
}

function maisonco_social_heading() {
	return esc_html__( 'SHARE:', 'maisonco' );
}

add_filter( 'osf_social_heading', 'maisonco_social_heading' );

function maisonco_get_post_link( $taxonomy = 'category', $post_type = [ 'any' ] ) {

	$id    = get_queried_object_id(); // Get the current post ID
	$links = [
		'previous_post' => null,
		'next_post'     => null,
		'previous'      => null,
		'next'          => null
	];

	// Use a tax_query to get all posts from the given term
	// Just retrieve the ids to speed up the query
	$post_args = [
		'post_type'      => $post_type,
		'fields'         => 'ids',
		'posts_per_page' => - 1,
	];

	// Get all the posts having the given term from all post types
	$q = get_posts( $post_args );

	//Get the current post position. Will be used to determine next/previous post
	$current_post_position = array_search( $id, $q );

	// Get the previous/older post ID
	if ( array_key_exists( $current_post_position + 1, $q ) ) {
		$previous = $q[ $current_post_position + 1 ];
	}
	// Get post title link to the previous post
	if ( isset( $previous ) ) {
		$previous_post      = get_post( $previous );
		$previous_post_link = get_permalink( $previous );
		$previous_title     = '<a href="' . $previous_post_link . '"><i class="opal-icon-arrow-left" aria-hidden="true"></i></a>';

	}

	// Get the next/newer post ID
	if ( array_key_exists( $current_post_position - 1, $q ) ) {
		$next = $q[ $current_post_position - 1 ];
	}

	// Get post title link to the next post
	if ( isset( $next ) ) {
		$next_post      = get_post( $next );
		$next_post_link = get_permalink( $next );
		$next_title     = '<a href="' . $next_post_link . '"><i class="opal-icon-arrow-right" aria-hidden="true"></i></a>';

	}

	if ( isset( $previous_title ) ) {
		$links['previous_post'] = $previous_title;
		$links['previous']      = $previous_post->ID;
	}

	if ( isset( $next_title ) ) {
		$links['next_post'] = $next_title;
		$links['next']      = $next_post->ID;
	}

	return (object) $links;
}


if ( ! function_exists( 'maisonco_fnc_related_post' ) ) {
	function maisonco_fnc_related_post( $relate_count = 4, $posttype = 'post', $taxonomy = 'category' ) {

		$terms   = get_the_terms( get_the_ID(), $taxonomy );
		$termids = array();

		if ( $terms ) {
			foreach ( $terms as $term ) {
				$termids[] = $term->term_id;
			}
		}

		$args = array(
			'post_type'      => $posttype,
			'posts_per_page' => $relate_count,
			'post__not_in'   => array( get_the_ID() ),
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'id',
					'terms'    => $termids,
					'operator' => 'IN'
				)
			)
		);

		$related = new WP_Query( $args );

		if ( $related->have_posts() ) {
			echo '<div class="related-posts">';
			echo '<h3 class="related-heading">' . esc_html__( 'Related posts', 'maisonco' ) . '</h3>';

			echo '<div class="row">';

			while ( $related->have_posts() ) : $related->the_post();

				get_template_part( 'template-parts/posts-grid/item-post', 'style-3' );

			endwhile;
			echo '</div>';
			echo '</div>';

			wp_reset_postdata();
		}


	}
}

if ( ! function_exists( 'maisonco_get_review_counting' ) ) {
	function maisonco_get_review_counting() {

		global $post;
		$output = array();

		for ( $i = 1; $i <= 5; $i ++ ) {
			$args         = array(
				'post_id'    => ( $post->ID ),
				'meta_query' => array(
					array(
						'key'   => 'rating',
						'value' => $i
					)
				),
				'count'      => true
			);
			$output[ $i ] = get_comments( $args );
		}

		return $output;
	}
}