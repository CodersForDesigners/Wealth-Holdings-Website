<?php

// Page-specific preparatory code goes here.
require_once __DIR__ . '/../inc/above.php';

?>

<!-- Header Section -->
<section class="header-section fill-blue-4 space-75-top space-50-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<a class="inline" href="/">
					<div class="logo space-50-bottom"><img class="block" src="../media/wh-logo-large-light.svg<?php echo $ver ?>"></div>
				</a>
			</div>
			<div class="columns small-12 large-10 xlarge-8">
				<div class="h2 strong">
					Search Results
				</div>
			</div>
		</div>
	</div>	
</section>
<!-- END: Header Section -->

<!-- Search Listing Section -->
<section class="search-listing-section space-50-top space-75-bottom">
	<div class="container">
		<div class="row">
			<div class="search-listing columns small-12 large-8 xlarge-7">
				<a class="item block space-25-top-bottom" href="#">
					<div class="title h5 strong space-min-bottom">Why should I buy and is my money secure?</div>
					<div class="description h6 opacity-50 space-min-bottom">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, perferendis aperiam quibusdam ipsam possimus optio alias? Excepturi, corporis eius inventore provident aut expedita quidem illo placeat aliquam ex, suscipit ratione.</div>
					<span class="label inline text-lowercase">Read More <span class="material-icons">subject</span></span>
				</a>
				<a class="item block space-25-top-bottom" href="#">
					<div class="title h5 strong space-min-bottom">When will my returns start?</div>
					<div class="description h6 opacity-50 space-min-bottom">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, perferendis aperiam quibusdam ipsam possimus optio alias? Excepturi, corporis eius inventore provident aut expedita quidem illo placeat aliquam ex, suscipit ratione.</div>
					<span class="label inline text-lowercase">Read More <span class="material-icons">subject</span></span>
				</a>
				<a class="item block space-25-top-bottom" href="#">
					<div class="title h5 strong space-min-bottom">How frequently will I get my returns?</div>
					<div class="description h6 opacity-50 space-min-bottom">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, perferendis aperiam quibusdam ipsam possimus optio alias? Excepturi, corporis eius inventore provident aut expedita quidem illo placeat aliquam ex, suscipit ratione.</div>
					<span class="label inline text-lowercase">Read More <span class="material-icons">subject</span></span>
				</a>
			</div>
		</div>
	</div>
</section>
<!-- END: Search Listing Section -->



<?php require_once __DIR__ . '/../inc/below.php'; ?>
