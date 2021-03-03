<?php

// Check if there's anything in the honeypot
	// If there is, then pretend there is a server error
if ( ! empty( $_GET[ 'bfs_hi_puf' ] ) ) {
	http_response_code( 500 );
	exit;
}

use BFS\CMS;

require_once __ROOT__ . '/inc/header.php';

$faqs = CMS::getPostsOf( 'faq', [
	's' => get_query_var( 's' )
] );
foreach ( $faqs as $faq ) {
	$faq->set( 'url', get_permalink( $faq->get( 'ID' ) ) );
	// If summary exists, use that, else pull from the faq content and crop it to below 199 characters ( and don't break in the middle of a word )
	$summary = $faq->get( 'summary' ) ?: wp_strip_all_tags( $faq->get( 'post_content' ) );
	if ( strlen( $summary ) <= 199 )
		$faq->set( 'content', $summary );
	else
		$faq->set(
			'content',
			preg_replace(
				'/\s[^\s]+$/',
				'',
				substr( $summary , 0, 199 )
			) . '...'
		);
}

?>

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
					Help Center : Search
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Header Section -->


<?php /* ----- Search Section ----- */
require __ROOT__ . '/inc/search-bar.php';
?>


<!-- Search Listing Section -->
<section class="search-listing-section space-50-top space-75-bottom">
	<div class="container">
		<div class="row">
			<div class="search-listing columns small-12 large-8 xlarge-7">
				<?php if ( ! empty( $faqs ) ) : ?>
					<?php foreach ( $faqs as $faq ) : ?>
						<a class="item block space-25-top-bottom" href="<?= $faq->get( 'url' ) ?>">
							<div class="title h5 strong space-min-bottom"><?= $faq->get( 'post_title' ) ?></div>
							<div class="description h6 opacity-50 space-min-bottom"><?= $faq->get( 'content' ) ?></div>
							<span class="label inline text-lowercase">Read More <span class="material-icons">subject</span></span>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="h4 strong">Sorry, we could not find any articles matching :</div>
					<div class="space-25-top p">"<?= esc_html( get_query_var( 's' ) ) ?>"</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<!-- END: Search Listing Section -->


<?php require_once __ROOT__ . '/inc/footer.php'; ?>
