<?php
/*
 *
 * This is a sample page you can copy and use as boilerplate for any new page.
 *
 */

require_once __DIR__ . '/../inc/above.php';

// Page-specific preparatory code goes here.


$investments = BFS\CMS::getPostsOf( 'investment' );

$faqs = BFS\CMS::getPostsOf( 'faq' );
foreach ( $faqs as $faq ) {
	$faq->set( 'featuredImage', get_the_post_thumbnail_url( $faq->get( 'ID' ) ) );
	$faqTextualContent = wp_strip_all_tags( $faq->get( 'post_content' ) );
	if ( ! $faq->get( 'summary' ) ) {
		$faq->set( 'summary', substr( $faqTextualContent, 0, 415 ) );
		if ( strlen( $faqTextualContent ) > 415 )
			$faq->set( 'thereIsMore?', true );
	}
	else
		$faq->set( 'thereIsMore?', true );
}

?>



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


<!-- Header Section -->
<section class="header-section">
	Logo
	Navigation
</section>
<!-- END: Header Section -->


<!-- Landing Section -->
<section class="landing-section fill-red-2 space-50-top">
	<div class="container">
		<div class="row">
			<div class="columns small-12 large-5">
				<div class="row">
					<div class="columns small-12 medium-4 space-25-top-bottom">
						<div class="logo"><img class="block" src="../media/wh-logo-large-dark.svg<?php echo $ver ?>"></div>
					</div>
					<div class="columns small-12 medium-8 large-12 space-50-top-bottom">
						<div class="h5 strong space-min-bottom">Curated rental-yielding assets, with</div>
						<div class="h1 strong">returns you cannot ignore.</div>
						<div class="h5 text-blue-4 space-50-top">Invest in rental-yielding real estate assets that are backed by a business model. Get the right returns, with managed volatility and capital protection.</div>
					</div>
				</div>
			</div>
			<div class="columns small-12 large-6 large-offset-1 space-25-bottom">
				<div class="char-image">
					<img class="block" src="../media/char-1-small.png<?php echo $ver ?>">
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Landing Section -->


<!-- Benefits Section -->
<section class="benefits-section fill-red-2 space-25-top space-75-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12 tile-grid">
				<div class="tile t-1 fill-blue-4">
					<!-- (1.) -->
					<div class="h4 strong space-50-bottom">Why not invest in a 3BHK flat and get a 7% return on this investment?</div>
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
				<div class="tile t-3 outline-red-2">
					<!-- (3.) -->
					<div class="h6 strong">own an apartment with <br>a business model</div>
				</div>
				<div class="tile t-4 fill-blue-4">
					<!-- (4.) -->
					<div class="h6 strong space-25-bottom"><span class="text-red-2">minimum</span> investment <br>amount</div>
					<div class="hn strong text-red-2">₹14 Lakhs</div>
				</div>
				<div class="tile t-5 fill-red-3">
					<!-- (5.) -->
					<div class="h6 strong line-height-xlarge">Bangalore <br>Delhi <br>Pune</div>
				</div>
				<div class="tile t-6 fill-red-3">
					<!-- (6.) -->
					<div class="h6 strong">walking distance from <br class="hide-small">tech parks, SEZs & schools</div>
				</div>
				<div class="tile t-7 fill-dark">
					<!-- (7.) -->
					<div class="h4 strong space-min-bottom">EMI</div>
					<div class="label strong">Invest only the down payment and <span class="text-red-2">let us fund your EMIs.</span></div>
				</div>
				<div class="tile t-8" style="background-image: url('../media/placeholder.png<?php echo $ver ?>'); background-size: cover; background-position: center center;">
					<!-- (8.) -->
				</div>
				<div class="tile t-9 fill-dark">
					<!-- (9.) -->
					<div class="h4 strong space-min-bottom">Lumpsum</div>
					<div class="label strong">Make a <span class="text-red-2">one-time investment</span> <br>and enjoy a monthly fixed <br>rental income.</div>
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
				<div class="tile t-13 fill-red-3">
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
					<div class="hn strong text-red-2">we fund your EMI</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Benefits Section -->


