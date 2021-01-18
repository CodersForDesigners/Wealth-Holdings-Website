
window.__BFS = window.__BFS || { };
window.__BFS.utils = window.__BFS.utils || { };

/*
 *
 * Wait for the specified number of seconds.
 * This facilitates a Promise or syncrhonous (i.e., using async/await ) style
 * 	of programming
 *
 */
function waitFor ( seconds ) {
	return new Promise( function ( resolve, reject ) {
		setTimeout( function () {
			resolve();
		}, seconds * 1000 );
	} );
}



/*
 *
 * Smooth scroll to a section
 *
 */
function smoothScrollTo ( locationHash ) {

	if ( ! locationHash )
		return;

	var locationId = locationHash.replace( "#", "" );
	var domLocation = document.getElementById( locationId );
	if ( ! domLocation )
		return;

	window.scrollTo( { top: domLocation.offsetTop, behavior: "smooth" } );

}



/*
 *
 * Schedule a function to execute on the *next* browser paint
 *
 */
function onNextPaint ( fn ) {
	return window.requestAnimationFrame( function () {
		return window.requestAnimationFrame( function () {
			return fn();
		} );
	} );
}



/*
 *
 * Recur a given function every given interval
 *
 */
function executeEvery ( interval, fn ) {

	interval = ( interval || 1 ) * 1000;

	var timeoutId;
	var frameId;
	var running = false;

	return {
		_schedule: function () {
			var _this = this;
			timeoutId = window.setTimeout( function () {
				frameId = window.requestAnimationFrame( function () {
					fn();
					_this._schedule()
				} );
			}, interval );
		},
		start: function () {
			if ( running )
				return;
			running = true;
			this._schedule();
		},
		stop: function () {
			window.cancelAnimationFrame( frameId );
			frameId = null;
			window.clearTimeout( timeoutId );
			timeoutId = null;
			running = false;
		}
	}

}



/*
 *
 * Debounce a given function if invoked within the given period
 *
 */
function debounce ( fn, duration ) {

	duration = ( duration || 1 ) * 1000;
	var timeoutId;
	var frameId;

	return function () {
		// Clear any previously scheduled execution *always*
		window.cancelAnimationFrame( frameId );
		window.clearTimeout( timeoutId );
		// Schedule a fresh execution of the provided function
		var context = this;
		var functionArguments = Array.prototype.slice.call( arguments );
		timeoutId = setTimeout( function () {
			frameId = window.requestAnimationFrame( function () {
				fn.apply( context, functionArguments );
			} );
		}, duration );
	};

}



/*
 *
 * Throttle the execution of a given function to a fixed frequency interval, regardless of how many times it is invoked
 *
 */
function throttle ( fn, duration ) {

	duration = ( duration || 1 ) * 1000;
	var timeoutId = null;
	var frameId = null;

	return function () {
		// If the function is yet to be executed, do nothing and simply return
		if ( frameId !== null || timeoutId !== null )
			return;

		// Else, schedule the function to execute at the end of the given interval
		var context = this;
		var functionArguments = Array.prototype.slice.call( arguments );
		timeoutId = setTimeout( function () {
			frameId = window.requestAnimationFrame( function () {
				fn.apply( context, functionArguments );
				frameId = null;
				timeoutId = null;
			} );
		}, duration );
	};

}



/*
 *
 * Add given data to the data layer variable established by GTM
 *
 */
function gtmPushToDataLayer ( data ) {
	if ( ! window.dataLayer )
		return;
	window.dataLayer.push( data );
}
window.__BFS.gtmPushToDataLayer = gtmPushToDataLayer;



/*
 *
 * ----- Renders a template with data
 *
 */
window.__BFS.renderTemplate = function () {

	var d;
	function replaceWith ( m ) {

		var pipeline = m.slice( 2, -2 ).trim().split( / *\| */ );
		var value = d[ pipeline[ 0 ] ];
		for ( var _i = 1; _i < pipeline.length; _i +=1 ) {
			value = __UTIL.template[ pipeline[ _i ] ]( value );
		}

		return value;

	}

	return function renderTemplate ( template, data ) {
		d = data;
		return template.replace( /({{[^{}]+}})/g, replaceWith );
	}

}();



/*
 *
 * Managerial Hub for "scroll" event handlers
 *
 */
function onScroll ( fn, options ) {

	options = options || { };

	// Create frequency controlled versions of the provided function
	if ( options.frequencyMode === "debounce" )
		fn = debounce( fn, options.interval );
	else if ( options.frequencyMode === "throttle" )
		fn = throttle( fn, options.interval );
	else if ( options.frequencyMode !== false || options.frequencyMode !== "none" || options.frequencyMode !== "default" )
		console.log( "WARNING: Please provide an explicity frequency mode so that your intention is explicit and clear." );

	// Add the provided function to the queue
	window.__BFS = window.__BFS || { };
	window.__BFS.bevahior = window.__BFS.bevahior || { };
	window.__BFS.bevahior.scrollHandlers = window.__BFS.bevahior.scrollHandlers || [ ];
	var scrollHandlers = window.__BFS.bevahior.scrollHandlers;
	scrollHandlers.push( { fn: fn, options: options } );

	if ( scrollHandlers.length > 1 )
		return;

	// Set up the scroll event handling mechanism
	initializeGlobalScrollHandler();

}

function initializeGlobalScrollHandler () {

	// var currentScrollY = window.scrollY || document.body.scrollTop;
	// var previousScrollY = currentScrollY;
	var scrollHandlers = window.__BFS.bevahior.scrollHandlers;
	function globalScrollHandler ( event ) {
		var context = this;
		// Call all the registered scroll handlers one after the other, providing useful data
		var _i;
		for ( _i = 0; _i < scrollHandlers.length; _i += 1 )
			scrollHandlers[ _i ].fn.call( context, event );

	};
	window.__BFS.bevahior.globalScrollHandler = globalScrollHandler;

	window.addEventListener( "scroll", globalScrollHandler, true );

}
