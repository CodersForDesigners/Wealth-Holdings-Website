<?php

/*
 |
 | Prevent the page from being cached
 |
 */
function dontCachePage () {
	header( 'Surrogate-Control: no-store' );
	header( 'Cache-Control: max-age=0, s-maxage=0, no-store, no-cache, must-revalidate, proxy-revalidate' );
	header( 'Pragma: no-cache' );
	header( 'Expires: 0' );
}



/*
 *
 * ----- Returns a string built from interpolating text enclosed in double curly braces (like this — {{ field }}) with values found in the provided context
 *
 */
function interpolateString ( $string, $context = [ ] ) {
	$formattedContext = [ ];
	foreach ( $context as $key => $value )
		$formattedContext[ '{{ ' . $key . ' }}' ] = $value;

	return str_replace( array_keys( $formattedContext ), array_values( $formattedContext ), $string );
}



/*
 *
 * Get a formatted string of the time interval between two dates
 *
 */
function getIntervalString ( $endDateString, $startDateString = null ) {

	// Set default values and build the DateTime objects
	if ( empty( $startDateString ) )
		$dateStart = new DateTime();
	else if ( is_string( $startDateString ) )
		$dateStart = date_create( $startDateString );
	$dateEnd = date_create( $endDateString );

	// Subtract the two dates
	$interval = date_diff( $dateStart, $dateEnd );

	// Build the formatted string
	$stringComponents = [ ];
	if ( $interval->d ) {
		if ( $interval->d === 1 )
			$stringComponents[ ] = '%d day';
		else
			$stringComponents[ ] = '%d days';
	}
	if ( $interval->h ) {
		if ( $interval->h === 1 )
			$stringComponents[ ] = '%h hr';
		else
			$stringComponents[ ] = '%h hrs';
	}
	if ( $interval->i ) {
		if ( $interval->i === 1 )
			$stringComponents[ ] = '%i min';
		else
			$stringComponents[ ] = '%i mins';
	}
	$formattedIntervalString = $interval->format( implode( ', ', $stringComponents ) );

	return $formattedIntervalString;

}



/*
 *
 * Dump the values on the page and onto JavaScript memory, finally end the script
 *
 */
function dd ( $data ) {

	echo '<pre>';
		var_dump( [ 'memory usage' => memory_get_usage() ] );
	echo '</pre>';

	echo '<pre>';
		var_dump( $data );
	echo '</pre>';

	echo '<pre>';
		var_dump( [ 'memory usage' => memory_get_usage() ] );
	echo '</pre>';

	echo '<script>';
		echo '__data = ' . json_encode( $data ) . ';';
	echo '</script>';

	exit;

}
