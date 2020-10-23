
/*
 * ------------------------\
 *  Form helpers
 * ------------------------|
 */
// Disable the form
function disableForm ( $form, message ) {
	$form.find( "input, select, button" ).prop( "disabled", true );
	if ( message ) {
		var $feedback = $form.find( "[ type = 'submit' ]" );
		$feedback.data( "default", $feedback.text() );
		$feedback.text( message );
	}
}
// Enable the form
function enableForm ( $form, message ) {
	$form.find( "input, select, button" ).prop( "disabled", false );
	var $feedback = $form.find( "[ type = 'submit' ]" );
	if ( message )
		$feedback.text( message );
	else if ( $feedback.data( "default" ) )
		$feedback.text( $feedback.data( "default" ) );
}

/*
 * ------------------------\
 *  Common event handlers
 * ------------------------|
 */
function onOTPSubmit ( event ) {

	var loginPrompt = this;
	var $form = loginPrompt.$OTPForm;

	var formData;
	try {
		formData = getFormData( $form, {
			otp: { type: "OTP", $: "[ name = 'otp' ]" }
		} );
	}
	catch ( e ) {
		// Reflect back sanitized values to the form
		setFormData( $form, e );
		// Trigger the OTP Error event
		loginPrompt.trigger( "OTPError", {
			message: "Please provide a valid OTP."
		} );
		return;
	}

	// Reflect back sanitized values to the form
	setFormData( $form, formData );

	var data = formData.reduce( function ( acc, f ) {
		acc[ f.name ] = f.value;
		return acc;
	}, { } );

	__.tempUser.verifyOTP( data.otp )
		.then( function () {
			__.user = __.tempUser;
			loginPrompt.trigger( "OTPVerified" );
		} )
		.catch( function ( e ) {
			loginPrompt.trigger( "OTPError", e );
		} );

}
function onOTPVerified () {
	var loginPrompt = this;
	// Trigger the login event
	loginPrompt.trigger( "login" );
}
function trackConversion ( loginPrompt ) {
	// Track the conversion
	var conversionUrl = $( loginPrompt.triggerElement ).data( "c" ) || loginPrompt.conversionSlug;
	__.utils.trackPageVisit( conversionUrl );
}
function onLogin () {
	var loginPrompt = this;
	// Set cookie ( for a month )
	__.utils.setCookie( "cupid-user", __.user, 31 * 24 * 60 * 60 );
	// Hide the OTP form
	$( loginPrompt.$OTPForm ).add( loginPrompt.$phoneForm ).slideUp( 500, function () {
		// Bring back the trigger element
		$( loginPrompt.triggerRegion ).slideDown( 500, function () {
			// Trigger the original event
			$( loginPrompt.triggerElement ).trigger( loginPrompt.triggerEvent );
		} );
	} );
	__.utils.getAnalyticsId()
		.then( function ( deviceId ) {
			__.user.hasDeviceId( deviceId );
			__.user.isOnWebsite();
		} )
}





/*
 * ------------------------\
 *  The Login Prompts
 * ------------------------|
 */
var __ = window.__CUPID;
var loginPrompts = { };

/*
 * 1. Contact Us section
 */
loginPrompts.contactUs = new __.LoginPrompt( "Contact Us", $( ".js_contact_form_section" ) );
loginPrompts.contactUs.triggerFlowOn( "submit", ".js_contact_form_1" );
// Skip the phone form because it is already integrated with the contact form
loginPrompts.contactUs.on( "requirePhone", function ( event ) {
	this.trigger( "phoneSubmit", event );
} );
// Since the phone number is already provided in the contact form, simply submit it programmatically
loginPrompts.contactUs.on( "phoneSubmit", function ( event ) {
	var loginPrompt = this;
	var $form = $( event.target ).closest( "form" );

	// Pull data from the form
	var formData;
	try {
		formData = getFormData( $form, {
			phoneNumber: { type: "phone number", $: ".js_phone_country_code, [ name = 'phone-number' ]" },
			budget: { type: "budget", $: "[ name = 'budget' ]" }
			// name: { type: "name", $: "[ name = 'name' ]" },
			// emailAddress: { type: "email address", $: "[ name = 'email-address' ]" },
		} );
	}
	catch ( e ) {
		// Reflect back sanitized values to the form
		setFormData( $form, e );
		// Prepare the error message
		var message = e.reduce( function ( message, issue ) {
			return message + "\n"
				+ ( issue.type[ 0 ].toUpperCase() + issue.type.slice( 1 ) );
		}, "" );
		message = "Please provide valid information for the following fields:" + message;
		// Report the message
		alert( message );
		return;
	}

	// Reflect back sanitized values to the form
	setFormData( $form, formData );

	// Get the relevant data
	var phoneNumber = formData[ 0 ].value.join( "" );

	// Create a new (but temporary) Person object
	__.tempUser = new __.Person( phoneNumber, loginPrompt.context );
		// Set the device id
	__.utils.getAnalyticsId()
		.then( function ( deviceId ) {
			__.tempUser.hasDeviceId( deviceId );
		} )
		// Attempt to find the person in the database
		.then( function () {
			return __.tempUser.getFromDB()
				// If the person exists, log in
				.then( function ( person ) {
					if ( person.verification && person.verification.isVerified ) {
						__.user = person;
						loginPrompt.trigger( "login", person );
					}
					else
						throw person;
				} )
				// If the person don't exist, add the person, and send an OTP
				.catch( function ( person ) {
					if ( person instanceof Error || ! person )
						trackConversion( loginPrompt );
					return __.tempUser.add()
						.then( function () {
							loginPrompt.trigger( "requireOTP" );
						} )
						.catch( function () {
							loginPrompt.trigger( "phoneError" );
						} );
				} )
		} );

} );
// When the phone number is to be submitted
loginPrompts.contactUs.on( "requireOTP", function ( event, phoneNumber ) {
	var loginPrompt = this;
	var $contactForm1 = loginPrompt.$site.find( ".js_contact_form_1" );
	disableForm( $contactForm1, "Sending..." );
	__.tempUser.requestOTP( loginPrompt.context )
		.then( function ( otpSessionId ) {
			__.tempUser.otpSessionId = otpSessionId;
			// Show OTP form, after hiding the Contact form
			var $otpForm = loginPrompt.$site.find( ".js_otp_form" );
			$contactForm1.slideUp( 500, function () {
				$otpForm.slideDown();
			} );
		} )
		.catch( function ( e ) {
			alert( e.message );
			enableForm( $contactForm1, "Invest Today" );
		} )
} );
// When the OTP is required
loginPrompts.contactUs.on( "OTPSubmit", onOTPSubmit );
loginPrompts.contactUs.on( "OTPError", function ( e ) {
	alert( e.message );
} );
loginPrompts.contactUs.on( "OTPVerified", onOTPVerified );
// When the user is logged in
loginPrompts.contactUs.on( "login", onLogin );
