<?php
/*
 |
 | Home Page
 |
 */
require_once __ROOT__ . '/lib/providers/wordpress.php';
require_once __ROOT__ . '/types/investments/investments.php';
require_once __ROOT__ . '/types/co-investments/co-investments.php';
require_once __ROOT__ . '/types/faqs/faqs.php';
require_once __ROOT__ . '/types/brochures/brochures.php';
require_once __ROOT__ . '/types/tiles/tiles.php';
require_once __ROOT__ . '/types/testimonials/testimonials.php';

use BFS\CMS\WordPress;
use BFS\Types\Investments;
use BFS\Types\CoInvestments;
use BFS\Types\FAQs;
use BFS\Types\Brochures;
use BFS\Types\Tiles;
use BFS\Types\Testimonials;



/**
 |
 | Investments
 |
 */
$investments = Investments::getAll();
$investmentCategories = Investments::getCategories();

// Determine whether to show the "View All" overlay at all in the first place
$numberOfInvestments = count( $investments );
$hideInvestmentsPagination = '';
if ( $numberOfInvestments <= 9 )
	$hideInvestmentsPagination .= 'view-all-l view-all-xl';
if ( $numberOfInvestments <= 6 )
	$hideInvestmentsPagination .= ' view-all-m';
if ( $numberOfInvestments <= 3 )
	$hideInvestmentsPagination .= ' view-all-s';


/**
 |
 | Co-Investments
 |
 */
$coInvestments = CoInvestments::getAll();
$coInvestmentCategories = CoInvestments::getCategories();

// Determine whether to show the "View All" overlay at all in the first place
$numberOfCoInvestments = count( $coInvestments );
$hideCoInvestmentsPagination = '';
if ( $numberOfCoInvestments <= 9 )
	$hideCoInvestmentsPagination .= 'view-all-l view-all-xl';
if ( $numberOfCoInvestments <= 6 )
	$hideCoInvestmentsPagination .= ' view-all-m';
if ( $numberOfCoInvestments <= 3 )
	$hideCoInvestmentsPagination .= ' view-all-s';



$webinarDate = WordPress::get( 'webinar_date' ) ?: ( 'Registered interest at ' . date( 'h:ia, d/m/Y' ) );



$faqs = FAQs::getFeatured();

$brochures = Brochures::getAll();

$tileLinks = Tiles::getBySection( 'home' );

// Fetch and chunk it in sets of 2
$testimonialSets = array_chunk(
	Testimonials::getAll(),
	2,
	true
);

?>

<?php require_once __ROOT__ . '/pages/partials/header.php'; ?>


<?php
 /*
  * ----- Seed some data for use in JavaScript
  */
?>
<script type="text/javascript">

	window.__BFS = window.__BFS || { };
	window.__BFS.data = window.__BFS.data || { };
	window.__BFS.data.webinarDate = "<?= $webinarDate ?>";

	<?php
		foreach ( $investments as $investment )
			$investmentsForJS[ ] = $investment->getAll();
	?>
	window.__BFS.data.investments = <?= json_encode( $investmentsForJS ) ?>;

	<?php
		foreach ( $coInvestments as $coInvestment )
			$coInvestmentsForJS[ ] = $coInvestment->getAll();
	?>
	window.__BFS.data.coInvestments = <?= json_encode( $coInvestmentsForJS ) ?>;

</script>



