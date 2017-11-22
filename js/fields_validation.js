function validate_login()
{
    var form_name = "login";
    
    var help_blocks = document.getElementsByClassName("help-block");
    var form_groups = document.getElementsByClassName("form-group");
    
    var username = document.forms[form_name]["username"].value;
    var password = document.forms[form_name]["password"].value;
    
    var err = "";
    
    err += validate_username(username,help_blocks[0],form_groups[0]);
    err += validate_pass1(password,help_blocks[1],form_groups[1]);
    
    if (err != "")
    {
        alert("Unable to proceed due to the following:\n\n"+err);
        return false;
    }
    else
    {
        return true;
    }
}

function validate_register()
{
    var form_name = "register";
    
    var help_blocks = document.getElementsByClassName("help-block");
    var form_groups = document.getElementsByClassName("form-group");
    
    var username = document.forms[form_name]["username"].value;
    var password = document.forms[form_name]["new_password"].value;
    var confirm_password = document.forms[form_name]["confirm_password"].value;
    var fullname = document.forms[form_name]["full_name"].value;
    var email = document.forms[form_name]["email"].value;
    
    var err = "";
    
    err += validate_username(username,help_blocks[0],form_groups[0]);
    err += validate_pass1(password,help_blocks[1],form_groups[1]);
    err += validate_pass2(password,confirm_password,help_blocks[2],form_groups[2]);
    err += validate_fname(fullname,help_blocks[3],form_groups[3]);
    err += validate_email(email,help_blocks[4],form_groups[4]);
    
    if (err != "")
    {
        alert("Unable to proceed due to the following:\n\n"+err);
        return false;
    }
    else
    {
        return true;
    }
}

function validate_profile()
{
    var form_name = "profile";
    
    var help_blocks = document.getElementsByClassName("help-block");
    var form_groups = document.getElementsByClassName("form-group");
    
    var fullname = document.forms[form_name]["full_name"].value;
    var email = document.forms[form_name]["email"].value;
    var current_password = document.forms[form_name]["current_password"].value;
    var new_password = document.forms[form_name]["new_password"].value;
    var confirm_password = document.forms[form_name]["confirm_password"].value;
    
    var err = "";
    
    err += validate_fname(fullname,help_blocks[0],form_groups[0]);
    err += validate_email(email,help_blocks[1],form_groups[1]);
    err += validate_pass1(current_password,help_blocks[2],form_groups[2]);
    err += validate_pass1(new_password,help_blocks[3],form_groups[3]);
    err += validate_pass2(new_password,confirm_password,help_blocks[4],form_groups[4]);
    
    if (err != "")
    {
        alert("Unable to proceed due to the following:\n\n"+err);
        return false;
    }
    else
    {
        return true;
    }
}

function validate_username(uname,hb,fg)
{
    var msg = "";
    if (uname == "")
    {
        msg += "Username cannot be left empty\n";
    }
    else if (uname.length < 6 || uname.length > 20)
    {
        msg += "Username needs to be between 8-20 characters long\n";
    }
    else if(/[^a-zA-Z0-9]/.test(uname) == true)
    {
        msg += "Username can only contain alphabets and numbers\n"
    }
    if (msg != "")
    {
        fg.className = "form-group has-error";
    }
    else
    {
        fg.className = "form-group";
    }
    hb.innerHTML = msg;
    return msg;
}

function validate_fname(fname,hb,fg)
{
    var msg = "";
    if (fname == "")
    {
        msg += "Full Name cannot be left empty\n";
    }
    else if(/[^a-zA-Z ]/.test(fname) == true)
    {
        msg += "Full Name can only contain alphabets\n"
    }
    if (msg != "")
    {
        fg.className = "form-group has-error";
    }
    else
    {
        fg.className = "form-group";
    }
    hb.innerHTML = msg;
    return msg;
}

function validate_email(email,hb,fg)
{
    var msg = "";
    if (email == "")
    {
        msg += "Email address cannot be left empty\n";
    }
    else
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (re.test(email) == false)
        {
            msg += "Email format is invalid\n";
        }
    }

    if (msg != "")
    {
        fg.className = "form-group has-error";
    }
    else
    {
        fg.className = "form-group";
    }
    hb.innerHTML = msg;
    return msg;
}

function validate_pass1(pass1,hb,fg)
{
    var msg = "";
    if (pass1 == "")
    {
        msg += "Password cannot be left empty\n";
    }
    else if (pass1.length < 8 || pass1.length > 20)
    {
        msg += "Password needs to be between 8-20 characters long\n";
    }
    else
    {
        var alpha = 0;
        var numer = 0;
        var invalid_symbols = 0;
        for (var i = 0; i < pass1.length; i++)
        {
            if (alpha > 0 && numer > 0 && invalid_symbols > 0)
            {
                break;
            }
            else
            {
                console.log(/['"&`()|<> ]/.test(pass1[i]))
                if (/['"&`()|<> ]/.test(pass1[i]) == true)
                {
                    invalid_symbols += 1;
                }
                else if (/[a-zA-Z]/.test(pass1[i]) == true)
                {
                    alpha += 1;
                }
                else if (/[0-9]/.test(pass1[i]) == true)
                {
                    numer += 1;
                }
            }
        }
        if (alpha == 0 || numer == 0 || invalid_symbols == 1)
        {
            msg += "Password needs to be alpha-numeric and should not contain certain symbols\n";
        }
    }
    if (msg != "")
    {
        fg.className = "form-group has-error";
    }
    else
    {
        fg.className = "form-group";
    }
    hb.innerHTML = msg;
    return msg;
}

function validate_pass2(pass1,pass2,hb,fg)
{
    var msg = "";
    if (pass2 == "")
    {
        msg += "Confirm password cannot be left empty";
    }
    else if (pass1.length < 8 || pass1.length > 20)
    {
        msg += "Confirm password needs to be between 8-20 characters long\n";
    }
    else if (pass1 != pass2)
    {
        msg += "Both passwords do not match\n";
    }
    if (msg != "")
    {
        fg.className = "form-group has-error";
    }
    else
    {
        fg.className = "form-group";
    }
    hb.innerHTML = msg;
    return msg;
}
              