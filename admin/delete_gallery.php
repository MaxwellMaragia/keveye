<?php
session_start();
$desc="";
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
		<title>Add Gallery Images</title>
		<?php include 'functions/actions.php' ?>

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
	  
	  <div class="panel-heading">Delete Gallery Images to Add new</div>
	  <div class="panel-body">
						  <?php
						      $error=$success='';
                              if($error)
                              {
                                $obj->errorDisplay($error);
                              }
                              if($success)
                              {
                                $obj->successDisplay($success);
                              }
                           ?>

	                	<table class="table  table-hover table-striped table-bordered display nowrap" id="table">
                          <thead>
                              <tr>

                              
                              <th>Image</th>
                              <th>Description</th>
                              
                              
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
                            	
                            	

                            	?>
                            	<tr>
                            		
                                    <td><?php echo "<img class='img-thumbnail' src='gallery/$img'>"?></td>
                                     <td><?php echo $desc?></td>
                                     
                                     
                            	</tr>
                              
                                     <?php
                                     $result=$unlink='';
                                      if(isset($_POST['delete']))
                                          {
                                           

                                           
                                           $sql = "DELETE FROM gallery";
                                           $result = mysqli_query($obj->con,$sql);
                                           if($result)
                                           {
                                            $un = unlink('gallery/'.$img);
                                            $success = "Images cleared! You can now add new ones";
                                           }
                                           
                                           
                                           else
                                           {
                                            $error = mysqli_error($obj->con);
                                           }
                                          }
                                     ?>
                                                                     

                                    


                            

                          <?php
                            }

                            
                          ?>

                           
                            
                          
                          </tbody>
                          </table>
                          <div class="btn-group">
                                    
                                    <a href="#delete" data-toggle="modal" title="delete"><button type="button" class="btn btn-danger btn-sm"> Clear Images </button> </a>
                                    

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
                                                      <p>Are you sure you want to delete <b><?php echo $desc?> </b> gallery images ?</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="delete" class="btn btn-primary" value="">Yes</button>
                                                  </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                      </div>	
                          

	    
	  	</div>

	  
	  
		</div>

  </div>
  </div>
  </div>

<?php include 'plugins/scripts.php'; ?>
</body>
</html>