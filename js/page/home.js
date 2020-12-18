
/*
 *
 * ----- CARDS
 *
 */
// Toggle between the EMI and Lumpsum content when hitting the Lumpsum/EMI toggle button
$( document ).on( "change", ".js_toggle_payment_mode", function ( event ) {
	let $card = $( event.target ).closest( ".js_investment_card" );
	$card.toggleClass( "show-emi" );
} );

// Flip the investment card if the user is not logged in
$( ".js_section_investment" ).on( "click", ".js_investment_get_details", function ( event ) {
	// Unflip cards that are already flipped
	var $flippedCards = $( ".js_section_investment" ).find( ".js_investment_card.flipped" );
	$flippedCards.removeClass( "flipped" );
	// Flip the card that was clicked on
	let $card = $( event.target ).closest( ".js_investment_card" );
	$card.addClass( "flipped" );
} );

// Un-flip the investment card if the user is clicks "Back"
$( ".js_section_investment" ).on( "click", ".js_investment_card_unflip", function ( event ) {
	let $card = $( event.target ).closest( ".js_investment_card" );
	$card.removeClass( "flipped" );
} );
