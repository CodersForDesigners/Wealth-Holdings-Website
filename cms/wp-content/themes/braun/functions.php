<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

require get_template_directory() . '/inc/hooks.php';



add_action( 'template_redirect', function () {

	// If the URL slug is simply `cms`, then forward to the login or admin screen depending on if the user is already logged in or not
	global $wp;
	if ( $wp->request == 'cms' ) {
		nocache_headers();
		$redirectURL = is_user_logged_in() ? get_admin_url() : wp_login_url();
		wp_redirect( $redirectURL, 302, 'BFS' );
		exit;
	}

	// If WordPress is being loaded as a module, then cut short the on the "template routing" and "response preparation".
	if ( \BFS\CMS::$onlySetupContext )
		add_filter( 'template_include', function ( $template ) {
			return get_template_directory() . '/template-stub.php';
		} );

} );

add_action( 'wp_enqueue_scripts', function () {

	// global $versionNumber;
	// $stylesheets = [
	// 	// Base
	// 	'normalize' => '1_normalize.css',
	// 	'base' => '2_base.css',
	// 	'grid' => '3_grid.css',
	// 	'helper' => '4_helper.css',
	// 	'stylescape' => '5_stylescape.css',
	// 	// Modules
	// 	'header' => 'modules/header.css',
	// 	'video-embed' => 'modules/video-embed.css',
	// 	'modal-box' => 'modules/modal-box.css',
	// 	'lazaro-signature' => 'modules/lazaro-signature.css',
	// 	// Pages + Sections + Modals
	// 	'page' => 'pages/page.css',
	// 	'modal-menu' => 'pages/modal/modal-menu.css',
	// 	'sample-section' => 'pages/section/sample-section.css'
	// ];

	// foreach ( $stylesheets as $handle => $stylesheet )
	// 	wp_enqueue_style( $handle, '/../css/' . $stylesheet, [ ], $versionNumber );

	// wp_register_script(
	// 	'twenty-twenty-one-ie11-polyfills-asset',
	// 	get_template_directory_uri() . '/assets/js/polyfills.js',
	// 	array(),
	// 	wp_get_theme()->get( 'Version' ),
	// 	true
	// );

	// wp_add_inline_script(
	// 	'twenty-twenty-one-ie11-polyfills',
	// 	wp_get_script_polyfill(
	// 		$wp_scripts,
	// 		array(
	// 			'Element.prototype.matches && Element.prototype.closest && window.NodeList && NodeList.prototype.forEach' => 'twenty-twenty-one-ie11-polyfills-asset',
	// 		)
	// 	)
	// );

} );

