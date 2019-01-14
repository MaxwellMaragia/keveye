<?php
session_start();

if(!isset($_SESSION['librarian_account']))
{
    header("location:../index.php");
}
else
{
    include "functions/actions.php";
    $librarian_id=$_SESSION['librarian_account'];
    $obj = new DataOperations();
    $where_librarian=array("username"=>$librarian_id);
    $get_librarian=$obj->fetch_records("staff_accounts",$where_librarian);

    foreach($get_librarian as $row)
    {
        $librarian_name=$row['names'];
    }
}

?>
    <!DOCTYPE html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Issue books</title>
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
<!--            content-->
         <?php
      
      $where=array("module"=>"issue books");
      $get_state=$obj->fetch_records("modules",$where);
      foreach($get_state as $row)
      {
        $state=$row['state'];
      }

      if($state=="unlocked")
      {
        ?>
            <form  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="input-group-lg" style="margin-bottom: 10px;">
                    <input type="text" id="student-search" class="form-control" placeholder="Name or admission" name="keyword" required="required">
                    <input type="submit" name="submit" class="hidden">
                </div>
            </form>
        <?php
      }
      else
      {
        ?>
       <div class="alert alert-danger">
       Oops!
       <hr>
           This module has been locked. Contact the administrator to unlock module
       </div>
        <?php
      }

    ?>
            <?php

            if(isset($_POST['submit'])) {

                $keyword = $obj->con->real_escape_string(htmlentities($_POST['keyword']));

                $where = array("AdmissionNumber"=>$keyword,"names"=>$keyword);

                $get_data = $obj->search_engine("student",$where);

                if($get_data) {

                    foreach ($get_data as $row) {
                        $admission = $row['AdmissionNumber'];
                        $names = $row['names'];
                        $class = $row['class'];
                        $_SESSION['student']=$admission;
                        $_SESSION['student_name']=$names;
                    }

                    $sql = "SELECT * FROM book_lend_list WHERE admission=$admission";
                    $execute = mysqli_query($obj->con, $sql);

                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php echo $names." ($admission,$class)"?>
                            </div>
                            <div class="panel-body">
                            <div class="col-md-6 pull-left">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Issued books
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                        if(mysqli_num_rows($execute)==0)
                                        {
                                            ?>
                                            <div class="alert alert-danger">
                                                This student has not been issued with any book yet
                                            </div>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Serial</th>
                                                <th>Issued date</th>
                                            </tr>
                                            </thead>
                                                <tbody>
                                                <?php
                                                 while($get_borrowed=mysqli_fetch_assoc($execute))
                                                 {
                                                     ?>
                                                     <tr>
                                                         <td><?=$get_borrowed['book_name']?></td>
                                                         <td><?=$get_borrowed['book_id']?></td>
                                                         <td><?=$get_borrowed['date_assigned']?></td>
                                                     </tr>
                                                     <?php
                                                 }
                                                ?>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <?php
                                if(mysqli_num_rows($execute) >=3)
                                {
                                    ?>
                                    <div class="alert alert-danger">
                                        Student already assigned 3 books hence cannot be assigned more
                                    </div>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <table class="table table-responsive table-striped table-bordered" id="table">

                                        <thead>
                                        <tr>
                                            <th>Book name</th>
                                            <th>Serial</th>
                                            <th>Category</th>
                                            <th>Assign</th>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        <?php

                                    $where=array("state"=>"available");
                                    $get_book_list=$obj->fetch_records("books",$where);
                                    foreach($get_book_list as $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['book_name']?></td>
                                            <td><?php echo $row['book_id']?></td>
                                            <td><?php echo $row['category']?></td>
                                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                                            <td align="center">
                                                <button type="submit" name="issue" value="<?=$row['book_id']?>" class="btn btn-info btn-sm">Issue</button>
                                            </td>
                                            </form>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php
                                }
                                ?>
                            </div>
                            </div>
                        </div>
                    <?php
                    }
                else
                {
                    ?>

                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div class="alert alert-danger" >
                                No student found for name or admission <?=$keyword?>
                            </div>
                        </div>
                    </div>

                <?php

                }
                }
            if(isset($_POST['issue']))
            {
                $book_id=$_POST['issue'];
                $where=array("book_id"=>$book_id);
                $get_book=$obj->fetch_records("books",$where);

                foreach($get_book as $row)
                {
                    $book_name=$row['book_name'];
                }

                $current_time = date("Y-m-d H:i:s");

                $data=array(
                    "admission"=>$_SESSION['student'],
                    "student_names"=>$_SESSION['student_name'],
                    "book_id"=>$book_id,
                    "book_name"=>$book_name,
                    "date_assigned"=>$current_time

                );

                //get student data

                if($obj->insert_record("book_lend_list",$data))
                {

                    $where=array("book_id"=>$book_id);
                    $data=array("state"=>"unavailable");

                    if($obj->update_record("books",$where,$data))
                    {

                        //saving logs
                        $data=array(
                            "student_id"=>$_SESSION['student'],
                            "student_name"=>$_SESSION['student_name'],
                            "book_serial"=>$book_id,
                            "book_title"=>$book_name,
                            "date"=>$current_time,
                            "action"=>"book issue",
                            "librarian"=>$librarian_name
                        );

                        if($obj->insert_record("book_logs",$data))
                        {

                            ?>
                            <div class="alert alert-success">
                                Book issued successfully
                            </div>
                        <?php
                        }
                        else
                        {
                            ?>
                            <div class="alert alert-danger">
                                Error issuing book
                            </div>
                        <?php
                        }
                        }


                    }



            }
            ?>
            </div>
        </div>
    <?php include "plugins/scripts.php"?>
    <script>
        $('#table').DataTable();
    </script>
    </body>
</html>
