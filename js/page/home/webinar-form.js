
/**
 |
 | Webinar Form
 |
 |
 */
$( function () {

// Imports
let BFSForm = window.__BFS.exports.BFSForm




var webinarForm = new BFSForm( ".js_webinar_form" );

	// Name
webinarForm.addField( "name", ".js_form_input_name", function ( values ) {
	let name = values[ 0 ]
	return BFSForm.validators.name( name )
} );

	// Email address
webinarForm.addField( "emailAddress", ".js_form_input_email", function ( values ) {
	let emailAddress = values[ 0 ]
	return BFSForm.validators.emailAddress( emailAddress )
} );

	// Phone number
webinarForm.addField( "phoneNumber", [ ".js_phone_country_code", ".js_form_input_phonenumber" ], function ( values ) {
	let [ phoneCountryCode, phoneNumberLocal ] = values
	return BFSForm.validators.phoneNumber( phoneCountryCode, phoneNumberLocal )
} );
// When programmatically focusing on this input field, which of the (two, in this case) input elements to focus on?
webinarForm.fields[ "phoneNumber" ].defaultDOMNodeFocusIndex = 1

webinarForm.submit = function submit ( data ) {
	let person = Cupid.getCurrentPerson( data.phoneNumber )
	person.setName( data.name )
	person.setEmailAddress( data.emailAddress )
	person.setSourcePoint( "Webinar" )

	Cupid.logPersonIn( person, { trackSlug: "register-for-webinar" } )

	let interest = `Webinar ${window.__BFS.data.webinarDate}`
	if ( ! person.hasInterest( interest ) ) {
		person.setInterests( interest )
		Cupid.savePerson( person )
		PersonLogger.registerInterest( person )
	}

	return Promise.resolve( person )
}





/*
 * ----- Form submission handler
 */
$( document ).on( "submit", ".js_webinar_form", function ( event ) {

	/*
	 | ----- Prevent default browser behaviour
	 */
	event.preventDefault();

	/*
	 | ----- Prevent interaction with the form
	 */
	webinarForm.disable();

	/*
	 | Provide feedback to the user
	 */
	webinarForm.giveFeedback( "Sending..." )

	/*
	 | ----- Extract data (and report issues if found)
	 */
	var data;
	try {
		data = webinarForm.getData();
	} catch ( error ) {
		alert( error.message )
		console.error( error.message )
		webinarForm.enable()
		webinarForm.fields[ error.fieldName ].focus()
		webinarForm.setSubmitButtonLabel()
		return;
	}

	/*
	 | ----- Submit data
	 */
	webinarForm.submit( data )
		.then( function () {
			webinarForm.getFormNode().fadeOut()
			showFeebackMessage( data )
		} )

} )




/**
 |
 | Helper functions
 |
 */
function hideForm () {
	webinarForm.getFormNode().addClass( "hidden" )
}
function showFeebackMessage ( data ) {
	let $feedbackMessage = $( ".js_section_webinar .js_post_registration_message" )
	let feedbackMessageTemplate = $feedbackMessage.html()
	let feedbackMessage = window.__BFS.renderTemplate( feedbackMessageTemplate, { emailAddress: data.emailAddress } )
	$feedbackMessage.html( feedbackMessage.trim() )
	$feedbackMessage.fadeIn()
}




} )
