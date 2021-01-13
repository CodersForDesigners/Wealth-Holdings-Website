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
			global $cmsIsEnabled;
			$cmsIsEnabled = true;
			establishContext();
		}
	}
}


/*
 *
 * Is the CMS enabled?
 *
 */
function cmsIsEnabled () {
	global $cmsIsEnabled;
	return $cmsIsEnabled;
}


/*
 *
 * Set up global variables
 *
 */
$siteUrl = ( isOnHTTPS() ? 'https://' : 'http://' ) . $_SERVER[ 'HTTP_HOST' ];
$cmsIsEnabled = false;
$thePost = null;
$postId = null;



/*
 *
 * Fetch the post object corresponding to the route.
 *	If neither a post object, nor a page file for the route exists, respond and re-direct.
 *
 */
function establishContext () {

	global $thePost;
	global $postId;
	global $postType;
	global $urlSlug;
	global $siteUrl;
	global $hasDedicatedTemplate;

	$thePost = getCurrentPost( $urlSlug, $postType );
	if ( ! empty( $thePost ) )
		$postType = $thePost->get( 'post_type' );

	if ( empty( $thePost ) and ! in_array( $postType, [ 'page', null ] ) ) {
		// echo 'Please create a corresponding page or post with the slug' . '"' . $urlSlug . '"' . 'in the CMS.';
		http_response_code( 404 );
		return header( 'Location: /', true, 302 );
		exit;
	}
	// If there is neither a corresponding post in the database nor a dedicated template for the given route, return a 404 and redirect
	else if ( empty( $thePost ) and ! $hasDedicatedTemplate ) {
		http_response_code( 404 );
		return header( 'Location: /', true, 302 );
		exit;
	}
	else if ( ! empty( $thePost ) )
		$postId = $thePost->get( 'ID' );

}



/*
 *
 * Get all posts of a certain type
 *
 */
function getPostsOf ( $type, $limit = -1, $exclude = [ ] ) {

	$limit = $limit ?: -1;
	if ( ! is_array( $exclude ) )
		if ( is_int( $exclude ) )
			$exclude = [ $exclude ];

	$posts = get_posts( [
	    'post_type' => $type,
	    'post_status' => 'publish',
	    'numberposts' => $limit,
	    // 'order' => 'ASC'
	    'orderby' => 'date',
	    'exclude' => $exclude
	] );

	foreach ( $posts as &$post ) {
		$post = get_object_vars( $post );
	}
	unset( $post );

	return $posts;

}

/*
 *
 * Pull custom content from ACF fields and native post fields from WordPress
 *
 */

/*
 *
 * Pull custom content from ACF fields and native post fields from WordPress
 *
 */
function getContent ( $fallback, $field, $context = null ) {

	if ( ! function_exists( 'get_field' ) )
		return $fallback;

	global $thePost;
	global $postType;

	// Setting this value here; used when searching for value recursively
	$contexts = $context ? [ ] : [ 'options' ];

	if ( empty( $context ) ) {
		// If the page is contextual to a post, then set that as the context
		$context = $thePost ? $thePost->get( 'ID' ) : 'options';
	}
	else if ( is_string( $context ) ) {
		if ( $context === 'navigation' ) {
			$navigationItems = wp_get_nav_menu_items( $field );
			if ( is_array( $navigationItems ) ) {
				foreach ( $navigationItems as &$item )
					$item = get_object_vars( $item );
					// $item = (array) $item;
				return $navigationItems;
			}
			else
				return $fallback;
		}
		else {
			$page = BFS\CMS::getPostBySlug( $context );
			if ( empty( $page ) or empty( $page[ 'ID' ] ) )
				$context = 'options';
			else
				$context = $page[ 'ID' ];
		}
	}


	if ( $context !== 'options' )
		array_unshift( $contexts, $context );
	$fieldParts = preg_split( '/\s*->\s*/' , $field );
	foreach ( $contexts as $currentContext ) {
		if ( $currentContext === 'options' )
			$content = get_field( $fieldParts[ 0 ], 'option' );
		else {
			$postCache = BFS\CMS::$cache[ $currentContext ] ?? BFS\CMS::getPostById( $currentContext ) ?? BFS\CMS::getPostBySlug( $currentContext );
			if ( empty( $postCache ) )
				continue;

			if ( ! empty( $postCache[ 'acf' ] ) )
				$content = $postCache[ 'acf' ][ $fieldParts[ 0 ] ] ?? get_field( $fieldParts[ 0 ], $postCache[ 'ID' ] ) ?? null;
		}


		// If no content was found, search in underlying native post object
		if ( empty( $content ) and ! empty( $thePost ) ) {
			if ( $currentContext and ( ! is_string( $currentContext ) ) )
				$content = $thePost->get( $fieldParts[ 0 ] ) ?? null;
			if ( empty( $content ) )
				continue;
		}

		$remainderFieldParts = array_slice( $fieldParts, 1 );
		foreach ( $remainderFieldParts as $namespace )
			$content = $content[ $namespace ] ?? [ ];

		if ( ! empty( $content ) )
			break;
	}

	if ( empty( $content ) )
		return $fallback;
	else
		return $content;

}



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
			$item[ 'posts' ] = getPostsOf( $item[ 'selectorOf' ], null, $thePost->get( 'ID' ) ?? [ ] );
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
 * Attempts to determine if the site is running on HTTPS.
 *  Borrowed code from the WordPress's `is_ssl` function.
 *
 */
function isOnHTTPS () {

	if ( isset( $_SERVER[ 'HTTPS' ] ) ) {
		if ( strtolower( $_SERVER['HTTPS'] ) == 'on' )
			return true;
		if ( $_SERVER[ 'HTTPS' ] == '1' )
			return true;
	}

	if ( isset( $_SERVER[ 'SERVER_PORT' ] ) )
		if ( $_SERVER[ 'SERVER_PORT' ] == '443' )
			return true;

	if ( isset( $_SERVER[ 'REQUEST_SCHEME' ] ) )
		if ( $_SERVER[ 'REQUEST_SCHEME' ] == 'https' )
			return true;

	return false;

}



/*
 *
 * Get the current post that the url is refering to
 *
 */
function getCurrentPost ( $slug, $type = null ) {

	if ( ! cmsIsEnabled() )
		return null;

	return BFS\CMS::getPostBySlug( $slug, $type );

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
