<?php

// Page-specific preparatory code goes here.
require_once __DIR__ . '/../inc/above.php';

$faqs = BFS\CMS::getPostsOf( 'faq' );
foreach ( $faqs as $faq ) {
	$faq->set( 'url', get_permalink( $faq->get( 'ID' ) ) );
	$faq->set( 'featuredImage', get_the_post_thumbnail_url( $faq->get( 'ID' ) ) );
	// If no free-form post content was provided, use the summary
	$faq->set( 'content', wp_strip_all_tags( $faq->get( 'post_content' ) ) ?: $faq->get( 'summary' ) );
}

?>



<section class="document-section space-50-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12 medium-10 medium-offset-1 xlarge-8 xlarge-offset-2 space-min">
				<?php foreach ( $faqs as $faq ) : ?>
					<a href="<?= $faq->get( 'url' ) ?>"><?= $faq->get( 'post_title' ) ?></a>
					<img src="<?= $faq->get( 'featuredImage' ) ?: '' ?>">
					<div><?= $faq->get( 'content' ) ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>





<?php require_once __DIR__ . '/../inc/below.php'; ?>
