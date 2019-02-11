<?php 
session_start();

if(!$_SESSION['username'])
{

    header('location:login.php');

}
else
{
 include 'functions/featured.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Featured News</title>
		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php' ?>
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
	  <div class="panel panel-default">
	  
	  <div class="panel-heading">Update Featured news to the website</div>
	  <div class="panel-body">
	  <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
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
                               <input type="text" placeholder="Type Title here" class="form-control" name="title" value="" required>
                           </div>
                           
                           
                           <div class="form-group">
                           <textarea class="form-control" rows="15" placeholder="Type the article here" name="news" required></textarea>
                           </div>

                         <div class="form-group">
                              <label for="exampleInputFile">Upload Image</label>
                              <input type="file" name="image" id="exampleInputFile" required>
                        
                          </div>
                           
                           <div class="form-group">
                               <button class="btn btn-primary" name="save" type="submit" >Save</button>
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