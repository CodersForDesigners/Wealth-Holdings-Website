<?php

namespace BFS\Types;

require_once __ROOT__ . '/lib/providers/wordpress.php';

use BFS\CMS\WordPress;

class Tiles {

	private static $typeSlug = 'tile-link';

	public static function getPreparedData ( $content ) {

		if ( empty( $content ) )
			return $content;

		$content->set( 'link', $content->get( 'arbitrary_link' ) ?: $content->get( 'attachment_link' ) );
		$content->set( 'videoId', $content->get( 'youtube_video_id' ) );

		return $content;
	}

	public static function get ( $options = [ ] ) {
		WordPress::setupContext();
		return WordPress::findPostsOf(
			self::$typeSlug,
			$options,
			[ self::class, 'getPreparedData' ]
		);
	}

	public static function getBySlug ( $slug ) {
		WordPress::setupContext();
		return self::getPreparedData( WordPress::findPostBySlug( $slug, self::$typeSlug ) );
	}

	public static function getFromURL ( $slug = null ) {
		WordPress::setupContext();
		if ( ! is_string( $slug ) )
			return self::getPreparedData( WordPress::getThisPost() );
		else
			return self::getBySlug( $slug, self::$typeSlug );
	}

	public static function getFeatured () {
		WordPress::setupContext();
		return self::getPreparedData( WordPress::findPostsOf(
			self::$typeSlug,
			[
				'meta_key' => '_is_ns_featured_post',
				'meta_value' => 'yes'
			]
		)[ 0 ] ?? null );
	}

	public static function getAll () {
		WordPress::setupContext();
		return WordPress::findPostsOf(
			self::$typeSlug,
			[
				'orderby' => [
					'date' => 'DESC',
					'menu_order' => 'ASC'
				]
			],
			[ self::class, 'getPreparedData' ]
		);
	}



	public static function setupGutenbergBlocks () {
		add_action( 'acf/init', function () {
			if ( ! function_exists( 'acf_register_block_type' ) )
				return;

			acf_register_block_type( [
				'name' => 'bfs-tile-link',
				'title' => __( 'Tiles' ),
				'description' => __( 'Tiles' ),
				'category' => CLIENT_SLUG,
				'icon' => 'testimonial',
				'align' => 'wide',
				'mode' => 'edit',
				'supports' => [
					'multiple' => false,
					'align' => [ 'wide' ]
				],
				'render_callback' => [ WordPress::class, 'acfRenderCallback' ]
			] );
		} );
	}

	public static function setupContentInputForm () {
		add_filter( 'register_post_type_args', function ( $args, $postType ) {
			if ( $postType !== self::$typeSlug )
				return $args;

			$args[ 'template' ] = [
				[ 'acf/bfs-tile-link' ]
			];
			$args[ 'template_lock' ] = 'all';

			return $args;
		}, 20, 2 );
	}



	// Register a `save_post` action hook
	public static function onSavingInstance () {
		add_action( 'bfs/init/backend', function () {
		// add_action( 'bfs/backend/on-editing-posts', function ( $postType ) {
			// if ( $postType !== self::$typeSlug )
				// return;

			self::registerHook__OnSavingPost();
		} );
	}

	public static function registerHook__OnSavingPost () {
		add_action( 'save_post_' . self::$typeSlug, [ __CLASS__, 'hook__SavePost' ], 100, 3 );
	}

	public static function unregisterHook__OnSavingPost () {
		remove_action( 'save_post_' . self::$typeSlug, [ __CLASS__, 'hook__SavePost' ], 100, 3 );
	}

	/*
	 | Capture certain ACF values and store them as native post (or post meta) attributes, or tags.
	 | This is to optimize the post querying.
	 |
	 */
	public static function hook__SavePost ( $postId, $post, $postWasUpdated ) {

		// this hook is invoked _even_ when a revision is created, i.e. post type of "revision"
		if ( WordPress::getPostType( $postId ) !== self::$typeSlug )
			return;

		// Unregister the save_post action hook to prevent an infinite recursive loop
		self::unregisterHook__OnSavingPost();



		$thePost = WordPress::findPostById( $postId );

		/*
		 | Capture the "title" value as the post title
		 */
		// Strip away all the HTML and newline characters
		$title = strip_tags( str_replace( "\r\n", ' ', $thePost->get( 'tile_title' ) ) );
		wp_update_post( [ 'ID' => $postId, 'post_title' => $title ], false, false );

		/*
		 | Capture the "Feature in" value as a tag
		 | All these tags are prefixed with `for-`
		 |
		 */
		$tags = array_map( function ( $tag ) {
			return 'for-' . $tag;
		}, $thePost->get( 'feature_on' ) );

		$allExistingPostTags = wp_get_post_tags( $postId, [ 'fields' => 'slugs' ] );
		$allOtherTags = array_filter( $allExistingPostTags, function ( $tag ) {
			return substr( $tag, 0, 4 ) != 'for-';
		} );

		$tagsToSet = array_merge( $allOtherTags, $tags );

		wp_set_post_tags( $postId, $tagsToSet, false );



		// Re-register the action hook
		self::registerHook__OnSavingPost();

	}

}
