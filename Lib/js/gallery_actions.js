!function ( window, $, undefined ) {
	function mFb() {
	}

	mFb.prototype = {
		patterns: {
			getCommonBaseUrl: function () {
				return 'http://localhost/GitHub/mFbGallery/';
			},
			showLoadingImage: function ( t ) {
				if( arguments.length ) {
					if( !$.isPlainObject( arguments[0] ) ) var t = {
						strElementSelector: '#' + arguments[0]
					};
					var e = $( t.strElementSelector ), i = e.height(), n = e.width() + 'px', s = e.closest( 'div.ui-dialog' ), o = $( '<div class="loading-overlay" />' ), a = 'bg-img-medium';
					t = $.extend( {
					}, {
						boolShowOverlayOnDialog: !0
					}, t ), e.length > 0 && (parseInt( n ) < 10 && (n = '100%'), 60 > i && 0 !== i ? a = 'bg-img-small' : o.css( 'min-height', 60 ), o.addClass( a ), o.css( {
						width: n,
						height: i,
						display: 'block'
					} ).attr( 'data-content-selector', t.strElementSelector ), t.strBackgroundPosition && o.css( {
						backgroundPosition: t.strBackgroundPosition
					} ), t.strHtmlContent && o.append( t.strHtmlContent ), e.prepend( o ), t.boolShowOverlayOnDialog === !0 ? s.length > 0 && e.find( '[data-content-selector="' + t.strElementSelector + '"]' ).css( {
						height: s.innerHeight() - 40,
						width: s.innerWidth() - 10
					} ) : (o.css( 'top', '0px' ), o.css( 'left', '0px' )))
				}
			},
			removeLoadingImage: function ( t ) {
				if( arguments.length ) {
					if( !$.isPlainObject( arguments[0] ) ) var t = {
						strElementSelector: '#' + arguments[0]
					};
					$( '[data-content-selector="' + t.strElementSelector + '"]' ).remove()
				}
			},
			ajaxRequest: function ( t ) {
				if( arguments.length ) {
					var e = this, i = {
							type: 'POST',
							beforeSend: function () {
								e.showLoadingImage( t )
							},
							success: function ( i ) {
								$( t.strElementSelector ).html( i ), e.removeLoadingImage( t ), e.bindEssentials()
							},
							error: function ( e, i, n ) {
								if( $( t.strElementSelector ).find( '.loading-overlay' ).length ) {
									var s = '<p class="alert error slim"><i></i>Oops! Something went wrong. ( ' + n + ' - ' + e.status + ' )</p>';
									$( t.strElementSelector ).html( s )
								}
							}
						};
					if( !$.isPlainObject( arguments[0] ) ) {
						var t = {
							url: arguments[1],
							strElementSelector: '#' + arguments[0]
						};
						t = $.extend( {
						}, t, arguments[2] || {
						} )
					}
					'string' == typeof t.beforeSend && (t.beforeSend = t.beforeSend.indexOf( '.' ) > 0 ? new Function( 'return ' + t.beforeSend )() : window[t.beforeSend]), 'string' == typeof t.success && (t.success = t.success.indexOf( '.' ) > 0 ? new Function( 'return ' + t.success )() : window[t.success]), 'string' == typeof t.complete && (t.complete = t.complete.indexOf( '.' ) > 0 ? new Function( 'return ' + t.complete )() : window[t.complete]), t = $.extend( {
					}, i, t ), $.ajax( t ), 'ga' === window.GoogleAnalyticsObject && 'function' == typeof window.ga && window.ga( 'send', 'pageview', {
						page: t.url
					} )
				}
			}
		}
	}, window.mfb = new mFb, window.getCommonBaseUrl = mfb.patterns.getCommonBaseUrl, window.showLoadingImage = mfb.patterns.showLoadingImage, window.removeLoadingImage = mfb.patterns.removeLoadingImage, window.ajaxRequest = mfb.patterns.ajaxRequest
}( window, jQuery );

mfb.fnCookies = (function ( $ ) {
	function setCookie( name, value, expires, path, domain, secure ) {
		var curCookie = name + '=' + escape( value ) + ((expires) ? '; expires=' + expires.toGMTString() : '') + ((path) ? '; path=' + path : '') + ((domain) ? '; domain=' + domain : '') + ((secure) ? '; secure' : '');
		document.cookie = curCookie;
	}

	function getCookie( name ) {
		var dc = document.cookie;
		var prefix = name + '=';
		var begin = dc.indexOf( '; ' + prefix );
		if( begin == -1 ) {
			begin = dc.indexOf( prefix );
			if( begin != 0 ) return null;
		}
		else
			begin += 2;
		var end = document.cookie.indexOf( ';', begin );
		if( end == -1 )
			end = dc.length;
		return unescape( dc.substring( begin + prefix.length, end ) );
	}

	function deleteCookie( name, path, domain ) {
		if( mfb.fnCookies.getCookie( name ) ) {
			document.cookie = name + '=' + ((path) ? '; path=' + path : '') + ((domain) ? '; domain=' + domain : '') + '; expires=Thu, 01-Jan-70 00:00:01 GMT';
		}//endif
	}//end deleteCookie

	return {
		setCookie: setCookie,
		getCookie: getCookie,
		deleteCookie: deleteCookie
	};
})( jQuery );

