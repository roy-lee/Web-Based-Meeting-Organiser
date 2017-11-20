<?php
    include("./php_mailer/src/PHPMailer.php");
    include("./php_mailer/src/SMTP.php");
    #include("./php_mailer/scr/Exception.php");
    
    //input false into skip to enable bounce checking
    function send_email($email,$subject,$message,$skip)
    {
        //credential for email account used to send email
        $username = "just41004@gmail.com";
        $password = 'Ju$t41004!';
        
        $recipient = $email;
        $sent = false;
        
        //create new PHPMailer class
        $mail = new PHPMailer\PHPMailer\PHPMailer;

        //Server settings for Gmail
        $mail->SMTPDebug = 0;                                 // 2 to Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $username;                          // SMTP username
        $mail->Password = $password;                            // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('just41004@gmail.com', 'ICT1004 Project Team');  //Setting from address and name
        $mail->addAddress($recipient);              // Add a recipient

        //Content
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = $message;

        if (!$mail->send()) //if message could not be sent due to connection to smtp server error
        {
            $mail->SmtpClose();
        }
        else
        {
            $mail->SmtpClose();
            if ($skip == false)
            {
                sleep(10); //Sleep to wait for mailbox to sort the rejected mail into the bounced mailbox we will read for later
                try 
                {
                    $hostname = '{imap.gmail.com:993/imap/ssl}Bounced';
                    $inbox = imap_open($hostname,$username,$password);

                    $emails = imap_search($inbox,'ALL');
                    $bounced = false;
                    if($emails) 
                    {
                        /* begin output var */
                        $output = '';

                        /* put the newest emails on top */
                        rsort($emails);

                        /* for every email... */
                        foreach($emails as $email_number) 
                        {
                            /*
                            // get information specific to this email
                            $overview = imap_fetch_overview($inbox,$email_number,0);

                            // output the email header information
                            $subject_info = $overview[0]->subject;
                            $from_info = $overview[0]->from;
                            $date_info = $overview[0]->date;*/

                            $message = imap_fetchbody($inbox,$email_number,2);
                            //check message if it contains details about this message being sent
                            if (strpos($message,$recipient) !== false && strpos($message,$username) !== false && strpos($message,"googlemail.com"))
                            {

                                imap_delete($inbox,$email_number);
                                imap_expunge($inbox);
                                $bounced = true;
                                break;
                            }

                        }

                    }
                    if($bounced == false)
                    {
                        $sent = true;
                    }
                    /* close the connection */
                    imap_close($inbox);
                } 
                catch (Exception $e) //if the webserver did not enable imap connection or something went wrong with the imap connection, assume email was sent and not bounced
                { 
                    $sent = true;
                }
            }
            else
            {
                $sent = true;
            }
        }
        return $sent;
    }

    
?>