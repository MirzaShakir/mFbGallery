<?php
session_start();
require_once __DIR__ . '/App/Includes/CommonFucntions.php';
require_once __DIR__ . '/App/Facebook/autoload.php';

$objFb = getobjFb();

$objHelper = $objFb->getRedirectLoginHelper();

try{
    $strAccessToken = $objHelper->getAccessToken();
} catch(  Facebook\Exceptions\FacebookResponseException $objException) {
    echo 'The graph returned with error: ' . $objException->getMessage();
    //exit;
} catch( Facebook\Exceptions\FacebookSDKException $objException ) {
    echo 'The SDK returned with error: ' . $objException->getMessage();
    //exit;
}

// check if user has already logged in or not
if( false == isset( $strAccessToken ) && false == getSession( 'fb_token') ) {
    $strLoginUrl = $objHelper->getLoginUrl( 'http://localhost/GitHub/mFbGallery/user_dashboard.php' );
    echo "<a href='" . $strLoginUrl . "'>Login to Facebook</a>";
    exit;
}
if( true == isset( $strAccessToken ) && false == getSession( 'fb_token') ) {
    setSession( 'fb_token', ( string ) $strAccessToken );
}

try{

    /*$requestFriends = $objFb->request('GET', '/me/friends?fields=id,name', array(), getSession( 'fb_token' ));
    $arrmixBatchFriends = ['user-friends' => $requestFriends,];
    $responsesFriends = $objFb->sendBatchRequest($arrmixBatchFriends, getSession( 'fb_token' ));
    foreach ($responsesFriends as $key => $response) {
        echo "Response: " . var_dump($response) . "</p>\n\n";
    }
    exit;*/

    //$objResponse= $objFb->get( '/me/albums', getSession( 'fb_token' ) );

    $arrmixAlbums = array();
    do {
        $strAfter = '';

        if( true == isset( $objResponse ) && true == array_key_exists( 'paging' , $objResponse->getDecodedBody() ) && true == array_key_exists( 'next' , $objResponse->getDecodedBody() ['paging'] ) ) {
            $strAfter = $objResponse->getDecodedBody()['paging']['cursors']['after'];
        }

        $objResponse = $objFb->get( '/me/albums?fields=name,id,count,cover_photo{picture}&limit=5&after=' . $strAfter, getSession( 'fb_token' ) );

        $objAlbums = ( array ) $objResponse->getDecodedBody() [ 'data' ];

        foreach( $objAlbums as $objAlbum ) {

            $arrmixAlbums[  $objAlbum['id'] ] =  array(
                 'id' => $objAlbum['id'],
                 'name' => $objAlbum['name'],
                 'count' => $objAlbum['count'],
                 'cover' => ( true == isset( $objAlbum['cover_photo'] )? $objAlbum['cover_photo']['picture'] : '' ),
            );
        }
    } while( true == array_key_exists( 'paging' , $objResponse->getDecodedBody() ) && true == array_key_exists( 'next' , $objResponse->getDecodedBody() ['paging'] ) );
} catch(  Facebook\Exceptions\FacebookResponseException $objException) {
    echo 'The graph returned with error: ' . $objException->getMessage();
    exit;
} catch( Facebook\Exceptions\FacebookSDKException $objException ) {
    echo 'The SDK returned with error: ' . $objException->getMessage();
    exit;
}
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
    <a class="navbar-brand" href="user_dashboard.php">mFbGallery</a>
</div>

    <ul class="nav navbar-top-links navbar-right">
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-messages">
        <li>
            <a href="#">
                <div>
                    <strong>John Doe</strong>
                                    <span class="pull-right text-muted">
                                            <em>Today</em>
                                    </span>
                </div>
                <div>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s...</div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                </div>
                <div>Lorem Ipsum has been the industry's standard dummy text ever since an kwilnw...</div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                </div>
                <div>Lorem Ipsum has been the industry's standard dummy text ever since the...</div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a class="text-center" href="#">
                <strong>Read All Messages</strong>
                <i class="fa fa-angle-right"></i>
            </a>
        </li>
    </ul>
    <!-- /.dropdown-messages -->
