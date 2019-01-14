<?php
session_start();

if($_SESSION['admin_login']){

    include("functions/actions.php");
    $obj=new DataOperations();
    $error=$success='';

    if(isset($_POST['submit']))
    {
        $year=$obj->con->real_escape_string(htmlentities($_POST['year']));
        $term=$obj->con->real_escape_string(htmlentities($_POST['term']));
        $opening_date=$obj->con->real_escape_string(htmlentities($_POST['opening_date']));
        $closing_date=$obj->con->real_escape_string(htmlentities($_POST['closing_date']));


        if(!is_numeric($year))
        {
            $error="Please enter valid year";
        }
        else
        {
            $period=$year." ".$term;
            $where=array("term"=>$period);

            if($obj->fetch_records("terms",$where))
            {
                $error="This term already exists";
            }
            else
            {
                $data=array("term"=>$period,"opening_date"=>$opening_date,"closing_date"=>$closing_date,"state"=>"In progress");
                //update the existing terms first
                $sql="UPDATE terms SET state='completed'";
                if(mysqli_query($obj->con,$sql))
                {
                    if($obj->insert_record("terms",$data))
                    {

                            $success="New term initiated successfully";
                        
                    }
                    else
                    {
                        echo mysqli_error($obj->con);
                    }
                }
            }

        }
    }




}
else{

    header('location:../index.php');

}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Academic Terms</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>
</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

        <?php include 'plugins/secondarynav.php' ?>


        <!--        -->


        <div class="row">
            <div class="col-md-12 " style="padding-top:20px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Add new term</div>
                    <div class="panel-body">
<!--                        --><?php
//
//                        if($_SESSION['message'])
//                        {
//                            $obj->successDisplay($_SESSION['message']);
//                        }
//                        ?>
                        <div class="form-inline alert alert-info" >
                            <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                <input type="text" class="form-control" required="required" placeholder="Enter year" name="year"/>
                                <select class="form-control " name="term" required="required">
                                    <option value="">Select term(1-3)</option>
                                    <option>term 1</option>
                                    <option>term 2</option>
                                    <option>term 3</option>
                                </select>
                                <input type="text" class="form-control" required="required" placeholder="Next opening date" name="opening_date" required="required"/>
                                <input type="text" class="form-control" required="required" placeholder="Current closing date" name="closing_date" required="required"/>

                                <button type="submit" class="btn btn-primary " name="submit">Add term</button>
                            </form>
                        </div>
                        <?php

                        if($error){

                            $obj->errorDisplay($error);

                        }
                        else if($success){

                            $obj->successDisplay($success);

                        }


                        ?>
                        <table class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>

                                <th>Term</th>
                                <th style="text-align: center;">State</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $table_row=$obj->fetch_all_records("terms");

                            foreach($table_row as $row)
                            {
                                ?>

                                <tr>

                                    <td ><?php echo $row["term"];?></td>

                                    <td align="center">
                                        <?=$row['state']?>
                                    </td>
                                </tr>


                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    </div><!-- end page inner-->
</div><!--end page wrapper-->
<?php include "plugins/scripts.php"?>


<script>
    $('.table').DataTable();
</script>
</body>
</html>