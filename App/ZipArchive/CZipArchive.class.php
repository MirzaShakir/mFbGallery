<?php
	require_once( __DIR__ . '/pclzip.lib.php' );

	class CZipArchive {
		protected $m_arrmixValidFiles;

		function __construct() {
			return true;
		}

		public function getImageExtension( $strImageUrl ) {
			if( false == is_null( $strImageUrl ) ) {
				$arrImageDiamensions = getimagesize( $strImageUrl );
				return image_type_to_extension( $arrImageDiamensions[ 2 ] );
			}
		}

		public function makeZipFile( $arrmixFiles, $strDestination, $boolIsOverwrite = false ) {

			try {
				//Check if file is exist already!!!
				if( true == file_exists( $strDestination ) && false == $boolIsOverwrite ) {
					echo( 'already exist' );

					return false;
				}

				if( false == valArr( $arrmixFiles ) ) {
					echo( 'no files found' );
					return false;
				}

				$objZip = new PclZip( $strDestination );
				$this->m_arrmixValidFiles = array();
				$objImageArray = array();

				foreach( $arrmixFiles as $intKey => $arrFile ) {

					if( true == isset( $arrFile ) && valArr( $arrFile ) ) {
						if( true == array_key_exists( 'image', $arrFile ) && true == array_key_exists( 'name', $arrFile ) ) {
							$objImage = file_get_contents( $arrmixFiles[ $intKey ]['image'] );
							$strName  = substr( $arrmixFiles[ $intKey ]['name'], 0, 15 );

							if( '' == $strName || true == is_null( $strName ) ) {
								$strName = rand( 9999, 999999 );
							}

							$objImageArray[] = array(
								PCLZIP_ATT_FILE_NAME    => $strName . '.' . $this->getImageExtension( $arrmixFiles[ $intKey ][ 'image' ] ),
								PCLZIP_ATT_FILE_CONTENT => $objImage,
							);

							$this->m_arrmixValidFiles[] = $objImage;
						}
					}
				}

				$objZip->create( $objImageArray );
				$objZip->add( $this->m_arrmixValidFiles );

				return file_exists( $strDestination );
			} catch( Exception $objException ) {
				die( $objException->getMessage() );
			}
		}

		public function makeZipFileFromAlbum( $arrmixFiles, $strDestination, $boolIsOverwrite = false, $arrAlbumDetails ) {

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

						if( '' == $strName || true == is_null( $strName ) ) {
							$strName = rand( 9999, 999999 );
						}

						$objImageArray[] = array(
							PCLZIP_ATT_FILE_NAME    => $strName . '.' . image_type_to_extension( $arrSize[2] ),
							PCLZIP_ATT_FILE_CONTENT => $objImage,
						);

						$this->m_arrmixValidFiles[] = $objImage;
					}
					$objZip->create( $objImageArray );
					$objZip->add( $this->m_arrmixValidFiles );
				}
				return file_exists( $strDestination );
			} catch( Exception $objException ) {
				die( $objException->getMessage() );
			}
		}
	}