<?php
/*
 |
 | Co-Investments section
 |
 |
 */

/*
 |
 | At one point, these investment cards were going to link to their own dedicated pages.
 |  And they would have been "share-able" as well (using the Web Share API).
 |   To that end, we'd store the url, title, description, and image of the investment;
 |    so that when the share button is clicked, we'd construct a share-able link that required all these attributes. This implementation was cut short. Whatever that has been done so far has been commented out.
 |
 | Following are the data-attributes that were added to every investment card (at the containing div):
 | - data-title="<?= $post_title ?>"
 | - data-description="<?= $description ?>"
 | - data-url="<?= $url ?>"
 | - data-image="<?= $featuredImage ?>"
 |
 */
function coInvestmentCard ( $id = '', $title = '', $yield = [ ], $return = [ ], $cost = '', $minInvestment = '' ) {
?>
	<div class="tile investment fill-black js_shareable js_co_investment_card" data-id="<?= $id ?>" >
		<div class="front">
			<div class="row meta-1 space-25-bottom">
				<div class="columns small-3 yield text-red-2">
					<div class="h5 strong text-uppercase">Yield</div>
					<div class="h5 fade-able"><span class="js_yield_amount"><?= $yield[ 'amount' ] ?? '' ?></span>%</div>
					<div class="small line-height-small js_yield_duration"><?= $yield[ 'duration' ] ?? '' ?></div>
				</div>
				<div class="columns small-9 rent text-neutral-2">
					<div class="h5 fade-able">₹ <span class="js_return_amount"><?= $return[ 'amount' ] ?? '' ?></span></div>
					<div class="small line-height-small js_return_duration"><?= $return[ 'duration' ] ?? '' ?></div>
					<div class="h5 strong text-uppercase">Rent</div>
				</div>
			</div>
			<div class="title h5 strong">
				<div class="title-lumpsum text-light fade-able js_title"><?= $title ?></div>
			</div>
			<div class="toggle space-25-top">
				<label class="toggle-button unselectable" tabindex="-1">
					<button class="button empty-pill fill-blue-1 text-dark js_co_own">Co-own</button>
				</label>
				<hr class="dashed neutral-4">
			</div>
			<div class="meta-2 space-25-top space-75-bottom">
				<div class="cost space-min-bottom text-neutral-2">
					<div class="label">Cost per unit</div>
					<div class="h6 fade-able">₹ <span class="js_cost"><?= $cost ?></span></div>
				</div>
				<div class="min-investment space-min-bottom text-red-2">
					<div class="label">Minimum Investment Amount</div>
					<div class="h6 fade-able">₹ <span class="js_minimum_investment"><?= $minInvestment ?></span></div>
				</div>
			</div>
			<div class="action space-25-top">
				<button class="fill-blue-3 js_co_investment_get_details">Get Details</button>
				<?php /* <button class="fill-red-2 button-icon js_modal_trigger hidden" data-mod-id="share" style="background-image: url('../media/icon/icon-share-more.svg<?php echo $ver ?>'); margin-left: calc(var(--space-min)/2);">Share</button> */ ?>
			</div>
		</div>
		<div class="back js_back">
			<?php /* the back of the card is injected programmatically on-the-fly */ ?>
		</div>
	</div>
<?php
}





?>
<section class="co-investment-section fill-neutral-4 space-75-top-bottom <?= $hideCoInvestmentsPagination ?> js_section_co_investment" id="co-investments-section" data-section-title="Co-Investments Section" data-section-slug="co-investments-section">
	<div class="row">
		<div class="container">
			<div class="columns small-12 space-50-bottom">
				<div class="h2 strong">Co-Ownership</div>
			</div>
			<?php if ( $numberOfCoInvestments > 2 ) : ?>
				<div class="filtration columns small-12 js_filtration">
					<div class="filters space-50-bottom">
						<?php foreach ( $coInvestmentCategories as $category ) : ?>
							<label class="filter inline fill-black <?= $category[ 'key' ] ?>">
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
					<div class="h4 line-height-large filtration-feedback js_filtration_feedback hidden">No co-investment options were found for the selected filters.</div>
				</div>
			<?php endif; ?>
			<div class="columns small-12 tile-grid js_co_investment_card_container">
			<?php foreach ( $coInvestments as $e ) : ?>
				<?= coInvestmentCard(
					$e->get( 'ID' ),
					$e->get( 'title' ),
					$e->get( 'yield' ),
					$e->get( 'return' ),
					$e->get( 'cost' ),
					$e->get( 'minimum_investment' )
				) ?>
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
<div class="js_templates hidden">
	<template class="js_template" data-name="co-investment-card">
		<?= coInvestmentCard() ?>
	</template>
</div>
