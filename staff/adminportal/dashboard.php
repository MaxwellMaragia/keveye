<?php

  session_start();

  if($_SESSION['admin_login']){

      $user = $_SESSION['admin_login'];
      include "functions/get_data.php";
      //locking or unlocking modules
  if(isset($_POST['module']))
  {

         $mod_id = $_POST['module'];
         $where = array("id"=>$mod_id);


         $mod_status = $obj->fetch_records("modules",$where);

         foreach($mod_status as $row)
         {
             $module_state = $row['state'];
         }



         if($module_state == "locked")
         {
             $data = array("state" => "unlocked");

             if($obj->update_record("modules",$where,$data))
             {
                 $success = "Account deactivated";
             }
         }
         else if($module_state == "unlocked")
         {
             $data = array("state" => "locked");

             if($obj->update_record("modules",$where,$data))
             {
                 $success = "Account activated";
             }
         }
     }

    

  }
  else{

      header('location:../index.php');

  }

?>
<!DOCTYPE html>


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin dashboard</title>
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

           <!--  title stats-->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="tile-stats tile-red">
                                <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                                <div class="num" data-start="0" data-end="7"
                                     data-postfix="" data-duration="1500" data-delay="0"><?php totalStudents();?></div>

                                <h3>students</h3>
                                <p>Total students</p>
                            </div>

                        </div>
                        <div class="col-md-3">

                            <div class="tile-stats tile-green">
                                <div class="icon"><i class="fa fa-user-md"></i></div>
                                <div class="num" data-start="0" data-end="2"
                                     data-postfix="" data-duration="800" data-delay="0"><?php totalTeacher();?></div>

                                <h3>teachers</h3>
                                <p>Total teachers</p>
                            </div>


                        </div>
                        <div class="col-md-3">

                            <div class="tile-stats tile-aqua">
                                <div class="icon"><i class="fa fa-gears"></i></div>
                                <div class="num" data-start="0" data-end="1"
                                     data-postfix="" data-duration="500" data-delay="0"><?php totalLibrarian();?></div>

                                <h3>Librarian</h3>
                                <p>Total librarians</p>
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="tile-stats tile-blue">
                                <div class="icon"><i class="fa fa-money"></i></div>
                                <div class="num" data-start="0" data-end="2"
                                     data-postfix="" data-duration="500" data-delay="0"><?php totalBursar();?></div>

                                <h3>Bursar</h3>
                                <p>Total bursar</p>
                            </div>

                        </div>

                             <div class="row" style="margin-top:27px;padding:18px;">
                                <div class="col-md-6">
                                    <div class="jumbotron">
                                        <div class="container">
                                            <h2>Administrator account</h2>
                                            <p>Usage instructions</p>
                                            <ul>
                                                <li>Add subjects</li>
                                                <li>Unlock modules</li>
                                                <li>Add streams</li>
                                                <li>Add classes</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                              <div class="col-md-6">
                                    <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Manage modules
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tbody>
                                                    <?php
                                                     $fetch_modules=$obj->fetch_all_records("modules");
                                                     foreach($fetch_modules as $row)
                                                     {
                                                        $module=$row['module'];
                                                        $state=$row['state'];
                                                        $id=$row['id'];
                                                        ?>
                                                     <tr>
                                                         <td><?=$module?></td>
                                                         <td><?=$state?></td>
                                                         <td>
                                                          <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                         <button name='module' value=<?php echo $id?> class='btn-link'>
                                                         <?php
                                                           
                                                           if($state=="locked")
                                                           {
                                                            echo "unlock";
                                                           }
                                                           else if($state=="unlocked")
                                                           {
                                                            echo "lock";
                                                           }

                                                         ?>
                                                         </button>
                                                         </form>
                                                         </td>
                                                     </tr>
                                                        <?php
                                                     }
                                                    ?>
                                                </tbody>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                              </div>

                            </div>

                    </div>
                </div>
            <!-- /. ROW  -->


            <!-- /. ROW  -->
            <footer><p>All right reserved. Design by: <a href="codei.co.ke">Codei</a></p></footer>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->

<!-- JS Scripts-->
<?php include "plugins/scripts.php"?>


</body>

</html>