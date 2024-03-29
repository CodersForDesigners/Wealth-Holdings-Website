<?php

namespace BFS\Types;

require_once __ROOT__ . '/lib/providers/wordpress.php';

use BFS\CMS\WordPress;

class Testimonials {

	private static $typeSlug = 'testimonial';

	public static function getPreparedData ( $content ) {

		if ( empty( $content ) )
			return $content;

		$photograph = $content->get( 'photograph' ) ?: [ 'sizes' => [ ] ];
		$photographURL = $photograph[ 'sizes' ][ 'thumbnail' ] ?: $photograph[ 'sizes' ][ 'small' ] ?: $photograph[ 'sizes' ][ 'medium' ] ?: $photograph[ 'sizes' ][ 'medium_large' ] ?: $photograph[ 'sizes' ][ 'large' ] ?: $photograph[ 'url' ] ?: '';
		$content->set( 'photograph', $photographURL );

		$videoThumbnail = $content->get( 'video_thumbnail' ) ?: [ 'sizes' => [ ] ];
		if ( $videoThumbnail !== false ) {
			$videoThumbnailURL =
					( $videoThumbnail[ 'sizes' ][ 'thumbnail' ] ?? false )
				?:	( $videoThumbnail[ 'sizes' ][ 'small' ] ?? false )
				?:	( $videoThumbnail[ 'sizes' ][ 'medium' ] ?? false )
				?:	( $videoThumbnail[ 'sizes' ][ 'medium_large' ] ?? false )
				?:	( $videoThumbnail[ 'sizes' ][ 'large' ] ?? false )
				?:	( $videoThumbnail[ 'url' ] ?? false )
				?:	null;
			$content->set( 'video_thumbnail', $videoThumbnailURL );
		}

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

	public static function getCategories () {
		WordPress::setupContext();
		$fields = acf_get_field(	// this function gets us the "Investment" field group settings
			'categories',	// return this field from the field group
			get_page_by_title( 'testimonials', OBJECT, 'acf-field-group' )->ID
		)[ 'sub_fields' ];
		$categories = [ ];
		foreach ( $fields as $field ) {
			$categories = [
				'key' => $el[ 'name' ],
				'label' => $el[ 'label' ],
				'values' => array_values( $el[ 'choices' ] )
			];
		}
		return $categories;
	}



	public static function setupGutenbergBlocks () {
		add_action( 'acf/init', function () {
			if ( ! function_exists( 'acf_register_block_type' ) )
				return;

			acf_register_block_type( [
				'name' => 'bfs-testimonials',
				'title' => __( 'Testimonials' ),
				'description' => __( 'Testimonials' ),
				'category' => CLIENT_SLUG,
				'icon' => 'money-alt',
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
				[ 'acf/bfs-testimonials' ]
			];
			$args[ 'template_lock' ] = 'all';

			return $args;
		}, 20, 2 );
	}

}
