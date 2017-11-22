<?php
$currentPage = 'counterProposal';
include("includes/header.inc.php");

require_once 'config.php';

$username = $_SESSION['username'];
$meetingid = 42; //hardcoded as an example, see how to parse it in

/* ---------------
 *
 * On page run
 *
 * ---------------  */

$userid = getUserId($mysqli,$username);


function getUserId($mysqli,$username) {
    // Prepare a select statement
    $sql = "select userID from user where username = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            //
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($userid);
                if ($stmt->fetch()) {
                    $_SESSION['userid'] = $userid;
                }
            }
        }


        // Close statement
        $stmt->close();
        return $userid;
    }
}

function getMeetingTimes($mysqli,$meetingid)
{
    $start_end_datetimes = array();
    $sql = "select startDate, endDate, startTime, endTime from meeting where meetingID = $meetingID;";
    
    
    
}
?>
<head>
    <link href="css/pages/createMeeting.css" rel="stylesheet" >
</head>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
            <li class="active">Create Meeting</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create a Meeting</h1>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">Counter Proposal</div>
                <div class="panel-body">
                    <form class="form-horizontal row-border" action="counter-proposal.php" name="counter-propose" id="counter-propose" method="post">

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="meetingtitle">Title</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="meetingtitle" placeholder="Meeting Title">
                                <span class="help-block"><?php echo $meetingtitle_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="meetingvenue">Venue</label>
                            <div class="col-md-10">
                                <select id="repeatSelect" name="meetingvenue" class="form-control">
                                    <?php display_venues($venues); ?>
                                </select>
                                <span class="help-block"><?php echo $meetingvenue_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Date Time</label>
                            <div class="col-md-5">
                                <input class="form-control" id=fromdate type="text" name="meetingfrom" placeholder="Date From">
                                <span class="help-block"><?php echo $datestart_err; ?></span>
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" id="todate" type="text" name="meetingto" placeholder="Date To">
                                <span class="help-block"><?php echo $dateend_err; ?></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="description">Description</label>
                            <div class="col-md-10">
                                <input class="form-control" id=description type="text" name="description" placeholder="Description of Meeting">            
                                <span class="help-block"><?php echo $description_err; ?></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="participants">Participants</label>
                            <div class="col-md-10">
                                <select id="participants" name="participants[]" class="form-control" multiple>
                                    <?php display_fullnames($user_fullnames); ?>
                                </select>
                                <span class="help-block"><?php echo "Click and drag or Ctl + Click to conduct multiple selection"; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 widget-right">
                                <button type="submit" class="btn btn-primary btn-md pull-right">Submit</button>
                                <button type="reset" class="btn btn-default btn-md pull-right">Reset</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div><!--/.row-->

<?php include("includes/footer.inc.php"); ?>


    <script src="js/moment.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/createMeeting.js"></script>