<?php

/*
 * Theme Supports
 *
 * Register support for certain features
 *
 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
 */


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


/*
 * Enable support for Post Thumbnails on posts and pages.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */
add_theme_support( 'post-thumbnails' );

add_theme_support( 'menu' );
add_theme_support( 'menus' );

// Full and wide align images
add_theme_support( 'align-wide' );

// Responsive embeds
add_theme_support( 'responsive-embeds' );

// Add support for Block Styles.
add_theme_support( 'wp-block-styles' );

// Add support for full and wide align images.
add_theme_support( 'align-wide' );

// Add support for editor styles.
add_theme_support( 'editor-styles' );
$backgroundColor = get_theme_mod( 'background-color', 'D1E4DD' );
// If the background color is dark, then enable dark mode for Gutenberg
if ( getRelativeLuminanceFromHex( $backgroundColor ) <= 127 )
	add_theme_support( 'dark-editor-style' );

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
