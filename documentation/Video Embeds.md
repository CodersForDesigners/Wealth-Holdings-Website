
# About
Here, we go over the embed markup and code required to pull down YouTube videos for use on the site.



## Embedding the video as is
To embed a YouTube video, add this markup,

```html
<div class="video-embed js_video_embed" data-src="68MBwELQKmk">
	<div class="video-loading-indicator"></div>
</div>
```

## Programmatic access to the video's player
To have control over the playback of the video, add the class `js_video_get_player`.

```html
<div class="video-embed js_video_embed js_video_get_player" data-src="68MBwELQKmk">
	<div class="video-loading-indicator"></div>
</div>
```
Now, in event handlers ( or even elsewhere ), you can access the video's player through the `player` attribute of the `js_video_get_player` element. For example,

```js
$( window ).on( "scroll", function ( event ) {
	if ( /* some condition is met */ ) {
		var player = $( "js_video_get_player" ).data( "player" );
		player.seekTo( 0 );
		player.playVideo();
		player.pauseVideo();
	}
} );
```
You can the rest of the player functions [here](https://developers.google.com/youtube/iframe_api_reference#Playback_controls).


## Playing the video automatically, on a loop, and hide its playback control UI
Add the class `video-embed-bg`, the data attributes `data-autoplay` and `data-loop`.
Also, add the class `js_video_get_player` to allow for the autoplay and loop features to work.

```html
<div class="video-embed video-embed-bg js_video_embed js_video_get_player" data-src="68MBwELQKmk" data-loop="true" data-autoplay="true">
	<div class="video-loading-indicator"></div>
</div>
```

## Show a placeholder image while the video loads to play automatically
```html
<div class="video-embed video-embed-bg js_video_embed js_video_get_player" data-src="68MBwELQKmk" data-autoplay="true">
	<div class="video-embed-placeholder" style="background-image: url( 'https://via.placeholder.com/1500' );"></div>
</div>
```
