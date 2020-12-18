<?php
/*
 *
 * This script sets up a global-level settings page.
 *
 */



/*
 *
 * Prevent auto-"correction" of URLs
 * 	Based on `https://core.trac.wordpress.org/ticket/16557`
 *
 */
add_filter( 'redirect_canonical', function ( $redirectUrl ) {
	if ( is_404() && ! isset( $_GET[ 'p' ] ) )
		return false;
	else
		return $redirectUrl;
} );



function bfs_theme_setup () {

	/*
	 * Theme Supports
	 *
	 * Register support for certain features
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
	 */
	// Enable support for Post Thumbnails on posts and pages.
	// @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'menus' );
	add_theme_support( 'editor-style' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'dark-editor-style' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );



	/*
	 *
	 * Media Settings
	 *
	 */
	add_image_size( 'small', 400, 400, false );



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

}

add_action( 'after_setup_theme', 'bfs_theme_setup' );
