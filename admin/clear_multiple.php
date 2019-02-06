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
  $f = $_POST['clear'];
  $where = array("form"=>$f);
  if($obj->delete_record("student",$where))
  {
  $success = "Students cleared successfully!";
  }
  else
  {
    $error = "Error occurred during clearance, try later!";
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
                <input type="text" id="student-search" class="form-control" placeholder="Search multiple students by form eg 4S" name="keyword" required="required">
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
         $where = array("form"=>$search);
         $get_data = $obj->search_engine("student",$where);

         if($get_data)
         {
          
          
          ?>
          <div class="row">
           <div class="panel panel-default" style="margin-top: 20px;">
                <div class="panel-heading">
                       <?php echo $search." Clearance";?>
                  </div>
                 <div class="panel-body">
                  <div class="col-md-8 col-md-offset-1">
                    <table class="table">
                      <tr>
                        <th>Name</th>
                        <th>Adm No.</th>
                        <th>Form</th>
                      </tr>
                      <?php
                      foreach ($get_data as $row)
                       {
                        $name = $row['name'];
                        $adm = $row['adm'];
                        $form = $row['form'];
                      ?>
                      <tr>
                        <td><?php echo $name?></td>
                        <td><?php echo $adm?></td>
                        <td><?php echo $form?></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td>
                          <a href="#clear<?php echo $form;?>" data-toggle="modal"><button type="button" class="btn btn-primary btn-md">Clear Students </button> </a>
                        </td>
                      </tr>
                    </table>
                    
                  </div>

                  <div class="modal fade" id="clear<?php echo $form;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Clearance</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                                                  <div class="alert alert-danger">
                                                     <p>Confirm the students you want to clear are the right ones since they will be immediately removed from the school system completely. </p>
                                                      <p>Are you sure you want to clear form <b><?php echo $form?> </b> students?</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="clear" class="btn btn-primary" value="<?php echo $form;?>">Continue</button>
                                                  </div>
                                                  </form>
                                              </div>
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
             <div class="col-md-6">
                
                    
                        <div class="alert alert-danger" style="margin-top: 20px;" >
                            No Students found in form <b><?php echo $search?></b>
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