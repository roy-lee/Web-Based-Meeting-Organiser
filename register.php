<?php
// Include config file
require_once 'config.php';
require_once 'send_email.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = $full_name = $email = "";
$username_err = $password_err = $confirm_password_err = $full_name_err = $email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif (strlen(trim($_POST['username'])) < 6)
    {
        $username_err = "Username must have at least 6 characters.";
    }
    elseif (strlen(trim($_POST['username'])) > 20)
    {
        $username_err = "Username must not exceed 20 characters.";
    }
    elseif (preg_match('/[^A-Za-z0-9]/', trim($_POST['username'])))
    {
        $username_err = "Username must not contain symbols.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userid FROM users WHERE username = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows >= 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST['password'])) < 8){
        $password_err = "Password must have at least 8 characters.";
    } 
    elseif (strlen(trim($_POST['password']))> 20 )
    {
        $password_err = "Password must not exceed 20 characters.";
    }else{
        $alpha = $numer = false;
        $pass_buff = $_POST['password'];
        for ($i=0; $i<strlen($pass_buff);$i++)
        {
            if ($alpha && $numer)
            {
                break;
            }
            else
            {
                if(ctype_alpha($pass_buff[$i]))
                {
                    $alpha = true;
                }
                elseif(is_numeric($pass_buff[$i]))
                {
                    $numer = true;
                }
            }
        }
        if ($alpha && $numer)
        {
            $password = trim($_POST['password']);
        }
        else
        {
            $password_err = "Password must have at least an alphabet and a number.";
        }
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';
    } else{
        if($password != trim($_POST['confirm_password'])){
            $confirm_password_err = 'Passwords did not match.';
        }
        else{
            $confirm_password = trim($_POST['confirm_password']);
        }
    }
    
    // Validate full name
    if(empty(trim($_POST["full_name"]))){
        $full_name_err = 'Please enter your full name.';
    }
    elseif (strlen($_POST['full_name']) > 255)
    {
        $full_name_err = "Full name must not exceed 255 characters.";
    }
    else{
        if (preg_match('/[^A-Za-z]/', $full_name))
        {
            $full_name_err = "Full name must only contain alphabets.";
        }
        else
        {
            $full_name = trim($_POST['full_name']);
        }
    }
    
    if(empty(trim($_POST["email"]))){
        $confirm_password_err = 'Please enter your email.';
    } 
    elseif (strlen($_POST['email']) > 255)
    {
        $email_err = "Email must not exceed 255 characters.";
    }else{
        if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL))
        {
          $email_err = "Email address is not valid.";
        }
        else{
            $sql = "SELECT userid FROM users WHERE email = ?";

            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = trim($_POST["email"]);

                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // store result
                    $stmt->store_result();

                    if($stmt->num_rows >= 1){
                        $email_err = "This email is already registered.";
                    } else{
                        $email = trim($_POST['email']);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($full_name_err) && empty($email_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, fullname, email, accountState) VALUES (?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_username, $param_password,$param_fullname,$param_email,$param_state);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fullname = $full_name;
            $param_email = $email;
            $param_state = "no";
            
            $subject = 'Verification for 1004 Account';
            $message = "Hi $full_name,<br>
            Your account has been successfully registered.<br>
            You must now validate your account by clicking the link below<br>
            http://localhost/ICT1004/Project2_roy/verification.php?id=$username&mode=verify<br><br>
            If you did not register for this account, click the link below<br>
            http://localhost/ICT1004/Project2_roy/verification.php?id=$username&mode=delete<br>
            Thanks.";
            $result = send_email($email,$subject,$message,false);
            
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
        }
        // Close statement
        $stmt->close();
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
                <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <fieldset>
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Username:<sup>*</sup></label>
                        <input type="text" name="username"class="form-control" value="<?php echo $username; ?>" required>
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password:<sup>*</sup></label>
                        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" required>
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password:<sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" required>
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
                        <input type="submit" class="btn btn-primary" value="Submit">
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
