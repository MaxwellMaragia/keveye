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

$error = $success="";
$sql = "SELECT * FROM gallery";
$result = mysqli_query($obj->con,$sql);
$num = mysqli_num_rows($result);

if(isset($_POST['save']))
{
 $c = count($_FILES['image']['name']);
 if($num < 5)
 {
 if($c < 6)
 {
  for($i=0; $i<$c; $i++)
  {
   $desc = $obj->con->real_escape_string(htmlentities($_POST['desc']));
   $img = $_FILES['image']['name'][$i];
   $data = array("image"=>$img,"category"=>$desc);
   $insert = $obj->insert_record("gallery",$data);
   if($insert)
   {
   	move_uploaded_file($_FILES['image']['tmp_name'][$i], "gallery/" .$img);
    $success = "Uploaded";
   }
   else
   {
   	$error = mysqli_error($obj->con);
   }
  }
 }
 else
 {
 	$error = "Upload Only 5 images";
 }
 }
 else
 {
  $error = "You can only add new gallery images after deleting the old ones, click delete images to clear old ones!";
 }
}
}

?>
<!DOCTYPE html>
<html>
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
	  
	  <div class="panel-heading">Upload Images for Memorable Moments
      <a href="delete_gallery.php" style="float: right; margin-top: -8px;"><button type="button" class="btn btn-primary bt-sm">Delete Images</button></a>
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
		    <label for="exampleInputFile">Upload Photo (You can upload up to 5 images at a time)</label>
		    <input type="file" name="image[]" id="exampleInputFile" multiple required>
		    
		  </div>
		  <div class="form-group">
           <input type="text" placeholder="Image category" class="form-control" name="desc" value="" required>
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