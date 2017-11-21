<?php

session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
else {
    $username = $_SESSION['username'];    
    require_once "config.php";
    
    $sql = "SELECT email, fullname, password FROM user WHERE username = ?";
    
    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s",$param_username);
        $param_username = $username;
        
        if($stmt->execute())
        {
            $stmt->store_result();
            
            if ($stmt->num_rows == 1)
            {
                $stmt->bind_result($email,$fullname,$password);
                if ($stmt->fetch())
                {
                    $user_email = $email;
                    $user_fullname = $fullname;
                    $user_currentpass = $password;
                }
            }
        }
    }
    
    // Define variables and initialize with empty values
    $new_password = $confirm_password = "";
    $current_password_err = $new_password_err = $confirm_password_err = $full_name_err = $email_err = "";
    $final_changes = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['full_name']) && isset($_POST['email']) && isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password']))
        {
            require_once "fields_validation.php";
            $final_changes = "*No changes were made due to invalid input*";            
            
            $full_name_err = validate_full_name();
            if (empty($full_name_err))
            {
                $user_fullname = trim($_POST['full_name']);
            }
            $email_err = validate_email("profile",$mysqli,$username);
            if (empty($email_err))
            {
                $user_email = trim($_POST['email']);
            }
            $current_password_err = current_password($user_currentpass);
            $new_password_err = validate_password();
            $confirm_password_err = confirm_passwords();
            
        }
        if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err) && empty($full_name_err) && empty($email_err))
        {
            $sql = "UPDATE user SET fullname = ?, email = ?, password = ? WHERE username = ?;";
            if($stmt = $mysqli->prepare($sql))
            {
                $stmt->bind_param("ssss",$param_fullname,$param_email,$param_password,$param_username);
                $param_fullname = trim($_POST['full_name']);
                $param_email = trim($_POST['email']);
                $param_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $param_username = $username;
                
                if ($stmt->execute())
                {
                    $stmt->close();
                    $final_changes = "Changes were updated successfully!";

                }
                else
                {
                    echo "Something went wrong. Please try again later.";
                }
            }
            else
            {
                echo "Unable to prepare statement";
            }
            
        }
    }
    
    $mysqli->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - Meeting Organiser</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .form-group > .btn {width: 49%; display: inline-block; }
    </style>
</head>
<body>
    <div class="row">
          <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
              <div class="panel-heading">Your Profile</div>
              <div class="panel-body">
                <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <fieldset>
                    <div class="form-group <?php echo (!empty($full_name_err)) ? 'has-error' : ''; ?>">
                        <label>Full Name:<sup>*</sup></label>
                        <input type="text" name="full_name"class="form-control" value="<?php echo $user_fullname; ?>" required>
                        <span class="help-block"><?php echo $full_name_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email:<sup>*</sup></label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user_email; ?>" required>
                        <span class="help-block"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($current_password_err)) ? 'has-error' : ''; ?>">
                        <label>Current Password:<sup>*</sup></label>
                        <input type="password" name="current_password" class="form-control" required>
                        <span class="help-block"><?php echo $current_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                        <label>New Password:<sup>*</sup></label>
                        <input type="password" name="new_password" class="form-control" required>
                        <span class="help-block"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password:<sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control" required>
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Update">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                      <a href='event-details.php' class='btn btn-info'>Go back to Homepage</a>
                      <span class="help-block text-center"><?php echo $final_changes; ?></span>
                    </fieldset>
                </form>
              </div>
            </div>
          </div><!-- /.col-->
        </div><!-- /.row -->
</body>
</html>