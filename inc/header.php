<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 */

// Get utility functions
require_once __ROOT__ . '/inc/utils.php';
require_once __ROOT__ . '/inc/cms.php';

use BFS\CMS;
use BFS\Router;


/* -- Lazaro Disclaimer and Footer -- */
require_once __ROOT__ . '/inc/signatures-and-disclaimers.php';

/*
 * A version number for versioning assets to invalidate the browser cache
 */
global $versionNumber;
$versionNumber = BFS_ASSET_VERSION_NUMBER;
$ver = '?v=' . $versionNumber;

/*
 * A class name for temporarily disabling sections or features or content parts while in development
 */
$hide = 'hidden';
$showMedium = 'show-for-medium';


$languageAttributes = CMS::$isEnabled ? get_language_attributes() : 'lang="en" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml"';


http_response_code( Router::$httpResponseCode );

?>
<!doctype html>
<html <?= $languageAttributes ?>>
<head>
	<?php require_once __ROOT__ . '/inc/head.php'; ?>
</head>

<body class="<?= ( CMS::$isEnabled and ! CMS::$onlySetupContext ) ? implode( ' ', get_body_class() ) : 'body' ?>" id="body">
<?php if ( CMS::$isEnabled and ! CMS::$onlySetupContext ) wp_body_open(); ?>

<?= CMS::get( 'arbitrary_code / after_body_opening' ) ?? '' ?>

<!--  ★  MARKUP GOES HERE  ★  -->

<div id="page-wrapper"><!-- Page Wrapper -->

	<div id="page-content"><!-- Page Content -->
