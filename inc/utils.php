<?php





/*
 *
 * Pull in the WordPress files if possible
 *
 */
function initWordPress () {

	if ( cmsIsEnabled() )
		return;

	$configFile = __DIR__ . '/../cms/wp-config.php';
	$configFile__AlternateLocation = __DIR__ . '/../wp-config.php';
	if ( file_exists( $configFile ) || file_exists( $configFile__AlternateLocation ) ) {
		$includeStatus = include_once __DIR__ . '/../cms/index.php';
		if ( $includeStatus ) {
			global $cmsHasBeenLoaded;
			// global $onlyPrepareWPContext;
			$cmsHasBeenLoaded = true;
			// $onlyPrepareWPContext = true;
			$GLOBALS[ 'onlyPrepareWPContext' ] = true;
			// establishContext();
		}
	}
}


/*
 *
 * Is the CMS enabled?
 *
 */
function cmsIsEnabled () {
	global $cmsHasBeenLoaded;
	return $cmsHasBeenLoaded;
}


/*
 *
 * Set up global variables
 *
 */
$siteUrl = ( HTTPS_SUPPORT ? 'https://' : 'http://' ) . $_SERVER[ 'HTTP_HOST' ];
if ( ! isset( $cmsHasBeenLoaded ) )
	$cmsHasBeenLoaded = false;
$postId = null;





/*
 *
 * Fetch a navigation menu and structure it accordingly
 *
 */
function getNavigationMenu ( $name ) {

	if ( ! cmsIsEnabled() ) {
		$menuItems = require_once __DIR__ . '/default-nav-links.php';
		return $menuItems;
	}

	$menuItems = getContent( [ ], $name, 'navigation' );

	foreach ( $menuItems as &$item ) {
		$itemUrl = $item[ 'url' ];

		// If the item has a contextual URL override
		$field = getContent( '', 'nav_override_from_field', $item[ 'ID' ] );
		if ( ! empty( $field ) and ! empty( getContent( '', $field ) ) ) {
			$itemUrl = getContent( '', $field );
			// If the override value is a phone number, perform some modifications
			if ( preg_match( '/^\+?[\d\s\-]+$/', $itemUrl ) ) {
				// Replace the navigation item's label as well
				$item[ 'title' ] = $itemUrl;
				// Prepend the `tel:` protocol to the URL
				$itemUrl = 'tel:' . str_replace( [ ' ', '-' ], '', $itemUrl );
			}
		}

		// If the item is an in-page (section) link, i.e. it starts with a `#`
		if ( ! empty( $itemUrl[ 0 ] ) and $itemUrl[ 0 ] === '#' ) {
			global $requestPath;
			$itemUrl = $requestPath . $itemUrl;
			$item[ 'type' ] = 'in-page';
			$item[ 'classes' ][ ] = 'hidden';
		}

		// If the item is a "post-selector"
		$item[ 'selectorOf' ] = getContent( '', 'post-type-selector', $item[ 'ID' ] );
		if ( ! empty( $item[ 'selectorOf' ] ) ) {
			global $thePost;
			$item[ 'type' ] = 'post-selector';
			$item[ 'posts' ] = getPostsOf( $item[ 'selectorOf' ], null, $thePost[ 'ID' ] ?? [ ] );
			$item[ 'classes' ][ ] = 'no-pointer';
		}
		else
			$item[ 'classes' ][ ] = 'clickable';

		// Finally, re-shape the data-structure to include all the relevant fields
		$item = [
			'label' => $item[ 'title' ],
			'url' => $itemUrl,
			'classes' => implode( ' ', $item[ 'classes' ] ),
			'type' => $item[ 'type' ] ?? '',
			'selectorOf' => $item[ 'selectorOf' ],
			'posts' => $item[ 'posts' ] ?? [ ]
		];
	}
	unset( $item );

	return $menuItems;
}



/*
 *
 * Dump the values on the page and onto JavaScript memory, finally end the script
 *
 */
function dd ( $data ) {

	echo '<pre>';
		var_dump( [ 'memory usage' => memory_get_usage() ] );
	echo '</pre>';

	echo '<pre>';
		var_dump( $data );
	echo '</pre>';

	echo '<pre>';
		var_dump( [ 'memory usage' => memory_get_usage() ] );
	echo '</pre>';

	echo '<script>';
		echo '__data = ' . json_encode( $data ) . ';';
	echo '</script>';

	exit;

}
