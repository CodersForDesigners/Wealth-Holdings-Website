
window.__BFS = window.__BFS || { };

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
 * Recur a given function every given interval
 *
 */
function executeEvery ( interval, fn ) {

	interval = ( interval || 1 ) * 1000;

	var timeoutId;
	var running = false;

	return {
		_schedule: function () {
			var _this = this;
			timeoutId = setTimeout( function () {
				window.requestAnimationFrame( function () {
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
			clearTimeout( timeoutId );
			timeoutId = null;
			running = false;
		}
	}

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
