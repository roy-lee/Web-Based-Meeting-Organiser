<?php

// Validate full name
function validate_full_name()
{
    $err = "";
    
    if(empty(trim($_POST["full_name"]))){
        $err = 'Please enter your full name.';
    }
    elseif (strlen($_POST['full_name']) > 255)
    {
        $err = "Full name must not exceed 255 characters.";
    }
    else{
        if (preg_match('/[^A-Za-z ]/', trim($_POST["full_name"])))
        {
            $err = "Full name must only contain alphabets.";
        }
    }
    return $err;
}

//validate email
function validate_email($mode,$mysqli,$username)
{
    $err = "";

    if(empty(trim($_POST["email"]))){
        $confirm_password_err = 'Please enter your email.';
    } 
    elseif (strlen($_POST['email']) > 255)
    {
        $err = "Email must not exceed 255 characters.";
    }
    else
    {
        if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL))
        {
          $err = "Email address is not valid.";
        }
        else
        {
            $sql = "SELECT username FROM user WHERE email = ?";

            if($stmt = $mysqli->prepare($sql))
            {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = trim($_POST["email"]);

                // Attempt to execute the prepared statement
                if($stmt->execute())
                {
                    // store result
                    $stmt->store_result();
                    if ($mode == "profile")
                    {
                        $stmt->bind_result($db_user);
                        $stmt->fetch();
                        if ($db_user != $username && !empty($db_user))
                        {
                            $err = "This email is already registered.";
                        }
                    }
                    else if ($mode == "register" && $stmt->num_rows >= 1)
                    {
                        $err = "This email is already registered.";
                    }
                }
                else
                {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    }
    return $err;
}

// Validate password
function validate_password()
{
    $err = "";
    
    if(empty(trim($_POST['new_password']))){
        $err = "Please enter a password.";
    } elseif(strlen(trim($_POST['new_password'])) < 8){
        $err = "Password must have at least 8 characters.";
    } 
    elseif (strlen(trim($_POST['new_password']))> 20 )
    {
        $err = "Password must not exceed 20 characters.";
    }else{
        $alpha = $numer = false;
        $pass_buff = $_POST['new_password'];
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
        if (!$alpha || !$numer)
        {
            $err = "Password must have at least an alphabet and a number.";
        }
    }
    return $err;
}

// Validate confirm password
function confirm_passwords()
{
    $err = "";
    
    if(empty(trim($_POST["confirm_password"]))){
        $err = 'Please confirm password.';
    } else{
        if(trim($_POST['new_password']) != trim($_POST['confirm_password'])){
            $err = 'Passwords did not match.';
        }
    }
    return $err;
}

// Validate current password to db
function current_password($password)
{
    $err = "";
    
    if(empty(trim($_POST['current_password']))){
        $err = "Please enter your current password.";
    }
    else
    {
        if (!password_verify($_POST['current_password'], $password))
        {
            $err = "Password entered is wrong.";
        }
    }
    return $err;
}

//validate username
function validate_username($mysqli)
{
    $err = "";
    if(empty(trim($_POST["username"]))){
        $err = "Please enter a username.";
    } elseif (strlen(trim($_POST['username'])) < 6)
    {
        $err = "Username must have at least 6 characters.";
    }
    elseif (strlen(trim($_POST['username'])) > 20)
    {
        $err = "Username must not exceed 20 characters.";
    }
    elseif (preg_match('/[^A-Za-z0-9]/', trim($_POST['username'])))
    {
        $err = "Username must not contain symbols and spaces.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userid FROM user WHERE username = ?";

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
                    $err = "This username is already taken.";
                } /*else{
                    $username = trim($_POST["username"]);
                }*/
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }
    return $err;
}







?>