
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
 *
 * Information Types
 *
 */
var InformationTypes = {
	name: Name,
	"email address": EmailAddress,
	"phone number": PhoneNumber,
	"OTP": OTP
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