mfb.fbFunctions = (function ( $ ) {
	function showAlertMessage( status, referenceDiv, message ) {
		referenceDiv.find( 'div.alert' ).remove();
		$( '<div class=\'alert alert-' + status + '\'><i></i>' + message + '</div>' ).prependTo( referenceDiv ).fadeIn().animate( {
				opacity: 1.0
			}, 9999 ).fadeOut( 500, function () {
				$( this ).remove();
			} );
	}

	function initGalleryActions( $objMainDiv ) {
		var $thisRef = this;

		$objMainDiv.on( 'click', '.js-btn-select-deselect', function() {
			var $this = $(this);
			if( 'true' == $this.data('is-checked') ) {
				$this.removeClass( 'is-checked' ).data( 'is-checked', 'false' );
			} else {
				$this.addClass( 'is-checked' ).data( 'is-checked', 'true' );
			}

			$objMainDiv.find( '.js-download-selected-photos, .js-download-selected-albums' ).addClass( 'hide' );
			if( 0 < $objMainDiv.find( '.js-btn-select-deselect.is-checked' ).length ) {
				$objMainDiv.find( '.js-download-selected-photos, .js-download-selected-albums' ).removeClass( 'hide' );
			}
		});

		$objMainDiv.on( 'click', '.js-download-selected-photos', function () {
			var $this = $( this ), arrPhotos = [];

			$objMainDiv.find( '.js-btn-select-deselect.is-checked' ).each( function() {
				arrPhotos.push({ 'image' : $(this).data('image'), 'name' : $(this).data('name') });
			});

			mfb.patterns.ajaxRequest( {
				strElementSelector: $objMainDiv,
				url: mfb.patterns.getCommonBaseUrl() + 'download_photo.php',
				dataType: 'json',
				data: {
					'photos': arrPhotos
				},
				success: function ( result ) {
					mfb.patterns.removeLoadingImage( {
						strElementSelector: $objMainDiv
					} );
					if( 'success' == result.type ) {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
						window.location = result.url;
					} else {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
					}
				}
			} );
		} );


		$objMainDiv.on( 'click', '.js-download-selected-albums', function () {
			var $this = $( this ), arrAlbums = [];

			$objMainDiv.find( '.js-btn-select-deselect.is-checked' ).each( function() {
				arrAlbums.push({ 'id' : $(this).data('album-id'), 'name' : $(this).data('name') });
			});

			mfb.patterns.ajaxRequest( {
				strElementSelector: $objMainDiv,
				url: mfb.patterns.getCommonBaseUrl() + 'download_album.php',
				dataType: 'json',
				data: {
					'albums': arrAlbums
				},
				success: function ( result ) {
					mfb.patterns.removeLoadingImage( {
						strElementSelector: $objMainDiv
					} );
					if( 'success' == result.type ) {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
						window.location = result.url;
					} else {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
					}
				}
			} );
		} );

		$objMainDiv.on( 'click', '.js-btn-download-one', function () {
			var $this = $( this ), strPhotoUrl = $this.data( 'image' ), strPhotoName = $this.data( 'name' );

			mfb.patterns.ajaxRequest( {
				strElementSelector: $objMainDiv,
				url: mfb.patterns.getCommonBaseUrl() + 'download_photo.php',
				dataType: 'json',
				data: {
					'photo[image]': strPhotoUrl,
					'photo[name]': strPhotoName
				},
				success: function ( result ) {
					mfb.patterns.removeLoadingImage( {
						strElementSelector: $objMainDiv
					} );

					if( 'success' == result.type ) {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
						window.location = result.url;
					} else {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
					}
				}
			} );
		} );

		$objMainDiv.on( 'click', '.js-btn-download-album', function () {
			var $this = $( this ), intAlbumId = $this.data( 'album-id' ), strAlbumName = $this.data( 'name' );

			mfb.patterns.ajaxRequest( {
				strElementSelector: $objMainDiv,
				url: mfb.patterns.getCommonBaseUrl() + 'download_album.php',
				dataType: 'json',
				data: {
					'album[id]': intAlbumId,
					'album[name]': strAlbumName
				},
				success: function ( result ) {
					mfb.patterns.removeLoadingImage( {
						strElementSelector: $objMainDiv
					} );

					if( 'success' == result.type ) {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
						window.location = result.url;
					} else {
						$thisRef.showAlertMessage( result.type, $objMainDiv, result.message );
					}
				}
			} );
		} );
	}

	return {
		showAlertMessage: showAlertMessage,
		initGalleryActions: initGalleryActions
	};
})( jQuery );