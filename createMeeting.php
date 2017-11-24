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

$userid = getUserId($mysqli,$organiserusername);
$venues = get_venues($mysqli);
$results = get_users($mysqli,$organiserusername);
$user_fullnames = $results[0];
$user_emails = $results[1];

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

    return $venues;
}

function get_users($mysqli,$organiser)
{
    $users_fullnames = array();
    $users_emails = array();

    $sql = "select * from user where username != '".$organiser."';";

    $result = $mysqli->query($sql);
    if ($result->num_rows >0)
    {
        while($row = $result->fetch_assoc())
        {
            $users_fullnames[$row['userID']] = $row['fullName'];
            $users_emails[$row['userID']] = $row['email'];
        }
    }
    else
    {
        $users_fullname[0] = "NIL";
        $users_emails[0] = "NIL";
    }

    $results = array($users_fullnames,$users_emails);
    return $results;
}

function display_fullnames($fullnames)
{
    foreach($fullnames as $key => $value)
    {
        echo "<option value=".$key.">".$value."</option>";
    }
}

function display_venues($venues)
{
    foreach($venues as $key => $value)
    {
        echo "<option value=".$value.">".$key."</option>";
    }
}

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
 * Post Validation
 *
 * ---------------  */
//Variables
$title = $description = $sdate = $edate = "";
$title_err = $venue_err = $description_err = $date_err = $participants_err = "";

if (isset($_POST['meetingtitle']) && isset($_POST['description']) && isset($_POST['meetingfrom']) && isset($_POST['meetingto']))
{
    //validate meeting title
    if (empty(trim($_POST['meetingtitle'])))
    {
        $title_err = "Please enter a meeting title.";
    }
    else if (strlen($_POST['meetingtitle']) > 45)
    {
        $title_err = "Meeting title length must not exceed 45 characters.";
    }
    else
    {
        $title = trim($_POST['meetingtitle']);
    }
    //validate meeting venue
    if (!isset($_POST['meetingvenue']))
    {
        $venue_err = "Please enter a meeting venue.";
    }
    //Validate meeting description
    if (empty(trim($_POST['description'])))
    {
        $description_err = "Please enter the meeting description.";
    }
    else if (strlen(trim($_POST['description'])) > 1024)
    {
        $description_err = "Meeting description must not exceed 1024 characters.";
    }
    else
    {
        $description = trim($_POST['description']);
    }
    //Validate start and end dates
    if (empty(trim($_POST['meetingfrom'])) && empty(trim($_POST['meetingto'])))
    {
        $date_err = "Please select a start and end date for the meeting.";
    }
    else
    {
        $sdate = trim($_POST['meetingfrom']);
        $edate = trim($_POST['meetingto']);
    }
    //validate participants
    if (!isset($_POST['participants']))
    {
        $participants_err = "Please select at least one participant for the meeting.";
    }
    if (empty($title_err) && empty($venue_err) && empty($description_err) && empty($date_err) && empty($participants_err)) //valiate error messages
    {
        validateOnPost($mysqli,$venues,$userid,$user_fullnames,$user_emails,$organiserusername);
    }
}else{echo "no post detected";}