<!-- Landing Section -->
<section class="landing-section fill-red-2 space-50-top" id="landing-section" data-section-title="Landing Section" data-section-slug="landing-section">
	<div class="container">
		<div class="row">
			<div class="columns small-12 large-5">
				<div class="row">
					<div class="columns small-12 medium-4 space-25-top-bottom">
						<div class="logo"><img class="block" src="/media/wh-logo-large-dark.svg<?php echo $ver ?>"></div>
					</div>
					<div class="columns small-12 medium-10 large-12 space-50-top-bottom">
						<div class="h5 strong space-min-bottom">Curated rental-yielding assets, with</div>
						<div class="h1 strong">returns you cannot ignore.</div>
						<div class="h5 text-blue-4 space-50-top">Invest in rental-yielding real estate assets that are backed by a business model. Get the right returns, with managed volatility and capital protection.</div>
					</div>
				</div>
			</div>
			<div class="columns small-12 large-6 large-offset-1 space-25-bottom">
				<div class="char-image">
					<img class="block" src="../media/char-1.png<?php echo $ver ?>">
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Landing Section -->


<!-- Benefits Section -->
<section class="benefits-section fill-red-2 space-25-top space-75-bottom" id="benefits-section" data-section-title="Benefits Section" data-section-slug="benefits-section">
	<div class="container">
		<div class="row">
			<div class="columns small-12 tile-grid">
				<div class="tile t-1 fill-blue-4">
					<!-- (1.) -->
					<div class="h5 strong space-50-bottom">Choose full ownership. Invest in a 3BHK, get it registered in your name. Get a fixed monthly income.</div>
					<div class="h6 strong space-50-bottom clearfix">
						<img class="icon float-left" src="../media/icon/icon-right-arrow-box-red.svg<?php echo $ver ?>">
						The return is better than a fixed deposit.
					</div>
					<div class="hn strong text-red-2">7%</div>
				</div>
				<div class="tile t-2">
					<!-- (2.) -->
					<div class="hn strong text-uppercase line-height-small">Invest</div>
				</div>
				<div class="tile t-3 outline-red-2 hide-small">
					<!-- (3.) -->
					<div class="h6 strong">own an apartment with <br>a business model</div>
				</div>
				<div class="tile t-4 minimum-investment-amount no-pointer" tabindex="-1">
					<div class="front fill-blue-4">
						<!-- (4.) -->
						<div class="h6 strong space-25-bottom">Buy our 'Co-Own' fractional units by investing just</div>
						<div class="hn strong text-red-2">₹5 Lakhs</div>
					</div>
					<div class="back fill-blue-4">
						<div>
							<a class="label block text-lowercase" href="/minimum-investment-amount">Read More <span class="material-icons">subject</span></a>
							<a class="label inline text-lowercase js_modal_trigger" data-mod-id="share" href="">Share <span class="material-icons backface-not-invisible" style="transform: scaleX(-1);">reply</span></a>
						</div>
					</div>
				</div>
				<div class="tile t-5 fill-blue-4 hide-small">
					<!-- (5.) -->
					<div class="space-min-bottom h5 strong">Co-own</div>
					<div class="label strong">Buy fractional units. <span class="text-red-2">Earn fixed monthly income.</span></div>
				</div>
				<div class="tile t-6 fill-red-3 hide-small">
					<!-- (6.) -->
					<div class="h6 strong">walking distance from SEZs, tech parks & schools</div>
				</div>
				<div class="tile t-7 emi no-pointer" tabindex="-1">
					<div class="front fill-dark">
						<!-- (7.) -->
						<div class="h5 strong space-min-bottom">EMI</div>
						<div class="small strong">Invest only the down payment and <span class="text-red-2">get the rent to fund your EMIs.</span></div>
					</div>
					<div class="back fill-dark">
						<div>
							<a class="label block text-lowercase" href="/emi">Read More <span class="material-icons">subject</span></a>
							<a class="label inline text-lowercase js_modal_trigger" data-mod-id="share" href="">Share <span class="material-icons backface-not-invisible" style="transform: scaleX(-1);">reply</span></a>
						</div>
					</div>
				</div>
				<div class="tile t-8" style="background-image: url('../media/char-4.png<?php echo $ver ?>'); background-size: cover; background-position: center center;">
					<!-- (8.) -->
				</div>
				<div class="tile t-9 lumpsum no-pointer" tabindex="-1">
					<div class="front fill-dark">
						<!-- (9.) -->
						<div class="h5 strong space-min-bottom">Lumpsum</div>
						<!-- <div class="label strong">Make a <span class="text-red-2">one-time investment</span> <br>and enjoy a monthly fixed <br>rental income.</div> -->
						<div class="small strong">Get a monthly fixed income, <br>on a <span class="text-red-2">one-time investment.</span></div>
					</div>
					<div class="back fill-dark">
						<div>
							<a class="label block text-lowercase" href="/lumpsum">Read More <span class="material-icons">subject</span></a>
							<a class="label inline text-lowercase js_modal_trigger" data-mod-id="share" href="">Share <span class="material-icons backface-not-invisible" style="transform: scaleX(-1);">reply</span></a>
						</div>
					</div>
				</div>
				<div class="tile t-10">
					<!-- (10.) -->
					<div class="hn strong text-uppercase line-height-small">Earn</div>
				</div>
				<div class="tile t-11 outline-red-2">
					<!-- (11.) -->
					<div class="h3 strong">9+9 Years</div>
					<div class="h5 strong">Rental Agreement</div>
				</div>
				<div class="tile t-12 fill-blue-4">
					<!-- (12.) -->
					<div class="h5 strong">fixed <span class="text-red-2">monthly income</span></div>
					<div class="hn strong text-red-2">6% to 7.5%</div>
				</div>
				<div class="tile t-13 fill-red-3 hide-small">
					<!-- (13.) -->
					<div class="h6 strong">10% Increment at the end of 9 years</div>
				</div>
				<div class="tile t-14">
					<!-- (14.) -->
					<div class="hn strong text-uppercase line-height-small">Gain</div>
				</div>
				<div class="tile t-15 outline-red-2">
					<!-- (15.) -->
					<div class="h5 strong space-25-bottom line-height-small clearfix">
						<img class="icon float-left" src="../media/icon/icon-bar-red.svg<?php echo $ver ?>">
						capital <br>appreciation
					</div>
					<div class="h5 strong line-height-small clearfix">
						<img class="icon float-left" src="../media/icon/icon-bar-red.svg<?php echo $ver ?>">
						+ fixed <br>rental income
					</div>
				</div>
				<div class="tile t-16 fill-blue-4">
					<!-- (16.) -->
					<div class="h5 strong">you invest the down payment</div>
					<div class="hn strong text-red-2">the rent funds your EMI</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Benefits Section -->


