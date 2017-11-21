<?php
$currentPage = 'userProfile';
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
else {
    $username = $_SESSION['username'];
    require_once "config.php";

    $sql = "SELECT email, fullName, password from users where username = ?";

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
                    $user_fullname = $fullName;
                    $user_currentpass = $password;
                }
            }
        }
    }
    // Define variables and initialize with empty values
    $current_password = $new_password = $confirm_password = $full_name = $email = "";
    $current_password_err = $new_password_err = $confirm_password_err = $full_name_err = $email_err = "";
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
                        <input type="text" name="username"class="form-control" value="<?php echo $user_fullname; ?>" required>
                        <span class="help-block"><?php echo $full_name_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email:<sup>*</sup></label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user_email; ?>" required>
                        <span class="help-block"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Current Password:<sup>*</sup></label>
                        <input type="password" name="current_password" class="form-control" value="<?php echo $current_password; ?>" required>
                        <span class="help-block"><?php echo $current_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>New Password:<sup>*</sup></label>
                        <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>" required>
                        <span class="help-block"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password:<sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" required>
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Update">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                      <button class="btn btn-info" onclick="location.href = './index.php';">Go back to Homepage</button>
                    </fieldset>
                </form>
              </div>
            </div>
          </div><!-- /.col-->
        </div><!-- /.row -->
</body>
</html>
