<?php

namespace BFS\CMS;

require_once __ROOT__ . '/lib/routing.php';

class WordPress {

	private static $dir = __DIR__;

	public static $isEnabled = null;
	public static $hasRouted = false;
	public static $onlySetupContext = false;

	public static $thisPostCache = null;
	// Entries are dynamically populated
		// The keys are post ids and values are the post objects.
	public static $postCache = [ ];
	// Map of post slugs to ids
	public static $postIdsBySlug = [ ];

	/*
	 * ----- When a post is queried and the `the_content` filter is applied to it, this is the reference where it attaches its data to
	 */
	public static $currentQueriedPostACF = [ ];



	public static function load () {
		self::$isEnabled = true;
		define( 'WP_USE_THEMES', false );
		require_once __ROOT__ . CMS_WORDPRESS_DIR . '/wp-load.php';
		// add_filter( 'wp_using_themes', '__return_false' );
		wp();
	}

	private static function resolveRouteToTemplate () {
		if ( ! defined( 'WP_USE_THEMES' ) )
			define( 'WP_USE_THEMES', true );
		add_filter( 'wp_using_themes', '__return_true' );
		require_once ABSPATH . WPINC . '/template-loader.php';
	}

	public static function route () {
		if ( self::$hasRouted )
			return;

		self::$hasRouted = true;
		self::load();
		self::resolveRouteToTemplate();
	}

	public static function setupContext ( $contextURL = null ) {

		// Override the `REQUEST_URI` server environment value
		if ( ! empty( $contextURL ) ) {
			$originalRequestURI = $_SERVER[ 'REQUEST_URI' ];
			$_SERVER[ 'REQUEST_URI' ] = $contextURL;
		}

		self::$onlySetupContext = true;
		if ( self::$isEnabled )
			return;

		// self::$isEnabled = true;
		self::load();

		// Restore the `REQUEST_URI` server environment value
		if ( ! empty( $contextURL ) )
			$_SERVER[ 'REQUEST_URI' ] = $originalRequestURI;

	}

	public static function getPostType ( $post ) {
		return get_post_type( $post );
	}

	public static function getThisPost () {

		if ( self::$thisPostCache )
			return new Content( self::$thisPostCache[ 'ID' ] );

		global $post;

		if ( ! $post )
			return null;

		$postObject = get_object_vars( $post );

		// Reset the var where the ACF data will be stored
			// 1. Fetch the ACF fields that are not blocks
		if ( function_exists( 'get_fields' ) )
			self::$currentQueriedPostACF = get_fields( $postObject[ 'ID' ] ) ?: [ ];
			// 2. Fetch the ACF fields that are blocks
		apply_filters( 'the_content', $postObject[ 'post_content' ] );
		// Neatly store all the ACF fields in a sub-field
		if ( function_exists( 'get_fields' ) )
			$postObject[ 'acf' ] = self::$currentQueriedPostACF;

		// Create the custom field stub
		$postObject[ '__custom' ] = [ ];

		self::$postCache[ $postObject[ 'ID' ] ] = $postObject;

		return new Content( $postObject[ 'ID' ] );
	}

	public static function get ( $key, $fallback = null ) {

		$value = self::$postCache[ $key ] ?? null;

		if ( empty( $value ) ) {
			// Get all the parts of the key
			$keyParts = explode( ' / ', $key );

			if ( function_exists( 'get_field' ) )
				$value = get_field( $keyParts[ 0 ], 'options' );
			if ( empty( $value ) and function_exists( 'get_option' ) )
				$value = get_option( $keyParts[ 0 ], null );

			// If a nested field is being queried, query through all the nested keys
			if ( count( $keyParts ) > 1 and ! empty( $value ) ) {
				$remainderKeyParts = array_slice( $keyParts, 1 );
				foreach ( $remainderKeyParts as $keyPart )
					$value = $value[ $keyPart ] ?? [ ];
			}

			// If no value is found, return the fallback
			if ( empty( $value ) )
				return $fallback;
		}

		// Cache the value *only* if it exists
		self::$postCache[ $key ] = $value;

		return $value;

	}

