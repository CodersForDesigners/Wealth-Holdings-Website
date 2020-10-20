<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package Brownie Fudge Sundae
 * @since 1.0.0
 */

require_once __DIR__ . '/../../../../conf.php';

# A convenient redirect to the login page
$requestURI = $_SERVER[ 'REQUEST_URI' ];
$theURLEndsWithCMS = preg_match( '/\/+cms\/+$/', $requestURI );
if ( $theURLEndsWithCMS ) {
	$adminPageURL = preg_replace( '/\/+/', '/', $requestURI . '/wp-admin' );
	$domainName = ( $_SERVER[ 'HTTP_HOST' ] ?: $_SERVER[ 'SERVER_NAME' ] );
	if ( ( ! empty( CMS_REMOTE_ADDRESS ) ) and ( $domainName !== CMS_REMOTE_ADDRESS ) ) {
		if ( $_SERVER[ 'SERVER_PORT' ] === 443 )
			$protocol = 'https://';
		else
			$protocol = 'http://';
		return header( 'Location: ' . $protocol . CMS_REMOTE_ADDRESS . '/' . $adminPageURL, true, 302 );
	}
	return header( 'Location: ' . $adminPageURL );
}

if ( is_user_logged_in() )
	get_footer();
