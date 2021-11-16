<?php

/*
 |
 | Prevent auto-"correction" of URLs
 | 	Based on `https://core.trac.wordpress.org/ticket/16557`
 |
 */
add_filter( 'redirect_canonical', function ( $redirectUrl ) {
	if ( is_404() && ! isset( $_GET[ 'p' ] ) )
		return false;
	else
		return $redirectUrl;
} );
