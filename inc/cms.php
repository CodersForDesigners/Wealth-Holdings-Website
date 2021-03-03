<?php

namespace BFS;

class CMS {

	private static $dir = __DIR__;

	public static $isEnabled = null;
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
	public static $currentQueriedPostId = null;



	public static function route () {
		if ( self::$isEnabled )
			return;

		// $configFile = self::$dir . '/../cms/wp-config.php';
		// $configFile__AlternateLocation = self::$dir . '/../wp-config.php';
		self::$isEnabled = true;
		$includeStatus = include_once self::$dir . '/../cms/index.php';
	}

	public static function setupContext () {
		self::$onlySetupContext = true;
		if ( self::$isEnabled )
			return;

		self::$isEnabled = true;
		$includeStatus = include_once self::$dir . '/../cms/index.php';
	}

	public static function getThisPost () {

		if ( self::$thisPostCache )
			return new Content( self::$thisPostCache[ 'ID' ] );

		global $post;

		if ( ! $post )
			return null;

		$postObject = get_object_vars( $post );

		// Reset the var where the ACF data will be stored
		self::$currentQueriedPostId = $postObject[ 'ID' ];
			// 1. Fetch the ACF fields that are not blocks
		if ( function_exists( 'get_fields' ) )
			self::$currentQueriedPostACF = get_fields( $postObject[ 'ID' ] ) ?: [ ];
			// 2. Fetch the ACF fields that are blocks
		$postObject[ 'post_content' ] = apply_filters( 'the_content', $postObject[ 'post_content' ] );
		// Neatly store all the ACF fields in a sub-field
		if ( function_exists( 'get_fields' ) )
			$postObject[ 'acf' ] = self::$currentQueriedPostACF;
		self::$currentQueriedPostId = null;

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

	public static function getPostsOf ( $type, $options = [ ] ) {

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


		foreach ( $postsFromDB as &$post ) {
			if ( isset( self::$postCache[ $post->ID ] ) ) {
				$post = self::$postCache[ $post->ID ];
				continue;
			}

			$post = get_object_vars( $post );
			// Reset the var where the ACF data will be stored
			self::$currentQueriedPostId = $post[ 'ID' ];
				// 1. Fetch the ACF fields that are not blocks
			if ( function_exists( 'get_fields' ) )
				self::$currentQueriedPostACF = get_fields( $post[ 'ID' ] ) ?: [ ];
				// 2. Fetch the ACF fields that are blocks
			$post[ 'post_content' ] = apply_filters( 'the_content', $post[ 'post_content' ] );
			// Neatly store all the ACF fields in a sub-field
			if ( function_exists( 'get_fields' ) )
				$post[ 'acf' ] = self::$currentQueriedPostACF;
			self::$currentQueriedPostId = null;

			// Create the custom field stub
			$post[ '__custom' ] = [ ];

			// Cache the post
			self::$postCache[ $post[ 'ID' ] ] = $post;
		}

		foreach ( $postsFromDB as &$post )
			$posts[ ] = new Content( $post[ 'ID' ] );

		return $posts;

	}

	public static function getPostById ( $id ) {

		if ( isset( self::$postCache[ $id ] ) )
			return new Content( self::$postCache[ $id ] );

		$postFromDB = get_post( $id, ARRAY_A ) ?? null;

		if ( ! $postFromDB )
			return null;

		$post = $postFromDB;
		// Reset the var where the ACF data will be stored
		self::$currentQueriedPostId = $post[ 'ID' ];
			// 1. Fetch the ACF fields that are not blocks
		if ( function_exists( 'get_fields' ) )
			self::$currentQueriedPostACF = get_fields( $post[ 'ID' ] ) ?: [ ];
			// 2. Fetch the ACF fields that are blocks
		$post[ 'post_content' ] = apply_filters( 'the_content', $post[ 'post_content' ] );
		// Neatly store all the ACF fields in a sub-field
		if ( function_exists( 'get_fields' ) )
			$post[ 'acf' ] = self::$currentQueriedPostACF;
		self::$currentQueriedPostId = null;
		// Create the custom field stub
		$post[ '__custom' ] = [ ];
		// Cache the post
		self::$postCache[ $id ] = $post;

		return new Content( $post[ 'ID' ] );

	}

	public static function getPostBySlug ( $slug, $type = null ) {

		$cachedPostId = self::$postIdsBySlug[ $slug ] ?? false;
		if ( $cachedPostId )
			return new Content( self::$postCache[ $cachedPostId ] );

		$type = $type ?: [ 'post', 'page', 'attachment' ];

		$postFromDB = get_page_by_path( $slug, OBJECT, $type ) ?? null;

		if ( ! $postFromDB )
			return null;

		$post = get_object_vars( $postFromDB );
		// Reset the var where the ACF data will be stored
		self::$currentQueriedPostId = $post[ 'ID' ];
			// 1. Fetch the ACF fields that are not blocks
		if ( function_exists( 'get_fields' ) )
			self::$currentQueriedPostACF = get_fields( $post[ 'ID' ] ) ?: [ ];
			// 2. Fetch the ACF fields that are blocks
		$post[ 'post_content' ] = apply_filters( 'the_content', $post[ 'post_content' ] );
		// Neatly store all the ACF fields in a sub-field
		if ( function_exists( 'get_fields' ) )
			$post[ 'acf' ] = self::$currentQueriedPostACF;
		self::$currentQueriedPostId = null;
		// Create the custom field stub
		$post[ '__custom' ] = [ ];
		// Cache the post
		self::$postCache[ $post[ 'ID' ] ] = $post;
		self::$postCache[ $slug ] = $post;

		return new Content( $post[ 'ID' ] );

	}

	public static function getNavigation ( $name, $urlPrefix = false ) {

		if ( ! self::$isEnabled )
			return [ ];

		$menuItems = \wp_get_nav_menu_items( $name ) ?: [ ];
		// Convert from class instances to regular associate arrays
		foreach ( $menuItems as &$item )
			$item = get_object_vars( $item );
		unset( $item );
		// Prepend a prefix if provided
		if ( $urlPrefix )
			foreach ( $menuItems as &$item )
				if ( $item[ 'url' ][ 0 ] === '#' )
					$item[ 'url' ] = $urlPrefix . $item[ 'url' ];
		unset( $item );

		return $menuItems;

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

	public function getAll () {
		return CMS::$postCache[ $this->postIdentifier ];
	}

	public function get ( $key ) {

		// Get a reference to the post's cache
		$postCache = CMS::$postCache[ $this->postIdentifier ];

		// Get the value from the cache
		if ( isset( $postCache[ '__custom' ][ $key ] ) )
			return $postCache[ '__custom' ][ $key ];
		else if ( ! empty( $postCache[ 'acf' ] ) and isset( $postCache[ 'acf' ][ $key ] ) )
			return $postCache[ 'acf' ][ $key ];
		else if ( isset( $postCache[ $key ] ) )
			return $postCache[ $key ];
		else
			return null;




		// If it wasn't in the cache.....

		// if ( $this->postId )
		// 	CMS::$postCache[ $this->postIdentifier ] = get_post( $this->postId, ARRAY_A ) ?? null;
		// else
		// 	CMS::$postCache[ $this->postIdentifier ] = get_page_by_path( $this->postSlug, OBJECT, $this->postType ?? [ 'post', 'page' ] ) ?? null;

		// $post = &CMS::$postCache[ $this->postIdentifier ];
		// if ( empty( $post ) )
		// 	throw new \Exception( 'Post not found.', 1 );

		// // Store the post id
		// $this->postId = $post[ 'ID' ];

		// CMS::$postCache[ $this->postId ] = &CMS::$postCache[ $this->postIdentifier ];

		// if ( isset( $post[ $key ] ) )
		// 	return $post[ $key ];

		// $post[ 'post_content' ] = apply_filters( 'the_content', $post[ 'post_content' ] );

		// if ( isset( $post[ $key ] ) )
		// 	return $post[ $key ];

		// $post[ $key ] = get_field( $key, $this->postId ) ?? null;

		// return $post[ $key ];

	}

	public function set ( $key, $value ) {
		CMS::$postCache[ $this->postIdentifier ][ '__custom' ][ $key ] = $value;
		return $this;
	}

}
