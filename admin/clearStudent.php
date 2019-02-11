<?php
session_start();

if(!$_SESSION['username'])
{

    header('location:login.php');

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Student Clearance</title>
		

		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php';?>	
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
   <div class="col-md-8 col-md-offset-2">
   	<h4 style="text-align:center;">Clearance Of Student</h4>
   	<p>Be extra carefull during clearance of student because once the student is cleared means that he is no longer the part of Machakos High School fraternity</p>
   	<p>You can choose from the links below either to clear a group of students or single student</p>
   	<p><a href="clear_multiple.php">Clear Class</a></p>
   	<p><a href="clear_single.php">Clear Single student</a></p>
   </div>

    </div>
</div>
</div>

<?php include 'plugins/scripts.php'; ?>
</body>
</html>