add_action( 'after_setup_theme', function () {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * This theme does not use a hard-coded <title> tag in the document head,
	 * WordPress will provide it for us.
	 */
	if ( class_exists( '\BFS\CMS' ) and ! \BFS\CMS::$onlySetupContext ) {
		add_theme_support( 'title-tag' );
		add_filter( 'document_title_separator', function ( $separator ) {
			return '|';
		} );
	}

	add_theme_support( 'menus' );

	/**
	 * Add post-formats support.
	 */
	add_theme_support( 'post-formats', [
		'link',
		'aside',
		'gallery',
		'image',
		'quote',
		'status',
		'video',
		'audio',
		'chat',
	] );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'small', 400, 400, false );


	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', [
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	] );

	// Add theme support for selective refresh for widgets.
	// add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-style' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'dark-editor-style' );

	// Custom background color.
	add_theme_support( 'custom-background', [
		'default-color' => 'd1e4dd'
	] );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Add support for custom line height controls.
	add_theme_support( 'custom-line-height' );

	// Add support for experimental link color control.
	add_theme_support( 'experimental-link-color' );

	// Add support for experimental cover block spacing.
	add_theme_support( 'custom-spacing' );

	// Add support for custom units.
	// This was removed in WordPress 5.6 but is still required to properly support WP 5.5.
	add_theme_support( 'custom-units' );

	/*
	 *
	 * Templates for the various Post Types
	 *
	 */
	add_filter( 'register_post_type_args', function ( $args, $postType ) {

		if ( $postType === 'investment' ) {
			$args[ 'template' ] = [
				[ 'acf/bfs-investments' ]
			];
			$args[ 'template_lock' ] = 'all';
		}
		else if ( $postType === 'faq' ) {
			$args[ 'template' ] = [
				[ 'core/paragraph', [ 'placeholder' => 'Type in a detailed answer here...' ] ],
				[ 'acf/bfs-faqs' ]
			];
			// $args[ 'template_lock' ] = 'all';
		}
		else if ( $postType === 'brochure' ) {
			$args[ 'template' ] = [
				[ 'acf/bfs-brochures' ]
			];
			$args[ 'template_lock' ] = 'all';
		}
		else if ( $postType === 'tile-link' ) {
			$args[ 'template' ] = [
				[ 'acf/bfs-tile-link' ]
			];
			$args[ 'template_lock' ] = 'all';
		}
		else if ( $postType === 'testimonial' ) {
			$args[ 'template' ] = [
				[ 'acf/bfs-testimonials' ]
			];
			$args[ 'template_lock' ] = 'all';
		}

		return $args;

	}, 20, 2 );



	/*
	 *
	 * Show the Meta-data page if ACF is enabled
	 *
	 */
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( [
			'page_title' => 'Metadata',
			'menu_title' => 'Metadata',
			'menu_slug' => 'metadata',
			'capability' => 'edit_posts',
			'parent_slug' => '',
			'position' => '5',
			'icon_url' => 'dashicons-info'
		] );
	}

} );



/*
 *
 * ----- Custom ACF Gutenberg blocks
 *
 */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_register_block_type' ) )
		return;

	// Investments block
	acf_register_block_type( [
		'name' => 'bfs-investments',
		'title' => __( 'Investments' ),
		'description' => __( 'Investments' ),
		'category' => 'wealth-holdings',
		'icon' => 'money-alt',
		'align' => 'wide',
		'mode' => 'edit',
		'supports' => [
			'multiple' => false,
			'align' => [ 'wide' ]
		],
		'render_callback' => 'acf_render_callback'
	] );

	// FAQs block
	acf_register_block_type( [
		'name' => 'bfs-faqs',
		'title' => __( 'FAQs' ),
		'description' => __( 'FAQs' ),
		'category' => 'wealth-holdings',
		'icon' => 'editor-textcolor',
		'align' => 'wide',
		'mode' => 'edit',
		'supports' => [
			'multiple' => false,
			'align' => [ 'wide' ]
		],
		'render_callback' => 'acf_render_callback'
	] );

	// Brochures block
	acf_register_block_type( [
		'name' => 'bfs-brochures',
		'title' => __( 'Brochures' ),
		'description' => __( 'Brochures' ),
		'category' => 'wealth-holdings',
		'icon' => 'media-document',
		'align' => 'wide',
		'mode' => 'edit',
		'supports' => [
			'multiple' => false,
			'align' => [ 'wide' ]
		],
		'render_callback' => 'acf_render_callback'
	] );

	// Testimonials block
	acf_register_block_type( [
		'name' => 'bfs-testimonials',
		'title' => __( 'Testimonials' ),
		'description' => __( 'Testimonials' ),
		'category' => 'wealth-holdings',
		'icon' => 'testimonial',
		'align' => 'wide',
		'mode' => 'edit',
		'supports' => [
			'multiple' => false,
			'align' => [ 'wide' ]
		],
		'render_callback' => 'acf_render_callback'
	] );

	// Tile Link block
	acf_register_block_type( [
		'name' => 'bfs-tile-link',
		'title' => __( 'Tile Link' ),
		'description' => __( 'A tile that can link to a post, attachment or trigger playback of a video.' ),
		'category' => 'wealth-holdings',
		'icon' => 'testimonial',
		'align' => 'wide',
		'mode' => 'edit',
		'supports' => [
			'multiple' => false,
			'align' => [ 'wide' ]
		],
		'render_callback' => 'acf_render_callback'
	] );

	// Form block
	acf_register_block_type( [
		'name' => 'bfs-form',
		'title' => __( 'Form' ),
		'description' => __( 'Form' ),
		'category' => 'wealth-holdings',
		'icon' => 'feedback',
		'align' => 'wide',
		'mode' => 'edit',
		'supports' => [
			'multiple' => false,
			'align' => [ 'wide' ]
		],
		'render_template' => get_template_directory() . '/inc/blocks/form.php'
	] );

	function acf_render_callback ( $block, $content, $is_preview, $post_id ) {
		if ( ! class_exists( '\BFS\CMS' ) )
			return;

		\BFS\CMS::$currentQueriedPostACF = array_merge( \BFS\CMS::$currentQueriedPostACF, get_fields() ?: [ ] );
	}

} );



