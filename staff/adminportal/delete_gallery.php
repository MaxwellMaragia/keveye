<?php
session_start();
$desc="";
 $error=$success='';
if(!$_SESSION['admin_login'])
{

    header('location:login.php');

}
else
{
 include 'functions/actions.php'; 
 $obj = new DataOperations();

 $result=$unlink='';
 if(isset($_POST['delete']))
  { 
                                           
  $get_data = $obj->fetch_all_records("gallery");
  foreach ($get_data as $row)
  {
    $img = $row['image'];
    $id = $row['id'];
  }
  $result = false;
  $cnt = array();
  $imgs = array();

  $cnt = $_POST['checkbox'];
                                         
  $sql = "DELETE FROM gallery WHERE id IN (".implode(",", $cnt) . ")";
  $result = mysqli_query($obj->con,$sql);
  if($result)
  {
  $sql1 = "SELECT * FROM gallery WHERE id IN (".implode(",", $cnt) . ")";
  $res = mysqli_query($obj->con,$sql1);
  while($file = mysqli_fetch_array($res))
  {
   $imgs[] = $file;
  }
  foreach ($imgs as $row) 
  {
    $un = unlink('../../gallery/'.$row['image']);
  }
  
  $success = "Images deleted successfully!";
  }
                                                                                    
  else
  {
  $error = mysqli_error($obj->con);
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
		<title>Add Gallery Images</title>

		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php';?> 	
	 <style type="text/css">
	 	.img-thumbnail
	 	{
	 		height: 100px;
	 		width: 50%;
	 	}
	 </style>
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">

    <div class="panel panel-default">
	  
	  <div class="panel-heading">Delete Gallery Images to Add new
    <a href="gallery.php" style="float: right; margin-top: -8px; font-size: 16px;">Back</a>
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
                    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
	                	<table class="table  table-hover table-striped table-bordered display nowrap" id="table">
                          <thead>
                              <tr>

                              
                              <th>Image</th>
                              <th>Description</th>
                              <th>Select images to delete</th>
                              
                              
                              </tr>
                           </thead>
                          <tbody>
                          <?php
                            $obj = new DataOperations();
                            $get_data = $obj->fetch_all_records("gallery");
                            foreach ($get_data as $row)
                             {
                            	
                            	
                            	$img = $row['image'];
                            	$desc = $row['category'];
                            	$id = $row['id'];
                            	

                            	?>
                            	<tr>
                            		
                                    <td><?php echo "<img class='img-thumbnail' src='../../gallery/$img'>"?></td>
                                     <td><?php echo $desc?></td>
                                     <td><input type="checkbox" name="checkbox[]" value="<?php echo $id; ?>"></td>
                                     
                                     
                            	</tr>

                          <?php
                            }

                            
                          ?>

                           
                            
                          
                          </tbody>
                          </table>
                          <div class="btn-group">
                                    
                                    <a href="#delete" data-toggle="modal" title="delete"><button type="button" class="btn btn-danger btn-sm"> Delete</button> </a>
                                    

                         </div>

                         <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>"> 

                                                  <div class="alert alert-danger">
                                                      <p>Delete images ?</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="delete" type="submit" class="btn btn-primary" value="">Yes</button>
                                                  </div>
                                                  </form>
                                              </div>
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
<script>
    $('.table').DataTable();
</script>
</body>
</html>