
/*
 |
 | Co-Investments
 |
 |
 */
;$( function () {



// Exports
window.__BFS = window.__BFS || { }
window.__BFS.exports = window.__BFS.exports || { }


/*
 |
 | The code in this file comprises of two sections:
 |  1. Some imperative code, that is mostly concerned with setting up things
 |  2. Interaction logic and Event handlers
 |
 |
 */

/*
 |
 | [[ 1 ]]
 | Set the `content-height` of the section
 |  so that CSS can tap into and use this var,
 |  in order to set up the "View All" slide-down transition
 |
 |
 */
var domCoInvestmentTileGrid = $( ".js_section_co_investment .js_co_investment_card_container" ).get( 0 );
domCoInvestmentTileGrid.style.setProperty( "--content-height", domCoInvestmentTileGrid.scrollHeight + "px" );
	// Track the height as and when the viewport gets resized
$( window ).on( "resize", debounce( function ( event ) {
	domCoInvestmentTileGrid.style.setProperty( "--content-height", domCoInvestmentTileGrid.scrollHeight + "px" );
} ) );


// TODO:: SET UP THE BACK OF THE CO-INVESTMENT CARD
/*
 |
 | Create an instance of the "Back of the Investment Card" template
 |  and store it on the back of the first actual co-investment card
 |  This instance is passed around, instead of stamping out a new instance every time it is required.
 |  This is because when the user has submitted the form on the back, the content of the (back of the) card changes (it has a feedback message, etc.).
 |  We don't want to have to derive this state every time.
 |  Hence, it is just easier to have on instance of this and pass it around.
 |
 |
 */
const $templateBackOfInvestmentCard = $( ".js_template[ data-name = 'co-investment-card-back' ]" )
$( ".js_co_investment_card" ).first().find( ".js_back" )
	.append( $templateBackOfInvestmentCard.html() )
// Now, get a reference to the node that was just injected into the DOM (in the previous statement)
let domBackOfCoInvestmentCard = $( ".js_co_investment_card" ).first().find( ".js_back > div" ).get( 0 )
window.__BFS.exports.domBackOfCoInvestmentCard = domBackOfCoInvestmentCard



/*
 |
 | On clicking "View All",
 |  Show *all* the co-investment cards
 |
 |
 */
$( ".js_section_co_investment .js_view_all" ).on( "click", function ( event ) {
	$( ".js_section_co_investment" ).addClass( "view-all" );
} );



/*
 |
 | On clicking "Back",
 |  Un-flip the co-investment card
 |
 |
 */
$( ".js_section_co_investment" ).on( "click", ".js_investment_card_unflip", function ( event ) {
	let $card = $( event.target ).closest( ".js_co_investment_card" );
	$card.removeClass( "flipped" );
} );



/*
 |
 | On interacting the filtration,
 |  filter and re-render the cards that match the filter criteria
 |
 |
 */
$( ".js_section_co_investment" ).on( "change", ".js_filter", function ( event ) {

	// Store some references
	var $coInvestmentSection = $( ".js_section_co_investment" );
	var $filtrationFeedback = $coInvestmentSection.find( ".js_filtration_feedback" );
	var $coInvestmentCardTemplate = $( ".js_template[ data-name = 'co-investment-card' ]" );

	// Get and set the selected filter
	var $filter = $( event.target );
	var filterCategory = $filter.data( "name" );
	var filterValue = $( event.target ).val();
	filtersSelected[ filterCategory ] = filterValue;


	// Block the filtration
	$coInvestmentSection.find( ".js_filtration" ).addClass( "no-pointer" );
	$coInvestmentSection.find( ".js_filter" ).prop( "disabled", true );
		// Un-focus
	$filter.get( 0 ).blur();

	// Remove the "View All" overlay
	$coInvestmentSection.find( ".js_view_all" ).trigger( "click" );

	// Add the filtration loading effect
	$coInvestmentSection.addClass( "filter-and-load" );

	// Move the back of the investment card to temporary holding space
	$( ".js_temp_holding_area" ).get( 0 ).appendChild( domBackOfCoInvestmentCard );

	// Get the investments that qualify for the filters selected
	var investments = window.__BFS.data.coInvestments.filter( filterByCategories );

	// Hide the filtration feedback
	$filtrationFeedback.addClass( "hidden" );

	// Add or remove investments if required
	var $coInvestments = $coInvestmentSection.find( ".js_co_investment_card" );
	if ( investments.length < $coInvestments.length ) {
		$coInvestments.slice( investments.length ).addClass( "marked-for-removal" );
		$coInvestments = $coInvestments.slice( 0, investments.length );
	}
	else if ( investments.length > $coInvestments.length ) {
		var _i = 1;
		var numberOfAdditionalInvestments = investments.length - $coInvestments.length;
		if ( $coInvestments.length === 0 ) {
			$( ".js_co_investment_card_container" ).prepend( $coInvestmentCardTemplate.html() );
			_i = 2;
			$coInvestments = $( ".js_section_co_investment" ).find( ".js_co_investment_card" );
		}
		for ( ; _i <= numberOfAdditionalInvestments; _i += 1 )
			$coInvestments.last().after( $coInvestmentCardTemplate.html() );
	}

	setTimeout( function () {

		// Populate the data on the investment cards
			// Yes, the cards are being queried again (because it reports the old number)
		var $coInvestments = $coInvestmentSection.find( ".js_co_investment_card:not( .marked-for-removal )" );
		$coInvestments.removeClass( "flipped" );
		$coInvestments.each( function ( _i, domEl ) {
			var $card = $( domEl );
			var investment = investments[ _i ];
			$card.find( ".js_yield_amount" ).text( investment.acf.yield.amount );
			$card.find( ".js_yield_duration" ).text( investment.acf.yield.duration );
			$card.find( ".js_rent_amount" ).text( investment.acf.return.amount );
			$card.find( ".js_rent_duration" ).text( investment.acf.return.duration );
			$card.find( ".js_title_lumpsum" ).text( investment.acf.title.lumpsum );
			$card.find( ".js_title" ).text( investment.acf.title );
			$card.find( ".js_cost" ).text( investment.acf.cost );
			$card.find( ".js_minimum_investment" ).text( investment.acf.minimum_investment );

			// $card.attr( "data-title", investment.post_title );
			// $card.attr( "data-description", investment.acf.default_payment_mode ? investment.acf.title.emi : investment.acf.title.lumpsum );
			// $card.attr( "data-url", investment.__custom.url );

		} )

		// Restore the back of the investment card ( *if* there are cards to show )
		if ( investments.length )
			// $coInvestments.first().find( ".js_back" ).get( 0 ).appendChild( $templateBackOfInvestmentCard.html() );
			$coInvestments.first().find( ".js_back" ).get( 0 ).append( $( $templateBackOfInvestmentCard.html() ).get( 0 ) );

		// Show a feedback message if no investments were found
		if ( investments.length === 0 )
			$filtrationFeedback.removeClass( "hidden" )

		// Remove the `faded-while-loading` class from the new cards
		$coInvestmentSection.find( ".js_co_investment_card.faded-while-loading" ).removeClass( "faded-while-loading" );

		// Remove the filtration loading effect
		$coInvestmentSection.removeClass( "filter-and-load" );

	}, 300 );

	setTimeout( function () {

		// Properly remove the cards that were marked for removal from the DOM
		$coInvestmentSection.find( ".js_co_investment_card.marked-for-removal" ).remove();

		// Un-block the filtration
		$coInvestmentSection.find( ".js_filtration" ).removeClass( "no-pointer" );
		$coInvestmentSection.find( ".js_filter" ).prop( "disabled", false );

	}, 500 )

} );





/*
 |
 | Helper constants and functions
 |
 |
 */
// Selecting a filter toggles the filter pipeline
var filtersSelected = {
	cost_per_share: "",
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



} );
