<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
session_start();
$source = "";
if(isset($_SESSION["Source"]) && isset($_SESSION["UserID"]))
{
	if($_SESSION["Source"] == "registration") 
	{
		//unset($_SESSION["Source"]);
		header("Location: /registration/");
	}
	
	if($_SESSION["Source"] == "new") 
	{	
		header("Location: /registration/tc.php");
	}

}
if(isset($_SESSION["UserID"]))
{
	header("Location: /portal/");
}

if (isset($_SERVER['QUERY_STRING']))
{
	if (strlen($_SERVER['QUERY_STRING']) > 0) 
	{
		$url = "/sp/?".$_SERVER['QUERY_STRING'];
		header('Location: '.$url);
	}
}


include('../../private/connect_sp.php');
require('../../private/csrf_token.php');
$CURRENT_PAGE_NAME = "login";
$error = false;
$login_name = "";
$login_password = "";
$csrf_token_tag = "";
$error_tag = "";
$user_id = 0;
$user_name = "";
$user_active = null;
$user_group = 0;
if ($_SERVER['REQUEST_METHOD']==='POST')
{
	if (isset($_POST["login_name"]))
	{
		$login_name = filter_var($_POST["login_name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["login_password"]))
	{
		$login_password = filter_var($_POST["login_password"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	$user_id = "";
	$user_is_admin = "";
	
	$sql = "Call Login('". $login_name . "','" . $login_password . "')";	
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 0) 
	{
		$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>  User not found.</div>";
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
				$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>  User not active.  Please call student office.</div>";
			}
		} 
		else
		{
			$error_tag = "<div class=\"alert alert-danger\"><strong>Problem!</strong>  Invalid user email or incorrect password.</div>";
		}
	}
	mysqli_close($conn);

}
else
{
	$csrf_token_tag = csrf_token_tag($CURRENT_PAGE_NAME);
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AM Training Institute">
    <meta name="author" content="CSand Bootstrap contributors">    
    <title>AM Training Institute | Portal Login</title>
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

    
    <!-- Custom styles for this template -->
    <link href="../css/heroes.css" rel="stylesheet">
  </head>
  <body>
    
<main>
 
  <div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 mb-3">AM Training Institute</h1>
        <p class="col-lg-10 fs-4">Online and hands-on classes are affordable and are offered at convenient times to make it easy to fit into your busy schedule. Register <a href="../Registration/">here</a> if not a student.</p>
      </div>
      <div class="col-md-10 mx-auto col-lg-5">
        <form class="p-4 p-md-5 border rounded-3 bg-light" action="/login/" method="POST">
		  <?= $csrf_token_tag ?>
		  <?= $error_tag ?>
          <div class="form-floating mb-3">
            <input type="email" name="login_name" class="form-control" id="login_name" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" name="login_password" class="form-control" id="login_password" placeholder="Password">
            <label for="floatingPassword">Password</label>
          </div>
          <a href="../portal/index.html">
          <button class="w-100 btn btn-lg btn-primary" type="submit" >Login</button>
		  </a>
          <hr class="my-4">
          <small class="text-muted">Forgot your password.</small>		 
        </form>
      </div>
    </div>
  </div>
  
</main>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      
  </body>
</html>
