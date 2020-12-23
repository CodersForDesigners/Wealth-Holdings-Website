
( function ( $ ) {









/* -/-/-/-/- CODE STARTS HERE -/-/-/-/- */

// Export to global state
window.__CUPID = window.__CUPID || { };
var __ = window.__CUPID;
__.v = 3;
var utils = { };
__.utils = utils;


/*
 * Setting
 */
__.settings = {
	client: "Wealth Holdings",
	clientSlug: "wealth-holdings",
	sourceMedium: "Website",
	cupidApiEndpoint: "https://wh.cupid.lazaro.in",
	trackingURL: "/track/"
};





/*
 *
 * Cookie helper library
 *
 */
/*!
 * JavaScript Cookie v2.2.1
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader;
	if (typeof define === 'function' && define.amd) {
		define(factory);
		registeredInModuleLoader = true;
	}
	if (typeof exports === 'object') {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function decode (s) {
		return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
	}

	function init (converter) {
		function api() {}

		function set (key, value, attributes) {
			if (typeof document === 'undefined') {
				return;
			}

			attributes = extend({
				path: '/'
			}, api.defaults, attributes);

			if (typeof attributes.expires === 'number') {
				attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
			}

			// We're using "expires" because "max-age" is not supported by IE
			attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

			try {
				var result = JSON.stringify(value);
				if (/^[\{\[]/.test(result)) {
					value = result;
				}
			} catch (e) {}

			value = converter.write ?
				converter.write(value, key) :
				encodeURIComponent(String(value))
					.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

			key = encodeURIComponent(String(key))
				.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
				.replace(/[\(\)]/g, escape);

			var stringifiedAttributes = '';
			for (var attributeName in attributes) {
				if (!attributes[attributeName]) {
					continue;
				}
				stringifiedAttributes += '; ' + attributeName;
				if (attributes[attributeName] === true) {
					continue;
				}

				// Considers RFC 6265 section 5.2:
				// ...
				// 3.  If the remaining unparsed-attributes contains a %x3B (";")
				//     character:
				// Consume the characters of the unparsed-attributes up to,
				// not including, the first %x3B (";") character.
				// ...
				stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
			}

			return (document.cookie = key + '=' + value + stringifiedAttributes);
		}

		function get (key, json) {
			if (typeof document === 'undefined') {
				return;
			}

			var jar = {};
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all.
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (!json && cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = decode(parts[0]);
					cookie = (converter.read || converter)(cookie, name) ||
						decode(cookie);

					if (json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					jar[name] = cookie;

					if (key === name) {
						break;
					}
				} catch (e) {}
			}

			return key ? jar[key] : jar;
		}

		api.set = set;
		api.get = function (key) {
			return get(key, false /* read as raw */);
		};
		api.getJSON = function (key) {
			return get(key, true /* read as json */);
		};
		api.remove = function (key, attributes) {
			set(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.defaults = {};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));
// Re-assign the library's namespace to a locally-scoped variable
utils.CookieJar = window.Cookies.noConflict();
if ( ! window.Cookies )
	delete window.Cookies;




/*
 *
 * Wait for the specified number of seconds.
 * This facilitates a Promise or syncrhonous (i.e., using async/await ) style
 * 	of programming
 *
 */
utils.waitFor = function waitFor ( seconds ) {
	return new Promise( function ( resolve, reject ) {
		setTimeout( function () {
			resolve();
		}, seconds * 1000 );
	} );
}



/*
 *
 *
 * Get the current time and date stamp
 *	in Indian Standard Time
 *
 *	reference
 *		https://stackoverflow.com/questions/22134726/get-ist-time-in-javascript
 *
 */
