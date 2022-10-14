
/**
 |
 | Get a Callback Form
 |
 |
 */
$( function () {

// Imports
let BFSForm = window.__BFS.exports.BFSForm




var callbackForm = new BFSForm( ".js_get_callback_form" );

	// Phone number
callbackForm.addField( "phoneNumber", [ ".js_phone_country_code", ".js_form_input_phonenumber" ], function ( values ) {
	let [ phoneCountryCode, phoneNumberLocal ] = values
	return BFSForm.validators.phoneNumber( phoneCountryCode, phoneNumberLocal )
} );
// When programmatically focusing on this input field, which of the (two, in this case) input elements to focus on?
callbackForm.fields[ "phoneNumber" ].defaultDOMNodeFocusIndex = 1

callbackForm.submit = function submit ( data ) {
	let person = Cupid.getCurrentPerson( data.phoneNumber )
	person.setSourcePoint( "Callback" )

	Cupid.logPersonIn( person, { trackSlug: "get-a-callback" } )

	let interest = "Request for callback"
	if ( ! person.hasInterest( interest ) ) {
		person.setInterests( interest )
		Cupid.savePerson( person )
		PersonLogger.registerInterest( person )
	}

	return Promise.resolve( person )
}





/**
 | Form submission handler
 |
 */
$( document ).on( "submit", ".js_get_callback_form", function ( event ) {

	/*
	 | Prevent default browser behaviour
	 |
	 */
	event.preventDefault();

	/*
	 | Prevent interaction with the form
	 |
	 */
	callbackForm.disable();

	/*
	 | Provide feedback to the user
	 |
	 */
	callbackForm.giveFeedback( "Sending..." )

	/*
	 | Extract data (and report issues if found)
	 |
	 */
	var data;
	try {
		data = callbackForm.getData();
	} catch ( error ) {
		alert( error.message )
		console.error( error.message )
		callbackForm.enable()
		callbackForm.fields[ error.fieldName ].focus()
		callbackForm.setSubmitButtonLabel()
		return;
	}

	/*
	 | Submit data
	 |
	 */
	callbackForm.submit( data )
		.then( function () {
			showFeebackMessage( data )
			callbackForm.giveFeedback( "Submitted" )
		} )

} )




/**
 |
 | Helper functions
 |
 |
 */
function showFeebackMessage ( data ) {
	let $feedbackMessage = $( ".js_section_get_callback .js_post_callback_submission_message" )
	$feedbackMessage.html( `We will call you back on <b class="p w-600">${ data.phoneNumber }</b> within one working day.` )
}




} )
