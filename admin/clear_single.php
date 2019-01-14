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
  $error = $success = "";
  if(isset($_POST['clear']))
  {
  $admno = $_POST['clear'];
  $where = array("adm"=>$admno);
  if($obj->delete_record("student",$where))
  {
   $success = "Student cleared successfully";
  }
  else
  {
    $error = "Problem clearing student, please try later!";
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
		<title>Student Clearance</title>
    
		

		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php';?>	
</head>
<body>
<?php include'plugins/navigation.php';?>
 <div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
      <form  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
            <div class="input-group-lg">
                <input type="text" id="student-search" class="form-control" placeholder="Search student by admission number" name="keyword" required="required">
                <input type="submit" name="submit" class="hidden">
            </div>
        </form>
        <div class="row">
         <div class="col-md-6" style="margin-top: 25px;">
             <?php

                        if($error){

                            $obj->errorDisplay($error);

                        }
                        else if($success){

                            $obj->successDisplay($success);

                        }
                           

                        ?>
         </div>
          
        </div>
        <?php
         $obj = new DataOperations();
         if(isset($_POST['submit']))
         {
          $search = $obj->con->real_escape_string(htmlentities($_POST['keyword']));
          $where = array("adm"=>$search);
          $get_data = $obj->search_engine("student",$where);

          if($get_data)
          {
           foreach ($get_data as $row) 
           {
             $name = $row['name'];
             $adm = $row['adm'];
             $form = $row['form'];
             $parent = $row['parent_name'];
             $contact = $row['parent_contact'];
           }
           ?>
           <div class="row">
                        <div class="panel panel-default" style="margin-top: 20px;">
                            <div class="panel-heading">
                               <?php echo $name." Clearance";?>
                            </div>
                            <div class="panel-body">

                          <div class="col-md-4">
                            <table class="table">
                            <tr>
                              <th>Name:</th>
                              <td><?php echo $name?></td>
                            </tr>
                            <tr>
                              <th>Adm number:</th>
                              <td><?php echo $adm?></td>
                            </tr>
                            <tr>
                              <th>Form:</th>
                              <td><?php echo $form?></td>
                            </tr>
                              <tr>
                                 <td>
                                <a href="#clear<?php echo $adm;?>" data-toggle="modal"><button type="button" class="btn btn-primary btn-lg">Clear Student </button> </a>
                                </td>
                            </tr>
                            </table>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-6">
                            <table class="table">
                              <tr>
                              <th>Parent/Gurdian Name:</th>
                              <td><?php echo $parent?></td>
                              </tr>
                              <tr>
                              <th>Parent/Gurdian Contact:</th>
                              <td><?php echo $contact?></td>
                              </tr>
                            </table>
                            </div>
                          
                          </div>
                          <div class="modal fade" id="clear<?php echo $adm;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Clearance</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                                                  <div class="alert alert-danger">
                                                     <p>Confirm the student you want to clear is the right one since he will be immediately removed from the school system completely. </p>
                                                      <p>Are you sure you want to clear <b><?php echo $name?> </b>?</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="clear" class="btn btn-primary" value="<?php echo $adm;?>">Continue</button>
                                                  </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                        </div>
           </div>
           <?php
          }
          else
          {
            ?>
            <div class="row">
             <div class="col-md-2"></div>
             <div class="col-md-6">
                
                    
                        <div class="alert alert-danger" style="margin-top: 20px;" >
                            No Details found for adm no. <b><?php echo $search?></b>
                        </div>
                        
                </div>
            </div>
            <?php
          }
         }
        ?>


      </div>
</div>
</div>
<?php include 'plugins/scripts.php'; ?>
</body>
</html>