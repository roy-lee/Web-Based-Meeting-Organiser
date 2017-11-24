<?php
ob_start( );
$currentPage = 'event';
include("includes/header.inc.php");
$id = @$_GET['id'];
// Comment away placeholder ID. For testing purpose
// $id = 1;


$inner_join = "Select mt.*, usr.*, ven.* FROM meeting mt INNER JOIN venue ven on mt.venue_venueID = ven.VenueID LEFT JOIN user usr on mt.user_UserID = usr.userID where mt.meetingID='$id' and mt.eventStatus = '1'";

$results = mysqli_query($conn,$inner_join);
$row = mysqli_fetch_assoc($results);

$title = $row['title'];
$description = $row['description'];
$start_date = $row['startDate'];
$end_date = $row['endDate'];
$start_time = substr($row['startTime'],0,5);
$end_time = substr($row['endTime'],0,5);
$venue 		= $row['venue'];
$username   = $row['username'];
$fullName   = $row['fullName'];
$email 		= $row['email'];
$userid     = $row['userID'];

if(isset($_POST['update_butt']))
{
	// if update button is press update the table
    $title 	= $_POST['title_tb'];
    $venue = $_POST['venue_tb'];
	$description = $_POST['descrip_tb'];
    if (!preg_match('/[@]/',$_POST['meetingfrom']) && !empty($_POST['meetingfrom']))
    {
        $start_datetime = date_create_from_format('D, M d, Y h:i A', $_POST['meetingfrom']);
        $start_date = $start_datetime->format('Y-m-d');
        $start_time = $start_datetime->format('h:i:s');
        if ((substr($_POST['meetingfrom'], -2) == "AM" && substr($start_time, 0, 2) == "12") || (substr($_POST['meetingfrom'], -2) == "PM" && substr($start_time, 0, 2) != "12"))
        {
            $timestamp = strtotime($start_time) + 60*60*12;
            $time = date('H:i:s', $timestamp);
            $start_time = $time;
        }
    }
    if (!preg_match('/[@]/',$_POST['meetingto']) && !empty($_POST['meetingto']))
    {
        $end_datetime = date_create_from_format('D, M d, Y h:i A', $_POST['meetingto']);
        $end_date = $end_datetime->format('Y-m-d');
        $end_time = $end_datetime->format('h:i:s');
        if ((substr($_POST['meetingto'], -2) == "AM" && substr($end_time, 0, 2) == "12") || (substr($_POST['meetingto'], -2) == "PM" && substr($end_time, 0, 2) != "12"))
        {
            $timestamp = strtotime($end_time) + 60*60*12;
            $time = date('H:i:s', $timestamp);
            $end_time = $time;
        }
    }
    $sql1 = "select venueID from venue where venue='$venue';";
    $result = $mysqli->query($sql1);
    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $venue_id = $row['venueID'];
        }
    }
    

	// validate if need to//
	$sql = "Update meeting set startTime='$start_time', endTime='$end_time', startDate='$start_date', endDate='$end_date', title='$title', description='$description', venue_venueID='$venue_id' where meetingID='$id'";
	mysqli_query($conn,$sql);
	// header("location:event-details.php?id=$id");

}
if(isset($_POST['joinButt']))
{
	$sql = "IINSERT INTO meeting_participants (meeting_meetingID,meeting_venue_venueID,meeting_user_userID,user_userID) VALUES($id,$venue,$userid,$username);";
	if(mysqli_query($conn,$sql))
	{

		header('location:index.php');
	}
	// joins the event, redirect user to the same page
	header("location:/event-details.php?edit&id=$id");

}

if(isset($_POST['editButt']))
{
	// edit the event, redirect user to the same page and edit it from there
	header("location:./event-details.php?edit&id=$id");

}
if(isset($_POST['delButt']))
{
	// set the eventStatus to '2'- deleted
	$sql = "Update meeting set eventStatus='2' where meetingID='$id'";
	if(mysqli_query($conn,$sql))
	{

		header('location:index.php');
	}
}

