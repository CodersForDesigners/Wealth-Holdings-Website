<?php

/*
 |
 | General Block settings
 |
 */
add_action( 'enqueue_block_editor_assets', function () {
	wp_enqueue_script(
		'bfs-guten-script',
		get_template_directory_uri() . '/js/blocks.js',
		[ 'wp-dom-ready', 'wp-blocks', 'wp-edit-post' ],
		filemtime( get_template_directory() . '/js/blocks.js' )
	);
} );
