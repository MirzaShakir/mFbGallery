<?php
	session_start();

	require_once __DIR__ . '/App/Shared/CFacebookApp.class.php';
	$objFb = new CFacebookApp();

	// check if user has already logged in or not
	if( true == $objFb->checkForAccessToken() || true == getSession( 'fb_token' ) ) {
		$objFb->revokePermissionsTaken();
	}

	session_destroy();
	header( 'Location: index.php' );
?>