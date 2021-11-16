<?php

add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin() )
		return;
	// Remove some default and stylesheets
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
} );
