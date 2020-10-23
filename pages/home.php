<?php
/*
 *
 * This is a sample page you can copy and use as boilerplate for any new page.
 *
 */

// Page-specific preparatory code goes here.

?>

<?php require_once __DIR__ . '/../inc/above.php'; ?>





<!-- Sample Section -->
<section class="sample-section">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
			</div>
		</div>
	</div>
</section>
<!-- END: Sample Section -->


<!-- Intro Section -->
<section class="intro-section fill-red-2 space-100-top-bottom">
	<div class="container">
		<div class="intro row">
			<div class="columns small-12">
				<div class="logo space-100-bottom"><img class="block" src="../media/wh-logo-large-dark.svg<?php echo $ver ?>"></div>
			</div>
			<div class="columns small-12 medium-10 large-4 inline-top">
				<div class="description h5 line-height-large text-blue-4 space-50-bottom">Real estate assets that are backed by a business model have better rental yields. Include this revolutionary new asset class in your portfolio. Invest today, get the right returns, with managed volatility and capital protection.</div>
			</div>
			<div class="columns small-12 medium-7 large-5 large-offset-1 xlarge-4 inline-top">
				<div class="h5 strong line-height-large space-min-bottom">Curated rental-yielding assets, with</div>
				<div class="h1 strong">returns you cannot ignore.</div>
			</div>
		</div>
	</div>
</section>


<!-- Action Section -->
<section class="action-section fill-blue-4 space-100-top-bottom">
	<div class="container">
		<div class="action row">
			<div class="columns small-12 medium-9 large-6 large-offset-5 xlarge-5">
				<div class="heading h3 strong line-height-medium clearfix space-50-bottom">
					<img class="float-left" src="../media/wh-logo-small-red.svg<?php echo $ver ?>">
					Why not invest in a 3BHK flat and <span class="text-red-2">get a 7% return</span> on this investment? The return is <span class="text-red-2">better than a fixed deposit.</span>
				</div>
			</div>
			<div class="columns small-12 space-50-bottom">
				<div class="char-image">
					<img class="block hide-large hide-xlarge" src="../media/char-1-small.png<?php echo $ver ?>">
					<img class="block hide-small hide-medium" src="../media/char-1-large.png<?php echo $ver ?>">
				</div>
			</div>
			<div class="columns small-12 medium-6 large-4 js_contact_form_section">
				<div class="form form-dark space-25-bottom">
					<form class="part-1 js_contact_form_1" data-c="general-enquiry-form">
						<div class="form-row space-min-bottom">
							<label for="phone-number">
								<div class="label line-height-xlarge opacity-50 cursor-pointer">Phone Number</div>
								<div class="row">
									<div class="columns position-relative small-3">
										<select class="block position-relative opacity-0 js_phone_country_code">
											<?php include __DIR__ . '/../inc/phone-country-codes.php' ?>
										</select>
										<input type="text" class="block position-absolute no-pointer js_phone_country_code_label" value="+91" style="top: 0; left: 0;">
									</div>
									<div class="columns small-9 space-min-left">
										<input id="phone-number" type="text" class="block" name="phone-number">
									</div>
								</div>
							</label>
						</div>
						<div class="form-row space-min-bottom">
							<label>
								<div class="label line-height-xlarge opacity-50 cursor-pointer">Investment Budget</div>
								<select class="block" name="budget">
									<option>14 Lakhs to 45 Lakhs</option>
									<option>45 Lakhs to 95 Lakhs</option>
									<option>95 Lakhs to 1.45Cr</option>
									<option>1.45Cr and above</option>
								</select>
							</label>
						</div>
						<div class="form-row space-min-bottom">
							<label>
								<div class="label line-height-xlarge opacity-50 cursor-pointer invisible">Submit</div>
								<button class="fill-red-2 block" type="submit">Invest Today</button>
							</label>
						</div>
					</form>
					<form class="part-2 js_contact_form_2" style="display: none">
						<div class="form-row space-min-bottom">
							<label>
								<div class="label line-height-xlarge opacity-50 cursor-pointer">Name</div>
								<input type="text" class="block" name="name">
							</label>
						</div>
						<div class="form-row space-min-bottom">
							<label>
								<div class="label line-height-xlarge opacity-50 cursor-pointer">Email</div>
								<input type="text" class="block" name="email-address">
							</label>
						</div>
						<div class="form-row space-min-bottom">
							<label>
								<div class="label line-height-xlarge opacity-50 cursor-pointer invisible">Submit</div>
								<button class="fill-red-2 block" type="submit">Submit Details</button>
							</label>
						</div>
					</form>
				</div>
				<form class="form form-dark space-25-bottom js_otp_form" style="display: none">
					<div class="form-row space-min-bottom">
						<label>
							<div class="label line-height-xlarge opacity-50 cursor-pointer">Enter the OTP</div>
							<input type="text" name="otp" class="block">
						</label>
					</div>
					<div class="form-row space-min-bottom">
						<label>
							<div class="label line-height-xlarge opacity-50 cursor-pointer invisible">Verify</div>
							<button class="fill-red-2 block" type="submit">Verify</button>
						</label>
					</div>
				</form>
			</div>
			<div class="columns small-12 medium-5 medium-offset-1 large-4">
				<div class="contact">
					<div class="h5 text-blue-1 space-min-bottom">Talk to an investment <br>manager today.</div>
					<a class="h3 inline text-red-2" href="tel:">+91 98860 98860 </a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Intro Section -->


