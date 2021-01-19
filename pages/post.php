<?php

// Page-specific preparatory code goes here.
require_once __DIR__ . '/../inc/above.php';

?>


<!-- Header Section -->
<section class="header-section fill-blue-4 space-75-top space-50-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12">
				<div class="logo space-50-bottom"><img class="block" src="../media/wh-logo-large-light.svg<?php echo $ver ?>"></div>
			</div>
			<div class="columns small-12 large-10 xlarge-8">
				<div class="h2 strong">
					<?= $thePost->get( 'post_title' ) ?>
				</div>
			</div>
		</div>
	</div>	
</section>
<!-- END: Header Section -->

<!-- Post Content Section -->
<section class="post-content-section space-75-top-bottom">
	<div class="container">
		<div class="row">
			<div class="post-content columns small-12 large-10 xlarge-8">

				<?= $thePost->get( 'post_content' ) ?>

			</div>
		</div>
	</div>
</section>
<!-- END: Post Content Section -->






<?php require_once __DIR__ . '/../inc/below.php'; ?>
