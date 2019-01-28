<?php
session_start();
$success=$error="";

if(!isset($_SESSION['admin_login'])){

    header('location:../index.php');
}
else
{
	include "functions/actions.php";
	$obj = new DataOperations();

	if(isset($_POST['postpone']))
	{
     $key = $_POST['postpone'];
     $date = $obj->con->real_escape_string(htmlentities($_POST['date']));
     $status = "postponed";
     $where = array("id"=>$key);
     $get_date = $obj->fetch_records("news_events",$where);
     foreach($get_date as $row)
     {
      $d = $row['time_date'];
     }
     if($date==$d)
     {
      $error = "Please enter new event date";
     }
     else
     {
     $data = array("new_date"=>$date,"status"=>$status);
     if($obj->update_record("news_events",$where,$data))
     {
      $success = "Postponed successfully!";
     }
     else
     {
     	$error = mysqli_error($obj->con);
     }
     }
     
	}

	if(isset($_POST['delete']))
	{
	 $key = $_POST['delete'];
     $get_data = $obj->fetch_all_records("news_events");
     foreach($get_data as $row)
     {
     	$img = $row['image'];
     }
     $where = array("id"=>$key);
     if($img==null)
     {
      if($obj->delete_record("news_events",$where))
      {
       $success = "Deleted successfully";
      }
     }
     else if($img!=null)
     {
      if($obj->delete_record("news_events",$where))
      {
      $un=unlink('../../event_img/'.$img);
      $success = "Deleted successfully";
      }
     }
     else
     {
     	$error = mysqli_error($obj->con);
     }


	}
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Events</title>
  <!-- Bootstrap Styles-->
  <?php include "plugins/resources.php" ?>

</head>
<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

    <?php include 'plugins/secondarynav.php' ?>
<!--        -->

        <div class="row">
            <div class="col-md-12 " style="background:white; padding-top:20px;">
                <!-- Advanced Tables -->
                <div class="panel panel-default" >
                    <div class="panel-heading" >Postpone/Delete Event
                    <a href="event.php" style="float: right; margin-top: -8px; font-size: 16px;">Back</a>
                    </div>
                    <div class="panel-body">


                    <?php

                        if($error){

                            $obj->errorDisplay($error);

                        }
                        else if($success){

                            $obj->successDisplay($success);

                        }


                        ?> 
                 <div class="table-responsive">
                 <form action="<?php $_SERVER['PHP_SELF']?>" METHOD="post">
                  <table class="table  table-hover table-striped table-bordered" id="dataTables-example">
                   <thead>
                          <tr>

                           <th>Event Title</th>
                           <th>Date</th>
                           <th>Details</th>
                           <th>Actions</th>
                            
                            </tr>
                     </thead>
                     <tbody>
                     	<?php
                         $get_data = $obj->fetch_all_records("news_events");
                         foreach($get_data as $row)
                         {
                         	$id = $row['id'];
                         	$title = $row['title'];
                         	$date = $row['time_date'];
                          $date1 = $row['new_date'];
                          $desc = $row['details'];


                            ?>
                               <tr>
                                <td><?php echo $title?></td>
                                <td><?php 
                                  if($date1==null)
                                  {
                                  echo $date;
                                  }
                                  else
                                  {
                                    echo "<del style='color:red'>".$date."</del> <br/>".$date1;
                                  }
                                  ?>
                                </td>
                                <td><?php echo $desc?></td>
                            
                            
                           <td>
                                <div class="btn-group">
                                    <a href="#postpone<?php echo $id;?>" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm">Postpone </button> </a>
                                    <a href="#delete<?php echo $id;?>" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm">Delete</button> </a>

                                </div>
                           </td>
                       </tr>
							<!-- Modal Postpone -->
                                  <div class="modal fade" id="postpone<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Postpone Event</h4>
                                              </div>
                                              <div class="modal-body">



                                                     <label>Set new Event Date</label>
                                                     <input type="text" value="<?=$date?>" name="date" id="names" class="form-control" required>
                                                    

                                              </div>

                                              <div class="modal-footer">
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                  <button name="postpone" type="submit" class="btn btn-primary" value="<?php echo $id?>">Postpone</button>
                                                  
                                              </div>
                                               </form>
                                          </div>
                                      </div>
                                  </div>
                                  <!--end of Modal Postpone-->

                                  <!-- Modal delete-->
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
                                                      Are you sure you want to delete <b></b><?php echo $title?> </b> event?
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="delete" type="submit" class="btn btn-primary" value="<?php echo $id?>">Delete Event</button>
                                                  </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                <!--end//modal delete-->
                            <?php
                         }

                     	?>
                     </tbody>

                 </table>
                 </form>

                </div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php include "plugins/scripts.php" ?>
</body>
</html>