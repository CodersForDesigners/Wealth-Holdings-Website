
/*
 |
 | Investment Form
 |
 |
 */
$( function () {

// Imports
let BFSForm = window.__BFS.exports.BFSForm
let domBackOfInvestmentCard = window.__BFS.exports.domBackOfInvestmentCard




var investmentForm = new BFSForm( ".js_investment_form" );

// The event(s) that trigger the form to appear
	// (the form is hidden by default)
$( ".js_section_investment" ).on( "click", ".js_investment_get_details", function ( event ) {

	// Store references
		// The card that was clicked on
	let $investmentCard = $( event.target ).closest( ".js_investment_card" );

	// Excluding the one just selected, unflip all the cards that are already flipped
	unflipInvestmentCardThatAreAlreadyFlipped( $investmentCard )

	if ( Cupid.personIsLoggedIn() ) {
		recordInterestInInvestment( $investmentCard )
		injectUIToBackOfInvestmentCard( $investmentCard )
		let person = Cupid.getCurrentPerson()
		provideFeedback( $investmentCard, `Our investment manager will get in touch with you shortly on ${person.phoneNumber}` )
		hideForm()
		flipInvestmentCard( $investmentCard )
	}
	else if ( ! $investmentCard.hasClass( "flipped" ) ) {
		injectUIToBackOfInvestmentCard( $investmentCard )
		window.requestAnimationFrame( function () {
			flipInvestmentCard( $investmentCard )
		} )
	}

} )



	// Phone number
investmentForm.addField( "phoneNumber", [ ".js_phone_country_code", ".js_form_input_phonenumber" ], function ( values ) {
	let [ phoneCountryCode, phoneNumberLocal ] = values
	return BFSForm.validators.phoneNumber( phoneCountryCode, phoneNumberLocal )
} );
// When programmatically focusing on this input field, which of the (two, in this case) input elements to focus on?
investmentForm.fields[ "phoneNumber" ].defaultDOMNodeFocusIndex = 1

investmentForm.submit = function submit ( data ) {
	let person = Cupid.getCurrentPerson( data.phoneNumber )
	person.setSourcePoint( "Investments" )

	Cupid.logPersonIn( person, { trackSlug: "invest-today" } )

	let interest = data.interest
	if ( ! person.hasInterest( interest ) ) {
		person.setInterests( interest )
		Cupid.savePerson( person )
		PersonLogger.registerInterest( person )
	}

	return Promise.resolve( person )
}





/*
 | ----- Form submission handler
 */
$( document ).on( "submit", ".js_investment_form", function ( event ) {

	/*
	 * ----- Prevent default browser behaviour
	 */
	event.preventDefault();

	/*
	 | Get a reference to the form
	 */
	let $form = $( event.target ).closest( "form" )
	let currentForm = investmentForm.bind( $form )

	/*
	 * ----- Prevent interaction with the form
	 */
	currentForm.disable();

	currentForm.setSubmitButtonLabel( "Sending..." )

	/*
	 * ----- Extract data (and report issues if found)
	 */
	let data;
	try {
		data = currentForm.getData();
	}
	catch ( error ) {
		alert( error.message )
		console.error( error.message )
		currentForm.enable()
		currentForm.fields[ error.fieldName ].focus()
		return;
	}
	let $investmentCard = currentForm.getFormNode().closest( ".js_investment_card" )
	// let paymentMode = $investmentCard.find( ".js_toggle_payment_mode" ).is( ":checked" ) ? "emi" : "lumpsum";
	// data.interest = "Investment: " + $investmentCard.find( ".js_title_" + paymentMode ).text();
	data.interest = getInterestString( $investmentCard )

	/*
	 | ----- Submit data
	 */
	currentForm.submit( data )
		.then( function ( response ) {
			injectUIToBackOfInvestmentCard( $investmentCard )
			provideFeedback( $investmentCard, `Our investment manager will get in touch with you shortly on ${data.phoneNumber}` )
			hideForm()
			flipInvestmentCard( $investmentCard )
		} )

} )





/*
 |
 | Helper functions
 |
 */
function hideForm () {
	investmentForm.getFormNode().addClass( "hidden" )
}
function provideFeedback ( $node, message ) {
	$node.find( ".js_message" ).text( message )
}
function getInterestString ( $investmentCard ) {
	let paymentMode
	if ( $investmentCard.find( ".js_toggle_payment_mode" ).is( ":checked" ) )
		paymentMode = "emi"
	else
		paymentMode = "lumpsum"
	let interest = "Investment: " + $investmentCard.find( ".js_title_" + paymentMode ).text();
	return interest
}
function flipInvestmentCard ( $investmentCard ) {
	$investmentCard.addClass( "flipped" )
}
function injectUIToBackOfInvestmentCard ( $investmentCard ) {
	// Transplant the markup for the back of investment card from wherever it was before
	$investmentCard.find( ".js_back" ).get( 0 ).appendChild( domBackOfInvestmentCard )
}
function unflipInvestmentCardThatAreAlreadyFlipped ( $exceptions ) {
	let $flippedCards = $( ".js_section_investment" ).find( ".js_investment_card.flipped" );
	$flippedCards.not( $exceptions ).removeClass( "flipped" );
}

function recordInterestInInvestment ( $investmentCard ) {

	let interest = getInterestString( $investmentCard )

	let person = Cupid.getCurrentPerson()
	if ( ! person.hasInterest( interest ) ) {
		person.setInterests( interest )
		Cupid.savePerson( person )
		PersonLogger.registerInterest( person )
	}

}





} )
