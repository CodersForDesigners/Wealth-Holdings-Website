<!-- ⬇ All Modals below this point ⬇ -->

<div id="modal-wrapper"><!-- Modal Wrapper -->
	<div class="modal-box js_modal_box">

		<!-- Modal Content : YouTube Video -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="youtube-video">
			<div class="container">
				<div class="row">
					<div class="columns small-12">
						<!-- video embed -->
						<div class="video-embed js_video_embed" data-autoplay="true">
							<!-- <div class="video-embed-placeholder" style="background-image: url( 'https://via.placeholder.com/1500' );"></div> -->
							<div class="video-loading-indicator"></div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- END : YouTube Video -->

		<!-- Modal Content : Share -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="share">
			<div class="container">
				<div class="row">
					<div class="modal-share columns small-12 medium-8 medium-offset-2 large-6 large-offset-3 fill-light space-50">
						<div class="text-neutral-2 h4 strong space-min-bottom opacity-50">Share</div>
						<div class="url-share space-25-bottom">
							<input type="text" class="url block fill-blue-4 js_url" readonly>
							<button class="button js_copy_url">Copy</button>
							<label class="feedback small text-neutral-2 js_copy_feedback">Copied to Clipboard</label>
							<textarea class="visuallyhidden js_url_hidden"></textarea>
						</div>
						<div class="social-share">
							<button class="share-button inline js_social_medium" data-social="facebook" style="background-image: url('../media/icon/icon-social-facebook.svg<?php echo $ver ?>'); background-color: #1877f2;">Facebook</button>
							<button class="share-button inline js_social_medium" data-social="linkedin" style="background-image: url('../media/icon/icon-social-linkedin.svg<?php echo $ver ?>'); background-color: #007BB5;">LinkedIn</button>
							<button class="share-button inline js_social_medium" data-social="twitter" style="background-image: url('../media/icon/icon-social-twitter.svg<?php echo $ver ?>'); background-color: #69ACE0;">Twitter</button>
							<button class="share-button inline js_social_medium" data-social="whatsapp" style="background-image: url('../media/icon/icon-social-whatsapp.svg<?php echo $ver ?>'); background-color: #3AC34C;">WhatsApp</button>
							<button class="share-button fill-red-2 inline hidden js_social_medium js_share_more_options" style="background-image: url('../media/icon/icon-share-more.svg<?php echo $ver ?>');">More</button>
						</div>
					</div>
				</div>
			</div>
		</div><!-- END : Share -->

	</div>

</div><!-- END : Modal Wrapper -->
