
$( function () {





/*
 * ----- Hold some references
 */
var $shareDialog = $( ".js_modal_box_content[ data-mod-id = 'share' ]" );

/*
 * ---- Show the "More" option on the Share dialog if the device supports the Web Share API
 */
let $moreOption = $shareDialog.find( ".js_share_more_options" );
if ( navigator.share )
	$moreOption.removeClass( "hidden" );

$moreOption.on( "click", function ( event ) {
	var parameters = getSharingParameters();
	var title = parameters.title;
	var text = parameters.description;
	var url = parameters.url;
	return navigator.share( {
		title: title,
		text: text,
		url: url
	} );
} );



// When the share modals are triggered
$( document ).on( "modal/open/share", function ( event, data ) {
	// Get the share parameters
	var $trigger = data.$trigger
	var $shareable = $trigger.closest( ".js_shareable" );
	var shareParameters = getSharingParameters( $shareable.get( 0 ) );

	// Set the URL
	$shareDialog.find( ".js_url" ).val( shareParameters.url );
	$shareDialog.find( ".js_url_hidden" ).text( shareParameters.url )

	// Set the share paramters on all the share options
	var $shareOptions = $shareDialog.find( ".js_social_medium" );
	$shareOptions.each( function ( _i, domEl ) {
		var parameter;
		for ( parameter in shareParameters )
			domEl.setAttribute( "data-" + parameter, shareParameters[ parameter ] );
	} );
	window._goodshare.reNewAllInstance();
} );


/*
 * ---- Copy the URL when the "COPY" button is clicked
 */
$shareDialog.on( "click", ".js_copy_url", function ( event ) {

	event.preventDefault();

	var $copyButton = $( event.target );
	var $feedbackMessage = $shareDialog.find( ".js_copy_feedback" );

	// Prevent the copy button from clicked on again for the next second
	$copyButton.prop( "disabled", true );
	setTimeout( function () {
		$copyButton.prop( "disabled", false );
		domUrl.blur();
		// Hide the "Copied to Clipboard" feedback message
		$feedbackMessage.removeClass( "show" );
	}, 1000 );


	var domUrl = $shareDialog.find( ".js_url_hidden" ).get( 0 );
	domUrl.select();

	try {
		document.execCommand( "copy" );

		// Show the "Copied to Clipboard" feedback message
		$feedbackMessage.addClass( "show" );
	}
	catch ( e ) {}

} );



/*
 * ---- Highlight the URL when the URL is simply clicked on
 */
$shareDialog.on( "click", ".js_url", function ( event ) {

	var domUrl = event.target;
	domUrl.select();

} )



function getSharingParameters ( domShareTrigger ) {
	var title
	var description
	var image
	var url

	domShareTrigger = domShareTrigger || { dataset: { } }

	title = domShareTrigger.dataset.title || document.getElementsByTagName( "title" )[ 0 ].innerText

	description = domShareTrigger.dataset.description
	if ( ! description ) {
		var domMetaTags = document.getElementsByTagName( "meta" )
		var domMetaTag
		for ( domMetaTag of domMetaTags )
			if ( domMetaTag.getAttribute( "name" ) === "description" )
				description = domMetaTag.getAttribute( "content" )
	}

	image = domShareTrigger.dataset.image
	if ( ! image ) {
		var domLinkTags = document.getElementsByTagName( "link" )
		var domLinkTag
		for ( domLinkTag of domLinkTags )
			if ( domLinkTag.getAttribute( "rel" ) === "apple-touch-icon" )
				image = domLinkTag.getAttribute( "href" )
	}

	url = domShareTrigger.dataset.url || window.location.href;

	return {
		title: title,
		description: description,
		image: image,
		url: url
	};
}





} );
