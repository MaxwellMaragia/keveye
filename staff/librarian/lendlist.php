<?php
session_start();

if(!isset($_SESSION['librarian_account']))
{
    header("location:../index.php");
}
else
{
    $success=$error='';

    include "functions/actions.php";
    $librarian_id=$_SESSION['librarian_account'];
    $obj = new DataOperations();
    $where_librarian=array("username"=>$librarian_id);
    $get_librarian=$obj->fetch_records("staff_accounts",$where_librarian);

    foreach($get_librarian as $row)
    {
        $librarian_name=$row['names'];
    }

    if(isset($_POST['clear']))
    {
        $book_id=$_POST['clear'];
        $where=array("book_id"=>$book_id);
        $get_books=$obj->fetch_records("book_lend_list",$where);

            foreach($get_books as $row)
            {
                $book_title=$row['book_name'];
                $student_name=$row['student_names'];
                $admission=$row['admission'];

            }
        //delete
        if($obj->delete_record("book_lend_list",$where))
        {
            //update book list
            $data=array("state"=>"available");
            if($obj->update_record("books",$where,$data))
            {
                //saving logs
                $current_time = date("Y-m-d H:i:s");
                $data=array(
                    "student_id"=>$admission,
                    "student_name"=>$student_name,
                    "book_serial"=>$book_id,
                    "book_title"=>$book_title,
                    "date"=>$current_time,
                    "action"=>"clearance",
                    "librarian"=>$librarian_name
                );

                if($obj->insert_record("book_logs",$data))
                {
                    $success="Student cleared successfully";
                }
                else
                {
                    $error=mysqli_error($obj->con);

                }
            }

        }

    }
}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Students with books</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>

</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

        <!--        secondary nav-->
        <?php

        include 'plugins/secondarynav.php';

        ?>
        <!--        end nav-->
        <!--        contents-->



        <div class="row" style="padding-top:30px;">
            <div class="col-md-12" style="margin-bottom:30px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="fa fa-list-ul"></span>
                        List of students with uncleared books
                    </div>
                    <div class="panel-body">
                        <?php
                        if($success)
                        {
                            $obj->successDisplay($success);
                        }
                        if($error)
                        {
                            $obj->errorDisplay($error);
                        }
                        ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Student names</th>
                                <th>Admission</th>
                                <th>Book title</th>
                                <th>Book serial</th>
                                <th>Date given</th>
                                <th>Clear</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $get_students_list=$obj->fetch_all_records("book_lend_list");
                            foreach($get_students_list as $row)
                            {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['student_names']?></td>
                                        <td><?php echo $row['admission']?></td>
                                        <td><?php echo $row['book_name']?></td>
                                        <td><?php echo $row['book_id']?></td>
                                        <td><?php echo $row['date_assigned']?></td>
                                        <td>
                                        <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                        <button class="btn btn-danger btn-sm" type="submit" name="clear" value="<?=$row['book_id']?>"><span class="glyphicon glyphicon-trash"></span></button>
                                        </form>
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
    </div>
    <!-- /. ROW  -->





</div>
</div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
</body>
</html>
