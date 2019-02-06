<?php
include 'actions.php';
$obj = new DataOperations();
$error=$success='';

if(isset($_POST['delete']))
{
    $key = $_POST['delete'];
    $where = array("job_id"=>$key);

  if($obj->delete_record("teacher",$where))
  {
  $success = "Deleted successfully!";
  }
  else
  {
  	$error = mysqli_error($obj->con);
  }
}

?>