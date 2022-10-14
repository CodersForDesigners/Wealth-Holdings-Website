
/*
 |
 | Co-Investment Form
 |
 |
 */
$( function () {

// Imports
let BFSForm = window.__BFS.exports.BFSForm
let domBackOfCoInvestmentCard = window.__BFS.exports.domBackOfCoInvestmentCard




var coInvestmentForm = new BFSForm( ".js_co_investment_form" );

// The event(s) that trigger the form to appear
	// (the form is hidden by default)
$( ".js_section_co_investment" ).on( "click", ".js_co_investment_get_details, .js_co_own", function ( event ) {

	// Store references
		// The card that was clicked on
	let $coInvestmentCard = $( event.target ).closest( ".js_co_investment_card" );

	// Excluding the one just selected, unflip all the cards that are already flipped
	unflipCoInvestmentCardThatAreAlreadyFlipped( $coInvestmentCard )

	if ( Cupid.personIsLoggedIn() ) {
		recordInterestInCoInvestment( $coInvestmentCard )
		injectUIToBackOfInvestmentCard( $coInvestmentCard )
		let person = Cupid.getCurrentPerson()
		provideFeedback( $coInvestmentCard, `Our investment manager will get in touch with you shortly on ${person.phoneNumber}` )
		hideForm()
		flipInvestmentCard( $coInvestmentCard )
	}
	else if ( ! $coInvestmentCard.hasClass( "flipped" ) ) {
		injectUIToBackOfInvestmentCard( $coInvestmentCard )
		window.requestAnimationFrame( function () {
			flipInvestmentCard( $coInvestmentCard )
		} )
	}

} )



	// Phone number
coInvestmentForm.addField( "phoneNumber", [ ".js_phone_country_code", ".js_form_input_phonenumber" ], function ( values ) {
	let [ phoneCountryCode, phoneNumberLocal ] = values
	return BFSForm.validators.phoneNumber( phoneCountryCode, phoneNumberLocal )
} );
// When programmatically focusing on this input field, which of the (two, in this case) input elements to focus on?
coInvestmentForm.fields[ "phoneNumber" ].defaultDOMNodeFocusIndex = 1

coInvestmentForm.submit = function submit ( data ) {
	let person = Cupid.getCurrentPerson( data.phoneNumber )
	person.setSourcePoint( "Co-Investments" )

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
$( document ).on( "submit", ".js_co_investment_form", function ( event ) {

	/*
	 * ----- Prevent default browser behaviour
	 */
	event.preventDefault();

	/*
	 | Get a reference to the form
	 */
	let $form = $( event.target ).closest( "form" )
	let currentForm = coInvestmentForm.bind( $form )

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
	let $coInvestmentCard = currentForm.getFormNode().closest( ".js_co_investment_card" )
	data.interest = getInterestString( $coInvestmentCard )

	/*
	 | ----- Submit data
	 */
	currentForm.submit( data )
		.then( function ( response ) {
			injectUIToBackOfInvestmentCard( $coInvestmentCard )
			provideFeedback( $coInvestmentCard, `Our investment manager will get in touch with you shortly on ${data.phoneNumber}` )
			hideForm()
			flipInvestmentCard( $coInvestmentCard )
		} )

} )





/*
 |
 | Helper functions
 |
 */
function hideForm () {
	coInvestmentForm.getFormNode().addClass( "hidden" )
}
function provideFeedback ( $node, message ) {
	$node.find( ".js_message" ).text( message )
}
function getInterestString ( $coInvestmentCard ) {
	return "Co-Investment: " + $coInvestmentCard.find( ".js_title" ).text();
}
function flipInvestmentCard ( $coInvestmentCard ) {
	$coInvestmentCard.addClass( "flipped" )
}
function injectUIToBackOfInvestmentCard ( $coInvestmentCard ) {
	// Transplant the markup for the back of investment card from wherever it was before, onto the given co-investment card
	$coInvestmentCard.find( ".js_back" ).get( 0 ).appendChild( domBackOfCoInvestmentCard )
}
function unflipCoInvestmentCardThatAreAlreadyFlipped ( $exceptions ) {
	let $flippedCards = $( ".js_section_co_investment" ).find( ".js_co_investment_card.flipped" );
	$flippedCards.not( $exceptions ).removeClass( "flipped" );
}

function recordInterestInCoInvestment ( $coInvestmentCard ) {

	let interest = getInterestString( $coInvestmentCard )

	let person = Cupid.getCurrentPerson()
	if ( ! person.hasInterest( interest ) ) {
		person.setInterests( interest )
		Cupid.savePerson( person )
		PersonLogger.registerInterest( person )
	}

}





} )
