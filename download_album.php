<?php
	session_start();
	ini_set( 'max_execution_time', 0 );

	require_once( 'App/Includes/CommonFucntions.php' );
	require_once( 'App/ZipArchive/CZipArchive.class.php' );
	require_once( 'App/Shared/CFacebookApp.class.php' );

	$objFb          = new CFacebookApp();
	$objZip         = new CZipArchive();
	$strFileName    =  'Downloads/Zips/album_' . rand( 9999, 999999 ) . '.zip';

	// check if user has already logged in or not
	if( true == $objFb->checkForAccessToken() && false == getSession( 'fb_token' ) ) {
		echo json_encode( array( 'type' => 'error', 'message' => 'Please login to continue', 'url' => $objFb->getRedirectUrl() ) );
		exit;
	}

	if( true == isset( $_REQUEST['album'] ) && true == isset( $_REQUEST['album']['id'] ) ) {
		$arrAlbumDetails = array( 'id' => $_REQUEST['album']['id'], 'name' => ( true == array_key_exists( 'name', $_REQUEST['album'] ) ? $_REQUEST['album']['name'] : ''  ) );

		$objFb->getPhotoFromAlbum( $arrAlbumDetails['id'] );

		if( true == $objZip->makeZipFileFromAlbum( $objFb->m_arrmixPhotos, $strFileName, $boolIsOverwrite = false, $arrAlbumDetails ) ) {
			echo json_encode( array( 'type' => 'success', 'message' => 'Album downloaded successfully.', 'url' => $strFileName ) );
		}

		/*echo json_encode( array( 'type' => 'error', 'message' => 'Album id is invalid.' ) );
		exit;*/
	} else if( true == isset( $_REQUEST['albums'] ) && true == valArr( $_REQUEST['albums'] ) ) {
		$arrAlbums = $_REQUEST['albums'];
		$objFb->getPhotosFromAlbums( $arrAlbums );

		if( true == $objZip->makeZipFileFromAlbum( $objFb->m_arrmixPhotos, $strFileName, $boolIsOverwrite = false, $arrAlbumDetails ) ) {
			echo json_encode( array( 'type' => 'success', 'message' => 'Album downloaded successfully.', 'url' => $strFileName ) );
		}
	}





	exit;
?>
