<?php
	session_start();
	error_reporting( E_ALL );
	require_once( 'App/Includes/CommonFucntions.php' );
	require_once( 'App/ZipArchive/CZipArchive.class.php' );
	require_once( 'App/Shared/CFacebookApp.class.php' );

	$objFb  = new CFacebookApp();
	$objZip = new CZipArchive();

	if( false == isset( $_REQUEST['album'] ) || false == isset( $_REQUEST['album']['id'] ) ) {
		return 'Hey!!, please specify album id to download';
	}
	$intAlbumId     = $_REQUEST['album']['id'];
	$strFileName    =  'Downloads/Zips/album_' . rand( 9999, 999999 ) . '.zip';

	// check if user has already logged in or not
	if( true == $objFb->checkForAccessToken() && false == getSession( 'fb_token' ) ) {
		header( 'Location: ' . $objFb->getRedirectUrl() );
	}
	$objFb->getPhotoFromAlbum( $intAlbumId );

	if( true == $objZip->makeZipFileFromAlbum( $objFb->m_arrmixPhotos, $strFileName ) ) {
		/*header( 'Content-Type: application/zip' );
		header( "Content-Disposition: attachment; filename = $strFileName" );
		header( 'Content-Length: ' . filesize( $strFileName ) );
		header( "Location: $strFileName" );
		header("Content-Transfer-Encoding: binary");
		readfile($strFileName);*/
		echo ($strFileName);
		exit;
	} else {
		echo( 'file not created' );
	}
?>
