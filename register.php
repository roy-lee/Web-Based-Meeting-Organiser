<?php
// Include config file
require_once 'config.php';
require_once 'send_email.php';
require_once 'fields_validation.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = $full_name = $email = "";
$username_err = $password_err = $confirm_password_err = $full_name_err = $email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $username_err = validate_username($mysqli);
    if(empty($username_err))
    {
        $username = trim($_POST['username']);
    }
    $password_err = validate_password();
    if(empty($password_err))
    {
        $password = trim($_POST['new_password']);
    }
    $confirm_password_err = confirm_passwords();
    if(empty($confirm_password_err))
    {
        $confirm_password = trim($_POST['confirm_password']);
    }
    $full_name_err = validate_full_name();
    if(empty($full_name_err))
    {
        $full_name = trim($_POST['full_name']);
    }
    $email_err = validate_email("register",$mysqli,"");
    if(empty($email_err))
    {
        $email = trim($_POST['email']);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($full_name_err) && empty($email_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO user (username, password, fullname, email, verified) VALUES (?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_username, $param_password,$param_fullname,$param_email,$param_verify);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fullname = $full_name;
            $param_email = $email;
            $param_verify = "no";
            
            $subject = 'Verification for 1004 Account';
            $message = "Hi $full_name,<br>
            Your account has been successfully registered.<br>
            You must now validate your account by clicking the link below<br>
            http://localhost/ICT1004/Project2_roy/verification.php?id=$username&mode=verify<br><br>
            If you did not register for this account, click the link below<br>
            http://localhost/ICT1004/Project2_roy/verification.php?id=$username&mode=delete<br><br>
            Do not reply to this email.<br>
            Thanks.";
            $result = send_email($email,$subject,$message,true);
            
            if ($result == true)
            {
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Redirect to login page
                    header("location: verification.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }
                
            }
            else {
                $email_err = "Unable to send email to this email address.";
            }
        // Close statement
        $stmt->close();
        }
        
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Meeting Organiser</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/fields_validation.js" type="application/javascript"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .form-group > .btn {width: 49%; display: inline-block; }
    </style>
</head>
<body>
    <div class="row">
          <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
              <div class="panel-heading">Sign Up</div>
              <div class="panel-body">
                <form role="form" name="register" onsubmit="return validate_register()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <fieldset>
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Username:<sup>*</sup></label>
                        <input type="text" name="username"class="form-control" value="<?php echo $username; ?>" required>
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password:<sup>*</sup></label>
                        <input type="password" name="new_password" class="form-control" required>
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password:<sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control" required>
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($full_name_err)) ? 'has-error' : ''; ?>">
                        <label>Full Name:<sup>*</sup></label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo $full_name; ?>" required>
                        <span class="help-block"><?php echo $full_name_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email:<sup>*</sup></label>
                        <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                        <span class="help-block"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Register">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                    </fieldset>
                </form>
              </div>
            </div>
          </div><!-- /.col-->
        </div><!-- /.row -->
</body>
</html>
