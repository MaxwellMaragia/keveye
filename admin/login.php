<?php
session_start();
include 'functions/actions.php';
$obj = new DataOperations();

$error = $success=$username="";
$_SESSION['username']='';

if(isset($_POST['login']))
{
$username = $obj->con->real_escape_string(htmlentities($_POST['username']));
$password = $obj->con->real_escape_string(htmlentities($_POST['password']));

$where = array("username"=>$username,"password"=>$password);
$exist = $obj->fetch_records("admin",$where);
if($exist)
{
 $_SESSION['username'] = $username;
 header('location: index.php');
}
else
{
  $error = "Wrong username or password!";
}
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Signin</title>
	<link rel="stylesheet" type="text/css" href="style.css">
  <?php include 'plugins/styles.php';?>
</head>
<body>
 <div class="header">
 	<h2>Keveye Girls High School Admin Login</h2>
 </div>
  <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>">
      <!--display errors here-->
                      <?php
                              if($error)
                              {
                                $obj->errorDisplay($error);
                              }
                              if($success)
                              {
                                $obj->successDisplay($success);
                              }
                           ?>
      
  	 <div class="input-group1">
  	     <label>Username</label>
  	 	<input type="text" name="username" value="<?=$username;?>" required>
  	 </div>
  	 <div class="input-group1">
  	    <label>Password</label>
  	 	<input type="password" name="password" required>
  	 </div>
  	 <div class="input-group1">
  	 	<button type="submit" name="login" class="button1">Login</button>
  	 </div>
    
  	 
  </form>
</body>
</html>