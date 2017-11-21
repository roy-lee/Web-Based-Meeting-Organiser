<?php
// Include config file
require_once 'config.php';

if(isset($_GET['id']) && isset($_GET['mode']))
{
    if (!empty($_GET['id']) && !empty($_GET['mode']))
    {
        $id = $_GET['id'];
        $mode = $_GET['mode'];
        
        $sql = "select accountState from users where username = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $stmt->bind_result($verified);
                    $stmt->fetch();
                    if ($verified == "yes")
                    {
                        echo "This account is already verified and no action will be taken. <a href='login.php'>Proceed to login now</a>";
                    }
                    else
                    {
                        if ($mode == "verify")
                        {
                            $sql = "UPDATE users SET accountState = 'yes' WHERE username = '$id';";
                            if ($stmt = $mysqli->prepare($sql))
                            {
                                if($stmt->execute()){
                                    // Redirect to login page
                                    echo "You are successfully verified. <a href='login.php'>Proceed to login now</a>";
                                } else{
                                    echo "Something went wrong. Please try again later.";
                                }
                            }
                            else
                            {
                                echo "Unable to prepare sql statement.";
                            }
                        }
                        elseif ($mode == "delete")
                        {
                            $sql = "DELETE FROM users where username = '$id' AND accountState = 'no';";
                            if ($stmt = $mysqli->prepare($sql))
                            {
                                if($stmt->execute()){
                                    // Redirect to login page
                                    echo "Your account is successfully deleted. Your data is no longer on our database.";
                                } else{
                                    echo "Something went wrong. Please try again later.";
                                }
                            }
                        }
                                    }
                } else{
                    echo "Unable to find this account.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        $stmt->close();
    }
}
else
{
    echo "Your account was successfully registered. A verification email was sent to your email account. You will need to verify your account before you can log in.";
}

// Close connection
$mysqli->close();
?>