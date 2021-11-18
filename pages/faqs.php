<?php
/*
 |
 | FAQs
 |
 */

require_once __ROOT__ . '/lib/routing.php';
require_once __ROOT__ . '/lib/providers/wordpress.php';
require_once __ROOT__ . '/types/faqs/faqs.php';
require_once __ROOT__ . '/types/tiles/tiles.php';

use BFS\Router;
use BFS\CMS\WordPress;
use BFS\Types\FAQs;
use BFS\Types\Tiles;


// If this is request to the base url `/faqs`, then redirect to the first FAQ
if ( Router::$urlSlug === 'faqs' and empty( $_GET[ 's' ] ) ) {
	$firstFAQ = FAQs::getFirstFAQ();
	$redirectURL = wp_make_link_relative( get_permalink( $firstFAQ->get( 'ID' ) ) );
	return Router::redirectTo( $redirectURL );
	exit;
}

// If this is a search query request, then delegate the handling to `faq-search.php`
if ( Router::$urlSlug == 'faqs' and !empty( $_GET[ 's' ] ) )
	return require_once __ROOT__ . '/pages/faq-search.php';

global $thisFAQ;
$thisFAQ = FAQs::getFromURL();

// If there isn't a corresponding post, redirect to the first FAQ
if ( empty( $thisFAQ ) ) {
	http_response_code( 404 );
	$firstFAQ = FAQs::getFirstFAQ();
	$redirectURL = '/' . wp_make_link_relative( get_permalink( $firstFAQ->get( 'ID' ) ) );
	return Router::redirectTo( $redirectURL );
	exit;
}

$faqs = FAQs::getAll();
$faqs__Tree = FAQs::getTreeRepresentation( $faqs );


function getFAQHierarchyMarkup ( $faqs__Tree, $parentId ) {
	if ( empty( $faqs__Tree[ $parentId ] ) )
		return '';

	global $thisFAQ;

	?>

	<ul>
		<?php foreach ( $faqs__Tree[ $parentId ] as $faq ) : ?>
			<li class="<?php if ( $faq->get( 'ID' ) === $thisFAQ->get( 'ID' ) ) : ?>active js_active<?php endif; ?>">
				<a href="<?= $faq->get( 'url' ) ?>"><?= $faq->get( 'post_title' ) ?></a>
				<?= getFAQHierarchyMarkup( $faqs__Tree, $faq->get( 'ID' ) ) ?>
				<button class="hierarchy-toggle js_expand">&#9654;</button>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php

}


$tileLinks = Tiles::getBySection( 'faqs' );



// Set the document's section title
$sectionTitle = 'Help Center';

require_once __ROOT__ . '/pages/partials/header.php';

?>

<script type="text/javascript">
	window.__BFS = window.__BFS || { };
	window.__BFS.post = {
		title: "<?= $thisFAQ->get( 'post_title' ) ?>"
	};

</script>

<!-- Header Section -->
<section class="header-section fill-blue-4 space-75-top space-25-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<a class="inline" href="/">
					<div class="logo space-50-bottom"><img class="block" src="/media/wh-logo-large-light.svg<?php echo $ver ?>"></div>
				</a>
			</div>
			<div class="columns small-12 large-10 xlarge-8">
				<div class="h2 strong">
					Help Center
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Header Section -->


<?php /* ----- Search Section ----- */
require __ROOT__ . '/pages/snippets/search-bar.php';
?>


<!-- FAQ Content Section -->
<section class="faq-content-section space-50-top-bottom">
	<div class="container">
		<div class="row">
			<div class="faq-sidebar columns small-12 large-4 js_faq_sidebar">
				<div class="sidebar-min fill-blue-1 hide-large hide-xlarge space-min cursor-pointer js_toggle_sidebar" tabindex="-1">
					<div class="sidebar-min-label h5 text-blue-4 opacity-50 clearfix"><span class="label float-left">Help Center Menu</span> <span class="icon material-icons float-right">expand_more</span></div>
					<div class="active-title h6 text-blue-4 js_current_category">Lumpsum</div>
				</div>
				<div class="faq-hierarchy js_faq_listing"><?= getFAQHierarchyMarkup( $faqs__Tree, 0 ) ?></div>
			</div>
			<div class="faq-content columns small-12 large-8 xlarge-7">
				<div class="title h4 strong space-50-bottom">
					<?= $thisFAQ->get( 'post_title' ) ?>
				</div>
				<div class="post-content">
					<?= $thisFAQ->get( 'post_content' ) ?>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: FAQ Content Section -->


