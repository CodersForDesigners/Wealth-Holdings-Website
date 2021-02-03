<?php

$formName = get_field( 'form_name' );
$fields = get_field( 'form_fields' );
$formSubmitLabel = get_field( 'form_submit_label' );
$formFeedback = get_field( 'form_feedback_message' );
foreach ( $fields as &$field ) {
	$field[ 'type' ] = $field[ 'acf_fc_layout' ];
	if ( $field[ 'type' ] == 'select' )
		$field[ 'options' ] = explode( "\r\n", $field[ 'options' ] );
}
unset( $field );

?>
<?php if ( $is_preview ) : ?>
	<h4>(A form will be placed here.)</h4>
<?php else : ?>

<section class="form-section form-dark fill-neutral-4 space-50 js_forms_container">
	<div class="h3 strong space-50-bottom"><?= $formName ?></div>
	<div class="forms-container">
		<form class="form primary-form js_primary_form" onsubmit="event.preventDefault()" data-feedback="<?= $formFeedback ?>">
			<?php foreach ( $fields as $index => $field ) : ?>
			<div class="form-row space-min-bottom">
				<label for="form-field-<?= $index ?>">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer"><?= $field[ 'label' ] ?></span><br>
					<?php if ( $field[ 'type' ] === 'text' ) : ?>
						<input class="block fill-dark" name="form-field-<?= $index ?>" type="text" id="form-field-<?= $index ?>" data-label="<?= $field[ 'label' ] ?>">
					<?php elseif ( $field[ 'type' ] === 'textarea' ) : ?>
						<textarea class="block fill-dark" name="form-field-<?= $index ?>" id="form-field-<?= $index ?>" data-label="<?= $field[ 'label' ] ?>"></textarea>
					<?php elseif ( $field[ 'type' ] === 'select' ) : ?>
						<select class="block fill-dark" name="form-field-<?= $index ?>" id="form-field-<?= $index ?>" data-label="<?= $field[ 'label' ] ?>">
							<?php foreach ( $field[ 'options' ] as $option ) : ?>
								<option><?= $option ?></option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				</label>
			</div>
			<?php endforeach; ?>
			<!-- The core fields -->
			<div class="form-row space-min-bottom">
				<label for="form-name">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Name</span><br>
					<input class="block fill-dark" name="name" type="text" id="form-name">
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<label for="form-email">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Email</span><br>
					<input class="block fill-dark" type="text" name="email-address" id="form-email">
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<label for="form-phone-number">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
					<div style="position: relative; display: flex">
						<select class="js_phone_country_code" style="position: absolute; top: 0; left: 0; background-color: transparent; color: transparent; width: 26%;">
							<?php include ABSPATH . '/../inc/phone-country-codes.php' ?>
						</select>
						<input type="text" class="no-pointer js_phone_country_code_label" value="+91" tabindex="-1" readonly style="width: 26%">
						<input class="block fill-dark" type="text" name="phone-number" id="form-phone-number">
					</div>
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<div class="js_form_feedback" style="display: none">
					Thank you.
					<br>
					Someone will get in touch with you shortly.
				</div>
				<label for="form-field-submit">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
					<button class="button fill-red-2" type="submit" id="form-field-submit"><?= $formSubmitLabel ?></button>
				</label>
			</div>
		</form>
		<form class="form otp-form js_otp_form" onsubmit="event.preventDefault()">
			<div class="form-row space-min-bottom">
				<label for="form-otp">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">We've sent you an OTP. Kindly provide it below.</span><br>
					<input class="block fill-dark" type="text" name="otp" id="form-otp">
				</label>
				<span class="small text-uppercase line-height-small opacity-50 cursor-pointer js_resend_otp hidden">Re-send OTP</span>
				<span class="small text-uppercase line-height-small opacity-50 cursor-pointer js_try_different_number hidden">Try a different number</span>
			</div>
			<div class="form-row space-min-bottom">
				<label for="">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
					<button class="button fill-red-2" type="submit">Verify OTP</button>
				</label>
			</div>
		</form>
	</div>
</section>
<script type="text/javascript" src="/js/modules/intercept-form-with-login-prompt.js"></script>
<?php endif; ?>