<!-- Investment Section -->
<?php require_once __DIR__ . '/sections/home/investments.php'; ?>
<!-- END: Investment Section -->


<!-- How Investments Work Section -->
<section class="how-investments-work-section fill-dark space-75-top-bottom" id="how-investments-work-section" data-section-title="How Investments Work Section" data-section-slug="how-investments-work-section">
	<div class="container">
		<div class="row">
			<div class="columns small-12 large-2 space-50-bottom">
				<div class="h2 strong">How does it work?</div>
			</div>
			<div class="lumpsum columns small-11 small-offset-1 medium-5 large-4 space-50-bottom">
				<div class="h3 strong text-yellow-2 space-25-bottom">Lumpsum</div>
				<div class="table">
					<div class="table-row h6">
						Invest in any of the listed residential properties.
					</div>
					<div class="table-row h6">
							The property is transferred to you via an absolute sale deed.
							<div class="tag label text-yellow-2">Invest</div>
						</div>
					<div class="table-row h6">
						Sign a 9-year rental agreement.
					</div>
					<div class="table-row h6">
							Get a fixed yearly rental income for 9 years.
							<div class="tag label text-yellow-2">Earn</div>
						</div>
					<div class="table-row h6">
							Get the benefit of capital appreciation plus fixed income.
							<div class="tag label text-yellow-2">Gain</div>
						</div>
				</div>
			</div>
			<div class="emi columns small-11 small-offset-1 medium-5 large-4">
				<div class="h3 strong text-green-2 space-25-bottom">EMI</div>
				<div class="table">
					<div class="table-row h6">
						Get a home loan approval for any of the listed residential properties.
					</div>
					<div class="table-row h6">
						Pay the ‘Minimum Investment Amount’ as the downpayment for the home loan.
					</div>
					<div class="table-row h6">
							Complete the home loan paperwork and formalities.
							<div class="tag label text-green-2">Invest</div>
						</div>
					<div class="table-row h6">
						Sign a 9-year rental agreement.
					</div>
					<div class="table-row h6">
							Get a fixed yearly rental income for 9 years.
							<div class="tag label text-green-2">Earn</div>
						</div>
					<div class="table-row h6">
						The rental income services your monthly EMI’s.
					</div>
					<div class="table-row h6">
							Build an appreciating asset without paying EMI.
							<div class="tag label text-green-2">Gain</div>
						</div>
				</div>
			</div>
		</div>
	</div>
	<div class="character row hide-small hide-medium">
		<div class="container">
			<div class="columns small-12 medium-8 large-4">
				<div class="char-image">
					<img class="block" src="../media/char-2.png<?php echo $ver ?>">
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: How Investments Work Section -->


