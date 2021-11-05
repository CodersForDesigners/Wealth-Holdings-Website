<?php
/**
 |
 | Response Preparation
 |
 */
# Set Headers
header_remove( 'X-Powered-By' );
header( 'Content-Type: application/json' );
