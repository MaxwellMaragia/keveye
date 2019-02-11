<?php
include 'actions.php';
$obj = new DataOperations();
$dir = $img_name = $temp_file =  $description ="";
$error=$success='';
if(isset($_POST['save']))
{



			
$img_name=$_FILES["image"]["name"];



$description = $_POST['desc'];

$sql = "SELECT * FROM carousel_pics";
$res = mysqli_query($obj->con,$sql);
$count = mysqli_num_rows($res);


//validation
$where = array("image"=>$img_name);
if($obj->fetch_records("carousel_pics",$where))
{
 $error = "Image already exist!";
}

else if($count>2)
{
$error = "Only three images allowed at a time, please delete old images to add new ones!";
}
else
{

move_uploaded_file($_FILES["image"]["tmp_name"],"uploads/" . $_FILES["image"]["name"]);

$data = array("image"=>$img_name,"short_message"=>$description);
$insert = $obj->insert_record("carousel_pics",$data);

if($insert)
{
 $success = "Uploaded";
}
else
{
	$error = mysqli_error($obj->con);
}
}


}

?>