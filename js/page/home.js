
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
