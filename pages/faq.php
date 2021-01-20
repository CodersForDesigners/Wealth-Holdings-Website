<?php

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
			<div class="faq-sidebar columns small-12 large-4">
				<div class="sidebar-min fill-blue-1 hide-large hide-xlarge space-min cursor-pointer" tabindex="-1">
					<div class="sidebar-min-label h5 text-blue-4 opacity-50 clearfix"><span class="label float-left">Help Center Menu</span> <span class="icon material-icons float-right">expand_more</span></div>
					<div class="active-title h6 text-blue-4">Lumpsum</div>
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




<script type="text/javascript">

	$( function () {

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
		 * ----- Expand a listing section on clicking on the adjacent arrow
		 */
		$( ".js_faq_listing" ).on( "click", ".js_expand", function ( event ) {
			$( event.target ).closest( "li" ).toggleClass( "show-hierarchy" );
		} );

	} );

</script>

<?php require_once __DIR__ . '/../inc/below.php'; ?>
