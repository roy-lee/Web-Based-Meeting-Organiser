<?php
ob_start( );
$currentPage = 'event';
include("includes/header.inc.php");
$id = @$_GET['id'];
// Comment away placeholder ID. For testing purpose
$id = 1;

$inner_join = "Select mt.*, usr.*, ven.* FROM meeting mt INNER JOIN venue ven on mt.venue_venueID = ven.VenueID LEFT JOIN user usr on mt.user_UserID = usr.userID where mt.meetingID='$id' and mt.eventStatus = '1'";
//$description = "Select * from meeting where meetingID=$id";

$results = mysqli_query($conn,$inner_join);
$row = mysqli_fetch_assoc($results);
//$venue = "Select venue from venue where venueID='$row[venue_venueID]'";
//$row2 = mysqli_fetch_assoc(mysqli_query($conn,$venue));
$title = $row['title'];
$description = $row['description'];
$start_date = $row['startDate'];
$start_time = $row['startTime'];
$venue 		= $row['venue'];
$username   = $row['username'];
$email 		= $row['email'];
$userid     = $row['userID'];

if(isset($_POST['update_butt']))
{
	// if update button is press update the table
	$venue = $_POST['venue_tb'];
	$start_time = $_POST['starttime_tb'];
	$start_date = $_POST['startdate_tb'];
	$desc		= $_POST['descrip_tb'];
	$title 		= $_POST['title_tb'];

	// validate if need to//

	mysqli_query($conn,"Update venue set venue ='$venue' where venueID=(Select venue_venueID from meeting where meetingID='$id')");
	$sql2 = "Update meeting set startTime='$start_time', startDate='$start_date', title='$title', description='$desc' where meetingID='$id'";
	mysqli_query($conn,$sql2);
	header('location:event-details.php');


}
if(isset($_POST['editButt']))
{
	// edit the event, redirect user to the same page and edit it from there
	header("location:/event-details.php?edit&id=$id");

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
            <div class="col-lg-12">
                <h1 class="page-header">Edit Event Details</h1>
            </div>
        </div>
        <!--/.row-->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="panel panel-info">
					<form action='' method='post'>
                    <div class="panel-heading"><input class='no_border' type='text' name='title_tb' value='<?php echo $title;?>'>
                    </div>
                    <div class="panel-body event-description">

                        <p>

                            <h3><?php echo $title.' Description'; ?></h3>
							<div><em class="fa fa-calendar-check-o">&nbsp;</em><input class='no_border' type='text' id='datepicker' name='startdate_tb' value ='<?php echo $start_date; ?>'>
							<div><em class="fa fa-clock-o">&nbsp;</em><input type='text' id='timepicker' class='time' value='<?php echo substr($start_time,0,8); ?>' name='starttime_tb'></div>
							<div><em class="fa fa-map-o">&nbsp;</em><input class='no_border' type='text' name='venue_tb' value ='<?php echo $venue; ?>'></div>
							<div><em class="fa fa-user">&nbsp;</em> <?php echo $username; ?></div>
							<div><em class="fa fa-envelope-o">&nbsp;</em> <?php echo $email;?></div>
							<textarea rows="4" cols='50' name='descrip_tb'><?php echo htmlspecialchars($description); ?></textarea>
							<div><input type="submit" class="btn btn-md btn-primary" name='update_butt' value='Update'></div>
							</form>

                    </div>
                </div>
            </div>
		</div>
	</div>
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
                                    <div class="panel-heading">Participants</div>
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
                      												$sql = "Select * from user where userID!=(select user_userID from meeting_participants where user_userID='$userid')";
                      												$results = mysqli_query($conn,$sql);
                      												while($row = mysqli_fetch_assoc($results))
                      												{
                      													echo"
                      														<tr>
                      															<td>".$row['fullName']."</td>
                      															<td>".$row['email']."</td>
                      														</tr>";
                      												}
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
