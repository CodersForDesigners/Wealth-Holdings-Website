<?php

$formName = get_field( 'form_name' );
$formContext = get_field( 'form_context' );
$fields = get_field( 'form_fields' ) ?: [ ];
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

<section class="form-section form-dark fill-neutral-4 space-50">
	<div class="h3 strong space-50-bottom"><?= $formName ?></div>
	<div class="forms-container js_forms_container">
		<form class="form primary-form js_cms_form" onsubmit="event.preventDefault()" data-context="<?= $formContext ?>" data-feedback="<?= htmlentities( $formFeedback ) ?>" data-heading="<?= $formName ?>">
			<?php foreach ( $fields as $index => $field ) : ?>
			<div class="form-row space-min-bottom">
				<label for="form-field-<?= $index ?>">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer"><?= $field[ 'label' ] ?></span><br>
					<?php if ( $field[ 'type' ] === 'text' ) : ?>
						<input class="block fill-dark js_cms_form_input" name="form-field-<?= $index ?>" type="text" id="form-field-<?= $index ?>" data-label="<?= $field[ 'label' ] ?>">
					<?php elseif ( $field[ 'type' ] === 'textarea' ) : ?>
						<textarea class="block fill-dark js_cms_form_input" name="form-field-<?= $index ?>" id="form-field-<?= $index ?>" data-label="<?= $field[ 'label' ] ?>"></textarea>
					<?php elseif ( $field[ 'type' ] === 'select' ) : ?>
						<select class="block fill-dark js_cms_form_input" name="form-field-<?= $index ?>" id="form-field-<?= $index ?>" data-label="<?= $field[ 'label' ] ?>">
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
					<input class="block fill-dark js_form_input_name" name="name" type="text" id="form-name">
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<label for="form-email">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Email</span><br>
					<input class="block fill-dark js_form_input_email" type="text" name="email-address" id="form-email">
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
						<input class="block fill-dark js_form_input_phonenumber" type="text" name="phone-number" id="form-phone-number">
					</div>
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<div class="text-red-2 h5 form-feedback js_form_feedback" style="display: none">
					Thank you.
					<br>
					We'll get in touch shortly.
				</div>
				<label for="form-field-submit">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
					<button class="button fill-red-2" type="submit" id="form-field-submit"><?= $formSubmitLabel ?></button>
				</label>
			</div>
		</form>
	</div>
</section>

<?php endif; ?>
