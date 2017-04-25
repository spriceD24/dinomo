<?php include_once("util/WebUtil.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/CacheUtil.php"); ?>

<?php
$webUtil = new WebUtil ();

$user = $webUtil->getLoggedInUser ();
// refresh the cookie
$webUtil->addLoggedInUser ( $user, ConfigUtil::getCookieExpDays () );

if(!$user->hasRole('admin'))
{
	header ( "Location: select_qa.php" );
	exit ();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dinomo Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="admin/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="admin/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="admin/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="admin/css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="admin/css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="admin/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="admin/https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="admin/https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Dinomo Admin</a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="select_qa.php"><i class="fa fa-home fa-fw"></i>Open QA Application</a></li>
        </ul>
  		
  		<ul class="nav navbar-right navbar-top-links">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <?=$user->name?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">

                <ul class="nav" id="side-menu">
                    <li class="">
                        <a href="#"><i class="fa fa-user fa-fw"></i>Manage Users<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href='#' onclick="showPage('new_user.php')"><i class="fa fa-user-plus fa-fw"></i>Add User</a>
                            </li>
                            <!-- 
                            <li>
                                <a href="#" onclick="showPage('manage_user.php?action=edit')"><i class="fa fa-user-secret fa-fw"></i>Edit User</a>
                            </li>
                            <li>
                                <a href="#" onclick="showPage('manage_user.php?action=remove')"><i class="fa fa-user-times fa-fw"></i>Remove User</a>
                            </li> -->
                            <li>
                                <a href="#" onclick="showPage('manage_user.php?action=view')"><i class="fa fa-users fa-fw"></i>Manage Existing Users</a>
                            </li>
                            <li>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="#"><i class="fa fa-table fa-fw"></i>Manage Projects<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href='#' onclick="showPage('new_project.php')"><i class="fa fa-edit fa-fw"></i>Add Project</a>
                            </li>
                            <li>
                                <a href="#" onclick="showPage('manage_project.php?action=view')"><i class="fa fa-files-o fa-fw"></i>Manage Existing Projects</a>
                            </li>
                            <li>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" onclick="showPage('cache_admin.php')"><i class="fa fa-wrench fa-fw"></i>Cache Admin</a>
                    </li>
                    <li>
                        <a href="#" onclick="showPage('log_admin.php')"><i class="fa fa-edit fa-fw"></i>View Logs</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">

		<iframe id="adminFrame" name="adminFrame" frameborder="0" style="overflow:hidden;height:1000px;width:100%" height="100%" width="100%"></iframe>

        </div>
    </div>

</div>

<!-- jQuery -->
<script src="admin/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="admin/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="admin/js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="admin/js/startmin.js"></script>

</body>

<script>

function showPage(site)
{
	document.getElementById('adminFrame').src = site;
}

</script>

</html>
