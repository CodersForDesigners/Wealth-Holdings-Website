<?php




require_once __DIR__ . '/conf.php';

/*
 * -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-
 *  Set some useful variables
 * -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-
 */
$documentRoot = $_SERVER[ 'DOCUMENT_ROOT' ];
$homePage = $documentRoot . '/pages/home.php';
// $_requestPathParts = explode( '?', $_SERVER[ 'REQUEST_URI' ], -1 );
$requestPath = trim(
	strstr( $_SERVER[ 'REQUEST_URI' ], '?', true ) ?: $_SERVER[ 'REQUEST_URI' ],
	'/'
) ?: 'home';
$postType = null;
$urlSlug = '';
// Does this route have a dedicated template?
$hasDedicatedTemplate = false;



/*
 * -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-
 *  Route the request to the correct file
 * -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-
 */
// Home page
if ( $requestPath === 'home' ) {
	$hasDedicatedTemplate = true;
	$urlSlug = 'home';
	$_GET[ '_slug' ] = 'home';
	return require_once $homePage;
}

// Every other page
$filename = $documentRoot . '/pages/' . $requestPath . '.php';
if ( file_exists( $filename ) ) {
	$hasDedicatedTemplate = true;
	// Set a query param
	$urlSlug = $requestPath;
	$_GET[ '_slug' ] = $requestPath;
	return require_once $filename;
}
else if ( count( explode( '/', $requestPath ) ) === 2 ) {
	[ $postType, $urlSlug ] = explode( '/', $requestPath );
	$_GET[ '_slug' ] = $urlSlug;
	// $_GET[ '_post_type' ] = $postType;
	$filename = $documentRoot . '/pages/' . $postType . '.php';
	return require_once $filename;
}
// Else fallback to the default template
else {
	$postType = 'post';
	$urlSlug = $requestPath;
	$filename = $documentRoot . '/pages/default.php';
	return require_once $filename;
}
