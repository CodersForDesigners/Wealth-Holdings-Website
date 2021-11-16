<?php

/* ------------------------------- \
 * Script Bootstrapping
 \-------------------------------- */
# * - Error Reporting
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );
# * - Request Permissions
header( 'Access-Control-Allow-Origin: *' );
# * - Date and Timezone
date_default_timezone_set( 'Asia/Kolkata' );
# * - Prevent Script Cancellation by Client
ignore_user_abort( true );
# * - Script Timeout
set_time_limit( 0 );



/* ------------------------------- \
 * Response Preparation
 \-------------------------------- */
# Set Headers
header_remove( 'X-Powered-By' );
header( 'Content-Type: application/json' );



/* ------------------------------- \
 * Request Parsing
 \-------------------------------- */
# Get JSON as a string
$json = file_get_contents( 'php://input' );
# Convert the JSON string to an object
$error = null;
try {
	$input = json_decode( $json );
	if ( empty( $input ) )
		throw new \Exception( "No data provided." );
	$event = $input->event;
	$input = $input->data;
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



/* ------------------------------------- \
 * Exceptions
 \-------------------------------------- */
if ( $input->phoneNumber === '+917760118668' )
	exit;


/* ------------------------------------- \
 * Pull in the dependencies
 \-------------------------------------- */
require_once __DIR__ . '/../../inc/datetime.php';
require_once __DIR__ . '/../../lib/deprecated-google-forms.php';



/* ------------------------------------- \
 * Ingest the data onto the Spreadsheet
 \-------------------------------------- */
# Interpret the data
$when = CFD\DateTime::getSpreadsheetDateFromISO8601( $input->when );
$activity = '';
$sourceMedium = '';
$sourcePoint = '';
$verificationMethod = $input->verificationMethod;
if ( $event === 'person/is/verified' ) {
	$activity = 'Verification';
	if ( ! empty( $input->source ) ) {
		if ( ! empty( $input->source->medium ) )
			$sourceMedium = $input->source->medium;
		if ( ! empty( $input->source->point ) )
			$sourcePoint = $input->source->point;
	}
}
else if ( $event === 'person/phoned/' ) {
	$activity = 'Phone';
	$sourceMedium = 'Phone';
	if (
		! empty( $input->agent )
		and ! empty( $input->agent->phoneNumber )
	)
		$sourcePoint = $input->agent->phoneNumber;
}
else if ( $event === 'person/on/website' ) {
	$activity = 'Website';
	$sourceMedium = 'Website';
	if ( ! empty( $input->where ) )
		$sourcePoint = $input->where;
}
$interests = empty( $input->interests ) ? '' : implode( ', ', $input->interests );
# Shape the data
$data = [
	'when' => $when,
	'activity' => $activity,
	'id' => $input->id,
	'phoneNumber' => $input->phoneNumber,
	'verified' => $input->verified,
	'verificationMethod' => $input->verificationMethod,
	'sourceMedium' => $sourceMedium,
	'sourcePoint' => $sourcePoint,
	'interests' => $interests,
	'duration' => $input->duration ?? '',
	'callRecording' => $input->recordingURL ?? ''
];
GoogleForms\submitPersonActivity( $data );



/* ------------------------------- \
 * Respond back to the client
 \-------------------------------- */
$output = $data ?: [ ];
echo json_encode( $output );
