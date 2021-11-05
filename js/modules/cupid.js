
class Person {

	constructor () {
		this.interests = [ ]
		this.extendedAttributes = { }
		this.sourceMedium = "Website"
	}

	setPhoneNumber ( phoneNumber ) {
		if ( typeof phoneNumber === "string" )
			this.phoneNumber = phoneNumber
		return this
	}
	setEmailAddress ( emailAddress ) {
		if ( typeof emailAddress === "string" )
			this.emailAddress = emailAddress
		return this
	}
	setName ( name ) {
		if ( typeof name === "string" )
			this.name = name
		return this
	}
	setSourcePoint ( sourcePoint ) {
		// do not set this attribute if it's already been set
		// 	(this field represents the source point the user's *first ever* interaction with the website)
		if ( typeof this.sourcePoint !== "string" )
			if ( typeof sourcePoint === "string" )
				this.sourcePoint = sourcePoint
		return this
	}
	setInterests ( interests ) {
		if ( ! Array.isArray( interests ) )
			interests = [ interests ]
		// Prune out
		interests = interests
					// non-string values
					.filter( e => typeof e === "string" )
					// empty values
					.filter( e => e.trim() )
		interests = interests.concat( this.interests )
		this.interests = Array.from( new Set( interests ) )
		return this
	}
	setExtendedAttributes ( data ) {
		if ( typeof data === "object" && !Array.isArray( data ) )
			this.extendedAttributes = Object.assign( { }, this.extendedAttributes || { }, data )
		return this
	}

	hasAPhoneNumber ( phoneNumber ) {
		return !! this.phoneNumber
	}

	hasInterest ( interest ) {
		return this.interests.includes( interest )
	}
	// hasExtendedAttribute ( attributePath ) {
	// 	if ( typeof attributePath !== "string" )
	// 		return false
	// 	let pathComponents = attributePath.split( "." )
	// 	for ( let pathComponent of pathComponents ) {
	// 	}
	// }

}


class PersonLogger {}
PersonLogger.newPerson = function newPerson ( person, options ) {
	return httpRequest( "/api/person-created", "POST", person, options )
}
PersonLogger.registerInterest = function registerInterest ( person, options ) {
	return httpRequest( "/api/person-register-interest", "POST", person, options )
}
PersonLogger.submitData = function submitData ( person, options ) {
	return httpRequest( "/api/person-submitted-data", "POST", person, options )
}
// PersonLogger.recordActivity = function recordActivity ( person, options ) {
// 	return httpRequest( "/api/person-activity", "POST", person, options )
// }
// PersonLogger.isOnWebsite = function isOnWebsite ( person, options ) {
// 	return httpRequest( "/api/person-on-website", "POST", person, options )
// }
// PersonLogger.submitMessage = function submitMessage ( person, options ) {
// 	return httpRequest( "/api/person-submitted-message", "POST", person, options )
// }


class Cupid {
}
Cupid.settings = {
	client: window.__BFS.CONF.cupid.client,
	clientSlug: window.__BFS.CONF.cupid.clientSlug,
	sourceMedium: window.__BFS.CONF.cupid.sourceMedium,
	cupidApiEndpoint: window.__BFS.CONF.cupid.cupidApiEndpoint,
	trackingURL: window.__BFS.CONF.trackingURL,
	authCookieName: window.__BFS.CONF.cupid.authCookieName,
	forceLogoutIfLoggedInBefore: new Date( window.__BFS.CONF.cupid.forceLogoutIfLoggedInBefore )
};

/*
 | Person management methods
 */
