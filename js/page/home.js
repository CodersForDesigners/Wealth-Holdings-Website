
/*
 *
 * ----- LUMPSUM v. EMI COMPARISON TABLE:
 * 			Expand/collapse the table when clicked on
 *
 */
$( document ).on( "click", ".js_table_head, .js_table_foot", function ( event ) {

	var $comparisonTable = $( ".js_lumpsum_emi_comparison" );
	var $tableTogglers = $( ".js_table_toggle" );
	var $tableRows = $( ".js_table_row" );

	var currentState = $comparisonTable.data( "state" );
	var nextState;

	// Is the table currently collapsed?
	var tableIsCollapsed = currentState === "collapsed";

	// Expand or collapse the table
	if ( tableIsCollapsed )
		$tableRows.removeClass( "hidden" );
	else
		$tableRows.addClass( "hidden" );

	// Determine the next state
	var nextState = tableIsCollapsed ? "expanded" : "collapsed";

	// Update the UI on each of the togglers
	$tableTogglers.each( function ( _i, el ) {
		var $toggle = $( el );
		$toggle.text( $toggle.data( "when-" + nextState ) );
	} );

	// Set the new state of table
	$comparisonTable.data( "state", nextState );

} );
