<?php

  include 'actions.php';
  $obj=new DataOperations();
  $error=$success="";
  if(isset($_POST['delete']))
  {
      $period=$obj->con->real_escape_string($_POST['period']);

      if($period == "Select period")
      {
          $error="Please select period";
      }
      else
      {
          $where=array("period"=>$period);
          if($obj->delete_record("results",$where))
          {
              if($obj->delete_record("final_result",$where))
              {
                  $success="Results deleted successfully";
              }
          }
      }
  }