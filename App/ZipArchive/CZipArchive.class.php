<?php
	require_once( 'App/ZipArchive/pclzip.lib.php' );

	class CZipArchive {
		protected $m_arrmixValidFiles;

		function __construct() {
			return true;
		}

		public function makeZipFile( $arrmixFiles, $strDestination, $boolIsOverwrite = false ) {

			try {
				//Check if file is exist already!!!
				if( true == file_exists( $strDestination ) && false == $boolIsOverwrite ) {
					echo( 'already exist' );

					return false;
				}

				$this->m_arrmixValidFiles = array();

				/*if( true == is_array( $arrmixFiles ) ) {
					foreach( $arrmixFiles as $strOneFile ) {
						if( true == $strOneFile ) {
							$this->m_arrmixValidFiles[] = $strOneFile;
						}
					}
				}*/

				$this->m_arrmixValidFiles = $arrmixFiles;

				if( 0 >= count( $this->m_arrmixValidFiles ) ) {
					echo( 'no files found' );

					return false;
				}

				$objZip = new PclZip( $strDestination );
				/*if( true !== $objZip->create( $strDestination, $boolIsOverwrite ? ZipArchive::OVERWRITE : ZipArchive::CREATE ) ) {
					echo( 'Zip cannot created' );
					return false;
				}*/

				/*foreach( $this->m_arrmixValidFiles as $strOneFile ) {*/
				$strFile = file_get_contents( $this->m_arrmixValidFiles['url'] );
				$arrSize = getimagesize( $this->m_arrmixValidFiles['url'] );

				$objZip->create( array(
				                      array(
					                      PCLZIP_ATT_FILE_NAME    => $this->m_arrmixValidFiles['name'] . '.' . image_type_to_extension( $arrSize[2] ),
					                      PCLZIP_ATT_FILE_CONTENT => $strFile,
				                      )
				                 ) );
				$objZip->add( $strFile );

				/*}*/

				return file_exists( $strDestination );
			} catch( Exception $objException ) {
				die( $objException->getMessage() );
			}
		}

		public function makeZipFileFromAlbum( $arrmixFiles, $strDestination, $boolIsOverwrite = false ) {

			try {
				//Check if file is exist already!!!
				if( true == file_exists( $strDestination ) && false == $boolIsOverwrite ) {
					echo( 'file already exist. ' );

					return false;
				}

				if( 0 >= count( $arrmixFiles ) ) {
					echo( 'no files found' );
					return false;
				}

				$objZip = new PclZip( $strDestination );

				if( true == is_array( $arrmixFiles ) ) {
					foreach( $arrmixFiles as $arrFile ) {
						$objImage = file_get_contents( $arrFile['image'] );
						$arrSize  = getimagesize( $arrFile['image'] );
						$strName  = substr( $arrFile['name'], 0, 15 );

						$objArray[] = array(
							PCLZIP_ATT_FILE_NAME    => $strName . '.' . image_type_to_extension( $arrSize[2] ),
							PCLZIP_ATT_FILE_CONTENT => $objImage,
						);

						/*$objZip->create( array(
						                      array(
							                      PCLZIP_ATT_FILE_NAME    => $strName . '.' . image_type_to_extension( $arrSize[2] ),
							                      PCLZIP_ATT_FILE_CONTENT => $objImage,
						                      )
						                 ) );*/
						//$objZip->add( $objImage );
						$this->m_arrmixValidFiles[] = $objImage;
					}
					$objZip->create( $objArray );
					$objZip->add( $this->m_arrmixValidFiles );
				}
				return file_exists( $strDestination );
			} catch( Exception $objException ) {
				die( $objException->getMessage() );
			}
		}
	}