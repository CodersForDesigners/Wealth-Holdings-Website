<?php

$videoId = get_field( 'youtube_video_id' );

?>
<div class="video-embed js_video_embed" data-src="<?= $videoId ?>">
	<div class="video-loading-indicator"></div>
</div>
