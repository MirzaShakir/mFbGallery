<?php
	require_once 'App/Includes/CommonFucntions.php';
	require_once 'App/Facebook/autoload.php';

	class CFacebookApp extends \Facebook\Facebook {
		const GRAPH_VERSION = 'v2.4';
		const APP_ID        = '1048936958480264';
		const APP_SECRET    = '19ed0e29c406e9ad1d7b801043fb2a63';
		const APP_URL       = 'http://localhost/GitHub/mFbGallery/user_dashboard.php';

		protected $m_arrstrPermissions = [
			'email',
			'public_profile',
			'user_friends',
			'user_about_me',
			'user_birthday',
			'user_photos'
		];

		public $m_arrmixAlbums;
		public $m_strAlbumName;
		public $m_arrmixPhotos;

		protected $m_objHelper;
		protected $m_objResponse;
		public $m_objUser;

		protected $m_strAccessToken;

		function __construct() {
			$arrmixConfig = [
				'app_id'                => self::APP_ID,
				'app_secret'            => self::APP_SECRET,
				'default_graph_version' => self::GRAPH_VERSION
			];
			parent::__construct( $arrmixConfig );
		}

		public function fetchResponse( $strRequestParams ) {
			return $this->get( $strRequestParams, getSession( 'fb_token' ) );
		}

		public function postResponse( $strRequestParams ) {
			return $this->post( $strRequestParams, getSession( 'fb_token' ) );
		}

		public function getPhotoAlbums() {
			$this->getLoggedInUserDetails();
			try{

				/*$requestFriends = $objFb->request('GET', '/me/friends?fields=id,name', array(), getSession( 'fb_token' ));
				$arrmixBatchFriends = ['user-friends' => $requestFriends,];
				$responsesFriends = $objFb->sendBatchRequest($arrmixBatchFriends, getSession( 'fb_token' ));
				foreach ($responsesFriends as $key => $response) {
					echo "Response: " . var_dump($response) . "</p>\n\n";
				}
				exit;*/
				//$objResponse= $objFb->get( '/me/albums', getSession( 'fb_token' ) );
				$this->m_arrmixAlbums = NULL;
				do {
					$strAfter = '';

					if( true == isset( $this->m_objResponse ) && true == array_key_exists( 'paging' , $this->m_objResponse->getDecodedBody() ) && true == array_key_exists( 'next' , $this->m_objResponse->getDecodedBody() ['paging'] ) ) {
						$strAfter = $this->m_objResponse->getDecodedBody()['paging']['cursors']['after'];
					}

					$this->m_objResponse = $this->fetchResponse( 'me/albums?fields=name,id,count,cover_photo{picture}&limit=5&after=' . $strAfter );
					//$this->m_objResponse = $this->fetchResponse( '/'. $this->m_objUser->getId()  .'/albums?limit=5&after=' . $strAfter );

					$objAlbums = ( array ) $this->m_objResponse->getDecodedBody() [ 'data' ];

					foreach( $objAlbums as $objAlbum ) {

						$this->m_arrmixAlbums[  $objAlbum['id'] ] =  array(
							'id' => $objAlbum['id'],
							'name' => $objAlbum['name'],
							'count' => $objAlbum['count'],
							'cover' => ( true == isset( $objAlbum['cover_photo'] )? $objAlbum['cover_photo']['picture'] : '' ),
						);
					}
				} while( true == array_key_exists( 'paging' , $this->m_objResponse->getDecodedBody() ) && true == array_key_exists( 'next' , $this->m_objResponse->getDecodedBody() ['paging'] ) );
			} catch(  Facebook\Exceptions\FacebookResponseException $objException) {
				echo 'The graph returned with error: ' . $objException->getMessage();
				exit;
			} catch( Facebook\Exceptions\FacebookSDKException $objException ) {
				echo 'The SDK returned with error: ' . $objException->getMessage();
				exit;
			}
		}

		public function getPhotoFromAlbum( $intAlbumId ) {

			if( false == valId( $intAlbumId ) ) {
				die( 'Invalid photo id passed.' );
			}

			$this->getLoggedInUserDetails();
			try{
				$this->m_arrmixPhotos = NULL;
				do {
					$strAfter = '';

					if( true == isset( $this->m_objResponse ) && true == array_key_exists( 'paging' , $this->m_objResponse->getDecodedBody() ) && true == array_key_exists( 'next' , $this->m_objResponse->getDecodedBody() ['paging'] ) ) {
						$strAfter = $this->m_objResponse->getDecodedBody()['paging']['cursors']['after'];
					}

					$this->m_objResponse = $this->fetchResponse(  $intAlbumId . '/photos?fields=name,id,images,album&limit=5&after=' . $strAfter );

					$objAlbums = ( array ) $this->m_objResponse->getDecodedBody() [ 'data' ];

					foreach( $objAlbums as $objAlbum ) {

						$this->m_arrmixPhotos[  $objAlbum['id'] ]['id'] =  $objAlbum['id'];

						if( true == array_key_exists( 'name', $objAlbum ) ) {
							$this->m_arrmixPhotos[  $objAlbum['id'] ]['name'] =  $objAlbum['name'];
						}

						if( true == array_key_exists( 'images', $objAlbum ) ) {
							$this->m_arrmixPhotos[  $objAlbum['id'] ]['image'] =  $objAlbum['images'][0]['source'];
						}

						if( true == array_key_exists( 'album', $objAlbum ) ) {
							$this->m_strAlbumName =  $objAlbum['album']['name'];
						}

						/*$this->m_arrmixAlbums[  $objAlbum['id'] ] =  array(
							'id' => $objAlbum['id'],
							'name' => $objAlbum['name']
						);*/
					}
				} while( true == array_key_exists( 'paging' , $this->m_objResponse->getDecodedBody() ) && true == array_key_exists( 'next' , $this->m_objResponse->getDecodedBody() ['paging'] ) );
			} catch(  Facebook\Exceptions\FacebookResponseException $objException) {
				echo 'The graph returned with error: ' . $objException->getMessage();
				exit;
			} catch( Facebook\Exceptions\FacebookSDKException $objException ) {
				echo 'The SDK returned with error: ' . $objException->getMessage();
				exit;
			}
		}

		public function getLoggedInUserDetails() {
			try{
				$this->m_objResponse = $this->fetchResponse( '/me?fields=id,name,picture.type(large)' );
				$this->m_objUser = $this->m_objResponse->getGraphUser();
			} catch(  Facebook\Exceptions\FacebookResponseException $objException) {
				echo 'The graph returned with error: ' . $objException->getMessage();
				exit;
			} catch( Facebook\Exceptions\FacebookSDKException $objException ) {
				echo 'The SDK returned with error: ' . $objException->getMessage();
				exit;
			}
		}

		public function removePermissionsTaken() {
			try{
				$this->m_objResponse = $this->postResponse( 'DELETE /{user-id}/permissions' );
				$this->m_objUser = $this->m_objResponse->getGraphUser();
			} catch(  Facebook\Exceptions\FacebookResponseException $objException) {
				echo 'The graph returned with error: ' . $objException->getMessage();
				exit;
			} catch( Facebook\Exceptions\FacebookSDKException $objException ) {
				echo 'The SDK returned with error: ' . $objException->getMessage();
				exit;
			}
		}

		public function setAccessTokenToSession() {
			if( true == isset( $this->m_strAccessToken ) && false == getSession( 'fb_token' ) ) {
				setSession( 'fb_token', ( string ) $this->m_strAccessToken );
			}
		}

		public function checkForAccessToken() {
			$this->setObjHelper();
			try{
				$this->m_strAccessToken = $this->m_objHelper->getAccessToken();
				$this->setAccessTokenToSession();
			} catch(  Facebook\Exceptions\FacebookResponseException $objException) {
				echo 'The graph returned with error: ' . $objException->getMessage();
				exit;
			} catch( Facebook\Exceptions\FacebookSDKException $objException ) {
				echo 'The SDK returned with error: ' . $objException->getMessage();
				exit;
			}
			return true;
		}

		public function setObjHelper() {
			if( false == isset( $this->m_objHelper ) || true == is_null( $this->m_objHelper ) ) {
				$this->m_objHelper = $this->getRedirectLoginHelper();
			}
		}

		public function getRedirectUrl() {
			$this->setObjHelper();

			return $this->m_objHelper->getLoginUrl( self::APP_URL );
		}
	}
?>