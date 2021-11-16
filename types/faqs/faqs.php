<?php

namespace BFS\Types;

require_once __ROOT__ . '/lib/providers/wordpress.php';

use BFS\CMS\WordPress;

class FAQs {

	private static $typeSlug = 'faq';

	public static function getPreparedData ( $content ) {

		if ( empty( $content ) )
			return $content;

		$content->set( 'url', get_permalink( $content->get( 'ID' ) ) );
		$content->set( 'featuredImage', get_the_post_thumbnail_url( $content->get( 'ID' ) ) );

		$faqTextualContent = wp_strip_all_tags( $content->get( 'post_content' ) );
		if ( ! $content->get( 'summary' ) ) {
			$content->set( 'summary', substr( $contentTextualContent, 0, 415 ) );
			if ( strlen( $contentTextualContent ) > 415 )
				$content->set( 'thereIsMore?', true );
		}
		else
			$content->set( 'thereIsMore?', true );

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
		return WordPress::findPostsOf(
			self::$typeSlug,
			[
				'meta_key' => '_is_ns_featured_post',
				'meta_value' => 'yes'
			],
			[ self::class, 'getPreparedData' ]
		);
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

	// Build the a hierarchical tree representation of the given FAQs
	public static function getTreeRepresentation ( $faqPosts ) {
		$tree = [ ];
		foreach ( $faqPosts as $faq ) {
			$tree[ $faq->get( 'post_parent' ) ][ ] = $faq;
		}
		return $tree;
	}

	public static function getFirstFAQ () {
		return self::get( [
			'numberposts' => 1
		] )[ 0 ];
	}



	public static function setupGutenbergBlocks () {
		add_action( 'acf/init', function () {
			if ( ! function_exists( 'acf_register_block_type' ) )
				return;

			acf_register_block_type( [
				'name' => 'bfs-faqs',
				'title' => __( 'FAQs' ),
				'description' => __( 'FAQs' ),
				'category' => CLIENT_SLUG,
				'icon' => 'editor-textcolor',
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
				[ 'core/paragraph', [ 'placeholder' => 'Type in a detailed answer here...' ] ],
				[ 'acf/bfs-faqs' ]
			];

			return $args;
		}, 20, 2 );
	}

}
