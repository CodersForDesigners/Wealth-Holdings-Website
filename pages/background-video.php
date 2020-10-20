<?php

// Page-specific preparatory code goes here.

?>

<?php require_once __DIR__ . '/../inc/above.php'; ?>






<!--
	This snippet is for when you want add a auto-playing video in the background,
	that is hosted on YouTube.
	The parameters you have to change are,
		1. the video ID, i.e. what comes after the "/embed/" and before the "?".
		2. the time ( in seconds ) at which point the video is to loop back to
			the beginning. This is mentioned after "end=".
 -->
<section>

	<div>
		<div class="video-embed video-embed-bg js_video_embed js_video_get_player" data-src="lncVHzsc_QA" data-loop="true" data-autoplay="true">
			<div class="video-embed-placeholder" style="background-image: url( 'https://via.placeholder.com/1500' );"></div>
			<!-- <div class="video-loading-indicator"></div> -->
		</div>
	</div>

</section> <!-- END : Video BG Section -->





<?php require_once __DIR__ . '/../inc/below.php'; ?>
