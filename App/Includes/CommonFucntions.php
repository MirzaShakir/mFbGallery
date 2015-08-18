<?php

	const APP_ID_ENV_NAME = 'FACEBOOK_APP_ID';
	define( 'mGALLERY', 'mgallery' );
	const FB_APP_ID        = '1048936958480264';
	const FB_APP_SECRET    = '19ed0e29c406e9ad1d7b801043fb2a63';
	const FB_GRAPH_VERSION = 'v2.4';

	function getobjFb() {
		return $objFb = new \Facebook\Facebook( [
		                                        'app_id'                => '1048936958480264',
		                                        'app_secret'            => '19ed0e29c406e9ad1d7b801043fb2a63',
		                                        'default_graph_version' => 'v2.4'
		                                        ] );
	}

	function getSession( $strSessionKey ) {
		if( PHP_SESSION_DISABLED !== session_status() ) {
			return $_SESSION[mGALLERY][$strSessionKey];
		}

		return false;
	}

	function setSession( $strSessionKey, $strSessionValue ) {
		if( PHP_SESSION_DISABLED !== session_status() ) {
			$_SESSION[mGALLERY][$strSessionKey] = $strSessionValue;
		}
	}

	function display( $arrmixValues, $boolIsExit = false ) {
		if( true == valArr( $arrmixValues ) ) {
			echo '<pre>';
			print_r( $arrmixValues );
			echo '</pre>';
		}

		if( true == $boolIsExit ) {
			exit;
		}
	}

	function valFile( $strFile, $intFileSize = 1 ) {
		return ( true == file_exists( $strFile ) && $intFileSize <= filesize( $strFile ) ) ? true : false;
	}

	function valStr( $strString, $intLen = 1 ) {
		$strString = ( false == valArr( $strString ) ) ? trim( ( string ) $strString ) : NULL;

		return ( true == isset( $strString[0] ) && $intLen <= strlen( $strString ) ) ? true : false;
	}

	function valArr( $arrmixValues, $intCount = 1, $boolCheckForEquality = false ) {
		$boolIsValid = ( true == is_array( $arrmixValues ) && $intCount <= count( $arrmixValues ) ) ? true : false;
		if( true == $boolCheckForEquality && true == $boolIsValid ) $boolIsValid = ( $intCount == count( $arrmixValues ) ) ? true : false;

		return $boolIsValid;
	}

	function valArrKeyExists( $arrmixHaystack, $strNeedle, $strValue = NULL ) {
		$boolIsValid = ( true == valArr( $arrmixHaystack ) && true == array_key_exists( $strNeedle, $arrmixHaystack ) ) ? true : false;

		if( true == $boolIsValid && NULL !== $strValue ) {
			$boolIsValid &= ( ( string ) $strValue === ( string ) $arrmixHaystack[$strNeedle] ) ? true : false;
		}

		return $boolIsValid;
	}

	function valId( $strMixVal ) {
		return ( 0 == ( int ) $strMixVal || false == is_int( ( int ) $strMixVal ) ? false : true );
	}
