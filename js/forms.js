
/*
 *
 * Information Types
 *
 */
var InformationTypes = {
	name: Name,
	"email address": EmailAddress,
	"phone number": PhoneNumber,
	"OTP": OTP,
	"budget": Budget
};
function Name ( value ) {
	if ( ! ( this instanceof Name ) )
		return new Name( value );

	this.value = value.trim().replace( /\s+/g, " " );
	this.isValid = function () {
		if ( ! this.value )
			return false;
		if ( /[^a-zA-Z\s\-']+/.test( this.value ) )
			return false;
		else
			return true;
	};
	this.get = function () {
		return this.value;
	}
}
function EmailAddress ( value ) {
	if ( ! ( this instanceof EmailAddress ) )
		return new EmailAddress( value );

	this.value = value.trim().replace( /\s/g, "" );
	this.isValid = function isValid () {
		return this.value && this.value.indexOf( "@" ) != -1;
	};
	this.get = function () {
		return this.value;
	}
}
function PhoneNumber ( countryCode, localNumber ) {
	if ( ! ( this instanceof PhoneNumber ) )
		return new PhoneNumber( countryCode, localNumber );

	this.countryCode = countryCode.replace( /[^\+\d]/g, "" );
	this.localNumber = localNumber.replace( /[^\d]/g, "" );
	this.isValid = function isValid () {
		if ( ! this.countryCode || ! this.localNumber )
			return false;
		else if ( ! /^\+\d{1,4}$/.test( this.countryCode ) )
			return false;
		else if ( /[^\d]+/.test( this.localNumber ) )
			return false;
		// Special handling for Indian numbers
		else if ( this.countryCode == "+91" && this.localNumber.length != 10 )
			return false;
		else
			return true;
	};
	this.get = function get () {
		return [ this.countryCode, this.localNumber ];
	};
}
function OTP ( value ) {
	if ( ! ( this instanceof OTP ) )
		return new OTP( value );

	this.value = value.trim().replace( /[^\d]/g, "" );
	this.isValid = function isValid () {
		return this.value && this.value.length > 3;
	};
	this.get = function () {
		return this.value;
	}
}

function Budget ( value ) {
	if ( ! ( this instanceof Budget ) )
		return new Budget( value );

	this.value = value.trim().replace( /\s+/g, " " );
	this.isValid = function () {
		if ( ! this.value )
			return false;
		else
			return true;
	};
	this.get = function () {
		return this.value;
	}
}


/*
 *
 * Extract data from a form
 *
 */
function getFormData ( $form, fields ) {
	let data = [ ];
	let issues = [ ];
	for ( name in fields ) {
		let selector = fields[ name ].$;
		let type = fields[ name ].type;
		let values = [ ].slice.call( $form.find( selector ) ).map( function ( domEl ) {
			return domEl.value;
		} );
		let informationPiece = InformationTypes[ type ].apply( null, values );
		let datum = {
			name: name,
			type: type,
			value: informationPiece.get(),
			$: selector
		};
		if ( ! informationPiece.isValid() )
			issues.push( datum );
		else
			data.push( datum );
	}

	// If there are issues, throw an error
	if ( issues.length )
		throw issues;

	return data;
}

/*
 *
 * Set data to a form
 *
 */
function setFormData ( $form, data ) {
	// For all the fields ( but the phone number )
	data
		.filter( function ( i ) { return i.name != "phoneNumber" } )
		.forEach( function ( i ) { $form.find( i.$ ).val( i.value ) } );

	// For the phone number
	data
		.filter( function ( i ) { return i.name == "phoneNumber" } )
		.forEach( function ( i ) {
			$form.find( i.$ ).last().val( i.value[ 1 ] );
			// i.value = i.value.join( "" )
		} )
}








/*
 * -------------------------------\
 * Contact Form
 * -------------------------------|
 */
// On submission of the form
$( document ).on( "submit", ".js_contact_form_1", function ( event ) {

	/* -----
	 * Prevent the default form submission behaviour
	 * 	which triggers the loading of a new page
	 ----- */
	event.preventDefault();

	var $form = $( event.target );


	// /* -----
	//  * Disable the form
	//  ----- */
	disableForm( $form, "Sending....." );


	// /* -----
	//  * Pull the data from the form
	//  ----- */
	var formData;
	try {
		formData = getFormData( $form, {
			// name: { type: "name", $: "[ name = 'name' ]" },
			// emailAddress: { type: "email address", $: "[ name = 'email-address' ]" },
			phoneNumber: { type: "phone number", $: ".js_phone_country_code, [ name = 'phone-number' ]" },
			budget: { type: "budget", $: "[ name = 'budget' ]" }
		} );
	}
	catch ( e ) {
		// Reflect back sanitized values to the form
		setFormData( $form, e );
		e.forEach( function ( issue ) {
			$( issue.$ ).addClass( "js_error" );
		} );
		// Report an error message
		var message = e.reduce( function ( message, issue ) {
			return message + "\n"
				+ ( issue.type[ 0 ].toUpperCase() + issue.type.slice( 1 ) );
		}, "" );
		message = "Please provide valid information for the following fields:" + message;
		alert( message );
		enableForm( $form, "Invest Today" );
		return;
	}
	// Reflect back sanitized values to the form
	setFormData( $form, formData );
	// Remove any prior error "markings"
	$form.find( ".js_error" ).removeClass( "js_error" );


	/* -----
	 * Process and Assemble the data
	 ----- */
	var __ = window.__CUPID;
	// Get the data in an key-value structure
	var data = formData.reduce( function ( acc, f ) {
		acc[ f.name ] = f.value;
		return acc;
	}, { } );


	// /* -----
	//  * Update the person's information
	//  ----- */
	__.user.isInterestedIn( data.budget );
	__.user.update();

	// /* -----
	//  * Give feedback to the user
	//  ----- */
	$form.slideUp();
	$( ".js_contact_form_2" ).slideDown();

} );


/*
 * -------------------------------\
 * Contact Form 2
 * -------------------------------|
 */
// On submission of the form
$( document ).on( "submit", ".js_contact_form_2", function ( event ) {

	/* -----
	 * Prevent the default form submission behaviour
	 * 	which triggers the loading of a new page
	 ----- */
	event.preventDefault();

	var $form = $( event.target );


	// /* -----
	//  * Disable the form
	//  ----- */
	disableForm( $form, "Sending....." );


	// /* -----
	//  * Pull the data from the form
	//  ----- */
	var formData;
	try {
		formData = getFormData( $form, {
			name: { type: "name", $: "[ name = 'name' ]" },
			emailAddress: { type: "email address", $: "[ name = 'email-address' ]" }
		} );
	}
	catch ( e ) {
		// Reflect back sanitized values to the form
		setFormData( $form, e );
		e.forEach( function ( issue ) {
			$( issue.$ ).addClass( "js_error" );
		} );
		// Report an error message
		var message = e.reduce( function ( message, issue ) {
			return message + "\n"
				+ ( issue.type[ 0 ].toUpperCase() + issue.type.slice( 1 ) );
		}, "" );
		message = "Please provide valid information for the following fields:" + message;
		alert( message );
		enableForm( $form, "Submit Details" );
		return;
	}
	// Reflect back sanitized values to the form
	setFormData( $form, formData );
	// Remove any prior error "markings"
	$form.find( ".js_error" ).removeClass( "js_error" );


	/* -----
	 * Process and Assemble the data
	 ----- */
	var __ = window.__CUPID;
	// Get the data in an key-value structure
	var data = formData.reduce( function ( acc, f ) {
		acc[ f.name ] = f.value;
		return acc;
	}, { } );
	__.user.name = data.name;
	__.user.emailAddress = data.emailAddress;


	// /* -----
	//  * Update the person's information
	//  ----- */
	__.user.update();

	// /* -----
	//  * Give feedback to the user
	//  ----- */
	$form.find( "[ type = 'submit' ]" ).text( "We'll call you shortly." );

} );
