<?php

/*
 *
 * This file contains snippets of HTML, CSS and JavaScript that collectively
 * 	detect if a user can "hover".
 * If the user can, that means he/she is probably using a pointing device,
 * 	like a mouse.
 *
 * USAGE:
 * It really depends on you use case.
 * If you need to know this information early on, then plonk this code higher
 * 	up in the page as part of your critical code; if not, then somewhere below.
 *
 * Also, the CSS, markup and JavaScript need not have to be clumped together.
 *	You can plonk the CSS in your stylesheets, JS in your script files,
 *	and the HTML somewhere in your markup, but before the JS part.
 *
 */

?>

<!--
	The CSS
 -->
<style type="text/css">

	.detect-hover { display: none }
	.detect-hover:after { display: none; content: "dunno"; }
	@media ( max-width: 1040px ) {
		.detect-hover:after { content: "maybe"; }
	}
	@media ( pointer: coarse ) {
		.detect-hover:after { content: "no"; }
	}
	@media not ( pointer: coarse ) {
		.detect-hover:after { content: "yes"; }
	}

</style>





<!--
	The Markup
 -->
<div class="detect-hover js_detect_hover"></div>





<!--
	The JavaScript
 -->
<script type="text/javascript">

	var userCanHover = function () {

		var canUserHover;

		var hoverDetectionSample = document.getElementsByClassName( "js_detect_hover" )[ 0 ];
		var wheelEventFired;

		// Deploy an event listener for the wheel event;
		// 	if it fires, we take that to imply that the user can "hover"
		function wheelEventHandler () {
			wheelEventFired = true;
			window.removeEventListener( "wheel", wheelEventHandler );
		}
		// window.addEventListener( "wheel", wheelEventHandler );

		return function userCanHover () {

			if ( wheelEventFired )
				return true;

			var detectionSampleValue = getComputedStyle( hoverDetectionSample, ":after" ).content.replace( /"/g, "" ).trim();
			canUserHover = [ "dunno", "yes" ].indexOf( detectionSampleValue ) !== -1;

			return canUserHover;

		};

	}();

	// Usage example
	// REPLACE WITH YOUR OWN!
	if ( userCanHover() )
		alert( "Fly! You fool." );
	else
		alert( "You cannot even hover." );

</script>
