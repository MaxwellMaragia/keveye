<?php
/**
 * Created by PhpStorm.
 * User: maxipain
 * Date: 11/26/2017
 * Time: 5:53 PM
 */

include "db.php";

class DataOperations extends DATABASE {



    public function fetch_all_records($table)
    {
        $sql = "SELECT * FROM ".$table;
        $array = array();
        $query = mysqli_query($this->con,$sql);

        while($row = mysqli_fetch_assoc($query))
        {
            $array[] = $row;
        }

        return $array;
    }

    public function search_engine($table,$where)
    {

        $sql = "";
        $condition = "";

        foreach($where as $key => $value)
        {
            $condition .= $key ."='" . $value . "' OR ";
        }

        $condition = substr($condition,0,-4);

        $sql = "SELECT * FROM ".$table." WHERE ".$condition;
        $array = array();
        $query = mysqli_query($this->con,$sql);

        while($row = mysqli_fetch_assoc($query))
        {
            $array[] = $row;
        }

        return $array;


    }


    public function insert_record($table,$fields)
    {
        $sql = "";
        $sql .="INSERT INTO ".$table;
        $sql .=" (".implode(",",array_keys($fields)).") VALUES";
        $sql .=" ('".implode("','",array_values($fields))."')";



        $query = mysqli_query($this->con,$sql);

        if($query)
        {
            return true;
        }

    }

    public function fetch_records($table,$where)
    {
        $sql = "";
        $condition = "";

        foreach($where as $key => $value)
        {
            $condition .= $key ."='" . $value . "' AND ";
        }

        $condition = substr($condition,0,-5);

        $sql = "SELECT * FROM ".$table." WHERE ".$condition;
        $array = array();
        $query = mysqli_query($this->con,$sql);

        while($row = mysqli_fetch_assoc($query))
        {
            $array[] = $row;
        }

        return $array;


    }

    public function delete_record($table,$where)
    {
        $sql = "";
        $condition = "";

        foreach($where as $key => $value)
        {
            $condition .= $key ."='" . $value . "' AND ";
        }
        $condition = substr($condition,0,-5);

        $sql = "DELETE FROM ".$table." WHERE ".$condition;

        if(mysqli_query($this->con,$sql))
        {
            return true;
        }

    }

    public function update_record($table,$where,$fields)
    {

        $sql = "";
        $condition = "";

        foreach($where as $key => $value)
        {
            $condition .= $key ."='" . $value . "' AND ";
        }

        $condition = substr($condition,0,-5);

        foreach($fields as $key => $value)
        {
            $sql .= $key . "='".$value."', ";
        }

        $sql = substr($sql,0,-2);
        $sql = "UPDATE ".$table." SET ".$sql." WHERE ".$condition;


        if(mysqli_query($this->con,$sql))
        {
            return true;
        }

    }

    public function errorDisplay($error)
    {
        echo "<div class='alert alert-danger'>
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
              $error
              </div>";
    }

    public function successDisplay($success)
    {

        echo "<div class='alert alert-success'>
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
              $success
              </div>";

    }

    //single function for validating string variables like names

    public function validate_string($variable)
    {

     if(!preg_match("/^[a-zA-Z ]*$/", $variable))
      {
          return false;
      }
     else
      {
         return true;
      }

    }

    public function validate_int($variable)
    {
        if(!filter_var($variable.FILTER_VALIDATE_INT))
        {
             return false;
        }
        else
        {
             return true;
        }
    }


  }