utils.getDateAndTimeStamp = function getDateAndTimeStamp ( options ) {

	options = options || { };
	var ISTOffset = 330 * 60 * 1000;
	var dateObject = new Date( ( new Date() ).getTime() + ISTOffset );

	// Date components
		// Year
	var year = dateObject.getUTCFullYear();
		// Month
	var month = ( dateObject.getUTCMonth() + 1 );
	if ( month < 10 ) month = "0" + month;
		// Day
	var day = dateObject.getUTCDate();
	if ( day < 10 ) day = "0" + day;

	// Time components
		// Hours
	var hours = dateObject.getUTCHours();
	if ( hours < 10 ) hours = "0" + hours;
		// Minutes
	var minutes = dateObject.getUTCMinutes();
	if ( minutes < 10 ) minutes = "0" + minutes;
		// Seconds
	var seconds = dateObject.getUTCSeconds();
	if ( seconds < 10 ) seconds = "0" + seconds;
		// Milli-seconds
	var milliseconds = dateObject.getUTCMilliseconds();
	if ( milliseconds < 10 ) milliseconds = "00" + milliseconds;
	else if ( milliseconds < 100 ) milliseconds = "0" + milliseconds;

	// Assembling all the parts
	var datetimestamp = year
				+ "/" + month
				+ "/" + day

				+ " " + hours
				+ ":" + minutes
				+ ":" + seconds
				+ "." + milliseconds

	if ( options.separator )
		datetimestamp = datetimestamp.replace( /[\/:\.]/g, options.separator );

	return datetimestamp;

}



/*
 *
 * This opens a new page in an iframe and closes it once it has loaded
 *
 */
utils.openPageInIframe = function openPageInIframe ( url, name, options ) {

	options = options || { };
	var closeOnLoad = options.closeOnLoad || false;

	var $iframe = $( "<iframe>" );
	$iframe.attr( {
		width: 0,
		height: 0,
		title: name,
		src: url,
		style: "display:none;",
		class: "js_iframe_trac"
	} );

	$( "body" ).append( $iframe );

	if ( closeOnLoad ) {
		$( window ).one( "message", function ( event ) {
			if ( location.origin != event.originalEvent.origin )
				return;
			var message = event.originalEvent.data;
			if ( message.status == "ready" )
				setTimeout( function () { $iframe.remove() }, 19 * 1000 );
		} );
	}
	else {
		return $iframe.get( 0 );
	}

}



/*
 *
 * Set a cookie asynchronously
 *
 * @params
 * 	name -> the name of the cookie
 * 	data -> it's either an object with data that is to be encoded into the cookie, or a string that is simply to be the cookie's value with no processing whatsoever
 * 	duration -> how long before the cookie expires ( in seconds )
 *
 */
utils.setCookie = function setCookie ( name, data, duration ) {

	// 1. Prepare the cookie
	var expiryTimestamp;
	var cookieValue;

	if ( data === null )
		duration = -1;
	else
		expiryTimestamp = new Date( ( new Date() ).getTime() + duration * 1000 );

	// For the _one_ scenario where we're storing an **un-encrypted** cookie for GTM to pick up on (a customer's external ID)
	// TODO: Need to figure out a way out of this
	if ( [ "string", "number" ].indexOf( typeof data ) !== -1 )
		cookieValue = data;
	else if ( typeof data == "object" )
		cookieValue = window.Base64.encode( JSON.stringify( data ) );
	else
		throw new Error( "Please provide either a Number, String or an Object for the cookie's value." );


	// 2. Set the cookie as a first-party
	if ( data === null )
		utils.CookieJar.remove( name, { secure: true } );
	else
		utils.CookieJar.set( name, cookieValue, {
			expires: expiryTimestamp,
			secure: window.location.protocol.indexOf( "https" ) != -1
		} );

	// 3. Set the cookie as a third-party
	// 		(in case the first-party one gets flushed out)
	var apiEndpoint = __.settings.cupidApiEndpoint;
	var path = "/cookie";
	var queryString = "?";

	queryString += "name=" + encodeURIComponent( name );
	queryString += "&name=" + encodeURIComponent( duration );
	queryString += "&data=" + encodeURIComponent( data );

	var url = apiEndpoint + path + queryString;

	var $iframe = $( "<iframe>" );
	$iframe.attr( {
		width: 0,
		height: 0,
		title: "Set cookie",
		src: url,
		style: "display:none;",
		class: "js_iframe_cookie_setter"
	} );
	$( "body" ).append( $iframe );

	// Remove the iframe afterwards,
	// when we can safely that the page has been loaded and the cookie set
	setTimeout( function () {
		$iframe.remove()
	}, 5000 );

}

