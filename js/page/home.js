
/*
 *
 * ----- INVESTMENTS
 *
 */

// For the markup comprising the back of the investment cards, we're using only **one** instance of it
// Every time a card is flipped, we move the markup from the previous card and onto the back of the card being flipped
//
$( ".js_investment_card" ).first().find( ".js_back" )
	.append( $( ".js_template.js_investment_card_back" ).html() )
var domBackOfInvestmentCard = $( ".js_investment_card" ).first().find( ".js_back > div" ).get( 0 )

// Flip the investment card if the user is not logged in
$( ".js_section_investment" ).on( "click", ".js_investment_get_details", function ( event ) {
	// Unflip cards that are already flipped
	var $flippedCards = $( ".js_section_investment" ).find( ".js_investment_card.flipped" );
	$flippedCards.removeClass( "flipped" );

	// Flip the card that was clicked on
	let $card = $( event.target ).closest( ".js_investment_card" );
		// But first, transplant the markup for the back of investment card from wherever it was before
		$card.find( ".js_back" ).get( 0 ).appendChild( domBackOfInvestmentCard );
		// Okay, now flip
		window.requestAnimationFrame( function () {
			$card.addClass( "flipped" );
		} )
} );

// Un-flip the investment card if the user is clicks "Back"
$( ".js_section_investment" ).on( "click", ".js_investment_card_unflip", function ( event ) {
	let $card = $( event.target ).closest( ".js_investment_card" );
	$card.removeClass( "flipped" );
} );



// Toggle between the EMI and Lumpsum content when hitting the Lumpsum/EMI toggle button
$( document ).on( "change", ".js_toggle_payment_mode", function ( event ) {
	let $card = $( event.target ).closest( ".js_investment_card" );
	$card.toggleClass( "show-emi" );
} );