<!-- Tile Links Section -->
<div class="tile-link-section space-75-bottom hidden">
	<div class="container">
		<div class="row">
			<div class="columns small-12 large-9 large-offset-3">
				<div class="row">
					<?php foreach ( $tileLinks as $tile ) : ?>
						<div class="tile columns small-12 medium-6 fill-<?= $tile->get( 'bg_color' ) ?> js_tile <?= $tile->get( 'trigger_login_flow' ) ? 'js_user_required' : '' ?>" <?php if ( $tile->get( 'trigger_login_flow' ) ) echo 'data-login-prompt-title="' . htmlentities( $tile->get( 'login_flow_title' ) ) . '"' ?>>
							<div class="layer-1" <?php if ( $tile->get( 'bg_image' ) ) : ?>style="background-image: url( '<?= $tile->get( 'bg_image' ) ?>' );"<?php endif; ?>></div>
							<div class="layer-2">
								<div class="label opacity-75 space-min-bottom text-<?= $tile->get( 'text_color' ) ?> js_tile_subheading"><?= $tile->get( 'tile_label' ) ?></div>
								<div class="<?= $tile->get( 'title_size' ) ?> strong space-25-bottom text-<?= $tile->get( 'text_color' ) ?> js_title"><?= $tile->get( 'tile_title' ) ?></div>
								<?php if ( $tile->get( 'link' ) ) : ?>
									<a class="button fill-<?= $tile->get( 'button_bg_color' ) ?> text-<?= $tile->get( 'button_color' ) ?> js_action" href="#" data-href="<?= $tile->get( 'link' ) ?>" target="_blank"><?= $tile->get( 'button_text' ) ?></a>
								<?php else: ?>
									<button class="button fill-<?= $tile->get( 'button_bg_color' ) ?> text-<?= $tile->get( 'button_color' ) ?> js_action js_modal_trigger" data-mod-id="youtube-video" data-src="<?= $tile->get( 'videoId' ) ?>"><?= $tile->get( 'button_text' ) ?></button>
								<?php endif; ?>
							</div>
							<div class="layer-3 js_tile_login_prompt_container"></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: Tile Links Section -->




<script type="text/javascript">

	$( function () {

		/*
		 * ----- Expand the entire listing on clicking the global toggle (mobile only)
		 */
		$( ".js_toggle_sidebar" ).on( "click", function ( event ) {
			var $faqSidebar = $( event.target ).closest( ".js_faq_sidebar" );
			$faqSidebar.toggleClass( "show-sidebar" );
		} );

		/*
		 * ----- Expand all the parent sections
		 */
		var $activeFAQ = $( ".js_faq_listing .js_active" );
		$activeFAQ
			.addClass( "active" )
			.addClass( "show-hierarchy" )
			.parentsUntil( ".js_faq_listing", "li" )
				.addClass( "show-hierarchy" )

		/*
		 * ----- Reflect the top-level section name in the Listing Heading / Toggle (mobile only)
		 */
		var $topLevelFAQ = $activeFAQ.parentsUntil( ".js_faq_listing", "li" ).last();
		if ( ! $topLevelFAQ.length )
			$topLevelFAQ = $activeFAQ;
		var currentTopLevelHeading = $topLevelFAQ.find( " > a" ).text();
		$( ".js_current_category" ).text( currentTopLevelHeading );

		/*
		 * ----- Expand a listing section on clicking on the adjacent arrow
		 */
		$( ".js_faq_listing" ).on( "click", ".js_expand", function ( event ) {
			$( event.target ).closest( "li" ).toggleClass( "show-hierarchy" );
		} );

	} );

</script>

<!-- Templates Section -->
<section class="js_section_templates hidden">
	<!-- TEMPLATE: Login Prompt for Tile Link -->
	<template class="js_template" data-name="tile-link-login-prompt">
		<div class="js_tile_login_prompt">
			<div class="form-row space-25-bottom">
				<div class="title h5 strong js_login_prompt_title">Signup to Download <br>for Free.</div>
			</div>
			<form class="form form-dark js_phone_form" onsubmit="event.preventDefault()">
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
						<div style="position: relative; display: flex">
							<select class="js_phone_country_code" style="position: absolute; top: 0; left: 0; background-color: transparent; color: transparent; width: 26%;">
								<?php include __ROOT__ . '/pages/snippets/phone-country-codes.php' ?>
							</select>
							<input type="text" class="no-pointer js_phone_country_code_label" value="+91" tabindex="-1" readonly style="width: 26%">
							<input class="block" type="text" name="phone-number" id="">
						</div>
					</label>
				</div>
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
						<button class="button fill-red-2" type="submit">Get Details</button>
					</label>
				</div>
			</form>
			<form class="form form-dark js_otp_form" style="display: none" onsubmit="event.preventDefault()">
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">We've sent you an OTP. Kindly provide it below.</span><br>
						<input class="block" type="text" name="otp" id="">
					</label>
					<span class="small text-uppercase line-height-small opacity-50 cursor-pointer js_resend_otp hidden">Re-send OTP</span>
					<span class="small text-uppercase line-height-small opacity-50 cursor-pointer js_try_different_number hidden">Try a different number</span>
				</div>
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
						<button class="button fill-red-2" type="submit">Verify OTP</button>
					</label>
				</div>
			</form>
			<div class="close js_close" tabindex="-1"><img class="icon block" src="../media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
		</div>
	</template>
	<!-- END: Template: Login Prompt for Tile Link -->
</section>

<?php require_once __ROOT__ . '/pages/partials/footer.php'; ?>
