<?php
session_start();

if(!$_SESSION['username'])
{

    header('location:login.php');

}
else
{
	include 'functions/delete_teacher.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Remove teacher</title>
		

		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php';?>	
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
      <div class="panel panel-default">
	  
	  <div class="panel-heading">Delete teacher from the system</div>
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
	    <table class="table  table-hover table-striped table-bordered display nowrap" id="table">
             <thead>
                  <tr>
                   <th>Job ID</th>
                    <th>Name</th>           
                    <th>Remove</th>        
                  </tr>
                </thead>
               <tbody>
               <?php
                $obj = new DataOperations();
                $get_data = $obj->fetch_all_records("teacher");
                foreach ($get_data as $row)
                 {
                   $job_id = $row['job_id'];
                   $name = $row['name'];

                  ?>
                  <tr>
                  	<td><?php echo $job_id?></td>
                  	<td><?php echo $name?></td>
                  	<td>
                      <div class="btn-group">
                                    
                        <a href="#delete<?php echo $job_id;?>" data-toggle="modal" title="delete"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> </a>
                        </div>
                  	</td>
                  </tr>

                       <div class="modal fade" id="delete<?php echo $job_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                                                  <div class="alert alert-danger">
                                                      <p>Are you sure you want to remove teacher <b><?php echo $name?> </b> from the system ?</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="delete" class="btn btn-primary" value="<?php echo $job_id;?>">Yes</button>
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
<script>
    $('.table').DataTable();
</script>
</body>
</html>