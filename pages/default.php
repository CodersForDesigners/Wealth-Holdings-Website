<?php

// Page-specific preparatory code goes here.
require_once __DIR__ . '/../inc/above.php';
?>



<section class="document-section space-50-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12 medium-10 medium-offset-1 xlarge-8 xlarge-offset-2 space-min">
				<h3><?= $thePost->post_title ?></h3>

				<?= $thePost->post_content ?>

			</div>
		</div>
	</div>
</section>





<?php require_once __DIR__ . '/../inc/below.php'; ?>
