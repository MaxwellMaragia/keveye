<?php
include 'actions.php';
$obj = new DataOperations();
$img_name = "";
$error=$success='';
date_default_timezone_set('Africa/Nairobi');
$fetch_id = $obj->fetch_all_records("featured_news");
foreach ($fetch_id as $row)
 {
	$id = $row['id'];
	$old_img = $row['image'];
}


if(isset($_POST['save']))
{
       $sql = "SELECT * FROM featured_news WHERE id='$id' AND image='$old_img' ";
        $result = mysqli_query($obj->con,$sql);
        while($file = mysqli_fetch_array($result))
          {
            $unlink = unlink('uploads/'.$file['image']);
          }
if($unlink)
{


 $title = $obj->con->real_escape_string(htmlentities($_POST['title']));
 $news = $obj->con->real_escape_string(htmlentities($_POST['news']));
 $time = date('Y/m/d H:i');
 $img_name=$_FILES["image"]["name"];

 $where = array("id"=>$id);
 $data = array(
       "title"=>$title,
       "time_date"=>$time,
       "details"=>$news,
       "image"=>$img_name
 	);
 
 if($obj->update_record("featured_news",$where,$data))
 {
 move_uploaded_file($_FILES["image"]["tmp_name"],"uploads/" . $_FILES["image"]["name"]);
  $success = "Featured news updated successfully!";
 }
 else
 {
 	$error = mysqli_error($obj->con);
 }
}else
{
	$error ="Problem deleting old image!";
}
}

?>