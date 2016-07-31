
/* Function to call all validation functions 
  relating to the sign-up page
*/
function validateSignUp()
{
  return (validateBlanks() 
       && validateEmail()
       && validateChars()
       && validatePasswords());
}

// Function to validate all fields against blank values
function validateBlanks()
{
  var fullName = getFullNameElement();
  var email = getEmailElement();
  var password = getPasswordElement();
  var confPassword = getConfirmPasswordElement();

  var fields = [fullName, email, password, confPassword];

  var i, fieldCount = fields.length;

  for(i = 0; i < fieldCount; i++)
  {
    if(fields[i].value == "")
    {
      alert(" All fields are required and cannot be left blank!");
      fields[i].style.borderColor = "red";

      return false;
    }
  }
  return true;
}

// Function to validate that the email is in correct format
function validateEmail()
{
  var email = getEmailElement();
  
  var emailFormat = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

  if(!email.value.match(emailFormat))
  {
    alert("Invalid email!");
  
    return false;
  }
  else
  {
    return true;
  }
}

// Validate the full name field for special chars
function validateChars()
{
  var nameChars = /^[A-Za-z\s]+$/;
  
  var fullName = getFullNameElement();

  if(!fullName.value.match(nameChars))
  {
    alert("Full name must only contain letters!");
    
    return false;
  }
  else
  {
    return true;
  }
}

/* 
  Function to validate passwords for non-matching passwords
  and for password not meeting length requirements
*/
function validatePasswords()
{
  var pwd = getPasswordElement();
  var conf_pwd = getConfirmPasswordElement();

  if(pwd.value != conf_pwd.value)
  {
    alert("Passwords do not match!");

    return false;
  }
  else if(pwd.value.length < 6 || conf_pwd.value.length < 6)
  {
    alert("Password must be at least 6 characters long!");

    return false;
  }
  else
  {
    return true;
  }
}


// Getters
function getFullNameElement()
{
  return document.getElementById("full_name_txt");
}

function getEmailElement()
{
  return document.getElementById("email_txt");
}

function getPasswordElement()
{
  return document.getElementById("pwd_txt");
}

function getConfirmPasswordElement()
{
  return document.getElementById("conf_pwd_txt");
}

function getSubmitBtnElement()
{
  return document.getElementById("submit");
}

// Function to resolve field names into a more legible format
function fieldNameResolution(field)
{
  if(field.id == "full_name_txt")
  {
    return "full name";
  }
  else if(field == "email_txt")
  {
    return "email";
  }
  else if(field == "pwd_txt")
  {
    return "password";
  }
  else if(field == "conf_pwd_txt")
  {
    return "confirm password";
  }
}


