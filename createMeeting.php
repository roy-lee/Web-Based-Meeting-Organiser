<?php
$currentPage = 'createMeeting';
include("includes/header.inc.php");

require_once 'config.php';

// Get user's ID
$userid = $_SESSION['userid'];


/* --------------- 
 * 
 * Post Validation 
 * 
 * ---------------  */

// startDate, endDate, startTime, endTime, title, description, venue_venueID, user_userID
// Variables
$startDate = $endDate = $startTime = $endTime = $title = $description = $venue = "";
$startDate_err = $endDate_err = $startTime_err = $endTime_err = $title_err = $description_err = $venue_err = "";

// Returned from db
$venueID = "";
$meetingID = "";

function insertVenue() {

    // Validate username
    if (empty(trim($_POST["venue"]))) {
        $venue_err = "Please enter a venue.";
    } else {
        // Prepare a select statement
        $sql = "INSERT INTO `meeting_organiser`.`venue` (`venue`) VALUES (?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_venue);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $venueID = $stmt->insert_id;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    return $venueid;
}

function validateBlanks() {

    // Validate blanks
    if (empty(trim($_POST["startDate"]))) {
        $startDate_err = "Please enter a start date.";
    }
    if (empty(trim($_POST["endDate"]))) {
        $endDate_err = "Please enter a end date";
    }
    if (empty(trim($_POST["startTime"]))) {
        $startTime_err = "Please enter a start time.";
    }
    if (empty(trim($_POST["endTime"]))) {
        $endTime_err = "Please enter an end time.";
    }
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter the meeting title.";
    }
    //    if (empty(trim($_POST["description"]))) {
    //        $description_err = "Please enter a description.";
    //    }
    if (empty(trim($_POST["venue"]))) {
        $venue_err = "Please enter venue.";
    }
}

function validateOnPost() {

    // Insert into venue and get id of insert
    insertVenue();

    // validate blank inputs
    validateBlanks();

    // Insert into meeting table
    // Check input errors before inserting in database
    if (empty($startDate_err) && empty($endDate_err) && empty($startTime_err) && empty($endTime_err) && empty($title_err) && empty($venue_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO "
                . "`meeting_organiser`.`meeting` (`startDate`, `endDate`, `startTime`, `endTime`, `title`, `description`, `venue_venueID`, `user_userID`) "
                . " VALUES (?, ?, ?, ?, ?, ?, ?, ?); ";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssss", $param_startDate, $param_enddate, $param_startTime
                    , $param_endTime, $param_title, $param_description, $param_venueID, $param_user_userID);

            // startDate, endDate, startTime, endTime, title, description, venue_venueID, user_userID
            $param_startDate = trim($_POST["startDate"]);
            $param_enddate = trim($_POST["endDate"]);
            $param_startTime = trim($_POST["startTime"]);
            $param_endTime = trim($_POST["endTime"]);
            $param_title = trim($_POST["title"]);
            $param_description = trim($_POST["meetingtitle"]);
            $param_venueID = trim($_POST["venue_venueID"]);
            $param_user_userID = trim($_SESSION['userid']);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Executed, pass to index, show meetings / insert meeting participants
                $meetingID = $stmt->insert_id; // Get the inserted statement's id
                insertParticipants();
            } else {
                // Stay on page, retry
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    function insertParticipants() {
        // Participants, can be add
        $participantids = $_POST["participantids"];

        if (empty(trim($_POST["participantids"]))) {
            $participant_err = "Please enter select at least one participant.";
        }

        $insertedmeetingid = $meetingid;

        // Insert participants into participant table
        foreach ($participantids as $pid) {
            // Prepare an insert statement
            $sql = "INSERT INTO meeting_participants (meeting_meetingid, user_userid) VALUES (?, ?)";

            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $param_meetingid, $param_participantid);

                // Set parameters
                $param_meetingid = $insertedmeetingid;
                $param_participantid = $pid;

                // Attempt to execute the prepared statement
                while ($stmt->execute()) {
                    // On executed
                }
                header("location: index.php");
            }

            // Close statement
            $stmt->close();
        }
    }

}

$x = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //    $p_startDate = trim($_POST["startDate"]);
    //    $p_enddate = trim($_POST["endDate"]);
    //    $p_startTime = trim($_POST["startTime"]);
    //    $p_endTime = trim($_POST["endTime"]);
    //    $p_title = trim($_POST["title"]);
    //    $p_description = trim($_POST["meetingtitle"]);
    //    $p_venueID = trim($_POST["venue_venueID"]);
    //    $p_user_userID = trim($_SESSION['userid']);
    //
    //    $x = $p_startDate . "\n"
    //            . $p_enddate . "\n"
    //            . $p_startTime . "\n"
    //            . $p_endTime . "\n"
    //            . $p_title . "\n"
    //            . $p_description . "\n"
    //            . $p_venueID . "\n"
    //            . $p_user_userID;
    
     validateOnPost();
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
            <div>
                <?php //echo $x ?>
            </div>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">Meeting Details</div>
                <div class="panel-body">
                    <form class="form-horizontal row-border" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="createMeeting" id="createMeetingForm">

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="title">Title</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="title" placeholder="Meeting Title">
                                <span class="help-block"><?php echo $title_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="venue">Venue</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="venue" placeholder="Meeting Venue">
                                <span class="help-block"><?php echo $venue_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="description">Description</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="description" placeholder="Description">
                                <span class="help-block"><?php echo $description_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Date</label>
                            <div class="col-md-5">
                                <input class="form-control" id="startDate" type="text" name="startDate" placeholder="Meeting start date">
                                <span class="help-block"><?php echo $startDate_err; ?></span>
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" id="endDate" type="text" name="endDate" placeholder="Meeting end date">
                                <span class="help-block"><?php echo $endDate_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Time</label>
                            <div class="col-md-5">
                                <input class="form-control" id="startTime" type="text" name="startTime" placeholder="Meeting start time">
                                <span class="help-block"><?php echo $startTime_err; ?></span>
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" id="endTime" type="text" name="endTime" placeholder="Meeting end time">
                                <span class="help-block"><?php echo $endTime_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="checkboxes">Select Participants</label>
                            <div class="col-md-4">
                                <?php
                                $userid = $username = "";
                                // Prepare a select statement
                                $sql = "SELECT userID, username FROM user WHERE verified ='yes'"
                                        . " AND userID NOT IN ( SELECT userID FROM user WHERE userID = ' " . $_SESSION['userid'] . " ' )";
                                if ($stmt = $mysqli->prepare($sql)) {
                                    // Attempt to execute the prepared statement
                                    if ($stmt->execute()) {
                                        // Store result
                                        $stmt->store_result();
                                        // Bind result variables
                                        $stmt->bind_result($retUserid, $retUsername);
                                        while ($stmt->fetch()) {
                                            echo "<div class='checkbox'>";
                                            echo "<label for='participants[]'>";
                                            echo "<input type='checkbox' name='participants[]' value='" . $retUserid . "' />";
                                            echo $retUsername . "</label>";
                                            echo "</div>";
                                        }
                                    } else {
                                        echo "Unable to retrieve participants. Please try again later.";
                                    }
                                }
                                // Close statement
                                $stmt->close();
                                ?>
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