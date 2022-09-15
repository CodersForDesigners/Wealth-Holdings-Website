<?php
# * - Error Reporting
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

// Script Bootstrapping
require_once __DIR__ . '/../../lib/api-script-bootstrap.php';
// Response Preparation
require_once __DIR__ . '/../../lib/api-script-response-preparation.php';
// Parse input from the request, reject if empty
require_once __DIR__ . '/../../lib/api-script-mandatory-input-parsing.php';


require_once __DIR__ . '/../../lib/http.php';

use BFS\HTTP;

/*
 |
 | Further input parsing
 |
 | If the essential data is not provided, respond accordingly
 |
 */
if ( empty( $input[ 'phoneNumber' ] ) )
     return HTTP::respond( 'Phone number not provided.', 400 );





/* ------------------------------------- \
 * Pull in the module dependencies
 \-------------------------------------- */
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../../lib/datetime.php';
require_once __DIR__ . '/../../lib/google-forms.php';

use Symfony\Component\Yaml\Yaml;



/* ------------------------------------- \
 * Ingest the data onto the Spreadsheet
 \-------------------------------------- */
# Interpret the data
$data = [ ];
$data[ 'phoneNumber' ] = $input[ 'phoneNumber' ];
$data[ 'name' ] = $input[ 'name' ] ?? null;
$data[ 'emailAddress' ] = $input[ 'emailAddress' ] ?? null;
$data[ 'sourceMedium' ] = $input[ 'sourceMedium' ];
$data[ 'sourcePoint' ] = $input[ 'sourcePoint' ] ?? '';
$data[ 'when' ] = CFD\DateTime::getCurrentTimestamp__SpreadsheetCompatible();

GoogleForms\submitPerson( $data );



/* ------------------------------- \
 * Respond back to the client
 \-------------------------------- */
HTTP::respond( $data, 200 );
