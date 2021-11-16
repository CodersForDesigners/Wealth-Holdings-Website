<?php

namespace BFS\Types;

require_once __ROOT__ . '/lib/providers/wordpress.php';

use BFS\CMS\WordPress;

class Brochures {

	private static $typeSlug = 'brochure';

	public static function getPreparedData ( $content ) {

		if ( empty( $content ) )
			return $content;

		$content->set( 'url', get_permalink( $content->get( 'ID' ) ) );
		if ( $content->get( 'default_payment_mode' ) )
			$content->set( 'defaultDescription', $content->get( 'title / emi' ) );
		else
			$content->set( 'defaultDescription', $content->get( 'title / lumpsum' ) );

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
				'name' => 'bfs-brochures',
				'title' => __( 'Brochures' ),
				'description' => __( 'Brochures' ),
				'category' => CLIENT_SLUG,
				'icon' => 'media-document',
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
				[ 'acf/bfs-brochures' ]
			];
			$args[ 'template_lock' ] = 'all';

			return $args;
		}, 20, 2 );
	}

}
