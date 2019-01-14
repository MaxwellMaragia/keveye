<?php
     session_start();
     if(isset($_SESSION['bursar_account']))
     {
         unset($_SESSION['bursar_account']);
         header('location:../index.php');
     }

    else
    {
        header('location:../index.php');
    }