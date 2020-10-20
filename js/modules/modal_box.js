
/*
 *
 * This script deals with modals; opening modals, closing modals,
 * 	clicking on modals, escaping modals.....
 *
 */

$( function () {

	/*
	 * Open a modal when its corresponding trigger(s) are hit
	 */
	$( document ).on( "click", ".js_modal_trigger", function ( event ) {

		event.preventDefault();

		var $modalTrigger = $( event.target ).closest( ".js_modal_trigger" );
		var modalId = $modalTrigger.data( "modId" );

		$( ".js_modal_box" ).fadeIn( 350 );	// Fade in the modal box
		$( document.body ).addClass( "modal-open" );	// Freeze the page layer
		// Show the modal's content
		$( ".js_modal_box_content" )
			.filter( "[ data-mod-id = '" + modalId + "' ]" )
			.addClass( "active" );

		// Trigger hooks with the modal id
		$( document ).trigger( "modal/open", { id: modalId } );
		$( document ).trigger( "modal/open/" + modalId, { id: modalId } );

	} );


	// close the modal
	function closeModal ( event ) {

		event.stopImmediatePropagation();
		event.preventDefault();

		var $activeModal = $( ".js_modal_box_content" ).filter( ".active" );
		var modalId = $activeModal.data( "modId" );

		$( ".js_modal_box" ).fadeOut( 350 );	// Hide the modal box
		$( document.body ).removeClass( "modal-open" ); // Un-freeze the page layer
		$activeModal.removeClass( "active" );	// Hide the modal content

		var $videoEmbeds = $( event.target )
			.closest( ".js_modal_box" )
			.find( ".js_video_embed" );
		$videoEmbeds.each( function ( _i, el ) {
			unsetVideoEmbed( el );
			setVideoEmbed( el );
		} )

		// Form reset operations
		$( ".form-error" ).removeClass( "form-error" );

		// Trigger the `modal/close` hook with the modal id
		$( document ).trigger( "modal/close", { id: modalId } );
		$( document ).trigger( "modal/close/" + modalId, { id: modalId } );

	}

	// Close Modal Box,
	// on clicking the close button
	$( ".js_modal_close" ).on( "click", closeModal );
	// on hitting the escape key
	$( document ).on( "keyup", function ( event ) {

		var keyAlias = ( event.key || String.fromCharCode( event.which ) ).toLowerCase();
		var keyCode = parseInt( event.which || event.keyCode );

		if ( keyAlias == "esc" || keyAlias == "escape" || keyCode == 27 ) {
			event.preventDefault();
			closeModal( event );
		}

	} )

} );
