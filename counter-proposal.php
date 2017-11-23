<?php
$currentPage = 'counterProposal';
include("includes/header.inc.php");

require_once 'config.php';

$username = $_SESSION['username'];
$meetingid = $_GET['id'];

/* ---------------
 *
 * On page run
 *
 * ---------------  */
$userid = getUserId($mysqli,$username);
$proposals = get_all_proposals($mysqli,$meetingid);
$datestart_err = $dateend_err = "";

if (isset($_POST['meetingfrom']) && isset($_POST['meetingto']))
{
    if (!empty($_POST['meetingfrom']) && !empty($_POST['meetingto']))
    {
        if (empty($datestart_err) && empty($dateend_err))
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

            $end_datetime = date_create_from_format('D, M d, Y h:i A', $_POST['meetingto']);
            $end_date = $end_datetime->format('Y-m-d');
            $end_time = $end_datetime->format('h:i:s');
            if ((substr($_POST['meetingto'], -2) == "AM" && substr($end_time, 0, 2) == "12") || (substr($_POST['meetingto'], -2) == "PM" && substr($end_time, 0, 2) != "12"))
            {
                $timestamp = strtotime($end_time) + 60*60*12;
                $time = date('H:i:s', $timestamp);
                $end_time = $time;
            }

            $sql = "INSERT INTO counter_proposal (startDate, endDate, startTime, endTime, status, user_userID, meeting_meetingID, meeting_venue_venueID, meeting_user_userID) VALUES (?,?,?,?,?,?,?,?,?);";

            if ($stmt = $mysqli->prepare($sql))
            {
                $stmt->bind_param("ssssiiiii", $param_sdate, $param_edate, $param_stime, $param_etime, $param_status, $param_uid, $param_mid, $param_vid, $param_ouid);

                $param_sdate = $start_date;
                $param_edate = $end_date;
                $param_stime = $start_time;
                $param_etime = $end_time;
                $param_status = 2;
                $param_ouid = $proposals[0]['meeting_user_userID'];
                $param_uid = $userid;
                $param_mid = $meetingid;
                $param_vid = $proposals[0]['meeting_venue_venueID'];

                $stmt->execute();
            } else {echo "unable to prepare statement";}
        }
    }else
    {
        $datestart_err = "Please enter the start date & time";
        $dateend_err = "Please enter the end date & time";
    }
} 

$proposals = get_all_proposals($mysqli,$meetingid);
$user_prop = get_user_proposals($mysqli,$userid,$meetingid);
$meeting_info = getMeetingInfo($mysqli,$meetingid);
//display_all_proposals($proposals); //function to display details in proposal array, can edit to display html/css/js

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

function getFullname($mysqli,$userid) {
    $fullname = "";
    // Prepare a select statement
    $sql = "select fullName from user where userID = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_userID);

        // Set parameters
        $param_userID = $userid;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            //
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($userfullname);
                if ($stmt->fetch()) {
                    $fullname = $userfullname;
                }
            }
        }

        // Close statement
        $stmt->close();
        return $fullname;
    }
}

function getMeetingInfo($mysqli,$meetingid)
{
    $mtitle = "";
    $mdesc = "";
    $sql = "SELECT title, description FROM meeting where meetingID = $meetingid";
    
    $result = $mysqli->query($sql);
    
    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $mtitle =$row['title'];
            $mdesc =$row['description'];
        }
    }
    return array($mtitle,$mdesc);
}

function getMeetingVenue($mysqli,$meetingid)
{
    $mvenue = "";
    $sql = "select venue from venue where venueid = (select venue_venueid from meeting where meetingid = $meetingid);";
    
    $result = $mysqli->query($sql);
    
    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $mvenue =$row['venue'];
        }
    }
    return $mvenue;
    
}

