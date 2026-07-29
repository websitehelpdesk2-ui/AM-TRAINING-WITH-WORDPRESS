<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
session_start();
include('../../private/connect_sp.php');
require('../../private/csrf_token.php');
$CURRENT_PAGE_NAME = "set password";
$error = false;
$QS = "c29tYzc1MDIwMUBnbWFpbC5jb";
$code = "C5jb";
$confirm_password = "";
$new_password = "";
$csrf_token_tag = "";
$error_tag = "";
$user_id = 0;
$user_email = "";
$user_active = null;
$user_group = 0;
if ($_SERVER['REQUEST_METHOD']==='POST')
{
	if (isset($_POST["user_code"]))
	{
		$user_id = str_replace($code, '', filter_var($_POST["user_code"], FILTER_SANITIZE_FULL_SPECIAL_CHARS));		
	}
	if (isset($_POST["user_email"]))
	{
		$user_email = filter_var($_POST["user_email"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["new_password"]))
	{
		$new_password = filter_var($_POST["new_password"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["confirm_password"]))
	{
		$confirm_password = filter_var($_POST["confirm_password"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if ($new_password != $confirm_password)
	{
		$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>Passwords are not the same, please try again.</div>";	
	    $user_id = str_replace($QS, "", $_SERVER['QUERY_STRING']);		
	}
	else
	{	
		$sql = "Call Set_Password(".$user_id.",'". $user_email . "','" . $new_password . "')";	
	    echo $sql;
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0) 
		{
			$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>Incorrect password, please try again.</div>";
		}
		else
		{
			$row = mysqli_fetch_row($result);		
			$user_id = $row[0];
			$user_name = $row[1];			
			$user_active = $row[2];		
			$user_group = $row[3];	
			if ($user_id != 0)
			{
				$_SESSION["UserID"] = $user_id;
				$_SESSION["UserName"] = $user_name;
				$_SESSION["UserGroup"] = $user_group;
				if ($user_active)
				{
					header('Location: /portal/');
				}
				else
				{
					$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>Invalid Account.  Please call student office.</div>";
				}
			} 
			else 
			{
				$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>Invalid Account.  Please call student office.</div>";
			}	
			
		}
	}
}
else
{
	$user_id = str_replace($QS, "", $_SERVER['QUERY_STRING']);
	$sql = "Call Get_User_Data('". $user_id . "')";	    
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_row($result);		
	$user_id = $row[0];	
    $user_email = $row[1];	
	$error = $row[2];	
	if ($error != '')	
	{
		$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>  Invalid user email.</div>";
	}		
}
mysqli_close($conn);
$csrf_token_tag = csrf_token_tag($CURRENT_PAGE_NAME);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AM Training Institute">
    <meta name="author" content="CSand Bootstrap contributors">    
    <title>AM Training Institute | Set Password</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/heroes/">

<link href="../css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }
    </style>
    <link href="../css/heroes.css" rel="stylesheet">
	<script src="../js/jquery-3.7.1.min.js"></script>
  </head>
  <body>
    
<main>
 
  <div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 mb-3">AM Training Institute</h1>
        <p class="col-lg-10 fs-4">Your password must be 8-20 characters long, must contain special characters "!@#$%&*_?", numbers, lower and upper letters only.</p>
		<div id="feedbackin" class="valid-feedback">
					Strong Password!
				</div>
				<div id="feedbackirn" class="invalid-feedback">
					Atlead 8 characters,
					Number, special character 
					Caplital Letter and Small letters
				</div>      
      </div>
      <div class="col-md-10 mx-auto col-lg-5">
	    
        <form class="p-4 p-md-5 border rounded-3 bg-light needs-validation" method="POST">
		<input type="hidden" name="user_code" id="user_code" value="C5jb<?=$user_id?>" />
		<h5>Set Password</h5>		
		  <?= $csrf_token_tag ?>
		  <?= $error_tag ?>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_email" name="user_email" placeholder="<?=$user_email?>" value="<?=$user_email?>" readonly>
            <label for="user_email">Email Address</label>
					
          </div>
          <div class="form-floating mb-3">
            <input type="password" name="new_password" class="form-control needs-validation" id="new_password" placeholder="Password" required>
            <label for="floatingPassword">New Password</label>
			<div id="new_password_progressbar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
          </div>
		  <div class="form-floating mb-3">
            <input type="password" name="confirm_password" class="form-control needs-validation" id="confirm_password" placeholder="Password" required>
            <label for="floatingPassword">Confirm New Password</label>
			<div id="confirm_password_progressbar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
          </div>
          <a href="../portal/index.html">
          <button class="w-100 btn btn-lg btn-primary" type="submit" >Submit</button>
		  </a>
          
          
        </form>
      </div>
    </div>
  </div>
  
</main>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../js/form-validation.js"></script> 
    <script type="text/javascript">
	$(document).ready(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      // making sure password enters the right characters
		form.validationPassword.addEventListener('keypress', function(event){
			console.log("keypress");
			console.log("event.which: " + event.which);
			var checkx = true;
			var chr = String.fromCharCode(event.which);
			console.log("char: " + chr);
			  

			var matchedCase = new Array();
			matchedCase.push("[!@#$%&*_?]"); // Special Charector
			matchedCase.push("[A-Z]");      // Uppercase Alpabates
			matchedCase.push("[0-9]");      // Numbers
			matchedCase.push("[a-z]");

			for (var i = 0; i < matchedCase.length; i++) {
				if (new RegExp(matchedCase[i]).test(chr)) {
					console.log("checkx: is true");					
					checkx = false;
				}
			}	
      
      if(form.validationPassword.value.length >= 20)
        checkx = true;
			
			if ( checkx ) {
                event.preventDefault();
              	event.stopPropagation();	  
          	}

		});
    
    //Validate Password to have more than 8 Characters and A capital Letter, small letter, number and special character
		// Create an array and push all possible values that you want in password
		var matchedCase = new Array();
		matchedCase.push("[$@$$!%*#?&]"); // Special Charector
		matchedCase.push("[A-Z]");      // Uppercase Alpabates
		matchedCase.push("[0-9]");      // Numbers
		matchedCase.push("[a-z]");     // Lowercase Alphabates
		

		form.validationPassword.addEventListener('keyup', function(){
		
		var messageCase = new Array();
		messageCase.push(" Special Charector"); // Special Charector
		messageCase.push(" Upper Case");      // Uppercase Alpabates
		messageCase.push(" Numbers");      // Numbers
		messageCase.push(" Lower Case");     // Lowercase Alphabates

		var ctr = 0;
		var rti = "";
		for (var i = 0; i < matchedCase.length; i++) {
			if (new RegExp(matchedCase[i]).test(form.validationPassword.value)) {
				if(i == 0) messageCase.splice(messageCase.indexOf(" Special Charector"), 1);
				if(i == 1) messageCase.splice(messageCase.indexOf(" Upper Case"), 1);
				if(i == 2) messageCase.splice(messageCase.indexOf(" Numbers"), 1);
				if(i == 3) messageCase.splice(messageCase.indexOf(" Lower Case"), 1);
				ctr++;
				//console.log(ctr);
				//console.log(rti);
			}
		}		
		
		
		//console.log(rti);
		// Display it
		var progressbar = 0;
		var strength = "";
		var bClass = "";
		switch (ctr) {
		case 0:
		case 1: 
			strength = "Way too Weak";
			progressbar = 15;
			bClass = "bg-danger";
			break;
		case 2:
			strength = "Very Weak";
			progressbar = 25;
			bClass = "bg-danger";
			break;
		case 3:
			strength = "Weak";	
			progressbar = 34;
			bClass = "bg-warning";			
			break;
		case 4:
			strength = "Medium";
			progressbar = 65;
			bClass = "bg-warning";						
			break;
		}
		
		if (strength == "Medium" && form.validationPassword.value.length >= 8 ) {
			strength = "Strong";
			bClass = "bg-success";			
			form.validationPassword.setCustomValidity("");			
		} else {
			form.validationPassword.setCustomValidity(strength);
		}

		var sometext = "";

		if(form.validationPassword.value.length < 8 ){
      var lengthI = 8 - form.validationPassword.value.length;
			sometext += ` ${lengthI} more Characters, `;
		} 

		sometext += messageCase;
		console.log(sometext);
		
		console.log(sometext);

		if(sometext){
			sometext = " You Need" + sometext;
		}


		$("#feedbackin, #feedbackirn").text(strength + sometext);
		$("#progressbar").removeClass( "bg-danger bg-warning bg-success" ).addClass(bClass);
		var plength = form.validationPassword.value.length ;
		if(plength > 0) progressbar += ((plength - 0) * 1.75) ;
		//console.log("plength: " + plength);
		var  percentage = progressbar + "%";
		form.validationPassword.parentNode.classList.add('was-validated');
		//console.log("pacentage: " + percentage);
		$("#progressbar").width( percentage );

				if(form.validationPassword.checkValidity() === true){
					form.verifyPassword.disabled = false;
				} else {
					form.verifyPassword.disabled = true;
				}
          		 
      
    }); 
      

      
    });
  }, false);
});

	</script>
      
  </body>
</html>
