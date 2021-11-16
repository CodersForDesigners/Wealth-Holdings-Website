<?php

require_once ABSPATH . '/../conf.php';


/**
 |
 | Add a custom Gutenberg block category
 |
 |
 */
add_action( 'bfs/backend/on-editing-posts', function ( $postType ) {

	// Add a custom block category
	add_filter( 'block_categories', function ( $categories ) {
		return array_merge( $categories, [
			[
				'slug' => CLIENT_SLUG,
				'title' => __( 'Wealth Holdings', 'bfs' ),
				'icon' => 'money'
			]
		] );
	} );

} );
