<?php
/*
 |
 | Investments section
 |
 |
 */
?>
<section class="investment-section fill-blue-4 space-75-top-bottom <?= $hideInvestmentsPagination ?> js_section_investment" id="investments-section" data-section-title="Investments Section" data-section-slug="investments-section">
	<div class="row">
		<div class="container">
			<div class="columns small-12 space-50-bottom">
				<div class="h2 strong">Full Ownership</div>
			</div>
			<div class="filtration columns small-12 js_filtration">
				<div class="filters space-50-bottom">
					<?php foreach ( $investmentCategories as $category ) : ?>
						<label class="filter inline fill-dark <?= $category[ 'key' ] ?>">
							<span class="icon fill-blue-3" style="background-image: url('../media/icon/filter/<?= $category[ 'key' ] ?>.svg<?php echo $ver ?>');"></span>
							<span class="key button"><?= $category[ 'label' ] ?></span>
							<select class="value text-blue-3 js_filter" data-name="<?= $category[ 'key' ] ?>">
									<option value="">All</option>
								<?php foreach ( $category[ 'values' ] as $value ) : ?>
									<option><?= $value ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					<?php endforeach; ?>
				</div>
				<div class="h4 line-height-large filtration-feedback js_filtration_feedback hidden">No investment options were found for the selected filters.</div>
			</div>
			<div class="columns small-12 tile-grid js_investment_card_container">
			<?php foreach ( $investments as $investment ) : ?>
				<div class="tile investment js_shareable <?= $investment->get( 'default_payment_mode' ) ? 'show-emi' : '' ?> js_investment_card" data-id="<?= $investment->get( 'ID' ) ?>" data-title="<?= $investment->get( 'post_title' ) ?>" data-description="<?= $investment->get( 'defaultDescription' ) ?>" data-image="<?= $investment->get( 'featuredImage' ) ?: '' ?>" data-url="<?= $investment->get( 'url' ) ?>">
					<div class="front">
						<div class="row meta-1 space-25-bottom">
							<div class="columns small-3 yield text-red-2">
								<div class="h5 strong text-uppercase">Yield</div>
								<div class="h5 fade-able"><span class="js_yield_amount"><?= $investment->get( 'yield' )[ 'amount' ] ?></span>%</div>
								<div class="small line-height-small js_yield_duration"><?= $investment->get( 'yield' )[ 'duration' ] ?></div>
							</div>
							<div class="columns small-9 rent text-neutral-2">
								<div class="h5 strong text-uppercase">Rent</div>
								<div class="h5 fade-able">₹ <span class="js_rent_amount"><?= $investment->get( 'rent' )[ 'amount' ] ?></span></div>
								<div class="small line-height-small js_rent_duration"><?= $investment->get( 'rent' )[ 'duration' ] ?></div>
							</div>
						</div>
						<div class="title h5 strong">
							<div class="title-lumpsum text-light fade-able js_title_lumpsum"><?= $investment->get( 'title' )[ 'lumpsum' ] ?></div>
							<div class="title-emi text-light fade-able js_title_emi"><?= $investment->get( 'title' )[ 'emi' ] ?></div>
						</div>
						<div class="toggle space-25-top">
							<label class="toggle-button unselectable" tabindex="-1">
								<input class="hidden js_toggle_payment_mode" type="checkbox" <?php if ( $investment->get( 'default_payment_mode' ) ) : ?>checked<?php endif; ?>>
								<div class="button pill"></div>
								<div class="button empty-pill">Lumpsum</div>
								<div class="button empty-pill">EMI</div>
							</label>
							<hr class="dashed blue-4">
						</div>
						<div class="meta-2 space-25-top text-neutral-2">
							<div class="size h5 space-min-bottom fade-able js_size"><?= $investment->get( 'size' ) ?></div>
							<div class="cost space-min-bottom">
								<div class="label">Cost of Asset</div>
								<div class="h6 fade-able">₹ <span class="js_cost"><?= $investment->get( 'cost' ) ?></span></div>
							</div>
							<div class="min-investment space-min-bottom">
								<div class="label">Minimum Investment Amount</div>
								<div class="h6 fade-able">₹ <span class="js_minimum_investment"><?= $investment->get( 'minimum_investment' ) ?></span></div>
							</div>
						</div>
						<div class="action space-25-top">
							<button class="fill-red-2 js_investment_get_details">Get Details</button>
							<button class="fill-red-2 button-icon js_modal_trigger hidden" data-mod-id="share" style="background-image: url('../media/icon/icon-share-more.svg<?php echo $ver ?>'); margin-left: calc(var(--space-min)/2);">Share</button>
						</div>
					</div>
					<div class="back js_back"></div>
				</div>
			<?php endforeach; ?>
				<div class="tile banner">
					<div class="row">
						<div class="sub-tile columns small-12 medium-6 large-7">
							<a class="card block fill-blue-3 space-25" href="/didnt-find-a-suitable-investment" target="_blank">
								<div class="h4 strong" style="padding-bottom: calc(var(--space-min)/2)">Didn’t find a suitable investment?</div>
								<div class="h6 space-25-bottom">Tell us what you are looking for.</div>
								<span class="link inline">
									<span class="h5 strong-underline blue-4 strong text-blue-4 line-height-large">Feedback</span>
									<img class="icon inline-middle" src="../media/icon/icon-right-arrow-strong-blue-4.svg<?php echo $ver ?>">
								</span>
							</a>
						</div>
						<div class="sub-tile columns small-12 medium-6 large-5">
							<a class="card block fill-blue-3 space-25" href="/get-a-sneak-peek" target="_blank">
								<div class="h5 strong space-25-bottom">Get a sneak peak at upcoming investment opportunities.</div>
								<span class="link inline">
									<span class="h5 strong-underline blue-4 strong text-blue-4 line-height-large">Register</span>
									<img class="icon inline-middle" src="../media/icon/icon-right-arrow-strong-blue-4.svg<?php echo $ver ?>">
								</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="view-all-toggle row space-75-top-bottom">
		<div class="container">
			<div class="columns small-12 text-center">
				<div class="inline view-all-toggle-button h4 strong space-25 js_view_all" tabindex="-1">
					<span>View All</span>
				</div>
			</div>
		</div>
	</div>
</section>
