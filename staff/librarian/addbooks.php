<?php
session_start();

if(!isset($_SESSION['librarian_account']))
{
    header("location:../index.php");
}
else
{
    include "functions/actions.php";
    $book_name=$book_serial=$success=$error='';
    $obj=new DataOperations();

    if(isset($_POST['addbook']))
    {
        $book_name=$obj->con->real_escape_string(htmlentities($_POST['title']));
        $book_serial=$obj->con->real_escape_string(htmlentities($_POST['serial']));
        $book_category=$obj->con->real_escape_string(htmlentities($_POST['category']));

        if(!$obj->validate_string($book_name))
        {
            $error="Invalid book name. Only characters and whitespace allowed";
        }
        else if(!is_numeric($book_serial))
        {
            $error="Serial number must be numerical";
        }
        else if($book_category == "Select category")
        {
            $error="Please select valid category";
        }
        else
        {
            $where=array("book_id"=>$book_serial);
            if($obj->fetch_records("books",$where))
            {
                $error="Book with this serial number exists";
            }
            else
            {
                $data=array(
                    "book_name"=>$book_name,
                    "book_id"=>$book_serial,
                    "state"=>"available",
                    "category"=>$book_category
                );

                if($obj->insert_record("books",$data))
                {
                    $success="Book added successfully";
                    $book_category=$book_serial=$book_name='';
                }
                else
                {
                    $error=mysqli_error($obj->con);
                }
            }
        }
    }
    if(isset($_POST['delete']))
    {
        $book_id=$_POST['delete'];
        $where=array("book_id"=>$book_id);
        if($obj->delete_record("books",$where))
        {
            $success="Book deleted successfully";
        }
        else
        {
            $error=mysqli_error($obj->con);
        }
    }

    if(isset($_POST['update']))
    {
        $title=$obj->con->real_escape_string(htmlentities($_POST['update-title']));
        $serial=$obj->con->real_escape_string(htmlentities($_POST['serial']));
        $category=$obj->con->real_escape_string(htmlentities($_POST['category']));

        if(!$obj->validate_string($title))
        {
            $error="Invalid book name. Only characters and whitespace allowed";
        }
        else if(!is_numeric($serial))
        {
            $error="Serial number must be numerical";
        }
        else
        {
            $where=array("book_id"=>$serial);
            $data=array("book_name"=>$title,"category"=>$category);
            if($obj->update_record("books",$where,$data))
            {
                $success="Update was successful";
            }
            else
            {
                mysqli_error($obj->con);
            }
        }

    }

}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add books</title>
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


        <div class="row" style="padding-top:30px;">
            <div class="col-md-12" style="margin-bottom:30px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="fa fa-list-ul"></span>
                        All books in library
                    </div>
                    <div class="panel-body">
                      <?php
      
      $where=array("module"=>"add books");
      $get_state=$obj->fetch_records("modules",$where);
      foreach($get_state as $row)
      {
        $state=$row['state'];
      }

      if($state=="unlocked")
      {
        ?>
       <div class="form-inline alert alert-info">
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                                <input type="text" placeholder="Book title" class="form-control" name="title" required value="<?=$book_name?>"/>
                                <input type="text" placeholder="Book serial" class="form-control" name="serial" required value="<?=$book_serial?>"/>
                                <select class="form-control" name="category" required="required">
                                    <option value="">Select category</option>
                                    <option>Form 1 syllabus</option>
                                    <option>Form 2 syllabus</option>
                                    <option>Form 3 syllabus</option>
                                    <option>Form 4 syllabus</option>
                                    <option>Revision</option>
                                    <option>Research</option>
                                    <option>Atlas</option>
                                    <option>Computing and technology</option>
                                    <option>Mathematical table</option>
                                    <option>Dictionary</option>
                                    <option>Kamusi</option>
                                    <option>Set book</option>
                                    <option>Novel</option>
                                    <option>Newspaper</option>
                                </select>
                                <button class="btn btn-primary" type="submit" name="addbook">
                                    Add book
                                </button>
                            </form>
                        </div>
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
                        if($error)
                        {
                            $obj->errorDisplay($error);
                        }
                        if($success)
                        {
                            $obj->successDisplay($success);
                        }
                        ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Book title</th>
                                <th>Serial number</th>
                                <th>Category</th>
                                <th>State</th>
                                <th style="text-align: center">Actions</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $get_book_list=$obj->fetch_all_records("books");

                            foreach($get_book_list as $row)
                            {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['book_name']?></td>
                                        <td><?php echo $row['book_id']?></td>
                                        <td><?php echo $row['category']?></td>
                                        <td><?php echo $row['state']?></td>
                                        <td align="center">
                                            <a href="#delete<?php echo $row['book_id'];?>" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> </a>
                                            <a href="#edit<?php echo $row['book_id'];?>" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> </button> </a>
                                        </td>
                                    </tr>

                                <!-- Modal delete-->
                                <div class="modal fade" id="delete<?php echo $row['book_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                    <div class="alert alert-danger">
                                                        Are you sure you want to delete <b></b><?php echo $row['book_name']?>?</b>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                        <button name="delete" class="btn btn-primary" value="<?php echo $row['book_id']?>">Delete book</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end//modal delete-->

                                <!-- Modal edit-->
                                <div class="modal fade" id="edit<?php echo $row['book_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Update dialog</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                    <label>Names</label>
                                                    <input type="text" class="form-control" value="<?php echo $row['book_name'] ?>" required name="update-title">
                                                    <label>Job number(uneditable)</label>
                                                    <input type="text" class="form-control" value="<?php echo $row['book_id'] ?>" disabled>
                                                    <input type="hidden"  value="<?php echo $row['book_id']?>" name="serial">
                                                    <label>Category</label>
                                                    <select class="form-control" name="category">
                                                        <option><?=$row['category']?></option>
                                                        <option>Form 1 syllabus</option>
                                                        <option>Form 2 syllabus</option>
                                                        <option>Form 3 syllabus</option>
                                                        <option>Form 4 syllabus</option>
                                                        <option>Revision</option>
                                                        <option>Research</option>
                                                        <option>Atlas</option>
                                                        <option>Mathematical table</option>
                                                        <option>Dictionary</option>
                                                        <option>Kamusi</option>
                                                        <option>Set book</option>
                                                        <option>Novel</option>
                                                        <option>Newspaper</option>
                                                    </select>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                        <input type="submit" name="update" id="update" class="btn btn-primary" value="Update"/>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end//modal edit-->
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
