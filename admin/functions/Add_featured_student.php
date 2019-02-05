<?php

include 'actions.php';
$obj = new DataOperations();

$error=$success='';



if(isset($_POST['save']))
{
$name = $obj->con->real_escape_string(htmlentities($_POST['name']));
$title = $obj->con->real_escape_string(htmlentities($_POST['title']));
$details = $obj->con->real_escape_string(htmlentities($_POST['details']));
$img = $_FILES["image"]["name"];


$sql = "SELECT * FROM featured_student";
$result = mysqli_query($obj->con,$sql);
$delete = array();
while ($fetch = mysqli_fetch_array($result)) 
{
	$delete[] = $fetch['id'];
}
$count = mysqli_num_rows($result);

$where = array("name"=>$name);




	$data = array(
          "name"=>$name,
          "title"=>$title,
          "details"=>$details,
          "image"=>$img
		);
	if($obj->insert_record("featured_student",$data))
	{
	if($count>2)
	{
	$sql1 = "SELECT image FROM featured_student WHERE id='$delete[0]'";
	$res = mysqli_query($obj->con,$sql1);
	while ($file = mysqli_fetch_array($res))
	 {
		$un=unlink('uploads/'.$file['image']);
	}
	$where1 = array("id"=>$delete[0]);
	$delete_row = $obj->delete_record("featured_student",$where1);
	}
      move_uploaded_file($_FILES["image"]["tmp_name"],"uploads/" . $_FILES["image"]["name"]);
      $success = "New featured student details recorded successfully!";
	}
	else
	{
		$error = mysqli_error($obj->con);
	}

}
?>