<!-- Co-Investment Section -->
<?php require_once __DIR__ . '/sections/home/co-investments.php'; ?>
<!-- END: Co-Investment Section -->

<!-- How Co-Investments Work Section -->
<section class="how-investments-work-section fill-black space-75-top-bottom" id="how-co-investments-work-section" data-section-title="How Co-Investments Work Section" data-section-slug="how-co-investments-work-section">
	<div class="container">
		<div class="row">
			<div class="columns small-12 large-2 space-50-bottom">
				<div class="h2 strong">How does it work?</div>
			</div>
			<div class="co-own columns small-11 small-offset-1 medium-5 large-4">
				<div class="h3 strong text-blue-1 space-25-bottom">Co-Own</div>
				<div class="table">
					<div class="table-row h6">
						Choose the number of units you want to purchase.
					</div>
					<div class="table-row h6">
						Fill out the application form and submit with KYC.
					</div>
					<div class="table-row h6">
						Transfer the payment to our bank account.
						<div class="tag label text-blue-1">Invest</div>
					</div>
					<div class="table-row h6">
						Documentation will be executed in your name.
					</div>
					<div class="table-row h6">
						Get a fixed monthly income.
						<div class="tag label text-blue-1">Earn</div>
					</div>
					<div class="table-row h6">
						Your units will appreciate to capture the appreciating property value.
					</div>
					<div class="table-row h6">
						Sell your units at any time.
						<div class="tag label text-blue-1">Gain</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="character row hide-small hide-medium">
		<div class="container">
			<div class="columns small-12 medium-8 large-4">
				<div class="char-image">
					<img class="block" src="../media/char-6.png<?php echo $ver ?>">
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: How Co-Investments Work Section -->


