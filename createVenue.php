<?php
$currentPage = 'createVenue';
include("includes/header.inc.php");

require_once 'config.php';

/* ---------------
 *
 * Post Validation
 *
 * ---------------  */
// Variables
$newVenueName = $success = "";
// Errors
$newVenueName_err = "";
$newVenueSuccessMsg = "";
 // Invalid input only
//$_POST["meetingtitle"]; //$_POST["meetingvenue"]; //$_POST["meetingallday"];
//$_POST["meetingfrom"]; //$_POST["meetingto"]; //$_POST["meetingrepeat"];

if(isset($_GET['id']))
{
    echo $_GET['id'];
    $sql1 = "select * from meeting where venue_venueid=".$_GET['id'].";";
    $result1 = $mysqli->query($sql1);
    if ($result1->num_rows > 0)
    {
        echo "
            <div class='col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main'>
             <div class='row'>
                 <div class='col-lg-12 col-md-12'>
                    <div class='alert bg-red' role='alert'><em class='fa fa-lg fa-warning'>&nbsp;</em>Venue used by other meetings and cannot be removed!</div>
                 </div>
             </div>
            </div>
            ";
    }
    else
    {
        $sql2 = "delete from venue where venueid=".$_GET['id'].";";
        $mysqli->query($sql2);
        echo "
            <div class='col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main'>
             <div class='row'>
                 <div class='col-lg-12 col-md-12'>
                    <div class='alert bg-teal' role='alert'><em class='fa fa-lg fa-warning'>&nbsp;</em>Venue successfully deleted!</div>
                 </div>
             </div>
            </div>
            ";
    }
}


 if(isset($_POST['save']) && empty($newVenueName_err))
{
     if(!empty($_POST['newVenueName']))
     {
        $sql1 = "SELECT venue from venue";
        $result = $mysqli->query($sql1);
        $valid = 1;

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                if ($row['venue'] == $_POST["newVenueName"])
                {
                    $valid = 0;
                }
            }
        }

        if ($valid)
        {
            $sql = "INSERT INTO venue (venue) VALUES ('".$_POST["newVenueName"]."')";

            $result = mysqli_query($conn,$sql);
            echo "
            <div class='col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main'>
             <div class='row'>
                 <div class='col-lg-12 col-md-12'>
                    <div class='alert bg-teal' role='alert'><em class='fa fa-lg fa-warning'>&nbsp;</em>New venue successfully created!</div>
                 </div>
             </div>
            </div>
            ";
        }
         else
         {
             $newVenueName_err = "Venue already exists and cannot be inserted.";
         }
     }else { $newVenueName_err = "Please enter a venue.";}
}



// if (isset($_POST["save"]))
// {
//     validateOnPost($mysqli,$newVenueName);
// }
//
// function validateOnPost($mysqli,$newVenueName) {
//
//     // Validate blanks
//     if (empty(trim($_POST["newVenueName"]))) {
//         $newVenueName_err = "Please enter a venue.";
//     }
//     /*
//      * Venue data
//      */
//     // Check input errors before inserting in database
//     if (empty($newVenueName_err)) {
//
//         // Prepare an insert statement
//         $sql = "INSERT INTO venue (venue) VALUES ?;";
//
//         if ($stmt = $mysqli->prepare($sql)) {
//             // Bind variables to the prepared statement as parameters
//             $stmt->bind_param("s", $_POST['newVenueName']);
//             // Attempt to execute the prepared statement
//             if ($stmt->execute()) {
//               $stmt->store_result();
//             }
//           }
//         }
//       }
  //           else {
  //               // Stay on page, retry
  //               echo "Something went wrong. Please try again later.";
  //           }
  //         } else {
  //       echo "unable to prepare statement";
  //     }
  //   }
  // }


// Close statement
$stmt->close();
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
            <li class="active">Create Venue</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create a Venue</h1>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">Meeting Details</div>
                <div class="panel-body">
                    <form class="form-horizontal row-border" action="createVenue.php" name="createVenue" id="createVenueForm" method="post">

                        <div class="form-group <?php echo (!empty($newVenueName_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-2 control-label" for="meetingtitle">New Venue</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="newVenueName" placeholder="Location Name" required>
                                <span class="help-block"><?php echo $newVenueName_err; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                          <div class="col-md-10">
                                <button type="submit" name="save" class="btn btn-primary btn-md">Submit</button>
                                <button type="reset" class="btn btn-default btn-md">Reset</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div style="visibility:hidden;" class="alert bg-teal "<?php if($success =='true'){echo 'show';}?>"" role="alert"><em class="fa fa-lg fa-warning">&nbsp;</em><?php echo $newVenueSuccessMsg; ?></div>
            </div>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h2>All Venues</h2>
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
                                              <th data-field="state" data-sortable="true">S/N</th>
                                              <th data-field="id" data-sortable="true">Venue</th>
                                              <th data-field="delete">Delete</th>
                                            </tr>
                                          </thead>
                                          <?php

                                          $query = "SELECT * from venue";

                                          if ($result = $mysqli->query($query)) {

                                              /* fetch associative array */
                                              $i = 1;
                                              while ($row = $result->fetch_assoc()) {
                                                echo"
                                                    <tr>
                                                      <td>".$i."</td>
                                                      <td>".$row['venue']."</td>
                                                      <td><a href='createVenue.php?id=".$row['venueID']."'><button type='button' class='btn btn-sm btn-danger'>Delete</button></a></td>
                                                    </tr>";
                                                    ++$i;
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

<?php include("includes/footer.inc.php"); ?>
