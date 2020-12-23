
$( function () {









/*
 *
 * If the URL has a hash value,
 * 	smooth-scroll to that section
 *	and restore the hash to the URL
 *
 */
// The hash was removed but cached in this variable
if ( window.__BFS.scrollTo ) {
	if ( window.scrollY < 1 )
		smoothScrollTo( window.__BFS.scrollTo );
	var fullURL = location.origin + location.pathname + location.search + window.__BFS.scrollTo;
	window.history.replaceState( { }, "", fullURL )
}



/*
 *
 * ----- Smooth-scroll to sections
 *
 */
$( document ).on( "click", "a[ href ]", function ( event ) {

	var $anchor = $( event.target ).closest( "a" );
	var domAnchor = $anchor.get( 0 );

	var urlParts = domAnchor.href.split( "#" );
	// If the url has more than one `#`es in it, we're not even going to try
	if ( urlParts.length !== 2 )
		return;

	var path = urlParts[ 0 ];
	var sectionId = urlParts[ 1 ];

	// If the path does not match that of the current page
	if ( path !== window.location.href )
		return;

	// If the section id is empty or a stub
	if ( ! sectionId.trim() || sectionId === "0" )
		return;

	// Prevent default behaviour
	event.preventDefault();
	event.stopPropagation();
	event.stopImmediatePropagation();

	// Close the navigation menu
	closeNavigationMenu();

	smoothScrollTo( sectionId );

	return false;

} );









} );
