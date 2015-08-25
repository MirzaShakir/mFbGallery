<?php
	session_start();
	require_once __DIR__ . '/App/Shared/CFacebookApp.class.php';
	$objFb = new CFacebookApp();

	// check if user has already logged in or not
	if( true == $objFb->checkForAccessToken() && false == getSession( 'fb_token' ) ) {
		header( 'Location: ' . $objFb->getRedirectUrl() );
	}

	$objFb->getPhotoAlbums();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php require_once('App/Includes/header_start.php'); ?>
    <title>mFbGallery : Albums</title>
    <?php require_once('App/Includes/header_end.php'); ?>
</head>
<body>
<div id="wrapper">
<nav class="navbar navbar-default top-navbar" role="navigation">
    <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="user_dashboard.php">mFbGallery</a>
</div>

<ul class="nav navbar-top-links navbar-right">
		<!-- /.dropdown -->
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
				<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="#"><i class="fa fa-user fa-fw"></i> <?php echo $objFb->m_objUser->getName(); ?></a></li>
				<!--<li><i class="fa fa-gear fa-fw"></i> <?php /*echo $objFb->m_objUser->getLocation(); */?> </li>-->
				<li class="divider"></li>
				<li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
			</ul>
			<!-- /.dropdown-user -->
		</li>
		<!-- /.dropdown -->
	</ul>
</nav>
<!--/. NAV TOP  -->
<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li>
                <a href="user_dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a class="active-menu" href="user_photos.php"><i class="fa fa-desktop"></i> mPhotos</a>
            </li>
            <li class="hide">
                <a href="user_friends.php"><i class="fa fa-fw fa-file"></i> mFriends</a>
            </li>
        </ul>
    </div>
</nav>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-header">Photo albums</h3>
            </div>
        </div>

        <div class="row">
            <?php
                $arrstrColors = array( 'green', 'blue', 'red', 'brown' );

                foreach( $objFb->m_arrmixAlbums as $arrmixAlbum ) {
                    $intColorId = array_rand( $arrstrColors, 1);
                    ?>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="panel panel-primary text-center no-boder bg-color-<?php echo $arrstrColors[ $intColorId ] ?>">
                                <div class="panel-footer back-footer-<?php echo $arrstrColors[ $intColorId ] ?>">
                                    <a class="thumbnail margin5-bottom" href="user_album.php?album[id]=<?php echo $arrmixAlbum['id'] ?>">
                                        <img class="js-img-thumbnail-preview img-responsive" alt="No preview" src="<?php echo $arrmixAlbum['cover'] ?>">
	                                </a>
	                                <a class="noref" href="user_album.php?album[id]=<?php echo $arrmixAlbum['id'] ?>"><span class="trunc"><?php echo substr( $arrmixAlbum['name'], 0, 12 ); ?> (<?php echo $arrmixAlbum['count'] ?>) </span></a>
	                                <div class="clearfix"></div>
	                                <button data-album-id="<?php echo $arrmixAlbum['id'] ?>" data-name="<?php echo $arrmixAlbum['name'] ?>" class="btn btn-default btn-sm js-btn-download-album"><i class="fa fa-download fa-1x"></i></button>
	                                <!--<a  class=" btn btn-default btn-sm right navbar-default back-footer-<?php /*echo $arrstrColors[ $intColorId ] */?>" data-url="<?php /*echo $arrmixAlbum['cover'] */?>"><i class="fa fa-download fa-2x"></i></a>-->
	                                <button class="btn btn-default btn-sm js-btn-select-deselect" data-is-checked="true" data-album-id="<?php echo $arrmixAlbum['id'] ?>" data-name="<?php echo $arrmixAlbum['name'] ?>">
		                                <i class="fa fa-check"></i>
	                                </button>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>
	    <div>
		    <button class="btn btn-default js-download-selected-albums hide"><i class="fa fa-download fa-2x"></i> Download selected albums</button>
	    </div>

        <!-- /. ROW  -->
        <footer><p>Developed by: <a target="_blank" href="http://smarterer.com/baigmirzashakir"> MirzaShakir Baig</a></p></footer>
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->

<div class="modal fade" id="js-modal-image-preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>
<!-- /. WRAPPER  -->
<?php require_once('App/Includes/footer_start.php'); ?>
<script type="text/javascript">
	mfb.fbFunctions.initGalleryActions( $('#page-wrapper') );
</script>
</body>
</html>
