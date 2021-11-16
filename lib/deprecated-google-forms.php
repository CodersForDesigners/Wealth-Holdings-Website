<?php

namespace GoogleForms;

ini_set( "display_errors", 1 );
ini_set( "error_reporting", E_ALL );

// Set the timezone
date_default_timezone_set( 'Asia/Kolkata' );
// Do not let this script timeout
set_time_limit( 0 );









function getFormBoundary () {
	return '----ThisIsNotAWallButABoundaryt1n4W34b';
}

/*
 *
 * Returns a `form-data` formatted string for use in a POST request
 *
 * **NOTE**: Leave the double quotes as is in this function.
 * 	The HTTP request won't work otherwise!
 *
 */
function formatToMultipartFormData ( $data ) {

	$formBoundary = getFormBoundary();
	$eol = "\r\n";
	$fieldMeta = "Content-Disposition: form-data; name=";
	$nameFieldQuote = "\"";
	$dataString = '';

	foreach ( $data as $name => $content ) {
		$dataString .= "--" . $formBoundary . $eol
					. $fieldMeta . $nameFieldQuote . $name . $nameFieldQuote
					. $eol . $eol
					. $content
					. $eol;
	}

	$dataString .= "--" . $formBoundary . "--";

	return $dataString;

}

function getAPIResponse ( $endpoint, $method, $data = [ ] ) {

	$httpRequest = curl_init();
	curl_setopt( $httpRequest, CURLOPT_URL, $endpoint );
	curl_setopt( $httpRequest, CURLOPT_RETURNTRANSFER, true );
	// curl_setopt( $httpRequest, CURLOPT_USERAGENT, '' );
	curl_setopt( $httpRequest, CURLOPT_HTTPHEADER, [
		'Cache-Control: no-cache, no-store, must-revalidate',
		'Content-Type: multipart/form-data; boundary=' . getFormBoundary()
	] );
	curl_setopt( $httpRequest, CURLOPT_POSTFIELDS, formatToMultipartFormData( $data ) );
	curl_setopt( $httpRequest, CURLOPT_CUSTOMREQUEST, $method );
	$response = curl_exec( $httpRequest );
	curl_close( $httpRequest );

	return $response;

}

/*
 *
 * Submit a Google Form
 *
 */
function submitPerson ( $data ) {

	$endpoint = 'https://docs.google.com/forms/d/e/'
			. '1FAIpQLSd0PjANXmJ30OkuD0NGHM488C_csA7sJXcBfCoLgg_w0PFqhg/formResponse';
	$requestBody = [
		'entry.1598701661' => $data[ 'when' ],
		'entry.1558794406' => $data[ 'id' ],
		'entry.328661206' => $data[ 'phoneNumber' ],
		'entry.1212174579' => $data[ 'name' ],
		'entry.734162030' => $data[ 'emailAddress' ],
		'entry.2063310127' => $data[ 'sourceMedium' ],
		'entry.378971646' => $data[ 'sourcePoint' ],
		'entry.425002556' => $data[ 'interests' ],
		'entry.1830663146' => $data[ 'callRecording' ]
	];

	$response = getAPIResponse( $endpoint, 'POST', $requestBody );

	return $response;

}

function submitPersonActivity ( $data ) {

	$endpoint = 'https://docs.google.com/forms/d/e/'
			. '1FAIpQLSdKATN9DD7tVtyZC-SKWSXSEdx8yrULbGMwklvETO0skLrHUw/formResponse';
	$requestBody = [
		'entry.268964798' => $data[ 'when' ],
		'entry.1096718998' => $data[ 'activity' ],
		'entry.470084288' => $data[ 'id' ],
		'entry.664435746' => $data[ 'phoneNumber' ],
		'entry.541206315' => $data[ 'verified' ],
		'entry.1777163392' => $data[ 'sourceMedium' ],
		'entry.688759612' => $data[ 'sourcePoint' ],
		'entry.1815278795' => $data[ 'interests' ],
		'entry.413347176' => $data[ 'duration' ],
		'entry.1295071222' => $data[ 'callRecording' ]
	];

	$response = getAPIResponse( $endpoint, 'POST', $requestBody );

	return $response;

}
