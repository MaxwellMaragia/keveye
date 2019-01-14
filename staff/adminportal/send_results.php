    <?php
    session_start();
    if($_SESSION['admin_login']){

        include "functions/actions.php";

        $obj=new DataOperations();
        $error = $success = '';




        if(isset($_POST['send']))
        {
            $period = $obj->con->real_escape_string(htmlentities($_POST['period']));
            $where = array('period'=>$period);
            $get_admissions = $obj->fetch_records('final_result',$where);
            if($get_admissions)
            {
                foreach($get_admissions as $row){
                    $admission = $row['admission'];

                    $where=array('AdmissionNumber'=>$admission);
                    $get_student = $obj->fetch_records('student',$where);

                    if($get_student)
                    {
                        foreach($get_student as $row)
                        {
                            $names = $row['names'];
                            $mobile = $row['fathersmobile'];



                            $sql = "SELECT GROUP_CONCAT(results.subject,CONCAT('(', results.total ,')') SEPARATOR ', '),final_result.average,final_result.grade FROM results INNER JOIN final_result ON results.admission = final_result.admission AND final_result.period = results.period  WHERE results.admission = $admission AND results.period = '$period'";
                            $exe = mysqli_query($obj->con, $sql);

                            if (mysqli_num_rows($exe) > 0) {
                                while ($row = mysqli_fetch_array($exe)) {

                                    $subject_result = $row["GROUP_CONCAT(results.subject,CONCAT('(', results.total ,')') SEPARATOR ', ')"];
                                    $average = round($row['average']);
                                    $grade = $row['grade'];
                                    $message = "$names  results for $period are $subject_result, Average($average), Mean grade( $grade )";


                                    include 'sending_results.php';



                                }
                            }
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
        <title>Send results</title>
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
            <!--        contents-->

            <div class="row">
                <div class="col-md-12 " style=" padding-top:20px;">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">SELECT TERM</div>
                        <div class="panel-body">
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
                            <div class="alert alert-info">
                                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                                    <div class="form-inline">
                                        <select name="period" required="required" class="form-control">
                                            <option value="">Select term</option>
                                            <?php
                                            $sql = "SELECT DISTINCT period FROM results";
                                            $exe = mysqli_query($obj->con,$sql);
                                            while($get_period = mysqli_fetch_assoc($exe))
                                            {
                                                $term = $get_period['period'];
                                                echo "<option>$term</option>";
                                            }
                                            ?>
                                        </select>
                                        <button class="btn btn-primary" type="submit" name="send">Send sms</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!--End Advanced Tables -->
            <!--        -->
        </div>
    </div>


    <?php include "plugins/scripts.php" ?>
    <?php include "plugins/table.php" ?>
    </body>
    </html>
