<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Braun E. Fridge
 *
 */

use BFS\CMS\WordPress;
use BFS\Router;

$footerNavigationMenuItems = WordPress::getNavigation( 'Footer', '/' );

?>

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
								<a class="link inline" href="/authorised-distributor-signup" target="_blank">
									<div class="h2 inline strong text-red-2 opacity-50 no-wrap">
										<span class="inline-top">Distributor</span>
										<img class="inline-top" src="/media/icon/icon-wh-arrow-red.svg<?php echo $ver ?>">
									</div>
									<div class="h5 strong">Apply to become an <br>authorised distributor.</div>
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


	<?php require_once __ROOT__ . '/pages/snippets/lazaro-signature.php'; ?>

</div><!-- END : Page Wrapper -->

<?php require_once __ROOT__ . '/pages/snippets/navigation.php'; ?>

<?php require_once __ROOT__ . '/pages/snippets/modals.php' ?>

<!--  ☠  MARKUP ENDS HERE  ☠  -->

<?php if ( ! BFS_ENV_PRODUCTION ) : ?>
	<?php require_once __ROOT__ . '/pages/snippets/lazaro-disclaimer.php'; ?>
<?php endif; ?>





<!-- JS Modules -->
<script type="text/javascript" src="/plugins/base64/base64.js__v3.7.2.min.js<?= $ver ?>"></script>
<script type="text/javascript" src="/plugins/js-cookie/js-cookie__v3.0.1.min.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/utils.js<?= $ver ?>"></script>
<?php if ( ! BFS_ENV_PRODUCTION ) : ?>
	<script type="text/javascript" src="/js/modules/disclaimer.js<?= $ver ?>"></script>
<?php endif; ?>
<script type="text/javascript" src="/js/modules/navigation.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/video_embed.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/modal_box.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/phone-country-code.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/cupid.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/forms.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/form-utils.js<?= $ver ?>"></script>
<?php if ( Router::$urlSlug == '' ) : ?>
	<script type="text/javascript" src="/js/page/home/home.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/page/home/investment-form.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/page/home/co-investments.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/page/home/co-investment-form.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/page/home/webinar-form.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/page/home/callback-form.js<?= $ver ?>"></script>
<?php else : ?>
	<script type="text/javascript" src="/js/page/cms-generated-form.js"></script>
<?php endif; ?>

<script type="text/javascript" src="/plugins/goodshare/goodshare-v6.1.5.min.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/sharing.js<?= $ver ?>"></script>
<script type="text/javascript" src="/js/modules/tile-links.js<?= $ver ?>"></script>
<?php if ( Router::$urlSlug == 'faqs' or ( WordPress::$isEnabled and get_post_type() === 'faq' ) ) : ?>
	<script type="text/javascript" src="/js/modules/search.js<?= $ver ?>"></script>
<?php endif; ?>

<!-- Slick Carousel -->
<script type="text/javascript" src="/plugins/slick/slick.min.js<?php echo $ver ?>"></script>

<script type="text/javascript">

	$( function () {

		/*
		 | Slick Slide Gallery
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
		});


	} );

</script>

<script type="text/javascript" src="/js/modules/carousel.js<?= $ver ?>"></script>

<?php if ( WordPress::$isEnabled and ! WordPress::$onlySetupContext ) wp_footer() ?>

<?= WordPress::get( 'arbitrary_code_before_body_closing' ) ?>

</body>

</html>