	public static function findPostsOf ( $type, $options = [ ], $callback = null ) {

		if ( ! self::$isEnabled )
			return [ ];

		$limit = $options[ 'limit' ] ?? -1;
		$exclude = $options[ 'exclude' ] ?? [ ];
		if ( ! is_array( $exclude ) )
			if ( is_int( $exclude ) )
				$exclude = [ $exclude ];

		$postsFromDB = get_posts( array_merge( [
			'post_type' => $type,
			'post_status' => 'publish',
			'numberposts' => $limit,
			// 'order' => 'ASC'
			'orderby' => 'date',
			'exclude' => $exclude
		], $options ) );

		$posts = [ ];
		// $callback = is_callable( $callback ) ? $callback : ( fn ( $i ) => $i );	// if using PHP 8
		$callback = is_callable( $callback ) ? $callback : ( function ( $i ) { return $i; } );

		foreach ( $postsFromDB as &$post ) {
			// if ( isset( self::$postCache[ $post->ID ] ) ) {
			// 	$post = self::$postCache[ $post->ID ];
			// 	$posts[ ]
			// 	continue;
			// }

			$post = get_object_vars( $post );

			// Reset the var where the ACF data will be stored
				// 1. Fetch the ACF fields that are not blocks
			if ( function_exists( 'get_fields' ) )
				self::$currentQueriedPostACF = get_fields( $post[ 'ID' ] ) ?: [ ];
				// 2. Fetch the ACF fields that are blocks
			apply_filters( 'the_content', $post[ 'post_content' ] );
			// Neatly store all the ACF fields in a sub-field
			if ( function_exists( 'get_fields' ) )
				$post[ 'acf' ] = self::$currentQueriedPostACF;

			// Create the custom field stub
			$post[ '__custom' ] = [ ];

			// Cache the post
			self::$postCache[ $post[ 'ID' ] ] = $post;

			$posts[ ] = call_user_func( $callback, new Content( $post[ 'ID' ] ) );

		}

		// foreach ( $postsFromDB as &$post )
		// 	$posts[ ] = new Content( $post[ 'ID' ] );

		return $posts;

	}

	public static function findPostById ( $id ) {

		if ( isset( self::$postCache[ $id ] ) )
			return new Content( self::$postCache[ $id ] );

		$postFromDB = get_post( $id, ARRAY_A ) ?? null;

		if ( ! $postFromDB )
			return null;

		$post = $postFromDB;

		// Reset the var where the ACF data will be stored
			// 1. Fetch the ACF fields that are not blocks
		if ( function_exists( 'get_fields' ) )
			self::$currentQueriedPostACF = get_fields( $post[ 'ID' ] ) ?: [ ];
			// 2. Fetch the ACF fields that are blocks
		apply_filters( 'the_content', $post[ 'post_content' ] );
		// Neatly store all the ACF fields in a sub-field
		if ( function_exists( 'get_fields' ) )
			$post[ 'acf' ] = self::$currentQueriedPostACF;

		// Create the custom field stub
		$post[ '__custom' ] = [ ];

		// Cache the post
		self::$postCache[ $id ] = $post;

		return new Content( $post[ 'ID' ] );

	}

	public static function findPostBySlug ( $slug, $type = null ) {

		$cachedPostId = self::$postIdsBySlug[ $slug ] ?? false;
		if ( $cachedPostId )
			return new Content( self::$postCache[ $cachedPostId ] );

		$type = $type ?: [ 'post', 'page', 'attachment' ];

		$postFromDB = get_page_by_path( $slug, OBJECT, $type ) ?? null;

		if ( ! $postFromDB )
			return null;

		$post = get_object_vars( $postFromDB );

		// Reset the var where the ACF data will be stored
			// 1. Fetch the ACF fields that are not blocks
		if ( function_exists( 'get_fields' ) )
			self::$currentQueriedPostACF = get_fields( $post[ 'ID' ] ) ?: [ ];
			// 2. Fetch the ACF fields that are blocks
		apply_filters( 'the_content', $post[ 'post_content' ] );
		// Neatly store all the ACF fields in a sub-field
		if ( function_exists( 'get_fields' ) )
			$post[ 'acf' ] = self::$currentQueriedPostACF;

		// Create the custom field stub
		$post[ '__custom' ] = [ ];

		// Cache the post
		self::$postCache[ $post[ 'ID' ] ] = $post;
		self::$postCache[ $slug ] = $post;

		return new Content( $post[ 'ID' ] );

	}

	public static function getNavigation ( $name, $urlPrefixForRelativeSectionURLs = false ) {

		if ( ! self::$isEnabled )
			return [ ];

		$menuItems = \wp_get_nav_menu_items( $name ) ?: [ ];
		// Convert from class instances to regular associate arrays, and append any custom data (if there is)
		foreach ( $menuItems as &$item ) {
			$item = get_object_vars( $item );
			$item[ 'acf' ] = get_fields( $item[ 'ID' ] );
		}
		unset( $item );

		// Relatively contextualize URLs that require it
		$urlSlugParts = explode( '/', \BFS\Router::getSanitizedURLSlug() );
		foreach ( $menuItems as &$item ) {
			$contextualizeToANestingLevel = $item[ 'acf' ][ 'relatively_contextualize_to_a_nesting_level' ];
			if ( ! $contextualizeToANestingLevel )
				continue;
			$nestingLevel = $item[ 'acf' ][ 'relatively_contextual_nesting_level' ];
			$item[ 'url' ] = implode( '/', array_slice( $urlSlugParts, 0, $nestingLevel ) )
						. $item[ 'url' ];
		}
		unset( $item );


		// Prepend a prefix (if provided) for URLs that are relative and start with a `#`
		if ( $urlPrefixForRelativeSectionURLs )
			foreach ( $menuItems as &$item )
				if ( $item[ 'url' ][ 0 ] === '#' )
					$item[ 'url' ] = $urlPrefixForRelativeSectionURLs . $item[ 'url' ];
		unset( $item );

		return $menuItems;

	}

