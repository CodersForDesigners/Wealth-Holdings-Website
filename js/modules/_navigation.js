
/*
 *
 * When scrolling through the page on mobile,
 * 1. Show the menu button on scrolling up.
 * 2. Hide the menu button on scrolling down.
 *
 */

 var currentScrollPosition = window.scrollY || document.body.scrollTop;
 var menuWidgetIsHidden = false;
 var controlDisplayOfMenuButtonOnScroll = function () {

 	var $menuWidget = $( ".js_menu_toggle" );

 	return function controlDisplayOfMenuButtonOnScroll () {

 		var scrollTop = window.scrollY || document.body.scrollTop;

 		/*
 		 * Show / hide the menu toggle depending on the scroll direction
 		 */
 		// Scrolling down
 		if ( scrollTop > currentScrollPosition ) {
 			// console.log( "The menu button should be hidden." )
 			if ( ! menuWidgetIsHidden ) {
 				menuWidgetIsHidden = true;
 				$menuWidget.addClass( "hide" );
 			}
 		}

 		// Scrolling up
 		if ( scrollTop < currentScrollPosition ) {
 			// console.log( "The menu button should be shown." )
 			if ( menuWidgetIsHidden ) {
 				menuWidgetIsHidden = false;
 				$menuWidget.removeClass( "hide" );
 			}
 		}

 		currentScrollPosition = scrollTop;

 	};

 }();

 // onViewportScrollThrottle( controlDisplayOfMenuButtonOnScroll );
 window.addEventListener( "scroll", controlDisplayOfMenuButtonOnScroll, true );



/*
 *
 * When scrolling through the page,
 * 1. Change the URL fragment to match the section that is currently being viewed.
 * 2. Reflect the current section in the navigation menu ( if applicable ).
 *
 */

var intervalToCheckForEngagement = 250;
var thresholdTimeForEngagement = 2000;
var timeSpentOnASection = 0;

var manageHistoryAndNavigationOnScroll = function () {

	var currentScrollTop;
	var previousScrollTop;
	var currentSection;
	var previousSection;
	var sectionScrollTop;

	// Get all the sections in the reverse order
	var $sections = Array.prototype.slice.call( $( ".js_section" ) )
					.reverse()
					.map( function ( el ) { return $( el ) } );
	// Get all the navigational links from the navigation menu
	var $navItems = $( ".js_nav_section a" );

	return function manageHistoryAndNavigationOnScroll () {

		var viewportHeight = $( window ).height();
		currentScrollTop = window.scrollY || document.body.scrollTop;
		currentSection = null;

		var _i
		for ( _i = 0; _i < $sections.length; _i += 1 ) {
			sectionScrollTop = $sections[ _i ].position().top;
			if (
				( currentScrollTop >= sectionScrollTop - viewportHeight / 2 )
				&&
				( currentScrollTop <= sectionScrollTop + $sections[ _i ].height() + viewportHeight / 2 )
			) {
				currentSection = $sections[ _i ].attr( "id" );
				break;
			}
		}
		if ( ! currentSection ) {
			currentSection = "/";
			sectionScrollTop = 0;
		}

		// Mark the corresponding item in the navigation menu
		$navItems
			.removeClass( "active" )
			.filter( "[ href = '#" + currentSection + "' ]" )
			.addClass( "active" )

		/*
		 * If the previous and the current section are the same, then add time
		 * Else, reset the "time spent on a section" counter
		 */
		if ( currentSection == previousSection ) {
			timeSpentOnASection += intervalToCheckForEngagement
			if ( timeSpentOnASection >= thresholdTimeForEngagement ) {
				if ( ! ( history.state && history.state.section == currentSection ) ) {
					history.pushState( {
						section: currentSection,
						scrollPosition: sectionScrollTop
					}, "", currentSection + "/" );
				}
			}
		}
		else {
			timeSpentOnASection = 0
		}

		previousScrollTop = currentScrollTop;
		previousSection = currentSection;

	};

}();

setInterval( manageHistoryAndNavigationOnScroll, intervalToCheckForEngagement );


$( window ).on( "popstate", function ( event ) {

	// reset the timeSpent var
	timeSpentOnASection = 0

	event.preventDefault();

} );





/*
 *
 * Smooth scroll to the section whenever a navigation menu item is hit
 *
 */
$( ".js_nav_section a" ).on( "click", function ( event ) {

	event.preventDefault();

	var domWhereTo = $( $( event.target ).attr( "href" ) ).get( 0 );
	domWhereTo.scrollIntoView( { behavior: "smooth" } );

	// Close the navigation menu
	$( "body" ).removeClass( "modal-open nav-open" );

} )