utils.getCookie = function getCookie ( name ) {
	var cookieString;
	var cookieData;

	try {
		cookieString = utils.CookieJar.get( name );
	}
	catch ( e ) {}

	try {
		cookieData = JSON.parse( window.Base64.decode( cookieString ) );
	}
	catch ( e ) {
		cookieData = cookieString;
	}

	return cookieData;
};



/*
 *
 * "Track" a page visit
 *
 * @params
 * 	name -> the url of the page
 *
 */
utils.trackPageVisit = function trackPageVisit ( name ) {

	/*
	 *
	 * Open a blank page and add the tracking code to it
	 *
	 */
	// Build the URL
	var baseTrackingURL = ( "/" + __.settings.trackingURL + "/" ).replace( /(\/+)/g, "/" );
	var baseURL = location.origin.replace( /\/$/, "" ) + baseTrackingURL;
	name = name.replace( /^[/]*/, "" );
	var url = baseURL + name;

	// Build the iframe
	utils.openPageInIframe( url, "", {
		closeOnLoad: true
	} );

}



/*
 *
 * "Post" a mail
 *
 * @params
 * 	subject -> the subject of the mail
 * 	body -> the body of the mail
 * 	to -> the email-address to send this to
 *
 */
utils.postMail = function postMail ( subject, body, to ) {

	var data = {
		subject,
		body,
		to
	};

	var apiEndpoint = __.settings.omegaApiEndpoint;
	var url = apiEndpoint + "/mail";

	var ajaxRequest = $.ajax( {
		url: url,
		method: "POST",
		data: data,
		dataType: "json"
	} );

	return new Promise( function ( resolve, reject ) {
		ajaxRequest.done( function ( response ) {
			resolve( response );
		} );
		ajaxRequest.fail( function ( jqXHR, textStatus, e ) {
			var errorResponse = getErrorResponse( jqXHR, textStatus, e );
			reject( errorResponse );
		} );
	} );

}



/*
 *
 * Get the unique analytics id dropped by Google Analytics
 *
 */
utils.callFunctionIfNotCalledIn = function callFunctionIfNotCalledIn ( fn, seconds ) {

	var called = false;
	var seconds = seconds || 1;

	function theFunction () {
		if ( called )
			return;
		called = true;
		return fn.apply( this, [ ].slice.call( arguments ) );
	}

	setTimeout( theFunction, seconds * 1000 );

	return theFunction;

}



/*
 *
 * Get the unique analytics id dropped by Google Analytics
 *
 */
utils.getAnalyticsId = function getAnalyticsId ( trackerName ) {

	if ( ! window.ga )
		return Promise.resolve();

	return new Promise( function ( resolve, reject ) {
		var resolvePromise = utils.callFunctionIfNotCalledIn( function ( value ) {
			if ( value )
				return resolve( value );
			else
				return resolve();
		} );
		ga( function ( defaultTracker ) {
			var tracker;
			if ( trackerName )
				tracker = ga.getByName( trackerName );
			if ( defaultTracker )
				tracker = defaultTracker;
			else
				tracker = ga.getAll()[ 0 ];

			return resolvePromise( tracker.get( "clientId" ) );
		} );
	} );

}



/*
 *
 * Handle error / exception response helper
 *
 */
utils.getErrorResponse = function getErrorResponse ( jqXHR, textStatus, e ) {
	var code = -1;
	var message;
	if ( jqXHR.responseJSON ) {
		code = jqXHR.responseJSON.code || jqXHR.responseJSON.statusCode;
		message = jqXHR.responseJSON.message;
	}
	else if ( typeof e == "object" ) {
		message = e.stack;
	}
	else {
		message = jqXHR.responseText;
	}
	var error = new Error( message );
	error.code = code;
	return error;
}



/*
 *
 * Show a notification
 *
 * This shows a notification toast with the provided message.
 *
 */
utils.notify = function notify ( message, options ) {
	alert( message );
}



/* -/-/-/-/- CODE ENDS HERE -/-/-/-/- */









}( jQuery ) );
