<?php $case = $_GET['case'];?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Admin Panel</title>

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?=FILE_URL?>plugins/font-awesome/css/font-awesome.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?=FILE_URL?>dist/css/adminlte.css">
	
	<link rel="stylesheet" href="<?=FILE_URL?>dist/css/style.css">
	
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?=FILE_URL?>plugins/ionic/ionicons.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<script type="text/javascript">
		var DOMAIN = '<?=DOMAIN;?>';
		var API = '<?=API;?>';
		var TEMPLATE = '<?=TEMPLATE;?>';
		var JS_URL = '<?=JS_URL;?>';
		var FILE_URL = '<?=FILE_URL;?>';
		var CASE   = '<?=$_GET['case'];?>';	
	</script>  

	<style>
		#time{padding-left: 30%; top:5px; font-size:20px; color:#fff;font-weight:500;}
	</style>

</head>

	<input type="hidden" id="case" value="<?php echo $case; ?>">

	<?php if($case != 'login') { ?>

	<nav class="main-header navbar navbar-expand border-bottom navbar-light bg-success">
		<!-- Left navbar links -->
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
			</li>
		</ul>

		<div align="center" id="time"></div>
		<!-- Right navbar links -->
		<ul class="navbar-nav ml-auto">
			<li class="dropdown user user-menu">
				<a href="javascript:;" class=""  data-toggle="dropdown">
					<span class="hidden-xs"><?= ucfirst($_SESSION['user_email']);?></span>
				</a>
				<ul class="dropdown-menu">
					<!-- User image -->
					<li class="user-header">
						<p><?= ucfirst($_SESSION['user_name']);?></p>
					</li>

					<!-- Menu Footer-->
					<li class="user-footer">
						<div class="pull-left">
							<a href="<?=DOMAIN?>index.php?case=profile" class="btn btn-primary btn-flat">Profile</a>
						</div>
						<div class="pull-right">
							<a href="<?=DOMAIN?>index.php?case=logout" class="btn btn-success btn-flat">Sign out</a>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</nav>

	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		<!-- Brand Logo -->
		<a href="index.php" class="brand-link bg-success">
		  <img src="<?=FILE_URL?>dist/img/AdminLTELogo.png" alt="MoyDom Dashboard" class="brand-image img-circle elevation-3" style="opacity: .8">
		  <span class="brand-text font-weight-light">Moydom</span>
		</a>

		<!-- Sidebar -->
		<div class="sidebar">
		  <!-- Sidebar user panel (optional) -->
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="info">
				  <a href="editprofile.php" class="d-block"><?php ?></a>
				</div>
			</div>

			<!-- Sidebar Menu -->
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

				<li class="nav-item">
				  <a href="index.php" class="nav-link">
					<i class="fa fa-dashboard nav-icon"></i>
					<p>Dashboard</p>
				  </a>
				</li>
				
				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=location-list" class="nav-link <?php echo ($params['case'] == 'location-list') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Locations</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=manage-user" class="nav-link <?php echo ($params['case'] == 'manage-user') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Customers</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=room-type-list" class="nav-link <?php echo ($params['case'] == 'room-type-list') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Room Types</p>
					</a>
				</li>
					
				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=apartment-list" class="nav-link <?php echo ($params['case'] == 'apartment-list') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Apartment Set</p>
					</a>
				</li>
			
				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=room-list" class="nav-link <?php echo ($params['case'] == 'room-list') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Rooms</p>
					</a>
				</li>
					
				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=reservations" class="nav-link <?php echo ($params['case'] == 'reservations') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Reservations</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=testimonials" class="nav-link <?php echo ($params['case'] == 'testimonials') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Testimonial</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=gallary-list" class="nav-link <?php echo ($params['case'] == 'gallary-list') ? "active" : ""; ?>">
					<i class="fa fa-image nav-icon"></i>
					<p>Manage Gallery</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=manage-ammenties" class="nav-link <?php echo ($params['case'] == 'manage-ammenties') ? "active" : ""; ?>">
					<i class="fa fa-users nav-icon"></i>
					<p>Manage Ammenties</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?=DOMAIN?>index.php?case=logout" class="nav-link">
					<i class="fa fa-users nav-icon"></i>
					<p>Logout</p>
					</a>
				</li>
			</ul>
		  </nav>
		  <!-- /.sidebar-menu -->
		</div>
		<!-- /.sidebar -->
	</aside>
	
	<?php } ?>
