<?php


    $error=$success=$stream='';
    $obj=new DataOperations();

    //saving stream
    if(isset($_POST['save']))
    {
        $stream=$obj->con->real_escape_string(htmlspecialchars($_POST['stream']));

        if($obj->validate_string($stream))
        {

            $data=array("stream_name"=>$stream);

            //checking if the stream exists
            if($obj->fetch_records("streams",$data))
            {
                $error="Stream already exists";
            }
            else
            {
                //saving data to database
                if($obj->insert_record("streams",$data))
                {
                    $success="Stream added successfully";
                }
                else
                {
                    $error="Error saving stream";
                }
            }

        }
        else{

            $error="Invalid stream name";
        }
    }

 //delete stream
  if(isset($_POST['delete']))
  {
      $stream_id=$_POST['delete'];
      $where=array("id"=>$stream_id);

      if($obj->delete_record("streams",$where))
      {
          $success="Stream deleted";
      }
      else{

          $error="Error deleting stream";
      }
  }