<?php
session_start();

if(!$_SESSION['admin_login'])
{

    header('location:../index.php');

}
else
{
include 'functions/addcarousel.php';	
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Adding new Sliding Photos</title>

		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php';?> 
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
    <?php include 'plugins/secondarynav.php';?>
      <div class="row">
   	  <div class="panel panel-default">
	  
	  <div class="panel-heading">Upload New Slidings Photos
      <a href="delete_carousel.php" style="float: right; margin-top: -8px;"><button type="button" class="btn btn-primary bt-sm">Delete Images</button></a>
	  </div>
    <?php
    $sql = "SELECT * FROM carousel_pics";
    $res = mysqli_query($obj->con,$sql);
    $count = mysqli_num_rows($res);
    if($count==3)
    {
     echo "<div class='alert alert-danger' style='margin-top:10px;'>Oops!, You can't add more Sliding images right now because there are maximum number of images already, to add new image click delete Images button on the right top to delete old images first!</div>";
    }
    else
    {

    ?>
	  <div class="panel-body">

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
	  <form  action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
	     <div class="form-group">
		    <label for="exampleInputFile">Upload Photo</label>
		    <input type="file" name="image" id="exampleInputFile" required>
		    
		  </div>
		  <div class="form-group">
           <input type="text" placeholder="Image description" class="form-control" name="desc" value="" required>
           </div>

             <div class="form-group">
              <button class="btn btn-primary" name="save" type="submit" >Upload</button>
             </div>
	  	
	  </form>

	    
	  	</div>
      <?php
      }
    ?>

		</div>


  </div>
  </div>
  </div>

<?php include 'plugins/scripts.php'; ?>
</body>
</html>