function get_all_proposals($mysqli,$meetingid)
{
    $proposals = array();
    
    $sql = "select * from counter_proposal where meeting_meetingID = $meetingid;";
    
    $result = $mysqli->query($sql);
    
    if ($result->num_rows > 0)
    {
        $count = 0;
        while($row = $result->fetch_assoc()) 
        {
            $proposals[$count] = $row;
            $count += 1;
        }
    }
    return $proposals;
}

function get_user_proposals($mysqli,$userid,$meetingid)
{    
    $sql = "select count(*) from counter_proposal where user_userID = $userid and meeting_meetingid = $meetingid ;";
    
    $result = $mysqli->query($sql);
    $rows = mysqli_fetch_assoc($result);
    
    $count = $rows['count(*)'];
    
    return $count;
}

function display_all_proposals($mysqli,$proposals,$datestart_err,$dateend_err,$user_prop) //use a for loop to loops through this array, it is a nested array inside, u can call the values by the database column names
{
    for ($i = 0; $i < count($proposals); $i++)
    {
        echo "<div class='form-group'>
                <label class='col-md-2 control-label'>Timeslot ".(1+$i)."</label>
                <div class='col-md-5'>
                    <input class='form-control' type='text' value='".$proposals[$i]['startDate']." @ ".substr($proposals[$i]['startTime'],0,5)."hrs' disabled='disabled'>
                </div>
                <div class='col-md-5'>
                    <input class='form-control' type='text' value='".$proposals[$i]['endDate']." @ ".substr($proposals[$i]['endTime'],0,5)."hrs' disabled='disabled'>
                </div>
              </div>";
        echo "<label class='col-md-12 control-label'>Proposed by: ".getFullname($mysqli,$proposals[$i]['user_userID'])."</label>";
    }
    if ($user_prop < 5 && count($proposals) < 10)
    {
        echo "<div class='form-group ".((!empty($datestart_err) || !empty($dateend_err)) ? "has-error" : "")."'>
            <label class='col-md-2 control-label'>Date Time</label>
            <div class='col-md-5'>
                <input class='form-control' id='fromdate' type='text' name='meetingfrom' placeholder='Date From'>
                <span class='help-block'>".$datestart_err."</span>
            </div>
            <div class='col-md-5'>
                <input class='form-control' id='todate' type='text' name='meetingto' placeholder='Date To'>
                <span class='help-block'>".$dateend_err."</span>
            </div>
           </div>
           <div class='form-group'>
                <div class='col-md-12 widget-right'>
                    <button type='submit' class='btn btn-primary btn-md pull-right'>Submit</button>
                    <button type='reset' class='btn btn-default btn-md pull-right'>Reset</button>
                </div>
            </div>";
    }
    else if ($user_prop >= 5)
    {
        echo "<label class='col-md-12 control-label'>You have already proposed 5 counter proposals, let others have a chance.</label>";
    }
    else if (count($proposals) >= 10)
    {
        echo "<label class='col-md-12 control-label'>No more counter proposals could be made for this meeting.</label>";
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
            <li class="active">Counter Proposal</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Counter Proposal</h1>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">Counter Proposal</div>
                <div class="panel-body">
                    <form class="form-horizontal row-border" action="counter-proposal.php?id=<?php echo $meetingid; ?>" name="counter-propose" id="counter-propose" method="post">

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="meetingtitle">Title</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="title" value="<?php echo $meeting_info[0]; ?>" disabled="disabled"></input>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="meetingvenue">Venue</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="meetingvenue" value="<?php echo getMeetingVenue($mysqli,$meetingid); ?>" disabled="disabled"></input>
                                <span class="help-block"></span>
                            </div>
                        </div>
                
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="description">Description</label>
                            <div class="col-md-10">
                                <input class="form-control" id=description type="text" name="description" value="<?php echo $meeting_info[1]; ?>" disabled="disabled">            
                                <span class="help-block"></span>
                            </div>
                        </div>
                
                        <?php display_all_proposals($mysqli,$proposals,$datestart_err,$dateend_err,$user_prop); ?>
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
<?php $mysqli->close(); ?>