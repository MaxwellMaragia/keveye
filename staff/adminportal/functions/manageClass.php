<?php


    include_once "actions.php";
    $obj=new DataOperations();
    $success=$error=$form=$stream='';

    if(isset($_POST['save']))
    {
        $stream = $obj->con->real_escape_string(htmlspecialchars($_POST['stream']));
        $form = $obj->con->real_escape_string(htmlspecialchars($_POST['form']));


        if($form == "Select form(1-4)")
        {
            return false;
            $error = "Please select form";
        }
        else if($stream == "Select stream")
        {
            $error="Please select stream";
        }
        else if(!$obj->validate_string($stream))
        {
            return false;
            $error = "Stream name must be character or string only";

        }
        else
        {
           if($form == "form 1")
           {
               $form = 1;
               $form_str = "form1";
           }
           else if($form == "form 2")
           {
               $form = 2;
               $form_str = "form2";
           }
           else if($form == "form 3")
           {
               $form = 3;
               $form_str = "form3";
           }
           else if($form == "form 4")
           {
               $form = 4;
               $form_str = "form4";
           }


           //saving data
            $initials = "$form$stream";
            $check_if_exists = array( "initials" => $initials );

            $data = array( "form" =>$form , "stream" => $stream , "initials" => $initials);

            if($obj->fetch_records("class",$check_if_exists))
            {

                $error = "This class already exists";

            }
            else
            {
                if($obj->insert_record("class",$data))
                {
                    $success = "Class added successfully";
                    $stream = "";
                }
                else
                {
                    $error = "Error ".mysqli_error($obj->con);
                }
            }

        }

    }

        //delete class
        if(isset($_POST['delete']))
        {
            $id=$_POST['delete'];
            $where=array("id"=>$id);

            if($obj->delete_record("class",$where))
            {
                $success="Class deleted";
            }
            else{

                $error="Error deleting class";
            }
        }