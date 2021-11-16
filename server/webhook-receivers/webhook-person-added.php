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
require_once __DIR__ . '/../../lib/datetime.php';
require_once __DIR__ . '/../../lib/deprecated-google-forms.php';



/* ------------------------------------- \
 * Ingest the data onto the Spreadsheet
 \-------------------------------------- */
# Interpret the data
$when = CFD\DateTime::getSpreadsheetDateFromISO8601( $input->when );
$name = $input->name ?? '';
$emailAddresses = empty( $input->emailAddresses ) ? '' : implode( ', ', $input->emailAddresses );
$interests = empty( $input->interests ) ? '' : implode( ', ', $input->interests );
$sourcePoint = $input->source->point ?? $input->agent->name ?? $input->agent->phoneNumber ?? '';
$callRecording = $input->recordingURL ?? '';
# Shape the data
$data = [
	'when' => $when,
	'id' => $input->id,
	'phoneNumber' => $input->phoneNumber,
	'name' => $name,
	'emailAddress' => $emailAddresses,
	'verified' => $input->verified,
	'sourceMedium' => $input->source->medium,
	'sourcePoint' => $sourcePoint,
	'interests' => $interests,
	'callRecording' => $callRecording
];
GoogleForms\submitPerson( $data );
// $spreadsheet->addRow( $data );



/* ------------------------------- \
 * Respond back to the client
 \-------------------------------- */
$output = $data ?: [ ];
echo json_encode( $output );
