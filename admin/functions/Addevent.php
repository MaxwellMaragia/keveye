<?php
include 'actions.php';
$obj = new DataOperations();

$error=$success='';

if(isset($_POST['save']))
{
$title = $obj->con->real_escape_string(htmlentities($_POST['title']));
$date = $obj->con->real_escape_string(htmlentities($_POST['date']));
$desc = $obj->con->real_escape_string(htmlentities($_POST['desc']));
$img = $_FILES["image"]["name"];

$sql = "SELECT * FROM news_events";
$result = mysqli_query($obj->con,$sql);
$delete = array();
while ($fetch = mysqli_fetch_array($result)) 
{
	$delete[] = $fetch['id'];
}
$count = mysqli_num_rows($result);

$where = array("title"=>$title);
if($obj->fetch_records("news_events",$where))
{
  $error = "Already the event exist, Ensure you type new title!";
}
else
{
	$data = array(
           "title"=>$title,
           "time_date"=>$date,
           "details"=>$desc,
           "image"=>$img
		);
if($obj->insert_record("news_events",$data))
{

if($count>3)
{
	$sql1 = "SELECT image FROM news_events WHERE id='$delete[0]'";
	$res = mysqli_query($obj->con,$sql1);
	while ($file = mysqli_fetch_array($res))
	 {
		$un=unlink('images/'.$file['image']);
	}
	$where = array("id"=>$delete[0]);
	$delete_row = $obj->delete_record("news_events",$where);	
}
	  move_uploaded_file($_FILES["image"]["tmp_name"],"images/" . $_FILES["image"]["name"]);
      $success = "New event added successfully!";
}
else
	{
		$error = mysqli_error($obj->con);
	}
}

}

?>