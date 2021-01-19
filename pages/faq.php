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

	?>

	<ul>
		<?php foreach ( $faqs__Tree[ $parentId ] as $faq ) : ?>
			<li>
				<a href="<?= $faq->get( 'url' ) ?>" target="_blank"><?= $faq->get( 'post_title' ) ?></a>
				<?= getFAQHierarchyMarkup( $faqs__Tree, $faq->get( 'ID' ) ) ?>
			</li>
		<?php endforeach; ?>
	<ul>

	<?php

}

?>



<section class="document-section space-50-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-6 medium-6 medium-offset-1 xlarge-6 xlarge-offset-2 space-min">
				<?= getFAQHierarchyMarkup( $faqs__Tree, 0 ) ?>
			</div>
		</div>
		<div class="row">
			<div class="columns small-12 medium-10 medium-offset-1 xlarge-8 xlarge-offset-2 space-min">
				<h3><?= $thePost->get( 'post_title' ) ?></h3>

				<?= $thePost->get( 'content' ) ?>

			</div>
		</div>
	</div>
</section>





<?php require_once __DIR__ . '/../inc/below.php'; ?>
