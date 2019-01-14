<?php
session_start();
$error = $success ="";
if(!$_SESSION['username'])
{

    header('location:login.php');

}
else
{
	include 'functions/actions.php';
	$obj = new DataOperations();

	if(isset($_POST['save']))
	{
     $password = $obj->con->real_escape_string(htmlentities($_POST['password']));
     $password_confirm = $obj->con->real_escape_string(htmlentities($_POST['password_confirm']));
     
     if($password !== $password_confirm)
     {
     $error = "Password do not much!";
     }
     else
     {
     	$where = array("username"=>$_SESSION['username']);
     	$data = array("password"=>$password);
        if($obj->update_record("admin",$where,$data))
        {
        $success = "Admin password updated successfully";
        }
        else
        {
        	$error = mysqli_error($obj->con);
        }
     }
     

	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Admin Reset Password</title>
		

		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php';?>
</head>
<body>
 <?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
       <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
       	<div class="col-md-6 col-md-offset-2">
       		<!--error dispaly-->
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
       	 
       	  <div class="form-group">
       	   <label>New Password</label>
           <input type="password" placeholder="password" class="form-control" name="password" value="" required>
           </div>
           
           <div class="form-group">
           <label>Confirm New Password</label>
           <input type="password" placeholder="Confirm password" class="form-control" name="password_confirm" value="" required>
           </div>

           <div class="form-group">
             <button class="btn btn-primary" name="save" type="submit" >Update</button>
            </div>
       		
       	</div>

       </form>

 	</div>
  </div>
</div>
<?php include 'plugins/scripts.php'; ?>
</body>
</html>