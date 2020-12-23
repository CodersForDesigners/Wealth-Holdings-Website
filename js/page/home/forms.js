
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
	// Record the activity
	__.utils.getAnalyticsId()
		.then( function ( deviceId ) {
			__.user.hasDeviceId( deviceId );
			__.user.isOnWebsite();
			__.user.update();	// the name and email might have been provided somewhere earlier
		} )

	loginPrompt.trigger( "postLogin" );
}



/*
 * ------------------------\
 *  The Login Prompts
 * ------------------------|
 */
var __ = window.__CUPID;
var loginPrompts = { };





/*
 * ----- Investments Login Flow
 */
loginPrompts.investments = new __.LoginPrompt( "Investments", $( ".js_section_investment" ) );
loginPrompts.investments.conversionSlug = "invest-today";

loginPrompts.investments.on( "requirePhone", function ( event ) {
	var loginPrompt = this;
	loginPrompt.$OTPForm.slideUp( 500, function () {
		enableForm( loginPrompt.$phoneForm );
		loginPrompt.$phoneForm.slideDown()
	} );
} );

loginPrompts.investments.on( "phoneSubmit", function ( event ) {
	var loginPrompt = this;
	var $form = $( event.target ).closest( "form" );

	// Pull data from the form
	var formData;
	try {
		formData = getFormData( $form, {
			phoneNumber: { type: "phone number", $: ".js_phone_country_code, [ name = 'phone-number' ]" }
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
loginPrompts.investments.on( "requireOTP", function ( event, phoneNumber ) {
	var loginPrompt = this;
	disableForm( loginPrompt.$phoneForm, "Sending..." );
	__.tempUser.requestOTP( loginPrompt.context )
		.then( function ( otpSessionId ) {
			__.tempUser.otpSessionId = otpSessionId;
			// Show OTP form, after hiding the Contact form
			loginPrompt.$phoneForm.slideUp( 500, function () {
				loginPrompt.$OTPForm.slideDown();
			} );
		} )
		.catch( function ( e ) {
			alert( e.message );
			enableForm( loginPrompt.$phoneForm );
		} )
} );
// When the OTP is required
loginPrompts.investments.on( "OTPSubmit", onOTPSubmit );
loginPrompts.investments.on( "OTPError", function ( e ) {
	alert( e.message );
} );
loginPrompts.investments.on( "OTPVerified", onOTPVerified );
// When the user is logged in
loginPrompts.investments.on( "login", onLogin );
loginPrompts.investments.on( "postLogin", function ( user ) {
	var loginPrompt = this;
	// Hide the OTP and phone forms
	$( loginPrompt.$OTPForm ).add( loginPrompt.$phoneForm ).slideUp( 500, function () {
		// Display an acknowledgement message
		var message = "Our investment manager will get in touch with you shortly on " + __.user.phoneNumber;
		loginPrompt.$site.find( ".js_message" ).text( message );
		// Trigger the default flow
		var $investmentCard = loginPrompt.$phoneForm.closest( ".js_investment_card" );
		$investmentCard.find( ".js_investment_get_details" ).trigger( "click" );
	} );
} );



/*
 * ----- Webinar Login Flow
 */
loginPrompts.webinar = new __.LoginPrompt( "Webinar", $( ".js_section_webinar" ) );
loginPrompts.webinar.conversionSlug = "register-for-webinar";

loginPrompts.webinar.on( "requirePhone", function ( event ) {
	var loginPrompt = this;
	loginPrompt.$site.removeClass( "show-otp-form" );
	enableForm( loginPrompt.$phoneForm );
} );

loginPrompts.webinar.on( "phoneSubmit", function ( event ) {
	var loginPrompt = this;
	var $form = $( event.target ).closest( "form" );

	// Pull data from the form
	var formData;
	try {
		formData = getFormData( $form, {
			name: { type: "name", $: "[ name = 'name' ]" },
			emailAddress: { type: "email address", $: "[ name = 'email-address' ]" },
			phoneNumber: { type: "phone number", $: ".js_phone_country_code, [ name = 'phone-number' ]" }
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
	var phoneNumber = formData[ 2 ].value.join( "" );

	// Create a new (but temporary) Person object
	__.tempUser = new __.Person( phoneNumber, loginPrompt.context );
	__.tempUser.name = formData[ 0 ].value;
	__.tempUser.emailAddress = formData[ 1 ].value;						;
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
						__.user.name = formData[ 0 ].value;
						__.user.emailAddress = formData[ 1 ].value;						;
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
loginPrompts.webinar.on( "requireOTP", function ( event, phoneNumber ) {
	var loginPrompt = this;
	disableForm( loginPrompt.$phoneForm, "Sending..." );
	__.tempUser.requestOTP( loginPrompt.context )
		.then( function ( otpSessionId ) {
			__.tempUser.otpSessionId = otpSessionId;
			// Show OTP form, after hiding the Contact form
			loginPrompt.$site.addClass( "show-otp-form" );
			enableForm( loginPrompt.$OTPForm );
		} )
		.catch( function ( e ) {
			alert( e.message );
			enableForm( loginPrompt.$phoneForm );
		} )
} );
// When the OTP is required
loginPrompts.webinar.on( "OTPSubmit", onOTPSubmit );
loginPrompts.webinar.on( "OTPError", function ( e ) {
	alert( e.message );
} );
loginPrompts.webinar.on( "OTPVerified", onOTPVerified );
// When the user is logged in
loginPrompts.webinar.on( "login", onLogin );
loginPrompts.webinar.on( "postLogin", function ( user ) {
	var loginPrompt = this;
	// Hide the OTP and phone forms
	$( loginPrompt.$OTPForm ).fadeOut( 250 )
	$( loginPrompt.$phoneForm ).slideUp( 500, function () {
		// Display an acknowledgement message
		var $feedbackMessage = loginPrompt.$site.find( ".js_post_registration_message" );
		var feedbackMessageTemplate = $feedbackMessage.html();
		var feedbackMessage = window.__BFS.renderTemplate( feedbackMessageTemplate, { emailAddress: __.user.emailAddress } );
		$feedbackMessage.html( feedbackMessage.trim() );
		$feedbackMessage.fadeIn();
	} );
	__.user.isInterestedIn( "Webinar: " + window.__BFS.data.webinarDate );
} );
