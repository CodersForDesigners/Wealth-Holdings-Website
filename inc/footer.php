<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

use BFS\CMS;
use BFS\Router;

$footerNavigationMenuItems = CMS::getNavigation( 'Footer', '/' );

?>
			<!-- ~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/ -->
			<!-- Page-specific content goes here. -->
			<!-- ~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/ -->

			<!-- Footer Section -->
			<section class="footer-section fill-blue-4 space-75-top-bottom" id="footer-section" data-section-title="Footer Section" data-section-slug="footer-section">
				<div class="container">
					<div class="row">
						<div class="about columns small-12 medium-6 large-4 space-50-bottom">
							<div class="logo space-50-bottom">
								<a class="inline" href="/">
									<img class="block" src="/media/wh-logo-large-light.svg<?php echo $ver ?>"></div>
								</a>
							<div class="h6 space-25-bottom text-blue-2">Wealth Holdings is a brand of Green <br>Infrastructure Projects Private Limited.</div>
							<!-- <a class="address inline space-25-bottom" target="_blank" href="https://maps.google.com"> -->
							<span class="address inline space-25-bottom">
								<span class="label inline text-blue-3 line-height-xlarge">Corporate Address :</span><br>
								<span class="h5 inline">1097, 5th Block, 18th B Main Rd <br>Rajajinagar, Bengaluru <br>Karnataka 560 010</span>
							</span>
							<!-- </a> -->
							<!-- <a class="phone inline" href="tel:+919341203040">
								<span class="label inline text-blue-3 line-height-xlarge">Phone Number :</span><br>
								<span class="h4 inline text-red-2 ">+91 93412 03040</span>
							</a> -->
						</div>
						<div class="columns small-12 medium-6 large-8 space-50-bottom">
							<div class="row featured-links">
								<div class="columns small-12 large-6 space-50-bottom">
									<a class="link inline <?= $hide ?>" href="/looking-to-sell-an-rental-asset" target="_blank">
										<div class="h2 inline strong text-red-2 opacity-50 no-wrap">
											<span class="inline-top">Sell</span>
											<img class="inline-top" src="/media/icon/icon-wh-arrow-red.svg<?php echo $ver ?>">
										</div>
										<div class="h5 strong">Looking to sell a rental <br>yielding asset?</div>
									</a>
								</div>
								<div class="columns small-12 large-6 space-50-bottom">
									<a class="link inline" href="/authorised-partner-signup" target="_blank">
										<div class="h2 inline strong text-red-2 opacity-50 no-wrap">
											<span class="inline-top">Partner</span>
											<img class="inline-top" src="/media/icon/icon-wh-arrow-red.svg<?php echo $ver ?>">
										</div>
										<div class="h5 strong">Apply to become an <br>authorised channel partner.</div>
									</a>
								</div>
							</div>
							<div class="row footer-navigation">
								<div class="columns small-12 large-6 large-offset-6">
									<div class="label text-blue-3 line-height-xlarge">Quick Links :</div>
								</div>
								<div class="columns small-12 large-6 large-offset-6" style="columns: 2; column-gap: var(--space-min);">
									<?php foreach ( $footerNavigationMenuItems as $item ) : ?>
										<a href="<?= $item[ 'url' ] ?>" class="link h6 strong block text-red-2 line-height-large"><?= $item[ 'title' ] ?></a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- END: Footer Section -->


		</div> <!-- END : Page Content -->


		<!-- Lazaro Signature -->
		<?php lazaro_signature(); ?>
		<!-- END : Lazaro Signature -->

	</div><!-- END : Page Wrapper -->

	<?php require_once __ROOT__ . '/inc/navigation.php'; ?>

	<?php require_once __ROOT__ . '/inc/modals.php' ?>

	<!--  ☠  MARKUP ENDS HERE  ☠  -->

	<?php if ( ! BFS_ENV_PRODUCTION ) : ?>
		<?php lazaro_disclaimer(); ?>
	<?php endif; ?>





	<!-- JS Modules -->
	<script type="text/javascript" src="/plugins/base64/js-base64-v3.6.0.min.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/utils.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/navigation.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/video_embed.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/modal_box.js<?= $ver ?>"></script>
	<?php if ( ! BFS_ENV_PRODUCTION ) : ?>
		<script type="text/javascript" src="/js/modules/disclaimer.js<?= $ver ?>"></script>
	<?php endif; ?>
	<script type="text/javascript" src="/js/modules/phone-country-code.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/cupid/utils.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/cupid/user.js<?= $ver ?>"></script>
	<?php if ( Router::$urlSlug == '' ) : ?>
		<script type="text/javascript" src="/js/page/home/home.js<?= $ver ?>"></script>
	<?php endif; ?>
	<script type="text/javascript" src="/js/modules/forms.js<?= $ver ?>"></script>
	<?php if ( Router::$urlSlug == '' ) : ?>
		<script type="text/javascript" src="/js/page/home/forms.js<?= $ver ?>"></script>
	<?php endif; ?>
	<script type="text/javascript" src="/plugins/goodshare/goodshare-v6.1.5.min.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/sharing.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/tile-links.js<?= $ver ?>"></script>
	<?php if ( Router::$urlSlug == 'faqs' or ( CMS::$isEnabled and get_post_type() === 'faq' ) ) : ?>
		<script type="text/javascript" src="/js/modules/search.js<?= $ver ?>"></script>
	<?php endif; ?>

	<!-- Slick Carousel -->
	<script type="text/javascript" src="/plugins/slick/slick.min.js<?php echo $ver ?>"></script>

	<script type="text/javascript">

		$( function () {

		/*
		 * Slick Slide Gallery
		 */

			$('.blocks-gallery-grid').slick({
				arrows: true,
				dots: false,
				infinite: true,
				speed: 800,
				autoplaySpeed: 3000,
				slidesToShow: 1,
				centerMode: true,
				variableWidth: true,
				lazyLoad: 'ondemand'
			}).slickNext();


		} );

	</script>

	<script type="text/javascript" src="/js/modules/carousel.js<?= $ver ?>"></script>

	<script type="text/javascript">

		/*
		 *
		 * Tell to Cupid that the user dropped by
		 *
		 */
		$( function () {

			var user = __CUPID.utils.getUser();
			if ( user ) {
				setTimeout( function () {
					__CUPID.utils.getAnalyticsId()
						.then( function ( deviceId ) {
							user.hasDeviceId( deviceId );
							user.isOnWebsite();
						} )
				}, 1500 );
			}

		} );

	</script>

	<?php if ( CMS::$isEnabled and ! CMS::$onlySetupContext ) wp_footer() ?>

	<?= CMS::get( 'arbitrary_code / before_body_closing' ) ?>

</body>

</html>
