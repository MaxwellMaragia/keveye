<?php 
session_start();

if(!$_SESSION['username'])
{

    header('location:login.php');

}
else
{
  include 'functions/register.php';
}


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Events</title>
		

		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php';?>
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
	<div class="panel panel-default">
	  
	  <div class="panel-heading">Register new student</div>
	  <div class="panel-body">
       <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
       	<div class="col-md-6 col-md-offset-2">
       	<!--error display-->
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
          <input type="text" placeholder="Name" class="form-control" name="name" value="<?=$name?>" required>
        </div>

        <div class="form-group">
          <input type="text" placeholder="adm number" class="form-control" name="adm" value="<?=$adm?>" required>
        </div>

        <div class="form-group">
          <input type="text" placeholder="Form eg 4S" class="form-control" name="form" value="<?=$form?>" required>
        </div>

        <div class="form-group">
          <input type="text" placeholder="Parent/Gurdian name" class="form-control" name="parent" value="<?=$parent?>" required>
        </div>

        <div class="form-group">
          <input type="text" placeholder="Parent/Gurdian contact" class="form-control" name="contact" value="<?=$contact?>" required>
        </div>

        <div class="form-group">
         <button class="btn btn-primary" name="register" type="submit" >Register</button>
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