changes from the master branch

db: using the same one i send out during whatsapp, hence maybe if users table not changed, it should be alright to use any other db

edited: nav.php (included role into session and navbar), user-profile.php, register.php, login.php

new: fields_validation (includes validation for most fields, used by register.php and user-profile.php, not needed for login.php)

*my send email function set to true to skip imap validation as it now seems to be unstable and fails the validation,
gmail seems to be able to send out the email to fake e-mail addresses,
hence i guess we just stick to javascript and php email validation for the time being