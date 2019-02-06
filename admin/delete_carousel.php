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
		<title>Adding new Sliding Photos</title>
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
	  
	  <div class="panel-heading">Delete Sliding photos to Add new</div>
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

                              <th>ID</th>
                              <th>Image</th>
                              <th>Description</th>
                              <th>Delete</th>
                              
                              </tr>
                           </thead>
                          <tbody>
                          <?php
                          $obj = new DataOperations();
                            $get_data = $obj->fetch_all_records("carousel_pics");
                            foreach ($get_data as $row)
                             {
                            	
                            	$id = $row['id'];
                            	$img = $row['image'];
                            	$desc = $row['short_message'];
                            	
                            	

                            	?>
                            	<tr>
                            		<td><?php echo $id?></td>
                                    <td><?php echo "<img class='img-thumbnail' src='uploads/$img'>"?></td>
                                     <td><?php echo $desc?></td>
                                     
                                     <td>
                                     <?php
                                     $result=$unlink='';
                                      if(isset($_POST['delete']))
                                          {
                                           $key = $_POST['delete'];
                                           $where = array("id"=>$key);

                                           
                                           $sql = "SELECT * FROM carousel_pics WHERE id='$key' AND image='$img' ";
                                           $result = mysqli_query($obj->con,$sql);
                                           while($file = mysqli_fetch_array($result))
                                           {
                                            $unlink = unlink('uploads/'.$file['image']);
                                           }
                                           if($unlink)
                                           {

                                           

                                           if($obj->delete_record("carousel_pics",$where))
                                           {
                                            
                                             $success = "The $desc image deleted successfully!";
                                           }
                                           }
                                           else
                                           {
                                            $error = mysqli_error($obj->con);
                                           }
                                          }
                                     ?>
                                <div class="btn-group">
                                    
                                    <a href="#delete<?php echo $id;?>" data-toggle="modal" title="delete"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> </a>
                                    

                                </div>                                     	

                                     </td>
                            	</tr>


                            <div class="modal fade" id="delete<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                                                  <div class="alert alert-danger">
                                                      <p>Are you sure you want to delete <b><?php echo $desc?> </b> image ?</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="delete" class="btn btn-primary" value="<?php echo $id;?>">Yes</button>
                                                  </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                      </div>

                          <?php
                            }
                            
                          ?>

                          </tbody>
                          </table>			 


	    
	  	</div>

	  
	  
		</div>

  </div>
  </div>
  </div>

<?php include 'plugins/scripts.php'; ?>
</body>
</html>