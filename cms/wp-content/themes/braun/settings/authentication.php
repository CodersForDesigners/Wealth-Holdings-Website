<?php

/*
 |
 | Authentication
 |
 | Only allow users with the `edit_user_auth` capability to edit their multi-factor authentication settings
 |
 */

add_action( 'bfs/init/backend', function () {

	/*
	 |
	 | - Only allow users with the `edit_user_auth` capability to edit their multi-factor authentication settings
	 |
	 */
	if ( defined( 'IS_PROFILE_PAGE' ) and IS_PROFILE_PAGE )
		if ( ! current_user_can( 'edit_user_auth' ) )
			remove_action( 'show_user_profile', [ Two_Factor_Core::class, 'user_two_factor_options' ] );

} );
