
/*
 *
 * Update the country code label
 * 	whenever a new country code is selected
 *
 */
$( document ).on( "change", ".js_phone_country_code", function ( event ) {
	var $countryCode = $( event.target );
	var countryCode = $countryCode.val().replace( /[^\+\d]/g, "" );
	$countryCode
		.closest( "form" )
		.find( ".js_phone_country_code_label" )
		.val( countryCode );
} );