<!-- Webinar Section -->
<section class="webinar-section fill-blue-4 js_section_webinar" id="webinar-section" data-section-title="Webinar Section" data-section-slug="webinar-section">
	<div class="webinar-image" style="background-image: url('../media/char-5.png<?php echo $ver ?>');"></div>
	<div class="container">
		<div class="row">
			<div class="webinar-form columns small-12 medium-6 medium-offset-6 space-75-top-bottom xlarge-5">
				<div class="h3 strong text-red-2 space-25-bottom">Register for the next webinar</div>
				<div class="h5 space-min-bottom"><?= $webinarDate ?></div>
				<div class="p opacity-75 space-50-bottom">Join our investment manager for a 30 minute presentation and 30 minutes of Q&A.</div>
				<div class="p opacity-75 space-50-bottom js_post_registration_message" style="display: none">
					Registration successful.
					You will receive an invite in your email ({{ emailAddress }}) inbox shortly.
					<br>
					See you on <?= $webinarDate ?>.
				</div>
				<div class="forms-container">
					<form class="form form-dark phone-form js_webinar_form" onsubmit="event.preventDefault()">
						<div class="form-row space-min-bottom">
							<label for="webinar-form-name">
								<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Name</span><br>
								<input class="block fill-dark js_form_input_name" name="name" type="text" id="webinar-form-name">
							</label>
						</div>
						<div class="form-row space-min-bottom">
							<label for="webinar-form-email">
								<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Email</span><br>
								<input class="block fill-dark js_form_input_email" type="text" name="email-address" id="webinar-form-email">
							</label>
						</div>
						<div class="form-row space-min-bottom">
							<label for="webinar-form-phone-number">
								<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
								<div style="position: relative; display: flex">
									<select class="js_phone_country_code" style="position: absolute; top: 0; left: 0; background-color: transparent; color: transparent; width: 26%;">
										<?php include __ROOT__ . '/pages/snippets/phone-country-codes.php' ?>
									</select>
									<input type="text" class="no-pointer js_phone_country_code_label" value="+91" tabindex="-1" readonly style="width: 26%">
									<input class="block fill-dark js_form_input_phonenumber" type="text" name="phone-number" id="webinar-form-phone-number">
								</div>
							</label>
						</div>
						<div class="form-row space-min-bottom">
							<label for="">
								<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
								<button class="button fill-red-2" type="submit">Register Today</button>
							</label>
						</div>
					</form>
					<form class="form form-dark otp-form js_otp_form" onsubmit="event.preventDefault()">
						<div class="form-row space-min-bottom">
							<label for="webinar-form-otp">
								<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">We've sent you an OTP. Kindly provide it below.</span><br>
								<input class="block fill-dark" type="text" name="otp" id="webinar-form-otp">
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
			</div>
		</div>
	</div>
</section>
<!-- END: Webinar Section -->


