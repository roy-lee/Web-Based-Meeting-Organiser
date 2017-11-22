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
		/**
  *Get an array of member values
  *
  *@para string $user_id  The member's userID
  *@return an array of the member value based on its userID (i.e. $result = $user->user_data('1'); echo $result['Name'];)
  */
function user_data($user_id)
 {
  $user_id=(int)$user_id;
  $func_num_args=func_num_args();
  $func_get_args=func_get_args();
  if($func_get_args>1)
  {
   unset($func_get_args[0]);
   $fields='`'.implode('`,`',$func_get_args).'`';
   $sql="SELECT $fields from user where `userID`='$user_id'";
   $results = mysqli_query($conn,$sql);
	 return mysqli_fetch_array($results);
  }
 }

 /**
  *Gets the member values.
  *
  *@para string $field   Member column Names(i.e. echo $user->member_values('Name');)
  *@return string of the member value
  */
 function member_values($field)
 {
  if($this->logged_in()==true)
  {
   $Session_user_id=$_SESSION['userID'];
   $user_data=$this->user_data($Session_user_id,'userID','email','username','fullName');
   return $user_data[$field];
  }
 }
/* check whether user is logged in */
 function logged_in()
 {
  return(isset($_SESSION['userID'])) ? true:false;
 }
		?>
