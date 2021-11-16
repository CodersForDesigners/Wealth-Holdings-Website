<?php

/**
 *
 * Get luminance from a HEX color.
 * 	(this has been "borrowed" verbatim from the twenty-twenty-one theme)
 *
 * @param string $hex The HEX color.
 *
 * @return int Returns a number (0-255).
 *
 */
function getRelativeLuminanceFromHex ( $hex ) {

	// Remove the "#" symbol from the beginning of the color.
	$hex = ltrim( $hex, '#' );

	// Make sure there are 6 digits for the below calculations.
	if ( strlen( $hex ) === 3 )
		$hex = substr( $hex, 0, 1 )
			. substr( $hex, 0, 1 )
			. substr( $hex, 1, 1 )
			. substr( $hex, 1, 1 )
			. substr( $hex, 2, 1 )
			. substr( $hex, 2, 1 );

	// Get red, green, blue.
	$red = hexdec( substr( $hex, 0, 2 ) );
	$green = hexdec( substr( $hex, 2, 2 ) );
	$blue = hexdec( substr( $hex, 4, 2 ) );

	// Calculate the luminance.
	$lum = ( 0.2126 * $red )
		+ ( 0.7152 * $green )
		+ ( 0.0722 * $blue );

	return (int) round( $lum );
}
