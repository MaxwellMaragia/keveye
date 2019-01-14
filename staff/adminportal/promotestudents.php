<?php

session_start();

if($_SESSION['admin_login']){

 include "functions/ManageStudents.php";

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
    <title>Promote students</title>
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

<div class="row">
	<div class="col-md-12">
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
		       <div class="alert alert-info" data-scroll-reveal="enter from the bottom after 1.1s" >
                       <span>
                          <strong> NOTE!! </strong>
                           <hr />
                           After promoting students. They will be moved to the next class but other details like fees and results will not change. Only the class.
                           <hr />
                           Students who are already in form 4 will be moved to another table with details like their names, admission and fee balance
                           <hr/>
                           <font style="color:red;">This action may take a lot of time to execute depending on the number of students in form 4 to be transfered to the graduates table. Your browser may even crash</font>
                           <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>">
                           	<button type="submit" name="promote" class="btn btn-warning btn-lg">Promote students</button>
                           </form>
                       </span>
            </div>
	</div>
</div>

        </div>
    </div>
<!--javascript plugin-->
<?php include "plugins/scripts.php" ?>
<!--//javascript-->
</body>
</html>