</li>
<!-- /.dropdown -->
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-tasks">
        <li>
            <a href="#">
                <div>
                    <p>
                        <strong>Task 1</strong>
                        <span class="pull-right text-muted">60% Complete</span>
                    </p>

                    <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60"
                             aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                            <span class="sr-only">60% Complete (success)</span>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <p>
                        <strong>Task 2</strong>
                        <span class="pull-right text-muted">28% Complete</span>
                    </p>

                    <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="28"
                             aria-valuemin="0" aria-valuemax="100" style="width: 28%">
                            <span class="sr-only">28% Complete</span>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <p>
                        <strong>Task 3</strong>
                        <span class="pull-right text-muted">60% Complete</span>
                    </p>

                    <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60"
                             aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                            <span class="sr-only">60% Complete (warning)</span>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <p>
                        <strong>Task 4</strong>
                        <span class="pull-right text-muted">85% Complete</span>
                    </p>

                    <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="85"
                             aria-valuemin="0" aria-valuemax="100" style="width: 85%">
                            <span class="sr-only">85% Complete (danger)</span>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a class="text-center" href="#">
                <strong>See All Tasks</strong>
                <i class="fa fa-angle-right"></i>
            </a>
        </li>
    </ul>
    <!-- /.dropdown-tasks -->
</li>
<!-- /.dropdown -->
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-alerts">
        <li>
            <a href="#">
                <div>
                    <i class="fa fa-comment fa-fw"></i> New Comment
                    <span class="pull-right text-muted small">4 min</span>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                    <span class="pull-right text-muted small">12 min</span>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                    <span class="pull-right text-muted small">4 min</span>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <i class="fa fa-tasks fa-fw"></i> New Task
                    <span class="pull-right text-muted small">4 min</span>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div>
                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                    <span class="pull-right text-muted small">4 min</span>
                </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a class="text-center" href="#">
                <strong>See All Alerts</strong>
                <i class="fa fa-angle-right"></i>
            </a>
        </li>
    </ul>
    <!-- /.dropdown-alerts -->
</li>
<!-- /.dropdown -->
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-user">
        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
        </li>
        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
        </li>
        <li class="divider"></li>
        <li><a href="#"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
        </li>
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
                <a class="active-menu" href="user_dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a href="user_photos_old.php"><i class="fa fa-desktop"></i> mPhotos</a>
            </li>
            <li>
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

                foreach( $arrmixAlbums as $arrmixAlbum ) {
                    $intColorId = array_rand( $arrstrColors, 1);
                    ?>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="panel panel-primary text-center no-boder bg-color-<?php echo $arrstrColors[ $intColorId ] ?>">
                                <div class="panel-footer back-footer-<?php echo $arrstrColors[ $intColorId ] ?>">
                                    <a class="thumbnail" href="#">
                                        <img class="img-responsive" alt="No preview" src="<?php echo $arrmixAlbum['cover'] ?>">
                                        <?php echo $arrmixAlbum['name'] ?>
                                        <span class="right text-right""> (<?php echo $arrmixAlbum['count'] ?>) </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>

        <!-- /. ROW  -->
        <footer><p>All right reserved. Template by: <a href="http://webthemez.com">WebThemez</a></p></footer>
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->
<!-- JS Scripts-->
<!-- jQuery Js -->
<script src="Lib/bootstrap3.3.5/js/jquery-1.10.2.js"></script>
<!-- Bootstrap Js -->
<script src="Lib/bootstrap3.3.5/js/bootstrap.min.js"></script>
<!-- Metis Menu Js -->
<script src="Lib/bootstrap3.3.5/js/jquery.metisMenu.js"></script>
<!-- Custom Js -->
<script src="Lib/bootstrap3.3.5/js/custom-scripts.js"></script>


</body>
</html>
