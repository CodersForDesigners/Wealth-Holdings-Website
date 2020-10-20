
/*
 *
 * Determines if a device's battery is **above** a given level. ( default = 20% )
 *
 */
function ifThereIsBattery ( level ) {

	level = ( level || 20 ) / 100;

	if ( ! ( "getBattery" in navigator ) )
		return Promise.resolve();

	return navigator.getBattery().then( function ( battery ) {
		if ( battery.charging || battery.level > level )
			return Promise.resolve();
		else
			return Promise.reject();
	} );

}

// Usage example
ifThereIsBattery()
	.then( function () {
		// Do things that you can do with sufficient charge
		alert( "There is sufficient charge." );
	} )
	.catch( function () {
		// Don't give the bells and whistles
		alert( "There is insufficient charge." );
	} )