function validateOnPost($mysqli,$venues,$userid,$user_fullnames,$user_emails,$organiserusername) {
    /*
     * Meeting data
     */
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
            $sql = "select max(meetingID) from meeting where user_userid = $userid;";
            $result = $mysqli->query($sql);
            if($result->num_rows > 0)
            {
                $meetingid = $result->fetch_assoc()['max(meetingID)'];

                /*
                 * Counter Proposal
                */
                $sql = "INSERT INTO counter_proposal (startDate, endDate, startTime, endTime, status, user_userID, meeting_meetingID, meeting_venue_venueID, meeting_user_userID) VALUES (?,?,?,?,?,?,?,?,?);";

                if ($stmt = $mysqli->prepare($sql))
                {
                    $stmt->bind_param("ssssiiiii", $param_sdate, $param_edate, $param_stime, $param_etime, $param_status, $param_uid, $param_mid, $param_vid, $param_ouid);

                    $param_sdate = $start_date;
                    $param_edate = $end_date;
                    $param_stime = $start_time;
                    $param_etime = $end_time;
                    $param_status = 1;
                    $param_ouid = $userid;
                    $param_uid = $userid;
                    $param_mid = $meetingid;
                    $param_vid = $_POST['meetingvenue'];

                    $stmt->execute();
                } else {echo "unable to prepare statement";}

                /*
                 * Participants
                 */
                $pids = $_POST['participants'];

                $sql = "INSERT INTO meeting_participants (meeting_meetingID, meeting_venue_venueID, meeting_user_userID, user_userID, status) VALUES (?,?,?,?,?);";

                if ($stmt = $mysqli->prepare($sql))
                {
                    $stmt->bind_param("iiiii", $param_mmid, $param_mvid, $param_uuid, $param_uid,$param_status);
                    $param_mmid = $meetingid;
                    $param_mvid = $_POST['meetingvenue'];
                    $param_uuid = $userid;
                    $param_status = 1;

                    require_once "send_email.php";
                    $subject = "Meeting Invitation by $organiserusername";
                    for($i = 0; $i < count($pids); $i++)
                    {
                        $pid = $pids[$i];
                        $param_uid = $pid;
                        if ($stmt->execute())
                        {
                            $p_fullname = $user_fullnames[$pid];
                            $p_email = $user_emails[$pid];
                            $message = "Hi $p_fullname,<br>
                            Title: ".$_POST['meetingtitle']."
                            You are invited to a meeting on ".$_POST['meetingfrom']."<br>
                            You can view and edit more details by logging into our website, Event Details page.<br><br>

                            Do not reply to this email.<br>
                            Thanks.";
                            $result = send_email($p_email,$subject,$message,true);
                        }
                        else {
                            // Stay on page, retry
                            echo "Something went wrong. Please try again later.";
                        }
                    }
                } else {echo "unable to prepare statement";}
            }
        } else {
            // Stay on page, retry
            echo "Something went wrong. Please try again later.";
        }
    } else {echo "unable to prepare statement";}

    // Close statement
    $stmt->close();
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

                        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-2 control-label" for="meetingtitle">Title</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="meetingtitle" placeholder="Meeting Title" value="<?php echo $title; ?>">
                                <span class="help-block"><?php echo $title_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group <?php echo (!empty($venue_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-2 control-label" for="meetingvenue">Venue</label>
                            <div class="col-md-10">
                                <select id="meetingvenue" name="meetingvenue" class="form-control">
                                    <?php display_venues($venues); ?>
                                </select>
                                <span class="help-block"><?php echo $venue_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-2 control-label">Date Time</label>
                            <div class="col-md-5">
                                <input class="form-control" id="fromdate" type="text" name="meetingfrom" placeholder="Date From" value="<?php echo $sdate; ?>">
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" id="todate" type="text" name="meetingto" placeholder="Date To" value="<?php echo $edate; ?>">
                            </div>
                            <span class="help-block"><?php echo $date_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-2 control-label" for="description">Description</label>
                            <div class="col-md-10">
                                <input class="form-control" id=description type="text" name="description" placeholder="Description of Meeting" value="<?php echo $description; ?>">
                                <span class="help-block"><?php echo $description_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group <?php echo (!empty($participants_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-2 control-label" for="participants">Participants</label>
                            <div class="col-md-10">
                                <select id="participants" name="participants[]" class="form-control" multiple>
                                    <?php display_fullnames($user_fullnames); ?>
                                </select>

                                <span class="help-block"><?php echo $participants_err; ?></span>
                                <span class="help-block">Click and drag or Ctl + Click to conduct multiple selection</span>
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
<?php $mysqli->close(); ?>
