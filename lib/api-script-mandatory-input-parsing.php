<?php
/**
 |
 | Request Parsing
 |
 | If no input data is provided, then an appropriate response is returned
 |
 */

/*
 | 1. Pull JSON body
 */
# Get JSON as a string
$json = file_get_contents( 'php://input' );
# Convert the JSON string to an object
$error = null;
$input = [ ];
try {
	$input = json_decode( $json, true );
}
catch ( \Exception $e ) {
}

/*
 | 2. Pull and merge payload from HTTP POST request body
 */
$input = array_merge( $input ?? [ ], $_POST ?? [ ] );

if ( empty( $input ) ) {
	echo json_encode( [
		'code' => 400,
		'message' => 'Data not provided'
	] );
	exit;
}
