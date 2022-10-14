<?php
/*
 |
 | Authorized Distributors section
 |
 |
 */

?>
<section class="distributors-section fill-neutral-2 space-75-top-bottom <?= $hideCoInvestmentsPagination ?> js_section_co_investment" id="authorized-distributors-section" data-section-title="Authorized Distributors Section" data-section-slug="authorized-distributors-section">
	<div class="container">
		<div class="row">
			<div class="columns small-12 space-50-bottom">
				<div class="h2 strong text-white">Authorized Distributors</div>
			</div>
		</div>
	</div>
	<div class="row distributors carousel js_carousel_container">
		<div class="carousel-list js_carousel_content">
			<?php foreach ( $distributors as $distributor ) : ?>
				<div class="carousel-list-item js_carousel_item">
					<div class="distributor">
						<div class="tile">
							<a class="image" href="<?= $distributor->get( 'acf / url' ) ?>" target="_blank" <?php if ( empty( $distributor->get( 'url' ) ) ) : ?>onclick="event.preventDefault()"<?php endif; ?>>
								<img src="<?= $distributor->get( 'image' ) ?>" alt="">
							</a>
							<div class="info">
								<div class="meta text-center text-blue-4">
									<div class="h6 w-600"><?= $distributor->get( 'post_title' ) ?></div>
									<?php if ( ! empty( $distributor->get( 'contact_person' ) ) ) : ?>
										<div class="p"><?= $distributor->get( 'contact_person' ) ?></div>
									<?php endif; ?>
									<a class="p" href="tel:<?= $distributor->get( 'phone_number' ) ?>"><?= $distributor->get( 'phone_number' ) ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="scroll-controls">
			<div class="row">
				<div class="container">
					<div class="columns small-6">
						<div class="scroll-button left scroll-left unselectable js_pager" data-dir="left" tabindex="-1"><img class="block" src="../media/icon/icon-left-arrow-neutral-4.svg<?php echo $ver ?>"></div>
					</div>
					<div class="columns small-6 text-right">
						<div class="scroll-button right scroll-right unselectable js_pager" data-dir="right" tabindex="-1"><img class="block" src="../media/icon/icon-right-arrow-neutral-4.svg<?php echo $ver ?>"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
