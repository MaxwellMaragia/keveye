<?php
  session_start();
  unset($_SESSION['bursar_login']);
  $_SESSION['logout']="Successfully logged out";
  header('location:../login.php');