<!-- Investment Section -->
<section class="investment-section fill-blue-4 space-75-top-bottom js_section_investment">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<div class="h2 strong">Pick an investment</div>
			</div>
			<div class="columns small-12">
				Filters
			</div>
			<div class="columns small-12 tile-grid">
			<?php foreach ( $investments as $investment ) : ?>
				<div class="tile investment js_investment_card">
					<div class="front">
						<div class="row meta-1 space-25-bottom">
							<div class="columns small-4 yield text-red-2">
								<div class="h5 strong text-uppercase">Yield</div>
								<div class="h5"><?= $investment->get( 'yield' )[ 'amount' ] ?>%</div>
								<div class="small line-height-small"><?= $investment->get( 'yield' )[ 'duration' ] ?></div>
							</div>
							<div class="columns small-8 rent text-neutral-2">
								<div class="h5 strong text-uppercase">Rent</div>
								<div class="h5">₹ <?= $investment->get( 'rent' )[ 'amount' ] ?></div>
								<div class="small line-height-small"><?= $investment->get( 'yield' )[ 'duration' ] ?></div>
							</div>
						</div>
						<div class="title h5 strong">
							<div class="title-lumpsum"><?= $investment->get( 'title' )[ 'lumpsum' ] ?></div>
							<div class="title-emi"><?= $investment->get( 'title' )[ 'emi' ] ?></div>
						</div>
						<div class="toggle space-25-top">
							<label class="toggle-button unselectable" tabindex="-1">
								<input class="hidden js_toggle_payment_mode" type="checkbox">
								<div class="button pill"></div>
								<div class="button empty-pill">Lumpsum</div>
								<div class="button empty-pill">EMI</div>
							</label>
						</div>
						<div class="meta-2 space-25-top text-neutral-2">
							<div class="size h5 space-min-bottom"><?= $investment->get( 'size' ) ?></div>
							<div class="cost space-min-bottom">
								<div class="label">Cost of Asset</div>
								<div class="h6">₹ <?= $investment->get( 'cost' ) ?></div>
							</div>
							<div class="min-investment space-min-bottom">
								<div class="label">Minimum Investment Amount</div>
								<div class="h6">₹ <?= $investment->get( 'minimum_investment' ) ?></div>
							</div>
						</div>
						<div class="action space-25-top">
							<button class="fill-red-2 js_investment_get_details">Get Details</button>
							<button class="fill-red-2" style="margin-left: var(--space-min);">Share</button>
						</div>
					</div>
					<div class="back js_back"></div>
				</div>
			<?php endforeach; ?>
				<div class="tile banner">
					<div class="p fill-neutral-2 space-25">
						Lorem ipsum dolor sit amet consectetur adipisicing elit. A fugit perspiciatis, voluptatibus dolorum, facere sapiente est, impedit exercitationem ut perferendis laboriosam, repudiandae consequatur ad rem odio adipisci hic ex. Perspiciatis?
					</div>
				</div>
			</div>
			<div class="columns small-12">
				View All
			</div>
		</div>
	</div>
</section>
<!-- END: Investment Section -->


<!-- How Section -->
<section class="how-section fill-dark space-75-top-bottom">
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
		Character
	</div>
</section>
<!-- END: How Section -->


<!-- Webinar Section -->
<section class="webinar-section fill-blue-4">
	<div class="webinar-image" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
	<div class="container">
		<div class="row">
			<div class="webinar-form columns small-12 medium-6 medium-offset-6 space-75-top-bottom xlarge-5">
				<div class="h3 strong text-red-2 space-25-bottom">Register for the next webinar</div>
				<div class="h5 space-min-bottom">Saturday 15th Dec 4:30 PM</div>
				<div class="p opacity-75 space-50-bottom">Join our investment manager for a 30 minute presentation and 30 minutes of Q&A.</div>
				<div class="form form-dark">
					<div class="form-row space-min-bottom">
						<label for="">
							<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Name</span><br>
							<input class="block fill-dark" type="text">
						</label>
					</div>
					<div class="form-row space-min-bottom">
						<label for="">
							<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Email</span><br>
							<input class="block fill-dark" type="text">
						</label>
					</div>
					<div class="form-row space-min-bottom">
						<label for="">
							<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
							<input class="block fill-dark" type="text">
						</label>
					</div>
					<div class="form-row space-min-bottom">
						<label for="">
							<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
							<button class="button fill-red-2">Get Details</button>
						</label>
					</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Webinar Section -->


