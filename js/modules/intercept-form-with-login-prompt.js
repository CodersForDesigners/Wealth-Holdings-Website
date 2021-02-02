
$( function () {

if ( ! $( ".js_forms_container" ).length )
	return;





/*
 *
 * ----- FORM CONTENT HEIGHTS
 *
 */
// Track and explicitly set the content heights of the primary and OTP forms
// This is to enable a smooth height transition for when the forms are to crossfade
var domFormsContainer = $( ".js_forms_container" ).get( 0 );
var domPrimaryForm = $( ".js_primary_form" ).get( 0 );
domPrimaryForm.style.setProperty( "--content-height", domPrimaryForm.scrollHeight + "px" );
var domOTPForm = $( ".js_otp_form" ).get( 0 );
domFormsContainer.style.setProperty( "--otp-form-height", domOTPForm.scrollHeight + "px" );
	// Track the height as and when the viewport gets resized
$( window ).on( "resize", debounce( function ( event ) {
	domPrimaryForm.style.setProperty( "--content-height", domPrimaryForm.scrollHeight + "px" );
	domFormsContainer.style.setProperty( "--otp-form-height", domOTPForm.scrollHeight + "px" );
} ) );






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
__BFS.loginPrompts = loginPrompts;





/*
 * ----- Login Flow
 */
loginPrompts.theForm = new __.LoginPrompt( "The Form", $( ".js_forms_container" ) );
loginPrompts.theForm.conversionSlug = location.pathname.replace( "/", "" );
loginPrompts.theForm.$primaryForm = loginPrompts.theForm.$site.find( ".js_primary_form" );

loginPrompts.theForm.triggerFlowOn( "submit", ".js_primary_form" );

loginPrompts.theForm.on( "requirePhone", function ( event ) {
	this.trigger( "phoneSubmit", event );
} );

loginPrompts.theForm.on( "phoneSubmit", function ( event ) {
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
		e.forEach( function ( issue ) {
			$( issue.$ ).addClass( "js_error" );
		} );
		// Prepare the error message
		var message = e.reduce( function ( message, issue ) {
			return message + "\n"
				+ ( issue.type[ 0 ].toUpperCase() + issue.type.slice( 1 ) );
		}, "" );
		message = "Please provide valid information for the following fields:" + message;
		// Report the message
		alert( message );
		enableForm( $form );
		return;
	}

	// Reflect back sanitized values to the form
	setFormData( $form, formData );

	// Get the relevant data
	var phoneNumber = formData[ 2 ].value.join( "" );

	// Create a new (but temporary) Person object
	__.tempUser = new __.Person( phoneNumber, loginPrompt.context );
	__.tempUser.name = formData[ 0 ].value;
	__.tempUser.emailAddress = formData[ 1 ].value;

		// Set the device id
	__.utils.getAnalyticsId()
		.then( function ( deviceId ) {
			__.tempUser.hasDeviceId( deviceId );
		} )
		// Attempt to find the person in the database
		.then( function () {
			__.tempUser.getFromDB()
				// If the person exists, log in
				.then( function ( person ) {
					if ( person.verification && person.verification.isVerified ) {
						__.user = person;
						__.user.name = formData[ 0 ].value;
						__.user.emailAddress = formData[ 1 ].value;
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
							if ( window.__CUPID.policies.requireOTP )
								loginPrompt.trigger( "requireOTP" );
							else {
								__.user = __.tempUser;
								loginPrompt.trigger( "login" );
							}
						} )
						.catch( function () {
							loginPrompt.trigger( "phoneError" );
						} );
				} )
		} );

} );

// When the phone number is to be submitted
loginPrompts.theForm.on( "requireOTP", function ( event, phoneNumber ) {
	var loginPrompt = this;

	__.tempUser.requestOTP( loginPrompt.context )
		.then( function ( otpSessionId ) {
			__.tempUser.otpSessionId = otpSessionId;
			// Scroll to the top of the form container, and crossfade between the main and OTP form
			window.scrollTo( { top: loginPrompt.$site.offset().top - 50, behavior: "smooth" } );
			setTimeout( function () {
				loginPrompt.$site.addClass( "show-otp" );
			}, 250 );
			enableForm( loginPrompt.$OTPForm );
		} )
		.catch( function ( e ) {
			alert( e.message );
			enableForm( loginPrompt.$primaryForm );
		} )
} );

// When the OTP is required
loginPrompts.theForm.on( "OTPSubmit", onOTPSubmit );
loginPrompts.theForm.on( "OTPError", function ( e ) {
	alert( e.message );
} );
loginPrompts.theForm.on( "OTPVerified", onOTPVerified );
// When the user is logged in
loginPrompts.theForm.on( "login", onLogin );

loginPrompts.theForm.on( "postLogin", function ( user ) {
	var loginPrompt = this;

	// loginPrompt.$OTPForm.css( { opacity: 0, pointerEvents: "none" } );
	loginPrompt.$primaryForm.find( "[ name = 'name' ]" ).prop( "disabled", true );
	loginPrompt.$primaryForm.find( "[ name = 'email-address' ]" ).prop( "disabled", true );
	loginPrompt.$primaryForm.find( "[ name = 'phone-number' ]" ).prop( "disabled", true );
	loginPrompt.$primaryForm.find( ".js_phone_country_code" ).prop( "disabled", true );

	loginPrompt.$site.removeClass( "show-otp" );
	enableForm( loginPrompt.$primaryForm );

	loginPrompt.$primaryForm.trigger( "submit" );
} );





} );
