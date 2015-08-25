<?php
	error_reporting( E_ALL );
	ini_set( 'max_execution_time', 0 );

	require_once 'App/Includes/CommonFucntions.php';
	require_once 'App/ZipArchive/CZipArchive.class.php';

	$objZip = new CZipArchive();
	$arrmixFiles    = array();
	$strFileName    =  'Downloads/Zips/archive_' . rand( 9999, 999999 ) . '.zip';

	if( true == isset( $_REQUEST['photos'] ) && true == valArr( $_REQUEST['photos'] ) ) {
		$arrmixFiles = $_REQUEST['photos'];
		if( true == $objZip->makeZipFile( $arrmixFiles, $strFileName ) ) {
			echo json_encode( array( 'type' => 'success', 'message' => 'Photos downloaded successfully!!!', 'url' => $strFileName ) );
			exit;
		}
	}

	if( false == isset( $_REQUEST['photo'] ) || false == isset( $_REQUEST['photo']['name'] )  || false == isset( $_REQUEST['photo']['image'] ) ) {
		echo json_encode( array( 'type' => 'error', 'message' => 'Unable to download photo, please try again.' ) );
		exit;
	}

	$arrmixFiles    = $_REQUEST['photo'];

	if( true == $objZip->makeZipFile( $arrmixFiles, $strFileName ) ) {
		echo json_encode( array( 'type' => 'success', 'message' => 'Photo downloaded successfully!!!', 'url' => $strFileName ) );
	} else{
		echo json_encode( array( 'type' => 'error', 'message' => 'Unable to download photo, please try again.' ) );
	}
	exit;
?>
