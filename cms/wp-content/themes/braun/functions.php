<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Braun E. Fridge
 *
 */

require_once ABSPATH . '/../lib/providers/wordpress.php';
define( 'THEME_SETTINGS_PATH', get_template_directory() . '/settings' );



require get_template_directory() . '/lib/utils.php';

\BFS\CMS\WordPress::setupHooks();



require_once THEME_SETTINGS_PATH . '/routing.php';
require_once THEME_SETTINGS_PATH . '/authentication.php';
require_once THEME_SETTINGS_PATH . '/url-auto-correction.php';
require_once THEME_SETTINGS_PATH . '/dequeue-defaults.php';
require_once THEME_SETTINGS_PATH . '/gutenberg-block-categories.php';

add_action( 'after_setup_theme', function () {

	// Theme supports
	require_once THEME_SETTINGS_PATH . '/theme-supports.php';
	// Document Title
	require_once THEME_SETTINGS_PATH . '/document-title.php';
	// Media settings
	require_once THEME_SETTINGS_PATH . '/media.php';
	// Custom Gutenberg Blocks
	require_once THEME_SETTINGS_PATH . '/custom-gutenberg-blocks.php';
	// Gutenberg Block editor settings
	require_once THEME_SETTINGS_PATH . '/gutenberg-block-editor.php';
	// Admin dashboard settings
	require_once THEME_SETTINGS_PATH . '/admin-dashboard.php';

} );



/*
 |
 | Data / Entity Types
 |
 */
require_once __ROOT__ . '/types/investments/investments.php';
require_once __ROOT__ . '/types/co-investments/co-investments.php';
require_once __ROOT__ . '/types/faqs/faqs.php';
require_once __ROOT__ . '/types/brochures/brochures.php';
require_once __ROOT__ . '/types/tiles/tiles.php';
require_once __ROOT__ . '/types/testimonials/testimonials.php';
require_once __ROOT__ . '/types/distributors/distributors.php';

use \BFS\Types;

/* ~ Investments ~ */
Types\Investments::setupGutenbergBlocks();
Types\Investments::setupContentInputForm();

/* ~ Co-Investments ~ */
Types\CoInvestments::setupGutenbergBlocks();
Types\CoInvestments::setupContentInputForm();

/* ~ FAQs ~ */
Types\FAQs::setupGutenbergBlocks();
Types\FAQs::setupContentInputForm();

/* ~ Brochures ~ */
Types\Brochures::setupGutenbergBlocks();
Types\Brochures::setupContentInputForm();

/* ~ Tiles ~ */
Types\Tiles::setupGutenbergBlocks();
Types\Tiles::setupContentInputForm();
Types\Tiles::onSavingInstance();

/* ~ Testimonials ~ */
Types\Testimonials::setupGutenbergBlocks();
Types\Testimonials::setupContentInputForm();

/* ~ (Authorized) Distributors ~ */
Types\Distributors::setupGutenbergBlocks();
Types\Distributors::setupContentInputForm();
