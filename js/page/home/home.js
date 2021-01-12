
$( function () {





/*
 *
 * ----- INVESTMENTS
 *
 */

// Clone an investment card and store it as a template
var $investmentCardCopy = $( $( ".js_section_investment" ).find( ".js_investment_card" ).first().get( 0 ).outerHTML );
$investmentCardCopy
	.addClass( "faded-while-loading" )
	.find( ".js_yield_amount, .js_yield_duration, .js_rent_amount, .js_rent_duration, .js_title_lumpsum, .js_title_emi, .js_cost, .js_minimum_investment" ).text( "" )
		.end()
	.find( ".js_toggle_payment_mode" ).prop( "checked", false )
$( ".js_section_templates" ).prepend( $( "<template class='js_template' data-name='investment-card'>" + $investmentCardCopy.get( 0 ).outerHTML.trim() + "</template>" ) );


// For the markup comprising the back of the investment cards, we're using only **one** instance of it
// Every time a card is flipped, we move the markup from the previous card and onto the back of the card being flipped
$( ".js_investment_card" ).first().find( ".js_back" )
	.append( $( ".js_template[ data-name = 'investment-card-back' ]" ).html() )
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

	// Update the Share parameters
	var investmentId = $card.data( "id" );
	var investment = window.__BFS.data.investments.find( function ( investment ) { return investment.ID === investmentId } );
	var shareURL = investment.__custom.url;
	var shareDescription;
	if ( investment.acf.default_payment_mode ) {
		if ( $card.hasClass( "show-emi" ) ) {
			shareURL += "?pm=lumpsum";
			shareDescription = investment.acf.title.lumpsum;
		}
		else
			shareDescription = investment.acf.title.emi;
	}
	else {
		if ( $card.hasClass( "show-emi" ) )
			shareDescription = investment.acf.title.lumpsum;
		else {
			shareURL += "?pm=emi";
			shareDescription = investment.acf.title.emi;
		}
	}
	$card.attr( "data-description", shareDescription );
	$card.attr( "data-url", shareURL );

	// Toggle the payment mode
	$card.toggleClass( "show-emi" );
} );



// Hitting "View All" shows all the investment cards
$( ".js_section_investment .js_view_all" ).on( "click", function ( event ) {
	$( ".js_section_investment" ).addClass( "view-all" );
} );


// Selecting a filter toggles the filter pipeline
var filtersSelected = {
	asset_cost: "",
	minimum_investment: ""
}
function filterByCategories ( investment ) {
	var category;
	for ( category in filtersSelected ) {
		var value = filtersSelected[ category ];
		if ( value && investment.acf.categories[ category ] !== value )
			return false;
	}
	return true;
}
var $investmentCardTemplate = $( ".js_template[ data-name = 'investment-card' ]" );
$( ".js_section_investment" ).on( "change", ".js_filter", function ( event ) {

	// Store some references
	var $investmentSection = $( ".js_section_investment" );
	var $filtrationFeedback = $investmentSection.find( ".js_filtration_feedback" );

	// Get and set the selected filter
	var $filter = $( event.target );
	var filterCategory = $filter.data( "name" );
	var filterValue = $( event.target ).val();
	filtersSelected[ filterCategory ] = filterValue;

	// Set the selected value on the markup
	var filterValue__Presentable = $filter.find( ":selected" ).text();
	$filter.parent().find( ".js_readable_value" ).text( filterValue__Presentable );

	// Block the filtration
	$investmentSection.find( ".js_filtration" ).addClass( "no-pointer" );
	$investmentSection.find( ".js_filter" ).prop( "disabled", true );
		// Un-focus
	$filter.get( 0 ).blur();

	// Remove the "View All" overlay
	$investmentSection.find( ".js_view_all" ).trigger( "click" );

	// Add the filtration n' loading effect
	$investmentSection.addClass( "filter-and-load" );

	// Move the back of the investment card to temporary holding space
	$( ".js_section_templates" ).get( 0 ).appendChild( domBackOfInvestmentCard );

	// Get the investments that qualify for the filters selected
	var investments = window.__BFS.data.investments.filter( filterByCategories );

	// Hide the filtration feedback
	$filtrationFeedback.addClass( "hidden" );

	// Add or remove investments if required
	var $investments = $investmentSection.find( ".js_investment_card" );
	if ( investments.length < $investments.length ) {
		$investments.slice( investments.length ).addClass( "marked-for-removal" );
		$investments = $investments.slice( 0, investments.length );
	}
	else if ( investments.length > $investments.length ) {
		var _i = 1;
		var numberOfAdditionalInvestments = investments.length - $investments.length;
		if ( $investments.length === 0 ) {
			$( ".js_investment_card_container" ).prepend( $investmentCardTemplate.html() );
			_i = 2;
			$investments = $( ".js_section_investment" ).find( ".js_investment_card" );
		}
		for ( ; _i <= numberOfAdditionalInvestments; _i += 1 )
			$investments.last().after( $investmentCardTemplate.html() );
	}

	setTimeout( function () {

		// Populate the data on the investment cards
			// Yes, the cards are being queried again (because it reports the old number)
		var $investments = $investmentSection.find( ".js_investment_card:not( .marked-for-removal )" );
		$investments.removeClass( "show-emi flipped" );
		$investments.each( function ( _i, domEl ) {
			var $card = $( domEl );
			var investment = investments[ _i ];
			$card.find( ".js_yield_amount" ).text( investment.acf.yield.amount );
			$card.find( ".js_yield_duration" ).text( investment.acf.yield.duration );
			$card.find( ".js_rent_amount" ).text( investment.acf.rent.amount );
			$card.find( ".js_rent_duration" ).text( investment.acf.rent.duration );
			$card.find( ".js_title_lumpsum" ).text( investment.acf.title.lumpsum );
			$card.find( ".js_title_emi" ).text( investment.acf.title.emi );
			$card.find( ".js_cost" ).text( investment.acf.cost );
			$card.find( ".js_minimum_investment" ).text( investment.acf.minimum_investment );
			$card.find( ".default_payment_mode" ).text( investment.acf.minimum_investment );
			$card.find( ".js_toggle_payment_mode" ).prop( "checked", investment.acf.default_payment_mode )

			$card.attr( "data-title", investment.post_title );
			$card.attr( "data-description", investment.acf.default_payment_mode ? investment.acf.title.emi : investment.acf.title.lumpsum );
			$card.attr( "data-url", investment.__custom.url );

			if ( investment.acf.default_payment_mode )
				$card.addClass( "show-emi" );
		} )

		// Restore the back of the investment card ( *if* there are cards to show )
		if ( investments.length )
			$investments.first().find( ".js_back" ).get( 0 ).appendChild( domBackOfInvestmentCard );

		// Show a feedback message if no investments were found
		if ( investments.length === 0 )
			$filtrationFeedback.removeClass( "hidden" )

		// Remove the `faded-while-loading` class from the new cards
		$investmentSection.find( ".js_investment_card.faded-while-loading" ).removeClass( "faded-while-loading" );

		// Remove the filtration n' loading effect
		$investmentSection.removeClass( "filter-and-load" );

	}, 300 );

	setTimeout( function () {

		// Properly remove the cards that were marked for removal from the DOM
		$investmentSection.find( ".js_investment_card.marked-for-removal" ).remove();

		// Un-block the filtration
		$investmentSection.find( ".js_filtration" ).removeClass( "no-pointer" );
		$investmentSection.find( ".js_filter" ).prop( "disabled", false );

	}, 500 )

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





} );
