
( function ( $ ) {









/* -/-/-/-/- CODE STARTS HERE -/-/-/-/- */

// Export to global state
window.__CUPID = window.__CUPID || { };

window.__CUPID.policies = window.__CUPID.policies || { }
window.__CUPID.policies.requireOTP = true;

// Make convenient references
var __ = window.__CUPID;
var utils = __.utils;





function LoginPrompt ( context, $site ) {
	this.context = context;
	this.$site = $site || $( document );
	this.$phoneForm = $site.find( "form.js_phone_form" );
	this.$OTPForm = $site.find( "form.js_otp_form" );
	// this.conversionUrl
	this.eventHandlers = { };
	// Store all instances of logins
	if ( ! LoginPrompt._instances )
		LoginPrompt._instances = { };
	LoginPrompt._instances[ context ] = this;

	// Trigger events on actions
	var loginPrompt = this;
	if ( this.$phoneForm.length ) {
		this.$phoneForm.on( "submit", function ( event ) {
			event.preventDefault();
			loginPrompt.trigger( "phoneSubmit", event );
		} );
	}
	this.$OTPForm.on( "submit", function ( event ) {
		event.preventDefault();
		loginPrompt.trigger( "OTPSubmit", event );
	} );
	this.$OTPForm.on( "click", ".js_resend_otp", function ( event ) {
		event.preventDefault();
		loginPrompt.trigger( "OTPResend", event );
	} );
	this.$OTPForm.on( "click", ".js_try_different_number", function ( event ) {
		event.preventDefault();
		loginPrompt.trigger( "requirePhone", event );
	} );
}
LoginPrompt.prototype.events = [
	"requirePhone", "phoneValidationError", "phoneSubmit", "phoneError",
	"requireOTP", "OTPSubmit", "OTPVerified", "OTPError",
	"login", "prepare"
];
LoginPrompt.prototype.triggerFlowOn = function triggerFlowOn ( event, elementSelector ) {
	this.triggerEvent = event;
	this.triggerElement = elementSelector;
	this.triggerRegion = $( elementSelector ).closest( ".js_login_trigger_region" );
	if ( this.triggerRegion.length )
		this.triggerRegion = this.triggerRegion.get( 0 );
	else
		this.triggerRegion = this.triggerElement;
	// Storing a reference to this
	let loginPrompt = this;
	return $( elementSelector ).on( event, function ( event ) {

		if ( getUser() ) {
			// Restore any hyperlinks
			loginPrompt.$site.find( "a" ).each( function ( _i, domAnchor ) {
				var $anchor = $( domAnchor );
				var url = $anchor.data( "href" );
				if ( url )
					$anchor.attr( "href", url );
			} );
			// Take any other preparatory action
			loginPrompt.trigger( "prepare", getUser() );
			loginPrompt.off( "prepare" );
			// Default to the default behavior
			return;
		}

		// If the user is **not** logged in,
		// 	prevent the registered event handlers from executing
		event.preventDefault();
		event.stopImmediatePropagation();

		// Prompt the user to log in
		loginPrompt.trigger( "requirePhone", event );

	} );
};
LoginPrompt.prototype.on = function on ( event, fn ) {
	if ( this.eventHandlers[ event ] )
		this.eventHandlers[ event ].push( fn );
	else
		this.eventHandlers[ event ] = [ fn ];
};
LoginPrompt.prototype.off = function on ( event ) {
	if ( this.eventHandlers[ event ] )
		this.eventHandlers[ event ] = [ ];
};
LoginPrompt.prototype.trigger = function trigger ( event, ...args ) {

	var eventHandlers = this.eventHandlers[ event ];
	if ( ! eventHandlers )
		return;

	var _i;
	for ( _i = 0; _i < eventHandlers.length; _i += 1 ) {
		eventHandlers[ _i ].apply( this, args );
	}

};
// Make this function accessible on the Cupid namespace
__.LoginPrompt = LoginPrompt;



function Person ( phoneNumber, sourcePoint ) {

	this.client = __.settings.clientSlug;
	this.source = { medium: __.settings.sourceMedium };
	this.phoneNumber = phoneNumber;

	if ( sourcePoint )
		this.source.point = sourcePoint;

}
// Make this function accessible on the Cupid namespace
__.Person = Person;

Person.prototype.hasDeviceId = function hasDeviceId ( id ) {
	if ( typeof id == "string" )
		this.deviceId = id;
	return this;
}

Person.prototype.isInterestedIn = function isInterestedIn ( things ) {

	// If the input neither a String or an Array, return
	if ( typeof things != "string" )
		if ( ! Array.isArray( things ) )
			return this;

	if ( typeof things == "string" )
		things = [ things ];

	// For backward compatibility
	if ( Array.isArray( things ) ) {
		things = things
					.reduce( function ( allThings, interest ) {
						if ( typeof interest == "object" )
							return allThings.concat(
								interest.product, interest.variant
							);
						else if ( typeof interest == "string" )
							return allThings.concat( interest );
						else
							return allThings;
					}, [ ] )
					.filter( function ( thing ) { return thing } )
	}


	this.interests = this.interests || [ ];
	this.interests = this.interests.concat( things );
	return this;

}


/*
 * Fetch the person from the database
 */
Person.prototype.getFromDB = function getFromDB () {

	var data = {
		client: this.client,
		phoneNumber: this.phoneNumber
	};

	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/people";

	var ajaxRequest = $.ajax( {
		url: url,
		method: "GET",
		data: data,
		contentType: "application/json",
		dataType: "json",
		// xhrFields: {
		// 	withCredentials: true
		// }
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {
			var person = response.data;
			var sourcePoint = person.source && person.source.point;
			var newPerson = new Person( person.phoneNumber, sourcePoint );
			newPerson = Object.assign( newPerson, person );
			if ( __.tempUser.name )
				newPerson.name = __.tempUser.name;
			if ( __.tempUser.emailAddress )
				newPerson.emailAddress = __.tempUser.emailAddress;
			newPerson.isInterestedIn( __.tempUser.interests );
			resolve( newPerson );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = utils.getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );

};

/*
 * Add a person
 */
Person.prototype.add = function add () {

	var data = {
		client: this.client,
		phoneNumber: this.phoneNumber,
		source: this.source,
		deviceId: this.deviceId
	};

	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/people";

	var ajaxRequest = $.ajax( {
		url: url,
		method: "POST",
		data: JSON.stringify( data ),
		contentType: "application/json",
		dataType: "json",
		// xhrFields: {
		// 	withCredentials: true
		// }
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {
			resolve( response );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = utils.getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );

};

/*
 *
 * Request an OTP to be sent to the person
 *
 */
Person.prototype.requestOTP = function requestOTP ( product, phoneNumber ) {

	var data = {
		client: this.client,
		phoneNumber: phoneNumber || this.phoneNumber,
		product: product
	};

	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/people/otp/send";

	var ajaxRequest = $.ajax( {
		url: url,
		method: "POST",
		data: JSON.stringify( data ),
		contentType: "application/json",
		dataType: "json",
		// xhrFields: {
		// 	withCredentials: true
		// }
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {

			if ( response.Status.toLowerCase() != "error" ) {
				// return the OTP Session ID
				resolve( response.Details );
				return;
			}

			var responseErrorMessage = response.Details.toLowerCase();
			// Message reads as follows:
				// Invalid Phone Number - Length Mismatch(Expected: 10)
			if ( /invalid/.test( responseErrorMessage ) ) {
				reject( { code: 1, message: "The phone number you've provided is not valid. Please try again." } );
				return;
			}
			else {
				reject( { code: 1, message: responseErrorMessage } );
				return;
			}

			resolve( response );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = utils.getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );

}

/*
 * Verify the OTP person provided by a person
 */
Person.prototype.verifyOTP = function verifyOTP ( otp ) {

	var person = this;

	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/people/otp/verify";

	var data = {
		client: this.client,
		otp: otp,
		sessionId: this.otpSessionId
	};

	var ajaxRequest = $.ajax( {
		url: url,
		method: "POST",
		data: JSON.stringify( data ),
		contentType: "application/json",
		dataType: "json",
		// xhrFields: {
		// 	withCredentials: true
		// }
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {
			var verificationWasSuccessful = false;
			var responseErrorMessage = response.Details.toLowerCase();

			if ( /mismatch/.test( responseErrorMessage ) )
				message = "OTP does not match. Please try again.";
			else if ( /combination/.test( responseErrorMessage ) )
				message = "OTP does not match. Please try again.";
			else if ( /expire/.test( responseErrorMessage ) )
				message = "OTP has expired. Please try again.";
			else if ( /missing/.test( responseErrorMessage ) )
				message = "Please provide an OTP.";
			else if ( response.Status.toLowerCase() != "error" )
				verificationWasSuccessful = true;
			else
				message = response.Details;

			if ( ! verificationWasSuccessful )
				return reject( { code: 1, message: message } );

			return person.verify()
				.then( function () {
					resolve( response );
				} )
				.catch( function () {
					resolve( response );
				} );

		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = utils.getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );

}

/*
 * Mark a person as "verified"
 * TODO: This is temporary
 */
Person.prototype.verify = function verify () {

	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/people/verify";
	var data = {
		client: __.settings.clientSlug,
		phoneNumbers: [ this.phoneNumber ],
		verificationMethod: "OTP"
	};

	var ajaxRequest = $.ajax( {
		url: url,
		method: "POST",
		data: JSON.stringify( data ),
		contentType: "application/json",
		dataType: "json",
		// xhrFields: {
		// 	withCredentials: true
		// }
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {
			resolve( response );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );
}

/*
 * TODO: Update a person's information
 */
Person.prototype.update = function update () {

	var data = {
		client: this.client,
		phoneNumber: this.phoneNumber,
		interests: this.interests,
		deviceId: this.deviceId,
		name: this.name,
		emailAddress: this.emailAddress
	};

	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/people";

	var ajaxRequest = $.ajax( {
		url: url,
		method: "PUT",
		data: JSON.stringify( data ),
		contentType: "application/json",
		dataType: "json",
		// xhrFields: {
		// 	withCredentials: true
		// }
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {
			resolve( response );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = utils.getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );

}


/*
 * Notifiy the Person's presence on the website
 */
Person.prototype.isOnWebsite = function isOnWebsite () {

	var data = {
		client: this.client,
		phoneNumber: this.phoneNumber,
		interests: this.interests,
		deviceId: this.deviceId,
		name: this.name,
		emailAddress: this.emailAddress
	};

	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/hooks/person-on-website";

	var ajaxRequest = $.ajax( {
		url: url,
		method: "POST",
		data: JSON.stringify( data ),
		contentType: "application/json",
		dataType: "json",
		// xhrFields: {
		// 	withCredentials: true
		// }
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {
			resolve( response );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = utils.getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );

}



/*
 *
 * Is a user logged in? Is there a user session present? If yes, then return it.
 *
 */
function getUser () {
	if ( __.user )
		return __.user;

	var user = utils.getCookie( "cupid-user" );
	if ( user ) {
		__.user = new Person( user.phoneNumber );
		return __.user;
	}
	return user;
}
utils.getUser = getUser;

// TODO: Remove
function getUserById ( id, options ) {

	options = options || { };

	var getUserPromise;

	if ( options.updated )
		getUserPromise = getUser__FromDB( id, "id", options );

	getUserPromise = getUser__FromLocalStorage( id, "id", options )
		.catch( function ( e ) {
			return getUser__FromDB( id, "id", options );
		} );

	return getUserPromise;

}

// TODO: Remove
function getUserByPhoneNumber ( phoneNumber, options ) {

	options = options || { };

	var getUserPromise;

	if ( options.updated )
		getUserPromise = getUser__FromDB( phoneNumber, "phoneNumber", options );

	getUserPromise = getUser__FromLocalStorage( phoneNumber, "phoneNumber", options )
		.catch( function ( e ) {
			return getUser__FromDB( phoneNumber, "phoneNumber", options );
		} );

	return getUserPromise;

}

// TODO: Remove
function getUser__FromLocalStorage ( identifyingAttribute, by, options ) {

	var user = utils.getCookie( "cupid-user" );

	if ( ! user )
		return Promise.reject( null );
	if ( user[ by ] != identifyingAttribute )
		return Promise.reject( null );

	return Promise.resolve( user );

}

// TODO: Remove
function getUser__FromDB ( identifyingAttribute, by, options ) {

	var client = __.settings.clientSlug;
	var apiEndpoint = __.settings.cupidApiEndpoint;
	var url = apiEndpoint + "/v2/people";
	var data = { client: client };
	data[ by ] = identifyingAttribute;

	var ajaxRequest = $.ajax( {
		url: url,
		method: "GET",
		data: data,
		dataType: "json"
	} );

	return new Promise( function ( resolve, reject ) {

		ajaxRequest.done( function ( response ) {
			var user = response.data;
			utils.setCookie( "cupid-user", user );
			resolve( user );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = utils.getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );

	} );

}

// TODO: Remove
function authenticationRequired ( event ) {

	var $target = $( event.target );
	var $triggerElement = $target.closest( ".qpid_auth" );
	var $trapSite = $target.closest( ".qpid_login_site" );
	var context = $trapSite.data( "context" );
	var loginPrompt = LoginPrompt._instances[ context ];

	if ( getUser() ) {
		// Restore any hyperlinks
		$trapSite.find( "a" ).each( function ( _i, domAnchor ) {
			var $anchor = $( domAnchor );
			var url = $anchor.data( "href" );
			$anchor.attr( "href", url );
		} );
		// Default to the default behavior
		return;
	}

	// If the user is **not** logged in,
	// 	prevent the registered event handlers from executing
	event.preventDefault();
	event.stopImmediatePropagation();

	// Prompt the user to log in
	if ( loginPrompt )
		loginPrompt.trigger( "requirePhone", event );
}
// $( "body" ).on( "click", ".qpid_auth:not( form )", authenticationRequired );
// If the trap site it_this is a form, then we want to hook on the form submission
// $( "body" ).on( "submit", "form.qpid_auth", authenticationRequired );





/* -/-/-/-/- CODE ENDS HERE -/-/-/-/- */









}( jQuery ) );
