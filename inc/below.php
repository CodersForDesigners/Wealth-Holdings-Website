
			<!-- ~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/ -->
			<!-- Page-specific content goes here. -->
			<!-- ~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/ -->

			<!-- Footer Section -->
			<section class="footer-section fill-blue-4 space-75-top-bottom" id="footer-section" data-section-title="Footer Section" data-section-slug="footer-section">
				<div class="container">
					<div class="row">
						<div class="about columns small-12 medium-6 large-4 space-50-bottom">
							<div class="logo space-50-bottom"><img class="block" src="../media/wh-logo-large-light.svg<?php echo $ver ?>"></div>
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
									<a class="link inline" href="">
										<div class="h2 inline strong text-red-2 opacity-50 no-wrap">
											<span class="inline-top">Sell</span>
											<img class="inline-top" src="../media/icon/icon-wh-arrow-red.svg<?php echo $ver ?>">
										</div>
										<div class="h5 strong">Looking to sell a rental <br>yielding asset?</div>
									</a>
								</div>
								<div class="columns small-12 large-6 space-50-bottom">
									<a class="link inline" href="">
										<div class="h2 inline strong text-red-2 opacity-50 no-wrap">
											<span class="inline-top">Partner</span>
											<img class="inline-top" src="../media/icon/icon-wh-arrow-red.svg<?php echo $ver ?>">
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
									<a href="" class="link h6 strong block text-red-2 line-height-large">Benefits</a>
									<a href="" class="link h6 strong block text-red-2 line-height-large">Investment</a>
									<a href="" class="link h6 strong block text-red-2 line-height-large">How does it work?</a>
									<a href="" class="link h6 strong block text-red-2 line-height-large">FAQs</a>
									<a href="" class="link h6 strong block text-red-2 line-height-large">Testimonials</a>
									<a href="" class="link h6 strong block text-red-2 line-height-large">Help Center</a>
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

	<?php require_once 'navigation.php'; ?>

	<?php require_once 'modals.php' ?>

	<!--  ☠  MARKUP ENDS HERE  ☠  -->

	<?php if ( ! BFS_ENV_PRODUCTION ) : ?>
		<?php lazaro_disclaimer(); ?>
	<?php endif; ?>









	<!-- JS Modules -->
	<script type="text/javascript" src="/plugins/base64/js-base64-v3.6.0.min.js"></script>
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
	<script type="text/javascript" src="/js/page/home/home.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/forms.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/page/home/forms.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/plugins/goodshare/goodshare-v6.1.5.min.js"></script>
	<script type="text/javascript" src="/js/modules/sharing.js<?= $ver ?>"></script>

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

	<!-- Other Modules -->
	<?php // require __DIR__ . '/inc/can-user-hover.php' ?>


	<?= getContent( '', 'arbitrary_code -> before_body_closing' ); ?>

	<?php /* Query Monitor CMS plugin */ ?>
	<?php if ( CMS_ENABLED and is_user_logged_in() ) : ?>
		<script type="text/javascript" src="cms/wp-content/plugins/query-monitor/assets/query-monitor.js<?= $ver ?>"></script>
	<?php endif; ?>


</body>

</html>
