<?php

use BFS\Router;

$redirectURL = '/faqs/'
			. ( Router::$urlSlug ?: 'introduction' );
header( 'Location: ' . $redirectURL, true, 302 );
exit;
