<?php
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
                        <h3>Amend this event</h3><br>
                        <button type="button" class="btn btn-md btn-info">Edit</button>
                        <button type="button" class="btn btn-md btn-warning">Modify</button>
                        <button type="button" class="btn btn-md btn-danger">Delete</button>
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

<?php include("includes/footer.inc.php"); ?>
