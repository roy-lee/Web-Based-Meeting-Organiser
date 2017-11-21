<?php
$currentPage = 'createMeeting';
include("includes/header.inc.php");

require_once 'config.php';

$organiserusername = $_SESSION['username'];
$userid = "";

/* ---------------
 *
 * On page run
 *
 * ---------------  */

function onPageRun() {
    getUserId();
}

onPageRun();

function getUserId() {
    // Prepare a select statement
    $sql = "SELECT userid FROM users WHERE username = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);

        // Set parameters
        $param_username = trim($_SESSION['username']);

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
    }
}

/* ---------------
 *
 * On page run
 *
 * ---------------  */


/* ---------------
 *
 * Post Validation
 *
 * ---------------  */
// Variables
$meetingtitle = $meetingvenue = $isallday = $datestart = $dateend = $venue = $organiserid = $repeattype = "";
// Errors
$meetingtitle_err = $meetingvenue_err = $datestart_err = $dateend_err = $organiserid_err = "";
$repeattype_err = $isallday_err = ""; // Invalid input only
//$_POST["meetingtitle"]; //$_POST["meetingvenue"]; //$_POST["meetingallday"];
//$_POST["meetingfrom"]; //$_POST["meetingto"]; //$_POST["meetingrepeat"];

function validateOnPost() {
    // Validate blanks
    if (empty(trim($_POST["meetingtitle"]))) {
        $meetingtitle_err = "Please enter a meeting title.";
    }
    if (empty(trim($_POST["meetingvenue"]))) {
        $meetingvenue_err = "Please enter a meeting venue";
    }
    //    if (empty(trim($_POST["meetingallday"]))) {
    //        $isallday_err = "Please enter a username.";
    //    }
    if (empty(trim($_POST["meetingfrom"]))) {
        $datestart_err = "Please enter the start date";
    }
    if (empty(trim($_POST["meetingto"]))) {
        $username_err = "Please enter the end date";
    }
    //    if (empty(trim($_POST["meetingrepeat"]))) {
    //        $repeattype_err = "Please enter a username.";
    //    }

    /*
     * Meeting data
     */
    // Check input errors before inserting in database
    if (empty($meetingtitle_err) && empty($meetingvenue_err) && empty($datestart_err) && empty($organiserid_err)
            && empty($repeattype_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO meeting (meetingtitle, isallday, datestart, dateend, venue, user_userid, repeattype) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?);";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssss", $param_meetingtitle, $param_isallday, $param_datestart, $param_dateend, $param_venue, $param_organiser, $param_repeattype);

            $param_meetingtitle = trim($_POST["meetingtitle"]);
            $param_isallday = trim($_POST["meetingallday"]);
            $param_datestart = trim($_POST["meetingfrom"]);
            $param_dateend = trim($_POST["meetingto"]);
            $param_venue = trim($_POST["meetingvenue"]);
            $param_organiser = trim($userid);
            $param_repeattype = trim($_POST["meetingrepeat"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Executed, pass to index, show meetings
                // TO DO Get the id of the meeting, pass to other method
            } else {
                // Stay on page, retry
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    /*
     * Participants
     */
    $participantids = $_POST["participantids"];

    $meetingid = "";

    // Insert participants into participant table
    foreach ($participantids as $pid) {
        // Prepare an insert statement
        $sql = "INSERT INTO participants (meeting_meetingid, user_userid) VALUES (?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_meetingid, $param_participantid);

            // Set parameters
            $param_meetingid = $meetingid;
            $param_participantid = $pid;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // On success
                header("location: index.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }
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
                <div class="panel-heading clearfix">Meeting Details</div>
                <div class="panel-body">
                    <form class="form-horizontal row-border" action="#" name="createMeeting" id="createMeetingForm">

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
                                <input class="form-control" type="text" name="meetingvenue" placeholder="Meeting Venue">
                                <span class="help-block"><?php echo $meetingvenue_err; ?></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-2 control-label" for="alldaycheckboxes">All Day</label>
                            <div class="col-md-10">
                                <label class="checkbox-inline" for="alldaycheckboxes-0">
                                    <input type="checkbox" name="meetingallday" id="alldaycheckboxes-0" value="true">
                                    All Day Event
                                </label>
                                <span class="help-block"><?php echo $isallday_err; ?></span>
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
                            <label class="col-md-2 control-label" for="repeatSelect">Repeat</label>
                            <div class="col-md-10">
                                <select id="repeatSelect" name="meetingrepeat" class="form-control">
                                    <option value="none">None</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <span class="help-block"><?php echo $repeattype_err; ?></span>
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
