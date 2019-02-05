<?php
include 'actions.php';
$obj = new DataOperations();

$error=$success='';

if(isset($_POST['save']))
{
  $name = $obj->con->real_escape_string(htmlentities($_POST['name']));
  $title = $obj->con->real_escape_string(htmlentities($_POST['title']));
  $img = $_FILES["image"]["name"];


$sql = "SELECT * FROM team";
$result = mysqli_query($obj->con,$sql);
$delete = array();
while ($fetch = mysqli_fetch_array($result)) 
{
	$delete[] = $fetch['id'];
}
$count = mysqli_num_rows($result);
$data = array(
        "name"=>$name,
        "title"=>$title,
        "image"=>$img
	);
if($obj->insert_record("team",$data))
{
if($count>3)
{
	$sql1 = "SELECT image FROM team WHERE id='$delete[0]'";
	$res = mysqli_query($obj->con,$sql1);
	while ($file = mysqli_fetch_array($res))
	 {
		$un=unlink('team/'.$file['image']);
	}
	$where = array("id"=>$delete[0]);
	$delete_row = $obj->delete_record("team",$where);	
}
	  move_uploaded_file($_FILES["image"]["tmp_name"],"team/" . $_FILES["image"]["name"]);
      $success = "Special student added to the School Amazing Team!";

}
else
	{
		$error = mysqli_error($obj->con);
	}
}

?>