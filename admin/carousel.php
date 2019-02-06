<?php
session_start();

if(!$_SESSION['username'])
{

    header('location:login.php');

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
      <div class="row">
   	  <div class="panel panel-default">
	  
	  <div class="panel-heading">Upload New Slidings Photos
      <a href="delete_carousel.php" style="float: right; margin-top: -8px;"><button type="button" class="btn btn-primary bt-sm">Delete Images</button></a>
	  </div>
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

	  
	  
		</div>


  </div>
  </div>
  </div>

<?php include 'plugins/scripts.php'; ?>
</body>
</html>