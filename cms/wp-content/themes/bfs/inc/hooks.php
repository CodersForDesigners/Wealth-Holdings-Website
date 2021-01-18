<?php

add_action( 'init', function () {

	if ( is_admin() )
		do_action( 'bfs/init/backend' );
	else
		do_action( 'bfs/init/frontend' );

} );


add_action( 'bfs/init/backend', 'setupActionForEnqueuingAssetsOnBackend' );

function setupActionForEnqueuingAssetsOnBackend () {

	global $pagenow;

	// Are we on the post creation or post editing page?
	if ( ! ( $pagenow === 'post.php' or $pagenow === 'post-new.php' ) )
		return;

	global $post;

	$postType = null;
	if ( ! empty( $_GET[ 'post' ] ) )
		$postType = get_post_type( $_GET[ 'post' ] );
	else if ( ! empty( $_GET[ 'post_type' ] ) )
		$postType = $_GET[ 'post_type' ];
	else if ( ! empty( $post ) )
		$postType = get_post_type( $post->ID );

	do_action( 'bfs/backend/on-editing-posts', $postType );

};
