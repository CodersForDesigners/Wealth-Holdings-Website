<?php
/**
 | The header.
 |
 | This is the template that displays all of the <head> section and everything up until main.
 |
 */

// Get utility functions
require_once __ROOT__ . '/lib/utils.php';
require_once __ROOT__ . '/lib/providers/wordpress.php';

use BFS\CMS\WordPress;
use BFS\Router;



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


$languageAttributes = WordPress::$isEnabled ? get_language_attributes() : 'lang="en" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml"';


http_response_code( Router::$httpResponseCode );

?>
<!doctype html>
<html <?= $languageAttributes ?>>
<head>
     <?php require_once __ROOT__ . '/pages/partials/head.php'; ?>
</head>

<body class="<?= ( WordPress::$isEnabled and ! WordPress::$onlySetupContext ) ? implode( ' ', get_body_class() ) : 'body' ?>" id="body">
<?php if ( WordPress::$isEnabled and ! WordPress::$onlySetupContext ) wp_body_open(); ?>

<?= WordPress::get( 'arbitrary_code_after_body_opening' ) ?? '' ?>

<!--  ★  MARKUP GOES HERE  ★  -->

<div id="page-wrapper"><!-- Page Wrapper -->

	<div id="page-content"><!-- Page Content -->