<!-- FAQs Section -->
<section class="faqs-section fill-neutral-1 space-75-top js_section_faqs" id="faqs-section" data-section-title="FAQs Section" data-section-slug="faqs-section">
	<div class="container">
		<div class="row text-blue-4">
			<div class="columns small-12 large-3">
				<div class="h2 strong space-50-bottom">FAQs</div>
			</div>
			<div class="columns small-12 large-9 xlarge-8">
				<div class="faqs space-75-bottom">
				<?php foreach ( $faqs as $faq ) : ?>
					<div class="faq space-25-top-bottom js_shareable js_faq" data-title="<?= $faq->get( 'post_title' ) ?>" data-description="<?= $faq->get( 'summary' ) ?>" data-image="<?= $faq->get( 'featuredImage' ) ?: '' ?>" data-url="<?= $faq->get( 'url' ) ?>">
						<div class="title h5 strong js_faq_title"><?= $faq->get( 'post_title' ) ?></div>
						<div class="summary">
							<div class="row">
								<?php if ( $faq->get( 'featuredImage' ) ) : ?>
									<div class="thumbnail columns small-12 medium-4 space-min-top">
										 <img class="block" src="<?= $faq->get( 'featuredImage' ) ?>">
									</div>
								<?php endif; ?>
								<div class="columns small-12 medium-8 space-min-top">
									<div class="description h6 opacity-50 space-min-bottom"><?= $faq->get( 'summary' ) ?></div>
									<div class="action clearfix hidden">
										<?php if ( $faq->get( 'thereIsMore?' ) ) : ?>
											<a class="label inline text-lowercase" href="<?= $faq->get( 'url' ) ?>" target="_blank">Read More <span class="material-icons">subject</span></a>
										<?php endif; ?>
										<a class="label inline text-lowercase js_modal_trigger" data-mod-id="share" href="">Share <span class="material-icons" style="transform: scaleX(-1);">reply</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
					<a class="inline big-link h4 strong hidden" tabindex="-1" href="/faqs" target="_blank">
						<span>Help Center</span>
					</a>
				</div>
				<!-- Brochure Section -->
				<div class="brochures hidden">
					<div class="row">
						<?php foreach ( $brochures as $brochure ) : ?>
							<div class="brochure columns small-12 medium-6 fill-<?= $brochure->get( 'color' )[ 'background' ] ?>">
								<div class="layer-1" style="background-image: url( '<?= $brochure->get( 'image' ) ?>' );"></div>
								<div class="layer-2">
									<div class="h4 strong space-25-bottom"><?= $brochure->get( 'post_title' ) ?></div>
									<a class="button fill-<?= $brochure->get( 'color' )[ 'button' ] ?>" href="<?= $brochure->get( 'brochure' ) ?>" target="_blank">Download Now</a>
								</div>
								<div class="layer-3">
									<form class="form block form-dark js_phone_form" onsubmit="event.preventDefault()">
										<div class="form-row space-25-bottom">
											<div class="title h5 strong">Signup to Download <br>for Free.</div>
										</div>
										<div class="form-row space-min-bottom">
											<label for="">
												<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
												<div style="position: relative; display: flex">
													<select class="js_phone_country_code" style="position: absolute; top: 0; left: 0; background-color: transparent; color: transparent; width: 26%;">
														<?php include __ROOT__ . '/pages/snippets/phone-country-codes.php' ?>
													</select>
													<input type="text" class="no-pointer js_phone_country_code_label" value="+91" tabindex="-1" readonly style="width: 26%">
													<input class="block" type="text" name="phone-number" id="">
												</div>
											</label>
										</div>
										<div class="form-row space-min-bottom">
											<label for="">
												<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
												<button class="button fill-red-2" type="submit">Get Details</button>
											</label>
										</div>
									</form>
									<form class="form block form-dark js_otp_form" style="display: none" onsubmit="event.preventDefault()">
										<div class="form-row space-min-bottom">
											<label for="">
												<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">We've sent you an OTP. Kindly provide it below.</span><br>
												<input class="block" type="text" name="otp" id="">
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
									<div class="close" tabindex="-1"><img class="icon block" src="../media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<!-- END: Brochure Section -->
			</div>
		</div>
	</div>
	<!-- Tile Links Section -->
	<div class="tile-link-section space-75-bottom hidden">
		<div class="container">
			<div class="row">
				<div class="columns small-12 large-9 large-offset-3">
					<div class="row">
						<?php foreach ( $tileLinks as $tile ) : ?>
							<div class="tile columns small-12 medium-6 fill-<?= $tile->get( 'bg_color' ) ?> js_tile <?= $tile->get( 'trigger_login_flow' ) ? 'js_user_required' : '' ?>" <?php if ( $tile->get( 'trigger_login_flow' ) ) echo 'data-login-prompt-title="' . htmlentities( $tile->get( 'login_flow_title' ) ) . '"' ?>>
								<div class="layer-1" <?php if ( $tile->get( 'bg_image' ) ) : ?>style="background-image: url( '<?= $tile->get( 'bg_image' ) ?>' );"<?php endif; ?>></div>
								<div class="layer-2">
									<div class="label opacity-75 space-min-bottom text-<?= $tile->get( 'text_color' ) ?> js_tile_subheading"><?= $tile->get( 'tile_label' ) ?></div>
									<div class="<?= $tile->get( 'title_size' ) ?> strong space-25-bottom text-<?= $tile->get( 'text_color' ) ?> js_title"><?= $tile->get( 'tile_title' ) ?></div>
									<?php if ( $tile->get( 'link' ) ) : ?>
										<a class="button fill-<?= $tile->get( 'button_bg_color' ) ?> text-<?= $tile->get( 'button_color' ) ?> js_action" href="#" data-href="<?= $tile->get( 'link' ) ?>" target="_blank"><?= $tile->get( 'button_text' ) ?></a>
									<?php else: ?>
										<button class="button fill-<?= $tile->get( 'button_bg_color' ) ?> text-<?= $tile->get( 'button_color' ) ?> js_action js_modal_trigger" data-mod-id="youtube-video" data-src="<?= $tile->get( 'videoId' ) ?>"><?= $tile->get( 'button_text' ) ?></button>
									<?php endif; ?>
								</div>
								<div class="layer-3 js_tile_login_prompt_container"></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END: Tile Links Section -->