<!-- FAQs Section -->
<section class="faqs-section fill-neutral-1 space-75-top js_section_faqs">
	<div class="container">
		<div class="row text-blue-4">
			<div class="columns small-12 large-3">
				<div class="h2 strong space-50-bottom">FAQs</div>
			</div>
			<div class="columns small-12 large-9 xlarge-8">
				<div class="faqs space-75-bottom">
				<?php foreach ( $faqs as $faq ) : ?>
					<div class="faq space-25-top-bottom js_faq">
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
									<div class="action clearfix">
										<?php if ( $faq->get( 'thereIsMore?' ) ) : ?>
											<a class="h6" href="<?= $faq->get( 'guid' ) ?>">Read More</a>
										<?php endif; ?>
										<a class="h6" href="">Share</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
				<div class="brochures">
					<div class="row">
						<div class="brochure columns small-12 medium-6 fill-blue-4">
							<div class="layer-1" style="background-image: url('/*-- insert image url here -- */<?php echo $ver ?>');"></div>
							<div class="layer-2">
								<div class="h4 strong space-25-bottom">Comparison to <br>Gold</div>
								<button class="button fill-red-2">Download Now</button>
							</div>
							<div class="layer-3">
								<div class="form block form-dark">
									<div class="form-row space-25-bottom">
										<div class="title h5 strong">Signup to Download <br>for Free.</div>
									</div>
									<div class="form-row space-min-bottom">
										<label for="">
											<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
											<input class="block" type="text">
										</label>
									</div>
									<div class="form-row space-min-bottom">
										<label for="">
											<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
											<button class="button fill-red-2">Get Details</button>
										</label>
									</div>
								</div>
								<div class="close" tabindex="-1"><img class="icon block" src="../media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
							</div>
						</div>
						<div class="brochure columns small-12 medium-6 fill-red-2">
							<div class="layer-1" style="background-image: url('/*-- insert image url here -- */<?php echo $ver ?>');"></div>
							<div class="layer-2">
								<div class="h4 strong space-25-bottom">Comparison to <br>Fixed Deposits</div>
								<button class="button fill-blue-4">Download Now</button>
							</div>
							<div class="layer-3">
								<div class="form block form-dark">
									<div class="form-row space-25-bottom">
										<div class="title h5 strong">Signup to Download <br>for Free.</div>
									</div>
									<div class="form-row space-min-bottom">
										<label for="">
											<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span><br>
											<input class="block" type="text">
										</label>
									</div>
									<div class="form-row space-min-bottom">
										<label for="">
											<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span><br>
											<button class="button fill-red-2">Get Details</button>
										</label>
									</div>
								</div>
								<div class="close" tabindex="-1"><img class="icon block" src="../media/icon/icon-close-red.svg<?php echo $ver ?>"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: FAQs Section -->


<!-- Testimonials Section -->
<section class="testimonials-section fill-light space-75-top-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<div class="h2 strong text-neutral-2">Testimonials</div>
			</div>
		</div>
	</div>
	<div class="row testimonials carousel js_carousel_container">
		<div class="carousel-list js_carousel_content">
			<div class="carousel-list-item js_carousel_item">
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="carousel-list-item js_carousel_item">
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="carousel-list-item js_carousel_item">
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="carousel-list-item js_carousel_item">
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="carousel-list-item js_carousel_item">
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
				<div class="testimonial">
					<div class="tile">
						<div class="testimonial-content-video" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');" tabindex="-1"></div>
						<div class="testimonial-content-text label">
							Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, aut atque sit temporibus maiores reiciendis nobis sunt, corrupti, inventore dolorum exercitationem saepe suscipit eaque dolorem optio necessitatibus molestiae excepturi libero?
						</div>
						<div class="testimonial-info">
							<div class="photo" style="background-image: url('../media/placeholder.png<?php echo $ver ?>');"></div>
							<div class="meta">
								<div class="name h6">Person Full Name</div>
								<div class="occupation label">occupation or company designation</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="scroll-controls">
			<div class="row">
				<div class="container">
					<div class="columns small-6">
						<div class="scroll-button button fill-red-2 scroll-left unselectable js_pager" data-dir="left" tabindex="-1"><img src="media/glyph/32-leftarrow.svg?v=20190917"></div>
					</div>
					<div class="columns small-6 text-right">
						<div class="scroll-button button fill-red-2 scroll-right unselectable js_pager" data-dir="right" tabindex="-1"><img src="media/glyph/32-rightarrow.svg?v=20190917"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: Testimonials Section -->


<!-- Footer Section -->
<section class="footer-section fill-blue-4 space-75-top-bottom">
	<div class="container">
		Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum dolores numquam, sit quasi placeat minima unde consequuntur soluta autem maxime? Eveniet pariatur, dolor hic impedit id at repudiandae officiis. Facere.
	</div>
</section>
<!-- END: Footer Section -->


<!-- TEMPLATE: Back of Investment Card -->
<template class="js_template js_investment_card_back">
	<div>
		<button class="unflip button fill-light js_investment_card_unflip">Back</button>
		<div class="h5 strong text-red-2">Get access to a detailed offer document now.</div>
		<div class="form form-dark">
			<div class="form-row space-min-bottom">
				<label for="">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span>
					<input class="block" type="text">
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<label for="">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span>
					<button class="button block fill-red-2">Get Details</button>
				</label>
			</div>
		</div>
		<hr style="border-color: var(--red-2);">
		<div class="h5 text-neutral-2">Saturday 15th Dec 4:30 PM</div>
		<div class="label">Join our investment manager for a 30min presentation and 30min of Q&A.</div>
		<div class="form form-dark">
			<div class="form-row space-min-bottom">
				<label for="">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Name</span>
					<input class="block" type="text">
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<label for="">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Email</span>
					<input class="block" type="text">
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<label for="">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Phone</span>
					<input class="block" type="text">
				</label>
			</div>
			<div class="form-row space-min-bottom">
				<label for="">
					<span class="small text-uppercase line-height-xlarge opacity-50 cursor-pointer">Submit</span>
					<button class="button block fill-red-2">Get Details</button>
				</label>
			</div>
		</div>
	</div>
</template>
<!-- END: TEMPLATE: Back of Investment Card -->







<?php require_once __DIR__ . '/../inc/below.php'; ?>