Cupid.currentPerson = null
Cupid.personIsLoggedIn = function personIsLoggedIn () {
	try {
		let person = Cupid.getCurrentPersonFromCookie()
		return person instanceof Person
	}
	catch ( e ) {
		return false
	}
}
Cupid.getCurrentPersonFromCookie = function getCurrentPersonFromCookie () {
	let personDataFromCookie = CookieJar.get( Cupid.settings.authCookieName )
	if ( typeof personDataFromCookie !== "object" )
		throw new Error( "Person not found." )
	else
		return Object.assign( new Person, personDataFromCookie )
}
Cupid.getCurrentPerson = function getCurrentPerson ( phoneNumber ) {
	if ( typeof phoneNumber === "string" )
		phoneNumber = phoneNumber.trim()

	if ( Cupid.currentPerson instanceof Person && Cupid.currentPerson !== null ) {
		// do nothing
	}
	else {
		try {
			Cupid.currentPerson = Cupid.getCurrentPersonFromCookie()
		}
		catch ( e ) {
			Cupid.currentPerson = new Person
		}
	}

	// If a phone number was provided
	if ( phoneNumber ) {
		// If the existing Person already has a phone number,
		//  but the phone numbers don't match,
		//  log out the Person,
		//  and create a new one
		if ( Cupid.currentPerson.hasAPhoneNumber() ) {
			if ( Cupid.currentPerson.phoneNumber !== phoneNumber ) {
				Cupid.logCurrentPersonOut()
				Cupid.currentPerson = new Person
			}
		}
		Cupid.currentPerson.setPhoneNumber( phoneNumber )
	}

	return Cupid.currentPerson
}
Cupid.logPersonIn = function logPersonIn ( person, options ) {
	if ( !( person instanceof Person ) )
		throw new Error( "Please provide a valid `Person` object." )

	options = options || { }

	// Get the Person from the cookie
		// (the one in memory is has the latest updates that the one in the cookie does not)
	let personFromCookie
	try {
		personFromCookie = Cupid.getCurrentPersonFromCookie()
	}
	// If person *is not found*, blindly log the given / provided person in
	catch ( e ) {
		person.loginTime = ( new Date ).getTime()
		PersonLogger.newPerson( person )
		// Simulate a visit to the track URL
		if ( options.trackSlug )
			trackPageVisit( name )
		Cupid.savePerson( person )
		return
	}
	// If the person *is found*, compare the key identifying attribute(s) — the phoneNumber (in this case)
	// If the identifying attribute(s) *do not match*, log the *current Person* out and log the *given / provided Person* in
	if ( personFromCookie.phoneNumber !== person.phoneNumber ) {
		Cupid.logCurrentPersonOut()
		person = new Person
		person.loginTime = ( new Date ).getTime()
		PersonLogger.newPerson( person )
		// Simulate a visit to the track URL
		if ( options.trackSlug )
			trackPageVisit( name )
		Cupid.savePerson( person )
		return
	}
	// If the identifying attribute(s) *match*, merge and save the Person data
	else {
		Object.assign( personFromCookie, person )
		Cupid.savePerson( personFromCookie )
		return
	}
}
Cupid.savePerson = function savePerson ( person ) {
	if ( !( person instanceof Person ) )
		throw new Error( "Please provide a valid `Person` object." )

	// Persist to memory
	Cupid.person = person
	// Persist to cookie
	CookieJar.set( Cupid.settings.authCookieName, person )
}
Cupid.personLoggedInBefore = function personLoggedInBefore ( timestamp ) {
	let currentPerson = Cupid.getCurrentPerson()
	if ( typeof currentPerson.loginTime !== "number" )
		return false
	else if ( Number.isNaN( currentPerson.loginTime ) )
		return false
	else
		return currentPerson.loginTime < timestamp
}
Cupid.logCurrentPersonOut = function logCurrentPersonOut () {
	// Remove from cookie
	CookieJar.remove( Cupid.settings.authCookieName )
	// Remove from memory
	Cupid.currentPerson = null
}



// On every page
// if ( Cupid.personLoggedInBefore( Cupid.settings.forceLogoutIfLoggedInBefore ) )
// 	Cupid.logCurrentPersonOut()
