<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * The canonical file is `/pages/404.php`.
 * `/cms/wp-content/themes/<theme>/404.php` has been symbolically linked to this.
 *
 *
 */

\BFS\Router::$httpResponseCode = 404;
require_once __ROOT__ . '/inc/header.php';

?>

	<!-- Landing Section -->
	<section class="landing-section fill-red-2 space-50-top">
		<div class="container">
			<div class="row">
				<div class="columns small-12 large-5">
					<div class="row">
						<div class="columns small-12 medium-4 space-25-top-bottom">
							<div class="logo"><img class="block" src="/media/wh-logo-large-dark.svg<?= $ver ?>"></div>
						</div>
						<div class="columns small-12 medium-10 large-12 space-50-top-bottom">
							<div class="h2 strong">It looks like nothing was found at this location.</div>
							<a class="h5 text-blue-4 space-50-top" href="/">Click here to go back to the home page.</a>
						</div>
					</div>
				</div>
				<div class="columns small-12 large-6 large-offset-1 space-25-bottom">
					<div class="char-image">
						<img class="block" src="/media/char-1.png<?= $ver ?>">
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- END: Landing Section -->

<?php
require_once __ROOT__ . '/inc/footer.php';
