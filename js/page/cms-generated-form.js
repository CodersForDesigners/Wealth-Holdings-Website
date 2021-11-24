
/**
 |
 | Form(s) generated from the form block on the CMS
 |
 |
 */
$( function () {

if ( ! $( ".js_forms_container" ).length )
	return;



// Imports
let BFSForm = window.__BFS.exports.BFSForm




let cmsForm = new BFSForm( ".js_cms_form" );

	// Name
cmsForm.addField( "name", ".js_form_input_name", function ( values ) {
	let name = values[ 0 ]
	return BFSForm.validators.name( name )
} );

	// Email address
cmsForm.addField( "emailAddress", ".js_form_input_email", function ( values ) {
	let emailAddress = values[ 0 ]
	return BFSForm.validators.emailAddress( emailAddress )
} );

	// Phone number
cmsForm.addField( "phoneNumber", [ ".js_phone_country_code", ".js_form_input_phonenumber" ], function ( values ) {
	let [ phoneCountryCode, phoneNumberLocal ] = values
	return BFSForm.validators.phoneNumber( phoneCountryCode, phoneNumberLocal )
} );
// When programmatically focusing on this input field, which of the (two, in this case) input elements to focus on?
cmsForm.fields[ "phoneNumber" ].defaultDOMNodeFocusIndex = 1

	// Input fields added through the Form block
for ( let domInput of $( ".js_cms_form_input" ) ) {
	let $input = $( domInput )
	let fieldLabel = $input.data( "label" )
	let fieldId = $input.attr( "id" )
	cmsForm.addField( fieldLabel, `#${fieldId}`, function ( values ) {
		let value = values[ 0 ]
		value = value.trim()

		if ( value.length === 0 )
			throw new Error( `Please provide information for this field: ${fieldLabel}` )

		return value
	} )
}

cmsForm.submit = function submit ( data ) {
	let person = Cupid.getCurrentPerson( data.phoneNumber )
	person.setName( data.name )
	person.setEmailAddress( data.emailAddress )
	let sourcePoint = data.formContext || data.urlSlug
	person.setSourcePoint( sourcePoint )

	let trackSlug = data.formContext ? slugify( data.formContext ) : data.urlSlug
	Cupid.logPersonIn( person, { trackSlug: trackSlug } )

	// Set the form heading as the "interest", else the URL slug
	let interest = cmsForm.getFormNode().data( "heading" ) || data.formContext
	if ( ! person.hasInterest( interest ) ) {
		person.setInterests( interest )
		Cupid.savePerson( person )
		PersonLogger.registerInterest( person )
	}


	let extendedAttributes = { ...data }
		delete extendedAttributes.name
		delete extendedAttributes.emailAddress
		delete extendedAttributes.phoneNumber
		delete extendedAttributes.formContext
		delete extendedAttributes.urlSlug

	if ( Object.keys( extendedAttributes ).length > 0 ) {
		person.setExtendedAttributes( extendedAttributes )
		Cupid.savePerson( person )
		PersonLogger.submitData( person )
	}

	return Promise.resolve( person )
}





/*
 * ----- Form submission handler
 */
$( document ).on( "submit", ".js_cms_form", function ( event ) {

	/*
	 | ----- Prevent default browser behaviour
	 */
	event.preventDefault();

	/*
	 | ----- Prevent interaction with the form
	 */
	cmsForm.disable();

	/*
	 | Provide feedback to the user
	 */
	cmsForm.giveFeedback( "Sending..." )

	/*
	 | ----- Extract data (and report issues if found)
	 */
	let data;
	try {
		data = cmsForm.getData();
		data.formContext = cmsForm.getFormNode().data( "context" ).trim()
		data.urlSlug = window.location.pathname.replace( "/", "" )
	} catch ( error ) {
		alert( error.message )
		console.error( error.message )
		cmsForm.enable()
		cmsForm.fields[ error.fieldName ].focus()
		cmsForm.setSubmitButtonLabel()
		return;
	}

	/*
	 | ----- Submit data
	 */
	cmsForm.submit( data )
		.then( function () {
			setFeedbackMessage( data )
			showFeedbackMessage()
		} )

} )





/**
 |
 | Form Content Heights
 |
 */
// Track and explicitly set the content heights of the primary and OTP forms
// This is to enable a smooth height transition for when the forms are to crossfade
let domFormsContainer = $( ".js_forms_container" ).get( 0 )
let domPrimaryForm = $( ".js_cms_form" ).get( 0 )
domPrimaryForm.style.setProperty( "--content-height", domPrimaryForm.scrollHeight + "px" )
	// Track the height as and when the viewport gets resized
function measureAndSetFormHeights ( event ) {
	domPrimaryForm.style.setProperty( "--content-height", domPrimaryForm.scrollHeight + "px" )
}
$( window ).on( "resize", debounce( measureAndSetFormHeights ) )



/**
 |
 | Helper functions
 |
 */
function setFeedbackMessage ( dataContext ) {
	// Pre-prepare the message
	let feedbackMessageTemplate = cmsForm.getFormNode().data( "feedback" )
	let feedbackMessage = window.__BFS.renderTemplate( feedbackMessageTemplate, dataContext );
	// Set the feedback message
	let $feedbackMessage = cmsForm.getFormNode().find( ".js_form_feedback" );
	$feedbackMessage.html( "<br>" + feedbackMessage );
}
function showFeedbackMessage () {
	let $feedbackMessage = cmsForm.getFormNode().find( ".js_form_feedback" );
	let $submitButton = cmsForm.getFormNode().find( "[ type = 'submit' ]" );
	// Hide the submit button
	$submitButton.parent().slideUp( 250, function () {
		// Initiate the process to present the feedback message. ( Yes, it is quite an involved process. )
		$feedbackMessage.slideDown( 250, function () {
			// The form's height is explicitly set. This is form certain transitions to work.
			measureAndSetFormHeights();
			// Fade in the message
			$feedbackMessage.css( { opacity: 1 } );
		} );
	} );
}





} )
