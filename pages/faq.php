<?php

// Page-specific preparatory code goes here.
require_once __DIR__ . '/../inc/above.php';

// If no free-form post content was provided, use the summary
$thePost->set( 'content', wp_strip_all_tags( $thePost->get( 'post_content' ) ) ?: $thePost->get( 'summary' ) );

?>



<section class="document-section space-50-bottom">
	<div class="container">
		<div class="row">
			<div class="columns small-12 medium-10 medium-offset-1 xlarge-8 xlarge-offset-2 space-min">
				<h3><?= $thePost->get( 'post_title' ) ?></h3>

				<?= $thePost->get( 'content' ) ?>

			</div>
		</div>
	</div>
</section>





<?php require_once __DIR__ . '/../inc/below.php'; ?>
