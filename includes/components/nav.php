<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || !isset($_SESSION['role']) || empty($_SESSION['role'])){
  header("location: login.php");
  exit;
}
?>

<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span></button>

					<a class="navbar-brand" href="#"><span>Meeting</span> Organiser</a>
                    <a href="./user-profile.php" class='navbar-brand pull-right'><span> | User</span> Profile | </a>

				</div>
			</div><!-- /.container-fluid -->
		</nav>

		<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
			<div class="profile-sidebar">
				<div class="profile-userpic">
					<img src="../../img/profile-pic-2.jpg" width="50" class="img-responsive" alt="">
				</div>
				<div class="profile-usertitle">
					<div class="profile-usertitle-name"><?php echo $_SESSION['username']." (".$_SESSION['role'].")"; ?></div>
					<div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="divider"></div>
			<form role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
				</div>
			</form>
			<ul class="nav menu">
				<li class="<?php if($currentPage =='index'){echo 'active';}?>"><a href="index.php"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
				<!-- <li><a href="widgets.html"><em class="fa fa-calendar">&nbsp;</em> Widgets</a></li> -->
				<li class="<?php if($currentPage =='event'){echo 'active';}?>"><a href="event-details.php"><em class="fa fa-calendar">&nbsp;</em> Event Details</a></li>
        <li><a href="createMeeting.php"><em class="fa fa-dashboard">&nbsp;</em> Create Meeting</a></li>
				<!-- <li><a href="charts.html"><em class="fa fa-bar-chart">&nbsp;</em> Charts</a></li>
				<li><a href="event-details.php"><em class="fa fa-calendar">&nbsp;</em> Event Details</a></li>
				<li><a href="charts.html"><em class="fa fa-bar-chart">&nbsp;</em> Charts</a></li>
				<li><a href="buttons.html"><em class="fa fa-hand-pointer-o">&nbsp;</em> Buttons</a></li>
				<li><a href="forms.html"><em class="fa fa-pencil-square-o">&nbsp;</em> Forms</a></li>
				<li><a href="tables.html"><em class="fa fa-table">&nbsp;</em> Tables</a></li>
				<li><a href="panels.html"><em class="fa fa-clone">&nbsp;</em> Alerts &amp; Panels</a></li>
				<li><a href="icons.html"><em class="fa fa-star-o">&nbsp;</em> Icons</a></li> -->
        <li class="<?php if($currentPage =='userProfile'){echo 'active';}?>"><a href="./user-profile.php?id=<?php echo $_SESSION['userID'] ?>"><em class="fa fa-user">&nbsp;</em>User Profile</a></li>
				<li><a href="logout.php"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
				<!-- <li class="parent "><a data-toggle="collapse" href="#sub-item-1">
					<em class="fa fa-file-o">&nbsp;</em> Pages <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><i class="fa fa-plus"></i></span>
					</a>
					<ul class="children collapse" id="sub-item-1">
						<li><a class="" href="gallery.html">
							Gallery
						</a></li>
						<li><a class="" href="search.html">
							Search
						</a></li>
						<li><a class="" href="login.html">
							Login
						</a></li>
						<li><a class="" href="error.html">
							Error 404
						</a></li>
					</ul>
				</li> -->
			</ul>
		</div><!--/.sidebar-->
