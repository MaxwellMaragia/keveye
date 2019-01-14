<?php

   include "actions.php";
   $obj = new DataOperations();

   $year = $_GET['year'];

  $where = array("form_str" => $year);


   $get_data = $obj->fetch_records("class",$where);



    foreach($get_data as $row)
    {

        $streams = array(


            $year => $row['stream']

        );

        if(isset($_GET['year']))
        {
            $c = $_GET['year'];
            if(isset($streams[$c]))
            {
                for($i = count($streams[$c]) -1; $i>=0; $i--)
                {
                    echo "<option value='".$streams[$c]."'>".$streams[$c]."</option>";
                }
            }
        }





    }