	public static function enqueueScript ( $slug, $pathRelativeToTheme, $dependencies = [ ] ) {

		return wp_enqueue_script(
			'bfs-' . $slug,
			get_template_directory_uri() . $pathRelativeToTheme,
			array_merge( [ 'wp-data', 'wp-hooks', 'wp-edit-post' ], $dependencies ),
			filemtime( get_template_directory() . $pathRelativeToTheme )
		);

	}

	public static function acfRenderCallback ( $block, $content, $is_preview, $post_id ) {
		self::$currentQueriedPostACF = array_merge(
			self::$currentQueriedPostACF,
			get_fields() ?: [ ]
		);
	}

	/*
	 |
	 | Set up the following custom hooks
	 | 	bfs/init/backend -> action that fires only on **backend** pages/routes
	 | 	bfs/init/frontend -> action that fires only on **frontent** pages/routes
	 | 	bfs/backend/on-editing-posts -> action that fires when editing a post (of any type) on the backend
	 |
	 */
	public static function setupHooks () {

		add_action( 'init', function () {

			if ( is_admin() or is_customize_preview() )
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

	}

}

class Content {

	private $postIdentifier;
	private $postId;
	private $postSlug;
	private $postType;

	// private $post;

	public function __construct ( $postIdOrSlug, $postType = null ) {
		if ( is_string( $postIdOrSlug ) ) {
			$this->postIdentifier = $postIdOrSlug;
			$this->postSlug = $postIdOrSlug;
			if ( ! empty( $postType ) ) {
				$this->postType = $postType;
				$this->postIdentifier = $postType . '/' . $this->postIdentifier;
			}
		}
		else {
			$this->postId = $postIdOrSlug;
			$this->postIdentifier = $postIdOrSlug;
		}
	}

	public function get ( $key ) {

		// Get a reference to the post's cache
		$postCache = WordPress::$postCache[ $this->postIdentifier ];

		switch ( $key ) {
			case 'content': {
				if ( ! $postCache[ 'content' ] )
					$postCache[ 'content' ] = apply_filters( 'the_content', $postCache[ 'post_content' ] );
				return $postCache[ 'content' ];
			}
		}

		// Get all the parts of the key
		$keyParts = explode( ' / ', $key );
		$baseKey = $keyParts[ 0 ];

		// Get the value from the cache
		if ( isset( $postCache[ '__custom' ][ $baseKey ] ) )
			$value = $postCache[ '__custom' ][ $baseKey ];
		else if ( ! empty( $postCache[ 'acf' ] ) and isset( $postCache[ 'acf' ][ $baseKey ] ) )
			$value = $postCache[ 'acf' ][ $baseKey ];
		else if ( isset( $postCache[ $baseKey ] ) )
			$value = $postCache[ $baseKey ];
		else
			return null;

		// If a nested field is being queried, query through all the nested keys
		if ( count( $keyParts ) > 1 and ! empty( $value ) ) {
			$remainderKeyParts = array_slice( $keyParts, 1 );
			foreach ( $remainderKeyParts as $keyPart )
				$value = $value[ $keyPart ] ?? null;
		}

		return $value;

	}

	public function getJSON ( ...$keys ) {

		if ( count( $keys ) > 0 ) {
			foreach ( $keys as $key )
				$post[ $key ] = $this->get( $key );
			return json_encode( $post );
		}

		// Get a reference to the post's cache
		$postCache = WordPress::$postCache[ $this->postIdentifier ];

		// Merge and overwrite the custom fields on top of the default ones
		$post = array_merge(
			$postCache,
			$postCache[ 'acf' ],
			$postCache[ '__custom' ]
		);
		unset( $post[ 'acf' ], $post[ '__custom' ] );

		return json_encode( $post );

	}

	public function getAll () {
		return WordPress::$postCache[ $this->postIdentifier ];
	}

	public function set ( $key, $value ) {
		WordPress::$postCache[ $this->postIdentifier ][ '__custom' ][ $key ] = $value;
		return $this;
	}

}