</section>
<!-- END: FAQs Section -->


<!-- Testimonials Section -->
<section class="testimonials-section fill-light space-75-top space-25-bottom" id="testimonials-section" data-section-title="Testimonials Section" data-section-slug="testimonials-section">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<div class="h2 strong text-neutral-2">Testimonials</div>
			</div>
		</div>
	</div>
	<div class="row testimonials carousel js_carousel_container">
		<div class="carousel-list js_carousel_content">
			<?php foreach ( $testimonialSets as $testimonialSet ) : ?>
				<div class="carousel-list-item js_carousel_item">
					<?php foreach ( $testimonialSet as $testimonial ) : ?>
						<div class="testimonial">
							<div class="tile">
								<?php if ( $testimonial->get( 'video_thumbnail' ) ) : ?>
									<div class="js_modal_trigger" data-mod-id="youtube-video" data-src="<?= $testimonial->get( 'video_url' ) ?>">
										<div class="testimonial-content-video" style="background-image: url( '<?= $testimonial->get( 'video_thumbnail' ) ?>' );" tabindex="-1"></div>
									</div>
								<?php endif; ?>
								<div class="testimonial-content-text label"><?= $testimonial->get( 'testimonial' ) ?></div>
								<div class="testimonial-info">
									<div class="photo" style="background-image: url( '<?= $testimonial->get( 'photograph' ) ?>' )"></div>
									<div class="meta">
										<div class="name h6"><?= $testimonial->get( 'post_title' ) ?></div>
										<div class="occupation label"><?= $testimonial->get( 'occupation' ) ?></div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="scroll-controls">
			<div class="row">
				<div class="container">
					<div class="columns small-6">
						<div class="scroll-button left scroll-left unselectable js_pager" data-dir="left" tabindex="-1"><img class="block" src="../media/icon/icon-left-arrow-red.svg<?php echo $ver ?>"></div>
					</div>
					<div class="columns small-6 text-right">
						<div class="scroll-button right scroll-right unselectable js_pager" data-dir="right" tabindex="-1"><img class="block" src="../media/icon/icon-right-arrow-red.svg<?php echo $ver ?>"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Testimonials Section -->


