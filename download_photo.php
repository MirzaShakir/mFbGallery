<?php
	error_reporting( E_ALL );
	require_once 'App/Includes/CommonFucntions.php';
	require_once( 'App/ZipArchive/CZipArchive.class.php' );

	$objZip = new CZipArchive();

	if( false == isset( $_REQUEST['photo'] ) || false == isset( $_REQUEST['photo']['name'] )  || false == isset( $_REQUEST['photo']['url'] ) ) {
		return 'Hey!!, please specify image url';
	}

	$strUrl         = $_REQUEST['photo']['url'];
	$strName        = substr( $_REQUEST['photo']['name'], 0, 15 );
	$strFileName    =  'Downloads/Zips/archive_' . rand( 9999, 999999 ) . '.zip';
	$arrmixFiles    = array( 'name' => $strName, 'url' => $strUrl );

	if( true == $objZip->makeZipFile( $arrmixFiles, $strFileName ) ) {
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
