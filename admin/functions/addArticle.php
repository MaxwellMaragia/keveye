<?php
include 'actions.php';
$obj = new DataOperations();

$error=$success='';
date_default_timezone_set('Africa/Nairobi');
if(isset($_POST['save']))
{
$title = $obj->con->real_escape_string(htmlentities($_POST['title']));
$author = $obj->con->real_escape_string(htmlentities($_POST['name']));
$club = $obj->con->real_escape_string(htmlentities($_POST['club']));
$article = $obj->con->real_escape_string(htmlentities($_POST['article']));
$time = date('Y/m/d H:i');

if($club == "Select Club")
{
$error = "Please select your club";
}
else
{
	$data = array(
          "title"=>$title,
          "article"=>$article,
          "club"=>$club,
          "time_date"=>$time,
          "author"=>$author
		);
	if($obj->insert_record("articles",$data))
	{
     $success = "New Article added successfully!";
	}else
	{
		$error = mysqli_error($obj->con);
	}
}
}

?>