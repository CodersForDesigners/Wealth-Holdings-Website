<?php
/*
 |
 | Get a Callback section
 |
 |
 */

?>
<section class="get-callback-section fill-blue-4 js_section_get_callback" id="get-callback-section" data-section-title="Get Callback Section" data-section-slug="get-callback-section">
	<div class="container">
		<div class="get-callback-form row space-75-top-bottom">
			<div class="columns small-12 large-5 h2 strong text-red-2 space-25-bottom">Get a Callback</div>
			<form class="columns small-12 large-7 form form-dark phone-form js_get_callback_form" onsubmit="event.preventDefault()">
				<div class="forms-container">
					<div class="form-row">
						<label for="webinar-form-phone-number">
							<div style="position: relative; display: flex">
								<select class="phone-country-code js_phone_country_code">
									<?php include __ROOT__ . '/pages/snippets/phone-country-codes.php' ?>
								</select>
								<input class="phone-country-code no-pointer js_phone_country_code_label" type="text" value="+91" tabindex="-1" readonly>
								<input class="block fill-dark js_form_input_phonenumber" type="text" name="phone-number" id="webinar-form-phone-number">
							</div>
						</label>
					</div>
					<button class="button fill-red-2" type="submit">Submit</button>
					<div class="fine-print label line-height-small opacity-50"><span class="js_post_callback_submission_message">We will call you back within one working day.</span></div>
				</div>
			</form>
		</div>
	</div>
</section>
