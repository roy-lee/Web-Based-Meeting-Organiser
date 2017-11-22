<?php
$currentPage = 'createMeeting';
include("includes/header.inc.php");
require_once 'config.php';
$organiserusername = $_SESSION['username'];
/* ---------------
 *
 * On page run
 *
 * ---------------  */
function onPageRun() {
    getUserId();
}
#onPageRun();
$userid = getUserId($mysqli,$organiserusername);
$venues = get_venues($mysqli);
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
$repeattype_err = $isallday_err = $description_err = ""; // Invalid input only
//$_POST["meetingtitle"]; //$_POST["meetingvenue"]; //$_POST["meetingallday"];
//$_POST["meetingfrom"]; //$_POST["meetingto"]; //$_POST["meetingrepeat"];
if (isset($_POST["meetingtitle"]))
{
    validateOnPost($mysqli,$venues,$userid);
}
function get_venues($mysqli)
{
    $venues = array();
    $sql = "select * from venue;";
    
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $venues[$row['venue']] = $row['venueID'];
        }
    }
    else
    {
        $venue['NIL'] = 0;
    }
    return $venues;
}
function display_venues($venues)
{
    foreach($venues as $key => $value)
    {
        echo "<option value=".$value.">".$key."</option>";
    }
}
function validateOnPost($mysqli,$venues,$userid) {
    // Validate blanks
    if (empty(trim($_POST["meetingtitle"]))) {
        $meetingtitle_err = "Please enter a meeting title.";
    }
    if (empty(trim($_POST["meetingvenue"]))) {
        $meetingvenue_err = "Please enter a meeting venue";
    }
    if (empty(trim($_POST["meetingfrom"]))) {
        $datestart_err = "Please enter the start date";
    }
    if (empty(trim($_POST["meetingto"]))) {
        $username_err = "Please enter the end date";
    }
    
    /*
     * Meeting data
     */
    // Check input errors before inserting in database
    if (empty($meetingtitle_err) && empty($meetingvenue_err) && empty($datestart_err) && empty($organiserid_err)
            && empty($repeattype_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO meeting (startDate, endDate, startTime, endTime, title, description, eventStatus, venue_venueid, user_userid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssiii", $param_sdate, $param_edate, $param_stime, $param_etime, $param_title, $param_description, $param_eventStatus, $param_venueid, $param_userid);
            
            $param_eventStatus = 1;
            $param_venueid = $_POST['meetingvenue'];
            $param_userid = $userid;
            
            $start_datetime = date_create_from_format('D, M d, Y h:i A', $_POST['meetingfrom']);
            $start_date = $start_datetime->format('Y-m-d');
            $start_time = $start_datetime->format('h:i:s');
            if ((substr($_POST['meetingfrom'], -2) == "AM" && substr($start_time, 0, 2) == "12") || (substr($_POST['meetingfrom'], -2) == "PM" && substr($start_time, 0, 2) != "12"))
            {
                $timestamp = strtotime($start_time) + 60*60*12;
                $time = date('H:i:s', $timestamp);
                $start_time = $time;
            }
            $param_sdate = $start_date;
            $param_stime = $start_time;
            
            $end_datetime = date_create_from_format('D, M d, Y h:i A', $_POST['meetingto']);
            $end_date = $end_datetime->format('Y-m-d');
            $end_time = $end_datetime->format('h:i:s');
            if ((substr($_POST['meetingto'], -2) == "AM" && substr($end_time, 0, 2) == "12") || (substr($_POST['meetingto'], -2) == "PM" && substr($end_time, 0, 2) != "12"))
            {
                $timestamp = strtotime($end_time) + 60*60*12;
                $time = date('H:i:s', $timestamp);
                $end_time = $time;
            }
            $param_edate = $end_date;
            $param_etime = $end_time;
            $param_title = $_POST['meetingtitle'];
            $param_description = $_POST['description'];
            
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Executed, pass to index, show meetings
                // TO DO Get the id of the meeting, pass to other method
                echo "<h1>Meeting created</h1>";
            } else {
                // Stay on page, retry
                echo "Something went wrong. Please try again later.";
            }
        } else {echo "unable to prepare statement";}
        // Close statement
        $stmt->close();
    }
    /*
     * Participants
     */
    /*
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
    }*/
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
                    <form class="form-horizontal row-border" action="createMeeting.php" name="createMeeting" id="createMeetingForm" method="post">

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