<?php
/**
 * Created by PhpStorm.
 * User: maxipain
 * Date: 12/12/2017
 * Time: 3:15 PM
 */

  $jobnumber = $_SESSION['bursar_login'];
  $where = array("jobnumber"=>$jobnumber);

  $get_data = $obj->fetch_records("bursar",$where);
  foreach($get_data as $row)
  {
      $jobnumber = $row['jobnumber'];
      $names = $row['names'];
  }