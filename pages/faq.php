<?php

// Get utility functions
require_once __DIR__ . '/../inc/utils.php';
// Include WordPress for Content Management
if ( CMS_ENABLED )
	initWordPress();

// If there isn't a corresponding post, redirect to the introduction FAQ
if ( empty( $thePost ) and ! $hasDedicatedTemplate ) {
	http_response_code( 404 );
	return header( 'Location: /faq/introduction', true, 302 );
	exit;
}

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

?>


<!-- Header Section -->
<section class="header-section fill-blue-4 space-75-top space-50-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<div class="logo space-50-bottom"><img class="block" src="../media/wh-logo-large-light.svg<?php echo $ver ?>"></div>
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


<!-- FAQ Content Section -->
<section class="faq-content-section space-75-top-bottom">
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
					<div class="tile columns small-12 medium-6 fill-red-1">
						<div class="layer-1" style="background-image: url('/* -- delete this and insert image url here -- */<?php echo $ver ?>');"></div>
						<div class="layer-2">
							<div class="label opacity-75 space-min-bottom">EMI</div>
							<div class="h4 strong space-25-bottom">Certe, inquam, pertinax non provident, similique sunt in ea quid est cur verear. Probabo, inquit, modo ista sis aequitate?</div>
							<button class="button fill-red-2">Read More</button>
						</div>
						<div class="layer-3">
							<div class="form block form-dark">
								<div class="form-row space-25-bottom">
									<div class="title h5 strong">Signup to Download <br>for Free.</div>
								</div>
								<div class="form-row space-min-bottom">
									<label for="">
										<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
										<input class="block" type="text">
									</label>
								</div>
								<div class="form-row space-min-bottom">
									<label for="">
										<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
										<button class="button fill-red-2">Get Details</button>
									</label>
								</div>
							</div>
							<div class="close" tabindex="-1"><img class="icon block" src="../media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
						</div>
					</div>
					<div class="tile columns small-12 medium-6 fill-blue-4">
						<div class="layer-1" style="background-image: url('/* -- delete this and insert image url here -- */<?php echo $ver ?>');"></div>
						<div class="layer-2">
							<div class="label opacity-75 space-min-bottom">Lumpsum</div>
							<div class="h4 strong space-25-bottom">Torquem detraxit hosti <br>et quidem faciunt, ut <br>earum motus et quasi <br>naturalem atque?</div>
							<button class="button fill-red-2">Read More</button>
						</div>
						<div class="layer-3">
							<div class="form block form-dark">
								<div class="form-row space-25-bottom">
									<div class="title h5 strong">Signup to Download <br>for Free.</div>
								</div>
								<div class="form-row space-min-bottom">
									<label for="">
										<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
										<input class="block" type="text">
									</label>
								</div>
								<div class="form-row space-min-bottom">
									<label for="">
										<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
										<button class="button fill-red-2">Get Details</button>
									</label>
								</div>
							</div>
							<div class="close" tabindex="-1"><img class="icon block" src="../media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
						</div>
					</div>
					<div class="tile columns small-12 medium-6 fill-dark">
						<div class="layer-1" style="background-image: url('/* -- delete this and insert image url here -- */<?php echo $ver ?>');"></div>
						<div class="layer-2">
							<div class="h4 strong space-25-bottom">Epicurus in armatum <br>hostem impetum</div>
							<button class="button fill-red-2">Watch Video</button>
						</div>
						<div class="layer-3">
							<div class="form block form-dark">
								<div class="form-row space-25-bottom">
									<div class="label opacity-75 space-min-bottom"><!-- Insert Label Text - Optional --></div>
									<div class="title h5 strong">Signup to Download <br>for Free.</div>
								</div>
								<div class="form-row space-min-bottom">
									<label for="">
										<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
										<input class="block" type="text">
									</label>
								</div>
								<div class="form-row space-min-bottom">
									<label for="">
										<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
										<button class="button fill-red-2">Get Details</button>
									</label>
								</div>
							</div>
							<div class="close" tabindex="-1"><img class="icon block" src="../media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
						</div>
					</div>
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

<?php require_once __DIR__ . '/../inc/below.php'; ?>
