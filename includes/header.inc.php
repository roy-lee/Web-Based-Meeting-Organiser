<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Meeting Organiser</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" >
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<link href="css/datepicker3.css" rel="stylesheet">
		<link href="css/bootstrap-table.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		<link href="css/event-details.css" rel="stylesheet" >

		<!--Custom Font-->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
		<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<?php include('config.php'); ?>
		<!-- Navbar -->
		<?php include('components/nav.php'); ?>
		<?php
		function numtomonth($month)
		{
			$month_array = ['Jan','Feb','Mar','April','May','June','July','Aug','Sep','Oct','Nov','Dec'];
			$month = (int)substr($month,5,8);
			$month = $month - 1;
			return $month_array[$month];
		}

		function getdateonly($date) {
			$date = substr($date,-2);
			return $date;
		}
		?>