if(isset($_GET['edit']) && !empty($_GET['id']))
{
	//show the edit page
?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="#">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Edit Event</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Edit Event Details</h1>
    </div>
  </div><!--/.row-->


  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">Event Description for '<?php echo $title;?>'</div>
        <div class="panel-body">

          <form class="form-horizontal row-border" action='' name="editMeeting" id="editMeetingForm" method='post'>
            <div class="form-group">
              <label class="col-md-2 control-label">Event Name</label>
              <div class="col-md-10">
                <input type="text" name='title_tb' value='<?php echo $title;?>' class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">Organiser</label>
              <div class="col-md-10">
                <input type="text" disabled="disabled" readonly="" value='<?php echo $fullName; ?>' class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">Organiser Email</label>
              <div class="col-md-10">
                <input type="text" disabled="disabled" readonly="" value='<?php echo $email; ?>' class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">Venue</label>
              <div class="col-md-10">
								<select class="form-control input-lg" name=venue_tb>
									<?php

									$sql = "select venue from venue";
							    $result = $mysqli->query($sql);
							    if ($result->num_rows >0)
							    {
							        while($row = $result->fetch_assoc())
							        {
												if ($row['venue'] == $venue) {
													echo "<option selected='selected' value='$venue'>$venue</option>";
												}
												else
													echo "<option value='".$row['venue'].";'>".$row['venue']." </option>";
							        }
							    }

									?>
                </select>
              </div>
            </div>


						<div class="form-group">
								<label class="col-md-2 control-label">Date Time</label>
								<div class="col-md-5">
										<input class="form-control" id="fromdate" type="text" value="<?php echo $start_date." @ ".$start_time."hrs"; ?>" name="meetingfrom" placeholder="<?php echo $start_date." @ ".$start_time."hrs"; ?>">
								</div>
								<div class="col-md-5">
										<input class="form-control" id="todate" type="text" value="<?php echo $end_date." @ ".$end_time."hrs"; ?>" name="meetingto" placeholder="<?php echo $end_date." @ ".$end_time."hrs"; ?>">
								</div>
						</div>




            <div class="form-group">
              <label class="col-md-2 control-label">Description</label>
              <div class="col-md-10">
                <textarea type="text" rows="10" cols='50' class="form-control" name='descrip_tb'><?php echo htmlspecialchars($description); ?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label"></label>
              <div class="col-md-10">
                <input type="submit" class="btn btn-md btn-primary" name='update_butt' value='Update'>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div><!--/.row-->

<?php
}
else
{

?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
                <li class="active">Event Details</li>
            </ol>
        </div>
        <!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Event Details</h1>
            </div>
        </div>
        <!--/.row-->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading"><?php echo $title;?>
                    </div>
                    <div class="panel-body event-description">
                      <p><?php echo $description;?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Event Info</div>
                    <div class="panel-body event-info">
                        <h3>At a glance...</h3><br>
                        <div><em class="fa fa-calendar-check-o">&nbsp;</em> <?php echo $start_date ?></div>
                        <div><em class="fa fa-clock-o">&nbsp;</em> <?php echo $start_time ?></div>
                        <div><em class="fa fa-map-o">&nbsp;</em> <?php echo $venue ?></div>
                        <div><em class="fa fa-user">&nbsp;</em> <?php echo $username?></div>
                        <div><em class="fa fa-envelope-o">&nbsp;</em> <?php echo $email?></div>

                        <hr>
                        <h3>Join this Event</h3>
                        <br>
            						<form action='' method ='POST'>
                          <input type="submit" value='Join' name='joinButt' class="btn btn-md btn-primary">
                          <input type="submit" value='Leave' name='leaveButt' id='delButt' class="btn btn-md btn-danger" onclick='return myFunction()'>
            						</form>

                        <hr>
                        <h3>Amend this event</h3>
                        <br>
            						<form action='' method ='POST'>
                          <input type="submit" value='Edit' name='editButt' class="btn btn-md btn-info">
                          <input type="submit" value='Delete' name='delButt' id='delButt' class="btn btn-md btn-danger" onclick='return myFunction()'>
            						</form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <!--/.main-->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h2>Additional Information</h2>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body tabs">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1" data-toggle="tab">Participants</a></li>
                            <li><a href="#tab2" data-toggle="tab">Tab 2</a></li>
                            <li><a href="#tab3" data-toggle="tab">Tab 3</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1">
                                <div class="panel panel-default">

                                    <div class="panel-body btn-margins">
                                      <div class="col-lg-12">
                                        <div class="panel panel-default">
                                          <div class="panel-body">
                                            <table data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                                              <thead>
                                                <tr>
                                                  <th data-field="state" data-sortable="true">Full Name</th>
                                                  <th data-field="id" data-sortable="true">Email</th>
                                                </tr>
                                              </thead>
                                              <?php
                      												// $sql = "SELECT * from user where userID = (Select * from meeting_participants where meeting_meetingID ='$id')";
																							// $results = mysqli_query($conn,$sql);
                      												// while($row = mysqli_fetch_assoc($results))
                      												// {
                      												// 	echo"
                      												// 		<tr>
                      												// 			<td>".$row['fullName']."</td>
                      												// 			<td>".$row['email']."</td>
                      												// 		</tr>";
                      												// }

																							$query = "SELECT * from user usr
																												inner join meeting_participants mp
																												on mp.user_userID = usr.userID
																												where mp.meeting_meetingID='$id'";

																							if ($result = $mysqli->query($query)) {

																							    /* fetch associative array */
																							    while ($row = $result->fetch_assoc()) {
																										echo"
			                      														<tr>
			                      															<td>".$row['fullName']."</td>
			                      															<td>".$row['email']."</td>
			                      														</tr>";
																							    }

																							    /* free result set */
																							    $result->free();
																							}

																							/* close connection */
																							$mysqli->close();
                      											  ?>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <!-- /.panel-->
                            </div>
                            <div class="tab-pane fade" id="tab2">
                                <h4>Tab 2</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget rutrum purus. Donec hendrerit ante ac metus sagittis elementum. Mauris feugiat nisl sit amet neque luctus, a tincidunt odio auctor.</p>
                            </div>
                            <div class="tab-pane fade" id="tab3">
                                <h4>Tab 3</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget rutrum purus. Donec hendrerit ante ac metus sagittis elementum. Mauris feugiat nisl sit amet neque luctus, a tincidunt odio auctor.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.panel-->
            </div>
            <!--/.col-->
        </div>
        <!-- /.row -->

    </div>

<?php } include("includes/footer.inc.php"); ?>
    
    <script src="js/moment.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/createMeeting.js"></script>
