<?php

require_once __DIR__ . '/../conf.php';


if ( HTTPS_SUPPORT )
	$httpProtocol = 'https';
else
	$httpProtocol = 'http';

if ( ! empty( CMS_REMOTE_ADDRESS ) ) {
	$remoteAddress = $httpProtocol . '://' . CMS_REMOTE_ADDRESS . $_SERVER[ 'REQUEST_URI' ];
	return header( 'Location: ' . $remoteAddress, true, 302 );
}
else
	http_response_code( 404 );
