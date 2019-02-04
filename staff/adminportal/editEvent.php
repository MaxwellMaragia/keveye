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

  if(isset($_POST['update']))
  {
    $title = $obj->con->real_escape_string(htmlentities($_POST['title']));
    $date = $obj->con->real_escape_string(htmlentities($_POST['date']));
    $desc = $obj->con->real_escape_string(htmlentities($_POST['desc']));
    $key = $_POST['update'];

    $data = array(
            "title"=>$title,
            "time_date"=>$date,
            "details"=>$desc   
    );
    $where = array("id"=>$key);
    if($obj->update_record("news_events",$where,$data))
    {
       $success = "Edited successfully";
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
  <title>Edit Events</title>
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
                    <div class="panel-heading" >Edit Event Details
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
                           <th>Edit</th>
                            
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
                                <td><?php echo $date?>
                                </td>
                                <td><?php echo $desc?></td>
                            
                            
                           <td>
                                <a href="#edit<?php echo $id;?>" data-toggle="modal"><button class="btn btn-warning" name="save" type="button" title="Edit Event details" ><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button></a>
                           </td>
                       </tr>
							<!-- Modal Edit -->
                                  <div class="modal fade" id="edit<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Edit Event Details</h4>
                                              </div>
                                              <div class="modal-body">
                                              <div class="form-group">
                                                 <input type="text" placeholder="Event Title eg AGM" class="form-control" name="title" value="<?=$title?>">
                                             </div>
                                             <div class="form-group">
                                               <input type="text" placeholder="Event Title eg AGM" class="form-control" name="date" value="<?=$date?>">
                                           </div>
                                           <div class="form-group">
                                           <textarea class="form-control" rows="12" placeholder="Type event description here" name="desc"><?=$desc?></textarea>
                                           </div>
                                                     
                                                     
                                              </div>

                                              <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" name="update" value="<?php echo $id?>">Update</button>
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                  
                                              </div>
                                               </form>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <!--end of Modal Edit-->

                                
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