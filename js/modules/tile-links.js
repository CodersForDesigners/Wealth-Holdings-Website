
$( function () {






// For the markup comprising the login prompt of the tile links, we're using only **one** instance of it
// Every time a tile link requiring user login is clicked, we move the markup from the previous tile link and onto the back of the current one
$( ".js_tile" ).first().find( ".js_tile_login_prompt_container" )
	.append( $( ".js_template[ data-name = 'tile-link-login-prompt' ]" ).html() )
var domTileLinkLoginPrompt = $( ".js_tile" ).first().find( ".js_tile_login_prompt" ).get( 0 )


$( ".js_tile a.js_action" ).each( function ( _i, domAnchor ) {
	var $anchor = $( domAnchor );
	var href = $anchor.data( "href" );
	$anchor.removeAttr( "data-href" );
	$anchor.data( "href", href );
} );


/*
 * ----- Close the login prompt on clicking the close button
 */
$( ".js_tile" ).on( "click", ".js_action", function ( event ) {

	// Close any login prompts that are open on any of the other tiles
	var $tiles = $( ".js_tile.js_user_required" );
	$tiles.removeClass( "open" );

	var $tile = $( event.target ).closest( ".js_tile" );

	var $action = $( event.target ).closest( ".js_action" );
	if ( window.__CUPID.user ) {
		// Restore the actionable markup
		// 	so that the user can see whatever s/he's meant to
		if ( $action.is( "a" ) && $action.attr( "href" ).length < 9 ) {
			$action.attr( "href", $action.data( "href" ) );
			$action.get( 0 ).click();
		}
		if ( ! $tile.hasClass( "js_recorded_interest" ) && $tile.hasClass( "js_user_required" ) ) {
			var contextualHeading = $tile.find( ".js_tile_subheading" ).text();
			var heading = $tile.find( ".js_title" ).text().replace( /\r?\n/g, " " );
			var interest = contextualHeading + ": " + heading;
			$tile.addClass( "js_recorded_interest" );
			window.__CUPID.user.isInterestedIn( interest );
			window.__CUPID.user.update();
		}
		return;
	}

	if ( $tile.hasClass( "js_user_required" ) ) {
		event.preventDefault();
		event.stopImmediatePropagation();
		if ( loginPrompts.theTileLink )
			loginPrompts.theTileLink.trigger( "requirePhone", event );
	}

} );
$( ".js_tile" ).on( "click", ".js_close", function ( event ) {
	var $tile = $( event.target ).closest( ".js_tile" );
	$tile.removeClass( "open" );
} );



/*
 * ----- Login Flow
 */
// If the Login Prompt API is not available, exit
if ( ! window.__CUPID )
	return;

var __ = window.__CUPID;
window.__BFS = window.__BFS || { };
var loginPrompts = { };
window.__BFS.loginPrompts = loginPrompts;


var pageName;
if ( window.__BFS && window.__BFS.post && window.__BFS.post.title )
	pageName = window.__BFS.post.title;
else
	pageName = "Home";

loginPrompts.theTileLink = new __.LoginPrompt( pageName, $( domTileLinkLoginPrompt ) );
loginPrompts.theTileLink.conversionSlug = location.pathname.replace( "/", "" );

function trackConversion ( loginPrompt ) {
	// Track the conversion
	var conversionUrl = $( loginPrompt.triggerElement ).data( "c" ) || loginPrompt.conversionSlug;
	__.utils.trackPageVisit( conversionUrl );
}

loginPrompts.theTileLink.on( "requirePhone", function ( event ) {
	var $tile = $( event.target ).closest( ".js_tile" );
	// Transplant the login prompt to the current tile
	$tile.find( ".js_tile_login_prompt_container" ).get( 0 ).appendChild( domTileLinkLoginPrompt );
	// Update the login prompt heading
	$tile.find( ".js_login_prompt_title" ).html( $tile.data( "login-prompt-title" ) );
	// Reveal the login prompt
	$tile.addClass( "open" );
} );

loginPrompts.theTileLink.on( "phoneSubmit", function ( event ) {
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
loginPrompts.theTileLink.on( "requireOTP", function ( event, phoneNumber ) {
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

loginPrompts.theTileLink.on( "OTPSubmit", function onOTPSubmit ( event ) {

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

} );

loginPrompts.theTileLink.on( "OTPError", function ( e ) {
	alert( e.message );
} );

loginPrompts.theTileLink.on( "OTPVerified", function onOTPVerified () {
	var loginPrompt = this;
	// Trigger the login event
	loginPrompt.trigger( "login" );
} );

loginPrompts.theTileLink.on( "login", function onLogin () {
	var loginPrompt = this;
	// Set cookie ( for a month )
	__.utils.setCookie( "cupid-user", __.user, 31 * 24 * 60 * 60 );
	// Record the activity
	__.utils.getAnalyticsId()
		.then( function ( deviceId ) {
			__.user.hasDeviceId( deviceId );
			__.user.update();	// the name and email might have been provided somewhere earlier
		} )

	loginPrompt.trigger( "postLogin" );
} );

loginPrompts.theTileLink.on( "postLogin", function postLogin () {
	var loginPrompt = this;
	var $tile = loginPrompt.$site.closest( ".js_tile" );
	$tile.removeClass( "open" );
} );





} );