<!-- Templates Section -->
<section class="js_section_templates hidden">
	<!-- TEMPLATE: Back of Investment Card -->
	<template class="js_template" data-name="investment-card-back">
		<div>
			<div class="close js_investment_card_unflip" tabindex="-1"><img class="icon block" src="/media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
			<div class="title h4 strong text-red-2 space-25-bottom js_message">Get access to a detailed offer document now.</div>
			<form class="form form-dark js_investment_form" onsubmit="event.preventDefault()">
				<div class="form-row space-min-bottom">
					<label for="investment-form-phone-number">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span>
						<div style="position: relative; display: flex">
							<select class="js_phone_country_code" style="position: absolute; top: 0; left: 0; background-color: transparent; color: transparent; width: 26%;">
								<?php include __ROOT__ . '/pages/snippets/phone-country-codes.php' ?>
							</select>
							<input type="text" class="no-pointer js_phone_country_code_label" value="+91" tabindex="-1" readonly style="width: 26%">
							<input class="block js_form_input_phonenumber" type="text" name="phone-number" id="investment-form-phone-number">
						</div>
					</label>
				</div>
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span>
						<button class="button block fill-red-2" type="submit">Get Details</button>
					</label>
				</div>
			</form>
			<div class="or-separator"><span class="label">OR</span><hr class="dashed neutral-4"></div>
			<div class="h5 text-neutral-2 line-height-xlarge"><?= $webinarDate ?></div>
			<div class="label space-25-bottom">Join our investment manager for a 30min presentation and 30min of Q&A.</div>
			<a href="#webinar-section" class="button" style="box-shadow: inset 0px 0px 0px 1px var(--red-3)">Register for Webinar</a>
		</div>
	</template>
	<!-- END: TEMPLATE: Back of Investment Card -->
	<!-- TEMPLATE: Back of Co-Investment Card -->
	<template class="js_template" data-name="co-investment-card-back">
		<div>
			<div class="close js_investment_card_unflip" tabindex="-1"><img class="icon block" src="/media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
			<div class="title h4 strong space-25-bottom js_message">Get access to a detailed offer document now.</div>
			<form class="form form-dark js_co_investment_form" onsubmit="event.preventDefault()">
				<div class="form-row space-min-bottom">
					<label for="investment-form-phone-number">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span>
						<div style="position: relative; display: flex">
							<select class="js_phone_country_code" style="position: absolute; top: 0; left: 0; background-color: transparent; color: transparent; width: 26%;">
								<?php include __ROOT__ . '/pages/snippets/phone-country-codes.php' ?>
							</select>
							<input type="text" class="no-pointer js_phone_country_code_label" value="+91" tabindex="-1" readonly style="width: 26%">
							<input class="block js_form_input_phonenumber" type="text" name="phone-number" id="investment-form-phone-number">
						</div>
					</label>
				</div>
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span>
						<button class="button block fill-red-2" type="submit">Get Details</button>
					</label>
				</div>
			</form>
			<div class="or-separator"><span class="label">OR</span><hr class="dashed neutral-4"></div>
			<div class="h5 text-neutral-2 line-height-xlarge"><?= $webinarDate ?></div>
			<div class="label space-25-bottom">Join our investment manager for a 30min presentation and 30min of Q&A.</div>
			<a href="#webinar-section" class="button" style="box-shadow: inset 0px 0px 0px 1px var(--red-3)">Register for Webinar</a>
		</div>
	</template>
	<!-- END: TEMPLATE: Back of Co-Investment Card -->
	<!-- [UNUSED] TEMPLATE: Login Prompt for Tile Link -->
	<template class="js_template" data-name="tile-link-login-prompt">
		<div class="js_tile_login_prompt">
			<div class="form-row space-25-bottom">
				<div class="title h5 strong js_login_prompt_title">Signup to Download <br>for Free.</div>
			</div>
			<form class="form form-dark js_phone_form" onsubmit="event.preventDefault()">
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
						<div style="position: relative; display: flex">
							<select class="js_phone_country_code" style="position: absolute; top: 0; left: 0; background-color: transparent; color: transparent; width: 26%;">
								<?php include __ROOT__ . '/pages/snippets/phone-country-codes.php' ?>
							</select>
							<input type="text" class="no-pointer js_phone_country_code_label" value="+91" tabindex="-1" readonly style="width: 26%">
							<input class="block js_form_input_phonenumber" type="text" name="phone-number" id="">
						</div>
					</label>
				</div>
				<div class="form-row space-min-bottom">
					<label for="">
						<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
						<button class="button fill-red-2" type="submit">Get Details</button>
					</label>
				</div>
			</form>
			<div class="close js_close" tabindex="-1"><img class="icon block" src="/media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
		</div>
	</template>
	<!-- END: Template: Login Prompt for Tile Link -->
</section>
<!-- END: Templates Section -->

<!-- Temporary DOM node hold area -->
<div class="js_temp_holding_area"></div>
<!-- END: Temporary DOM node hold area -->





<?php require_once __ROOT__ . '/pages/partials/footer.php'; ?>
