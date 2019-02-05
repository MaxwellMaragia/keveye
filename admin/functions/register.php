<?php
include 'actions.php';
$obj = new DataOperations();

$error = $success = "";
$name = $adm = $form = $parent = $contact = "";

if(isset($_POST['register']))
{
$name = $obj->con->real_escape_string(htmlentities($_POST['name']));
$adm = $obj->con->real_escape_string(htmlentities($_POST['adm']));
$form = $obj->con->real_escape_string(htmlentities($_POST['form']));
$parent = $obj->con->real_escape_string(htmlentities($_POST['parent']));
$contact = $obj->con->real_escape_string(htmlentities($_POST['contact']));
$password = password_hash($adm, PASSWORD_DEFAULT);

$where = array("adm"=>$adm);
$exist = $obj->fetch_records("student",$where);
if($exist)
{
$error = "Student with the same admission number already exist!";
} 

else
{

$data = array(
        "adm"=>$adm,
        "name"=>$name,
        "form"=>$form,
        "parent_contact"=>$contact,
        "parent_name"=>$parent,
        "student_password"=>$password,
        "parent_password"=>$password
);

$insert = $obj->insert_record("student",$data);
if($insert)
{
$success = "New student registered successfully";
}
else
{
	$error = mysqli_error($obj->con);
}

}
}

?>