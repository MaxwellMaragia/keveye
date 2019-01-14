<?php
session_start();
if(!isset($_SESSION['student'])){
    header('location: student_login.php');
}else{
    session_destroy();
    header('location:student_login.php');
}
?>