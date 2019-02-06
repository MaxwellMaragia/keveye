<?php
include 'actions.php';
$obj = new DataOperations();

$error=$success='';

if(isset($_POST['save']))
{
	$topic = $obj->con->real_escape_string(htmlentities($_POST['topic']));
	$details = $obj->con->real_escape_string(htmlentities($_POST['details']));

	$data = array("notice"=>$topic, "details"=>$details);
	if($obj->insert_record("notices",$data))
	{
      $success = "New Notice added successfully!";
	}
	else
	{
		$error = mysqli_error($obj->con);
	}

}

?>