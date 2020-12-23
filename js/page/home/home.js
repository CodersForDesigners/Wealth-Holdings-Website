
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

	// The card that was clicked on
	let $card = $( event.target ).closest( ".js_investment_card" );

	// Excluding the one just selected, unflip all the cards that are already flipped
	var $flippedCards = $( ".js_section_investment" ).find( ".js_investment_card.flipped" );
	$flippedCards.not( $card ).removeClass( "flipped" );

	// If user is logged in, don't flip anything; just record their interest.
	var __ = window.__CUPID;
	if ( __.utils.getUser() ) {
		var paymentMode = $card.find( ".js_toggle_payment_mode" ).is( ":checked" ) ? "emi" : "lumpsum";
		var interest = "Investment: " + $card.find( ".js_title_" + paymentMode ).text();
		__.user.isInterestedIn( interest );
		__.user.update();
		// Transplant the markup for the back of investment card from wherever it was before
		$card.find( ".js_back" ).get( 0 ).appendChild( domBackOfInvestmentCard );
		// Display an acknowledgement message
		var message = "Our investment manager will get in touch with you shortly on " + __.user.phoneNumber;
		$card.find( ".js_message" ).text( message );
		// Hide the login prompts
		$card.find( ".js_phone_form, .js_otp_form" ).hide();
		// Flip the card
		$card.addClass( "flipped" );
	}
	else if ( ! $card.hasClass( "flipped" ) ) {
		// But first, transplant the markup for the back of investment card from wherever it was before
		$card.find( ".js_back" ).get( 0 ).appendChild( domBackOfInvestmentCard );
		// Okay, now flip
		window.requestAnimationFrame( function () {
			$card.addClass( "flipped" );
		} )
	}

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



// Hitting "View All" shows all the investment cards
$( ".js_section_investment .js_view_all" ).on( "click", function ( event ) {
	$( ".js_section_investment" ).addClass( "view-all" );
} );



/*
 *
 * ----- FAQs
 *
 */
$( ".js_section_faqs" ).on( "click", ".js_faq_title", function ( event ) {

	var $selectedFaq = $( event.target ).closest( ".js_faq" );
	var $faqs = $( ".js_faq" );

	if ( $selectedFaq.hasClass( "open" ) )
		$selectedFaq.removeClass( "open" )
	else {
		$faqs.removeClass( "open" );
		$selectedFaq.addClass( "open" );
	}

} );
