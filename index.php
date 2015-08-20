<?php
	session_start();
	require_once __DIR__ . '/App/Shared/CFacebookApp.class.php';
	$objFb = new CFacebookApp();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php require_once('App/Includes/header_start.php'); ?>
    <title>mFbGallery : Home</title>
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
    <a class="navbar-brand" href="user_dashboard.html">mFbGallery</a>
</div>
</nav>
<!--/. NAV TOP  -->
<nav class="navbar-default navbar-side hide" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li>
                <a href="user_dashboard.html"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a href="user_photos.html"><i class="fa fa-desktop"></i> mPhoto Albums</a>
            </li>
            <li>
                <a class="active-menu" href="index.html"><i class="fa fa-fw fa-file"></i> Login</a>
            </li>
        </ul>
    </div>
</nav>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    Fecebook gallery for photos.
                </h1>
	            <a target="_self" href="<?php echo $objFb->getRedirectUrl() ?>">
		            <button class="btn btn-default btn-sm">Login to Facebook</button>
		        </a>
            </div>
        </div>
        <!-- /. ROW  -->
	    <footer>
		    <!-- <p>All right reserved. Template by: <a href="http://webthemez.com">WebThemez</a></p> -->
	    </footer>
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->
<?php require_once('App/Includes/footer_start.php'); ?>
</body>
</html>
 