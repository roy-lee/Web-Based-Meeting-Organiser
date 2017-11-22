<?php
$currentPage = 'index';
include("includes/header.inc.php");
?>
<link rel="stylesheet" href="css/pages/index.css">

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="#">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Dashboard</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Dashboard</h1>
    </div>
  </div><!--/.row-->

  <div class="panel panel-container">
    <div class="row">
      <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
        <div class="panel panel-teal panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-calendar color-blue"></em>
            <div class="large">
              <?php
              $query = "SELECT COUNT(*) AS SUM FROM meeting where eventStatus = '1'";
              $result = mysqli_query($mysqli,$query);
              $rows = mysqli_fetch_assoc($result);
              echo $rows['SUM'];
              ?>
              </div>
            <div class="text-muted">Upcoming Events</div>
          </div>
        </div>
      </div>

      <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
        <div class="panel panel-blue panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-calendar-check-o color-orange"></em>
            <div class="large">
              <?php
              $query = "SELECT COUNT(*) AS SUM FROM meeting";
              $result = mysqli_query($mysqli,$query);
              $rows = mysqli_fetch_assoc($result);
              echo $rows['SUM'];
              ?>
            </div>
            <div class="text-muted">Events so far</div>
          </div>
        </div>
      </div>

      <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
        <div class="panel panel-orange panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-users color-teal"></em>
            <div class="large">
            <?php
            $query = "SELECT COUNT(*) AS SUM FROM user";
            $result = mysqli_query($mysqli,$query);
            $rows = mysqli_fetch_assoc($result);
            echo $rows['SUM'];
            ?>
            </div>
            <div class="text-muted">Registered members</div>
          </div>
        </div>
      </div>

      <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
        <div class="panel panel-orange panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-check-square-o color-red"></em>
            <div class="large">
            <?php
            $query = "SELECT COUNT(*) AS SUM FROM meeting_participants where user_userID = '1' ";
            $result = mysqli_query($mysqli,$query);
            $rows = mysqli_fetch_assoc($result);
            echo $rows['SUM'];
            ?>
            </div>
            <div class="text-muted">Registered Events</div>
          </div>
        </div>
      </div>

    </div><!--/.row-->
  </div>

  <div class="row">

    <div class="col-md-12">
    <div class="col-md-6 no-padding">
      <div class="panel panel-default ">
        <div class="panel-heading">
          Timeline of Events
          <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
        <div class="panel-body timeline-container">
          <ul class="timeline">

            <?php
            $sqlOrganiser = "SELECT usr.*, mt.* from meeting mt INNER JOIN user usr on mt.user_userID = usr.userID where mt.eventStatus = '1'";
            $resultsOrganiser = mysqli_query($conn,$sqlOrganiser);
            while($row = mysqli_fetch_assoc($resultsOrganiser))
            {
              echo "
                <li>
                  <div class='timeline-badge primary'><em class='glyphicon glyphicon-calendar'></em></div>
                  <div class='timeline-panel'>
                    <div class='timeline-heading'>
                    <div class='col-xs-2 col-md-2 date pull-right'>
                      <div class='large'>30</div>
                      <div class='text-muted'>Jun</div>
                    </div>
                      <h4 class='timeline-title'>".$row['title']."</h4>

                    </div>
                    <div class='timeline-body'>
                      <p>".$row['description']."</p>
                      <hr>
                      <div><p class='pull-left'>Organised by: <br>".$row['fullName']."</p>
                      <a href='event-details.php?id=".$row['meetingID']."'><button type='button' class='btn btn-md btn-primary pull-right'>Details</button></a>
                    </div>
                    </div>
                  </div>
                </li>";
              }
            ?>
          </ul>
        </div>
      </div>
    </div><!--/.timeline of events -->


    <div class="col-md-6">
      <div class="panel panel-default articles">
        <div class="panel-heading">
          My Events
          <ul class="pull-right panel-settings panel-button-tab-right">
            <li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
              <em class="fa fa-cogs"></em>
            </a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li>
                  <ul class="dropdown-settings">
                    <li><a href="#">
                      <em class="fa fa-cog"></em> Settings 1
                    </a></li>
                    <li class="divider"></li>
                    <li><a href="#">
                      <em class="fa fa-cog"></em> Settings 2
                    </a></li>
                    <li class="divider"></li>
                    <li><a href="#">
                      <em class="fa fa-cog"></em> Settings 3
                    </a></li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
          <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
        <div class="panel-body articles-container">

          <?php
          $sqlJoinedEvents = "SELECT * FROM meeting_participants mp
                              INNER JOIN meeting mt
                              ON mp.meeting_meetingID = mt.meetingID where mp.user_userID = '1'";

          // $sqlOrganiser = "SELECT usr.*, mt.* from meeting mt INNER JOIN user usr on mt.user_userID = usr.userID where mt.eventStatus = '1'";
          $resultsJoinedEvents = mysqli_query($conn,$sqlJoinedEvents);
          while($row = mysqli_fetch_assoc($resultsJoinedEvents))
          {
            echo "

            <div class='article border-bottom'>
              <div class='col-xs-12'>
                <div class='row'>
                  <div class='col-xs-2 col-md-2 date'>
                    <div class='large'>30</div>
                    <div class='text-muted'>Jun</div>
                  </div>
                  <div class='col-xs-10 col-md-10'>
                    <h4><a href='#'>".$row['title']."</a></h4>
                    <p>".$row['description']."</p>
                    <hr>
                    <div>
                    <a href='event-details.php?id=".$row['meetingID']."'><button type='button' class='btn btn-md btn-primary pull-right'>Details</button></a>
                  </div>
                  </div>
                </div>
              </div>
              <div class='clear'></div>
            </div><!--End .article-->";
            }
          ?>
          
        </div>
      </div><!--End .articles-->
    </div><!--/.col-->



<?php include("includes/footer.inc.php"); ?>
