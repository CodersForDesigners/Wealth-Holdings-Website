
/*
 *
 * ----- Search form
 *
 */
$( function () {

	$( ".js_search_form" ).on( "submit", function ( event ) {

		var $form = $( event.target ).closest( "form" );
		var $essentialField = $form.find( "[ name = 'bfs_hi_puf' ]" );

		if ( $essentialField.val().length !== 0 ) {
			event.preventDefault();
			return;
		}

		// Extract the query
		var $query = $form.find( "[ name = 's' ]" );
		var query = $query.val().trim();

		// Make the search query
		window.location.href = window.location.origin + "/faqs" + "?s=" + query;

	} );

} )
