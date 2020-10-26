
			<!-- ~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/ -->
			<!-- Page-specific content goes here. -->
			<!-- ~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/~/ -->

		</div> <!-- END : Page Content -->


		<!-- Lazaro Signature -->
		<?php lazaro_signature(); ?>
		<!-- END : Lazaro Signature -->

	</div><!-- END : Page Wrapper -->

	<?php require_once 'modals.php' ?>

	<!--  ☠  MARKUP ENDS HERE  ☠  -->

	<?php if ( ! BFS_ENV_PRODUCTION ) : ?>
		<?php lazaro_disclaimer(); ?>
	<?php endif; ?>









	<!-- JS Modules -->
	<script type="text/javascript" src="/js/modules/utils.js"></script>
	<!-- <script type="text/javascript" src="/js/modules/device-charge.js"></script> -->
	<!-- <script type="text/javascript" src="/js/modules/video_embed.js"></script> -->
	<script type="text/javascript" src="/js/modules/modal_box.js"></script>
	<?php if ( ! BFS_ENV_PRODUCTION ) : ?>
		<script type="text/javascript" src="/js/modules/disclaimer.js"></script>
	<?php endif; ?>
	<script type="text/javascript" src="/js/modules/phone-country-code.js<?= $ver ?>"></script>
	<script type="text/javascript" src="/js/modules/cupid/utils.js"></script>
	<script type="text/javascript" src="/js/modules/cupid/user.js"></script>
	<script type="text/javascript" src="/js/forms.js"></script>
	<script type="text/javascript" src="/js/login-prompts.js"></script>
	<script type="text/javascript" src="/js/page/home.js"></script>

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