add_action( 'bfs/backend/on-editing-posts', function ( $postType ) {

	// Add a custom block category
	add_filter( 'block_categories', function ( $categories ) {
		return array_merge( $categories, [
			[
				'slug' => 'wealth-holdings',
				'title' => __( 'Wealth Holdings', 'bfs' ),
				'icon' => 'money'
			]
		] );
	} );

	// Only allow access to certain types of blocks
	wp_enqueue_script(
		'bfs-block-access',
		get_template_directory_uri() . '/js/block-access.js',
		[ 'wp-data', 'wp-edit-post' ],
		filemtime( get_template_directory() . '/js/block-access.js' )
	);

} );


/**
 * Remove the `no-js` class from body if JS is supported.
 */
add_action( 'wp_footer', function () {
	echo '<script>document.body.classList.remove("no-js");</script>';
} );



/*
 *
 * ----- Tile Links
 *	Capture certain ACF values and store them as native post (or post meta) attributes, or tags.
 *	This is to optimize the post querying.
 *
 */
function tileLink__SavePostHook ( $postId, $post, $postWasUpdated ) {

	if ( get_post_type( $postId ) !== 'tile-link' )
		return;

	require_once __DIR__ . '/../../../../../inc/cms.php';

	// Unregister the save_post action hook to prevent an infinite loop
	remove_action( 'save_post_tile-link', 'tileLink__SavePostHook', 100, 3 );

	$thePost = \BFS\CMS::getPostById( $postId );

	/*
	 * Capture the "title" value as the post title
	 */
	// Strip away all the HTML and newline characters
	$title = strip_tags( str_replace( "\r\n", ' ', $thePost->get( 'tile_title' ) ) );
	wp_update_post( [ 'ID' => $postId, 'post_title' => $title ], false, false );


	/*
	 * Capture the "Feature in" value as a tag
	 */
	$tags = array_map( function ( $tag ) {
		return 'for-' . $tag;
	}, $thePost->get( 'feature_on' ) );

	$allExistingPostTags = wp_get_post_tags( $postId, [ 'fields' => 'slugs' ] );
	$allOtherTags = array_filter( $allExistingPostTags, function ( $tag ) {
		return substr( $tag, 0, 4 ) != 'for-';
	} );

	$tagsToSet = array_merge( $allOtherTags, $tags );

	wp_set_post_tags( $postId, $tagsToSet, false );

	// Re-register the action hook
	add_action( 'save_post_tile-link', 'tileLink__SavePostHook', 100, 3 );

}
// Register a `save_post` action hook for the Tile Link post
add_action( 'save_post_tile-link', 'tileLink__SavePostHook', 100, 3 );



/*
 *
 * ----- robots.txt
 * 	Disable the default one.
 *
 */
add_filter( 'robots_txt', function ( $output, $isSitePublic ) {
	if ( ! $isSitePublic ) {
		$output = 'User-agent: *'
				. "\n"
				. 'Disallow: /'
				. "\n"
				. 'Disallow: /*'
				. "\n"
				. 'Disallow: /*?'
				. "\n";
	}
	else
		$output = '';

	return $output;
}, 100, 2 );
