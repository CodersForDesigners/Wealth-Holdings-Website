<?php

/*
 *
 * Build out the data-structure driving the page navigation markup
 *
 */
$navigationMenuItems = getNavigationMenu( 'Primary' );

?>

<!-- Header Section -->
<section class="header-section">
	<div class="container">
		<div class="header row">
			<div class="columns small-3">
				<a class="logo" href="/">
					<img src="media/logo.svg<?php echo $ver ?>">
				</a>
			</div>
			<div class="text-right columns small-9">
				<div class="navigation inline">
					<?php foreach ( $navigationMenuItems as $item ) : ?>
						<a class="button js_nav_button" href="<?php echo $item[ 'url' ] ?>"><?php echo $item[ 'label' ] ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section> <!-- END : Header Section -->
