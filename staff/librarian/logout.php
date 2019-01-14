<?php
     session_start();
     if(isset($_SESSION['librarian_account']))
     {
         unset($_SESSION['librarian_account']);
         header('location:../index.php');
     }

    else
    {
         header('location:../index.php');
    }