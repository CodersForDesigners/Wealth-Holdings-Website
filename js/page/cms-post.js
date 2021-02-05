
/*
 *
 * ----- FORMS
 *
 */
$( function () {

if ( ! $( ".js_forms_container" ).length )
	return;





$( document ).on( "submit", ".js_primary_form", function ( event ) {

	/*
	 * Cache references to frequently used DOM nodes
	 */
	var $form = $( event.target );
		// We're skipping over the core fields that will be handled by Cupid's login flows
	var $fields = $form.find( "[ name ^= 'form' ]" );
	var $submitButton = $form.find( "[ type = 'submit' ]" );
	var $feedbackMessage = $form.find( ".js_form_feedback" );
	var __ = window.__CUPID;

	// Disable relevant portions of the forms
	disableForm( $form );
	$fields.prop( "disabled", true );
	$submitButton.prop( "disabled", true );

	// Pre-prepare the feedback message in the event of a successful form submission
	var successFeedbackMessageTemplate = $form.data( "feedback" );
	var successFeedbackMessage = window.__BFS.renderTemplate( successFeedbackMessageTemplate, __.user );
	// Set the feedback message
	$feedbackMessage.html( "<br>" + successFeedbackMessage );

	var formContext = ( $form.data( "context" ) || "" ).trim();


	/*
	 * ----- If the form has no other fields,
	 *			besides the core ones,
	 *			and we're to simply to register interest
	 *			capturing whatever context the form is placed in.
	 */
	if ( ! $fields.length ) {
		provideFeedbackToTheUser( $submitButton, $feedbackMessage );
		// Register interests and update the user record
		__.user.isInterestedIn( formContext );
		__.user.update();
		return;
	}

	// Capture the form field values
	var validationIssues = [ ];
	var interests = [ ];
	Array.prototype.slice.call( $fields ).every( function ( el ) {
		var $field = $( el );
		var label = $field.data( "label" );
		var value = $field.val().trim();
		if ( value.length < 1 ) {
			validationIssues.push( label );
			return false;
		}
		if ( formContext.length )
			interests.push( formContext + ": " + label + ": " + value );
		else
			interests.push( label + ": " + value );
		return true;
	} );

	// Abort if validation issues are present
	if ( validationIssues.length > 0 ) {
		$fields.prop( "disabled", false );
		var feedbackMessage = "Kindly provide valid input for the following field(s):\n";
		feedbackMessage += validationIssues.join( "\n" );
		alert( feedbackMessage );
		$fields.prop( "disabled", false );
		return;
	}

	// Set the feedback message
	$feedbackMessage.html( "<br>" + successFeedbackMessage );

	provideFeedbackToTheUser( $submitButton, $feedbackMessage );

	// Register interests and update the user record
	__.user.isInterestedIn( interests );
	__.user.update();

} );


function provideFeedbackToTheUser ( $submitButton, $feedbackMessage ) {
	// Hide the submit button
	$submitButton.parent().slideUp( 250, function () {
		// Initiate the process to present the feedback message. ( Yes, it is quite an involved process. )
		$feedbackMessage.slideDown( 250, function () {
			// The form's height is explicitly set. This is form certain transitions to work.
			__BFS.utils.measureAndSetFormHeights();
			// Fade in the message
			$feedbackMessage.css( { opacity: 1 } );
		} );
	} );
}





} );