<!-- How Section -->
<section class="how-section space-100-top-bottom">
	<div class="container">
		<div class="row">
			<div class="how columns small-12 large-10">
				<div class="h3 strong space-50-bottom">How does it work?</div>
				<div class="table">
					<div class="row table-head">
						<div class="table-cell h5 fill-red-2 columns small-6 space-25 clearfix">Lumpsum <span class="float-right">+</span></div>
						<div class="table-cell h5 fill-blue-4 columns small-6 space-25 clearfix">EMI <span class="float-right">+</span></div>
					</div>
					<div class="row table-row">
						<div class="table-cell p fill-red-1 text-red-4 columns small-6 space-25"><span class="num strong">1.</span>Invest in any of the listed residential properties. </div>
						<div class="table-cell p fill-blue-2 text-blue-4 columns small-6 space-25"><span class="num strong">1.</span>Get a home loan approval for any of the listed residential properties.</div>
					</div>
					<div class="row table-row">
						<div class="table-cell p fill-red-1 text-red-4 columns small-6 space-25"><span class="num strong">2.</span>The property is transferred to you via an absolute sale deed. </div>
						<div class="table-cell p fill-blue-2 text-blue-4 columns small-6 space-25"><span class="num strong">2.</span>Pay the ‘Minimum Investment Amount’ as the downpayment for the home loan.</div>
					</div>
					<div class="row table-row">
						<div class="table-cell p fill-red-1 text-red-4 columns small-6 space-25"><span class="num strong">3.</span>Sign a 9-year rental agreement.</div>
						<div class="table-cell p fill-blue-2 text-blue-4 columns small-6 space-25"><span class="num strong">3.</span>Complete the home loan paperwork and formalities. </div>
					</div>
					<div class="row table-row">
						<div class="table-cell p fill-red-1 text-red-4 columns small-6 space-25"><span class="num strong">4.</span>Get a fixed yearly rental income for 9 years.</div>
						<div class="table-cell p fill-blue-2 text-blue-4 columns small-6 space-25"><span class="num strong">4.</span>Sign a 9-year rental agreement.</div>
					</div>
					<div class="row table-row">
						<div class="table-cell p fill-red-1 text-red-4 columns small-6 space-25">&nbsp;</div>
						<div class="table-cell p fill-blue-2 text-blue-4 columns small-6 space-25"><span class="num strong">5.</span>Get a fixed yearly rental income for 9 years.</div>
					</div>
					<div class="row table-row">
						<div class="table-cell p fill-red-1 text-red-4 columns small-6 space-25">&nbsp;</div>
						<div class="table-cell p fill-blue-2 text-blue-4 columns small-6 space-25"><span class="num strong">6.</span>The rental income services your monthly EMI’s. </div>
					</div>
					<div class="row table-foot">
						<div class="table-cell text-red-2 text-right text-uppercase label space-min columns small-6">Show Details</div>
						<div class="table-cell text-blue-4 text-right text-uppercase label space-min columns small-6">Show Details</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: How Section -->


<!-- About Section -->
<section class="about-section fill-blue-4 space-100-top-bottom">
	<div class="container">
		<div class="row">
			<div class="about columns small-12 large-10 xlarge-8">
				<div class="logo"><img class="block" src="../media/wh-logo-large-red.svg<?php echo $ver ?>"></div>
				<div class="p text-blue-1 space-50-top">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit quas dignissimos est suscipit ratione consequatur debitis sequi, molestiae quasi, esse, aliquam quibusdam necessitatibus vel accusantium alias, cum. Molestiae, mollitia, voluptatem.</div>
			</div>
		</div>
	</div>
</section>
<!-- END: About Section -->

<?php require_once __DIR__ . '/../inc/below.php'; ?>
