<?php

/*
 *
 * Build out the data-structure driving the page navigation markup
 *
 */
$navigationMenuItems = \BFS\CMS::getNavigation( 'Home', '/' );

?>

<?php /*
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
*/ ?>

<!-- Menu Section -->
<section class="menu-section">
	<div class="container">
		<button class="menu-button fill-blue-4 inline js_menu_toggle" tabindex="-1">
			<span class="menu-label h6"></span>
			<span class="menu-icon">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</button>
	</div>
</section>
<!-- END: Menu Section -->


<!-- Navigation Section -->
<section class="navigation-section">
	<div class="container text-right">
		<div class="nav-list inline fill-blue-4 text-left space-75 space-200-bottom">
			<div class="title h2 strong text-blue-3 space-50-bottom">Menu</div>
			<?php foreach ( $navigationMenuItems as $item ) : ?>
				<a href="<?= $item[ 'url' ] ?>" class="link h5 strong block line-height-large"><?= $item[ 'title' ] ?></a>
			<?php endforeach; ?>
		</div>
		<div tab-index="-1" class="nav-close-area js_nav_close_area"></div>
	</div>
</section>
<!-- END: Navigation Section -->