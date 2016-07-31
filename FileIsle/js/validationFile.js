/*Error Messaging*/

/*Function to populate the error_msg (div) dynamically*/
  function populateErrorMsg(errorMsg)
  {
    var errorMsgDiv = document.getElementById("error_msg");
    errorMsgDiv.style.display = 'block';
    errorMsgDiv.innerHTML = errorMsg;
  }

  /*Function to reset the error_msg (div) in its initial state*/
  function resetErrorMsg()
  {
    var errorMsgDiv = document.getElementById("error_msg");
    errorMsgDiv.style.display = 'none';
  }


/* The below functions may be used to validate multiple values
within both Registration, Login and Forgot Password pages
they may be used to validate different fields so that data can
be controlled and may increse validate the data to be gathered*/

//Registration page validation
function validateRegistration(form){
   return (
        validateTextInput(form));
//     && validateFirstAndLastName()
//     && validateEmail(form)
//     && validateSelectOpt()
//     && validatePassword(form)
//     && matchPasswords(form));
}

//Login page validation
function validateLogin(form){
   return (validateTextInput(form)
     && validatePassword(form));
}

//Forgot Password page validation
function validateForgotPassword(form){
   return (validateTextInput(form)
     && validateSelectOpt()
     && validatePassword(form)
     && matchPasswords(form));
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

// Form Validation - Normal Inputs
function validateTextInput(form)
{
  for(var i=0; i<form.elements.length; i++)
  {
    if(form.elements[i].type === "text" && form.elements[i].value === "")
    {
      form.elements[i].focus();
      form.elements[i].style.borderColor = "red";
      form.elements[i].style.borderWidth = "thick";
      populateErrorMsg("Kindly enter your " + form.elements[i].name);
      return false;
    }   
    else
    {
      resetErrorMsg();
      form.elements[i].style.borderColor = "#282828";
      form.elements[i].style.borderWidth = "initial";
    }
  }
  return true;  
}

//Validation First and Last to avoid the user entering any numerical chars
function validateFirstAndLastName()
{
  var fName = document.getElementById("f_name");
  var lName = document.getElementById("l_name");
  var allowedChars = /^[A-Za-z]+$/;

   if(!fName.value.match(allowedChars) && !lName.value.match(allowedChars))
   {
    fName.style.borderColor = "red";
    fName.style.borderWidth = "thick";
    lName.style.borderColor = "red";
    lName.style.borderWidth = "thick";
    populateErrorMsg("First and Last names cannot contain numbers!");
    return false;
   }
   else if(!lName.value.match(allowedChars))
   {
    fName.style.borderColor = "#282828";
    fName.style.borderWidth = "initial";
    lName.focus();
    lName.style.borderColor = "red";
    lName.style.borderWidth = "thick";
    populateErrorMsg("First and Last names cannot contain numbers!");
    return false;
   }
   else if(!fName.value.match(allowedChars))
   {
    lName.style.borderColor = "#282828";
    lName.style.borderWidth = "initial";
    fName.focus();
    fName.style.borderColor = "red";
    fName.style.borderWidth = "thick";
    populateErrorMsg("First and Last names cannot contain numbers!");
    return false;
   }
   else
  {
    resetErrorMsg();
    fName.style.borderColor = "#282828";
    fName.style.borderWidth = "initial";
    lName.style.borderColor = "#282828";
    lName.style.borderWidth = "initial";
  }
  return true;
}

//Email Address Validation
function validateEmail(form)
{
  var emailValue = form.elements["email_add"].value;
  var indexOfAt = emailValue.indexOf("@");
  var indexOfDot = emailValue.lastIndexOf(".");

  if (indexOfAt < 1 || (indexOfDot - indexOfAt < 2 || emailValue.split("@").length -1 > 1)) 
  {
   populateErrorMsg("Invalid email!");
   form.elements["email_add"].focus();
   form.elements["email_add"].style.borderColor = "red";
   form.elements["email_add"].style.borderWidth = "thick";

   return false;
  }
  else
  {
   resetErrorMsg();
   form.elements["email_add"].borderColor = "#E5E5E5";
  }
  return true;
}

//Validating the Select option
function validateSelectOpt()
{
  var selElement = document.getElementById('security_quest');
  if(selElement.selectedIndex == 0)
  {
    selElement.focus();
    selElement.style.borderColor = "red";
    selElement.style.borderWidth = "thick";
    populateErrorMsg("Kindly select a " + selElement.name);
    return false;
  }
  else
  {
    resetErrorMsg();
    selElement.style.borderColor = "#282828";
    selElement.style.borderWidth = "initial";
  }
  return true;
}

//Validating password for Null & min / max length
function validatePassword(form)
{
  var pwdValue = form.elements["pwd_input"].value;
  var pwdElement = form.elements["pwd_input"];

  //Validating for null value
  if (pwdValue == "") 
  {
    populateErrorMsg("Password cannot be empty!");
    pwdElement.focus();
    pwdElement.style.borderColor = "red";
    pwdElement.style.borderWidth = "thick";

    return false;
  }
  else
  {
    resetErrorMsg();
    pwdElement.style.borderColor = "#E5E5E5";
  }

  //Validating that value isn't < than 6 chars
  if (pwdValue.length < 6)
  {
    populateErrorMsg("Password should be between 6 - 12 characters!");
    form.elements["pwd_input"].style.borderColor = "red";
    form.elements["pwd_input"].style.borderWidth = "thick";
    form.elements["conf_pwd_input"].style.borderColor = "red";
    form.elements["conf_pwd_input"].style.borderWidth = "thick";

    return false;
  }
  else
  {
    resetErrorMsg();
    form.elements["pwd_input"].borderColor = "#E5E5E5";
    form.elements["conf_pwd_input"].borderColor = "#E5E5E5";
  }

  //Validating that value isn't > than 12 chars
  if (pwdValue.length > 12) 
  {
    populateErrorMsg("Password should be between 6 - 12 characters!");
    form.elements["pwd_input"].style.borderColor = "red";
    form.elements["pwd_input"].style.borderWidth = "thick";
    form.elements["conf_pwd_input"].style.borderColor = "red";
    form.elements["conf_pwd_input"].style.borderWidth = "thick";
    
    return false;
  }
  else
  {
    resetErrorMsg();
    form.elements["pwd_input"].borderColor = "#E5E5E5";
    form.elements["conf_pwd_input"].borderColor = "#E5E5E5";
  }

  //Validating through regex
  exp = /[a-z]/;

  if (!exp.test(pwdValue)) 
  {
    populateErrorMsg("Password should contain at least one lower case letter!");
    form.elements["pwd_input"].style.borderColor = "red";
    form.elements["pwd_input"].style.borderWidth = "thick";
    form.elements["conf_pwd_input"].style.borderColor = "red";
    form.elements["conf_pwd_input"].style.borderWidth = "thick";
    return false;
  }
  else
  {
    resetErrorMsg();
    form.elements["pwd_input"].borderColor = "#E5E5E5";
    form.elements["conf_pwd_input"].borderColor = "#E5E5E5";
  }

  exp = /[A-Z]/;

  if (!exp.test(pwdValue)) 
  {
    populateErrorMsg("Password should contain at least one upper case letter!");
    form.elements["pwd_input"].focus;
    form.elements["pwd_input"].style.borderColor = "red";
    form.elements["pwd_input"].style.borderWidth = "thick";
    form.elements["conf_pwd_input"].style.borderColor = "red";
    form.elements["conf_pwd_input"].style.borderWidth = "thick";
    return false;
  }
  else
  {
    resetErrorMsg();
    form.elements["pwd_input"].borderColor = "#E5E5E5";
    form.elements["conf_pwd_input"].borderColor = "#E5E5E5";
  }

  return true;
  }

function matchPasswords(form)
{
  var pwdValue = form.elements["pwd_input"].value;
  var confPwdValue = form.elements["conf_pwd_input"].value;
  if (pwdValue !== confPwdValue) 
  {
    populateErrorMsg("Passwords do not match!");
    form.elements["pwd_input"].style.borderColor = "red";
    form.elements["pwd_input"].style.borderWidth = "thick";
    form.elements["conf_pwd_input"].style.borderColor = "red";
    form.elements["conf_pwd_input"].style.borderWidth = "thick";

    return false;
  }
  else
  {
    resetErrorMsg();
    form.elements["pwd_input"].borderColor = "#E5E5E5";
    form.elements["conf_pwd_input"].borderColor = "#E5E5E5";
  }
  return true;
}
