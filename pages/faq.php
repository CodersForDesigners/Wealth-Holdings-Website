<?php

// Get utility functions
require_once __DIR__ . '/../inc/utils.php';
// Include WordPress for Content Management
if ( CMS_ENABLED )
	initWordPress();

// If this is search query request, then delegate the handling to `faq-search.php`
if ( $urlSlug == 'faq' and ! empty( $_GET[ 's' ] ) )
	return require_once __DIR__ . '/faq-search.php';

// If there isn't a corresponding post, redirect to the introduction FAQ
if ( empty( $thePost ) and ! $hasDedicatedTemplate ) {
	http_response_code( 404 );
	return header( 'Location: /faq/introduction', true, 302 );
	exit;
}

// Set the page title
$pageTitle = $thePost->get( 'post_title' ) . ' | Help Center';

// Page-specific preparatory code goes here.
require_once __DIR__ . '/../inc/above.php';

$faqs = BFS\CMS::getPostsOf( 'faq' );
$faqs__Tree = [ ];
foreach ( $faqs as $faq ) {
	$faq->set( 'url', get_permalink( $faq->get( 'ID' ) ) );
	$faq->set( 'featuredImage', get_the_post_thumbnail_url( $faq->get( 'ID' ) ) );
	// If no free-form post content was provided, use the summary
	$faq->set( 'content', wp_strip_all_tags( $faq->get( 'post_content' ) ) ?: $faq->get( 'summary' ) );
	// Build the a hierarchical tree representation of all the FAQs
	$faqs__Tree[ $faq->get( 'post_parent' ) ][ ] = $faq;
}

function getFAQHierarchyMarkup ( $faqs__Tree, $parentId ) {
	if ( empty( $faqs__Tree[ $parentId ] ) )
		return '';

	global $thePost;

	?>

	<ul>
		<?php foreach ( $faqs__Tree[ $parentId ] as $faq ) : ?>
			<li class="<?php if ( $faq->get( 'ID' ) == $thePost->get( 'ID' ) ) : ?>active js_active<?php endif; ?>">
				<a href="<?= $faq->get( 'url' ) ?>"><?= $faq->get( 'post_title' ) ?></a>
				<?= getFAQHierarchyMarkup( $faqs__Tree, $faq->get( 'ID' ) ) ?>
				<button class="hierarchy-toggle js_expand">&#9654;</button>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php

}


$tileLinks = BFS\CMS::getPostsOf( 'tile-link', [ 'tag' => 'for-faqs' ] );
foreach ( $tileLinks as $tile ) {
	$tile->set( 'link', $tile->get( 'arbitrary_link' ) ?: $tile->get( 'attachment_link' ) );
	$tile->set( 'videoId', $tile->get( 'youtube_video_id' ) );
}

?>

<script type="text/javascript">

	window.__BFS = window.__BFS || { };
	window.__BFS.post = {
		title: "<?= $thePost->get( 'post_title' ) ?>"
	};

</script>

<!-- Header Section -->
<section class="header-section fill-blue-4 space-75-top space-25-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<a class="inline" href="/">
					<div class="logo space-50-bottom"><img class="block" src="../media/wh-logo-large-light.svg<?php echo $ver ?>"></div>
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


<!-- Search Section -->
<section class="search-section fill-blue-4 space-25-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<form role="search" method="get" id="searchform" class="searchform" action="/faq">
					<div class="search-bar fill-dark">
						<label class="label visuallyhidden screen-reader-text" for="s">Search for:</label>
						<input class="search-input" type="text" value="" name="s" id="s" placeholder="How can we help?">
						<input class="search-button button fill-red-2" type="submit" id="searchsubmit" value="Search">
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<!-- END: Search Section -->


<!-- FAQ Content Section -->
<section class="faq-content-section space-50-top-bottom">
	<div class="container">
		<div class="row">
			<div class="faq-sidebar columns small-12 large-4 js_faq_sidebar">
				<div class="sidebar-min fill-blue-1 hide-large hide-xlarge space-min cursor-pointer js_toggle_sidebar" tabindex="-1">
					<div class="sidebar-min-label h5 text-blue-4 opacity-50 clearfix"><span class="label float-left">Help Center Menu</span> <span class="icon material-icons float-right">expand_more</span></div>
					<div class="active-title h6 text-blue-4 js_current_category">Lumpsum</div>
				</div>
				<div class="faq-hierarchy js_faq_listing"><?= getFAQHierarchyMarkup( $faqs__Tree, 0, $thePost->get( 'ID' ) ) ?></div>
			</div>
			<div class="faq-content columns small-12 large-8 xlarge-7">
				<div class="title h4 strong space-50-bottom">
					<?= $thePost->get( 'post_title' ) ?>
				</div>
				<div class="post-content">
					<?= $thePost->get( 'post_content' ) ?>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: FAQ Content Section -->


<!-- Tile Links Section -->
<div class="tile-link-section space-75-bottom">
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
								<?php include __DIR__ . '/../inc/phone-country-codes.php' ?>
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

<?php require_once __DIR__ . '/../inc/below.php'; ?>
