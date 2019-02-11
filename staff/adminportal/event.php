<?php
session_start();

if(!$_SESSION['admin_login'])
{

    header('location:../index.php');

}
else
{
	include 'functions/Addevent.php';
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
    <?php include 'plugins/secondarynav.php';?>
      <div class="row">
	  <div class="panel panel-default">
	  
	  <div class="panel-heading">Add New Event to the website</div>
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
                               <input type="text" placeholder="Event Title eg AGM" class="form-control" name="title" value="<?=$title?>" required>
                           </div>

                           <div class="form-group">
                               <input type="text" placeholder="Event Date eg 25/02/2018" class="form-control" name="date" value="<?=$date?>" required>
                           </div>

                           <div class="form-group">
                           <textarea class="form-control" rows="8" placeholder="Type event description here" name="desc" required><?=$desc?></textarea>
                           </div>
                           
                           

                         <div class="form-group">
                              <label for="exampleInputFile">Upload Image (Optional)</label>
                              <input type="file" name="image" id="exampleInputFile">
                        
                          </div>
                           
                           <div class="form-group">
                               <button class="btn btn-primary" name="save" type="submit" >Save</button>
                               <a href="editEvent.php">Edit Event Details</a>
                               <a href="delete_event.php" style="float: right;">Postpone/Cancel Event</a>
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