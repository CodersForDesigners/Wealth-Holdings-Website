<?php
/**
 |
 | Request Parsing
 |
 | If no input data is provided, then an appropriate response is returned
 |
 */

# Get JSON as a string
$json = file_get_contents( 'php://input' );
# Convert the JSON string to an object
$error = null;
$input = null;
try {
	$input = json_decode( $json, true );
	if ( empty( $input ) )
		throw new \Exception( "No data provided." );
}
catch ( \Exception $e ) {
	$error = $e->getMessage();
}
if ( ! empty( $error ) ) {
	echo json_encode( [
		'code' => 400,
		'message' => 'Data not provided'
	] );
	exit;
}
