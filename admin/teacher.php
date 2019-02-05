<?php
session_start();

if(!$_SESSION['username'])
{

    header('location:login.php');

}
else
{
  


include 'functions/actions.php';
$obj = new DataOperations();
$error=$success="";

if(isset($_POST['save']))
{
$name = $obj->con->real_escape_string(htmlentities($_POST['name']));
$job_id = $obj->con->real_escape_string(htmlentities($_POST['job_id']));
$password = password_hash($job_id,PASSWORD_DEFAULT);

$where = array("job_id"=>$job_id);
$exist = $obj->fetch_records("teacher",$where);
if($exist)
{
$error = "Teacher with the same Job ID exist! Use different Job ID to register";
}
elseif(!is_numeric($job_id))
{
$error = "Invalid Job ID";
}
else
{


$data = array("name"=>$name,"job_id"=>$job_id,"password"=>$password);
if($obj->insert_record("teacher",$data))
{
$success = "$name registered successfully! For the new login $name should use $job_id as Password";
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
		<title>Add Article</title>
		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php' ?>
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
	  <div class="panel panel-default">
	  
	  <div class="panel-heading">Register New Teacher</div>
	  <div class="panel-body">
	  <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
                   <div class="form-group">
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
                               <input type="text" placeholder="Full Name" class="form-control" name="name" value="" required>
                          </div>
                          <br>

                           <div class="form-group">
                               <input type="text" placeholder="Teacher's Job ID" class="form-control" name="job_id" value="" required>
                          </div>
                           
                           <br>
                           
                           <div class="form-group">
                               <button class="btn btn-primary" name="save" type="submit" >Register</button>
                           </div>
                       </div> 
                   </div>
                   </form>
	    
	  	</div>

	  
	  
		</div>

  </div>
  </div>
  </div>

<?php include 'plugins/scripts.php'; ?>
</body>
</html>