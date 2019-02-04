<?php

include_once "functions/actions.php";

//variables
$error=$success=$names=$admission=$class=$fname=$mname=$fmobile=$category=$mmobile=$success='';

//initiate object of actions class
$obj=new DataOperations();

//trigger query if add new student form is submitted

if(isset($_POST['save']))
{
    $names=$obj->con->real_escape_string(htmlspecialchars($_POST['names']));
    $admission=$obj->con->real_escape_string(htmlspecialchars($_POST['admission']));
    $class=$obj->con->real_escape_string(htmlspecialchars($_POST['class']));
    // $fname=$obj->con->real_escape_string(htmlspecialchars($_POST['fname']));
    // $mname=$obj->con->real_escape_string(htmlspecialchars($_POST['mname']));
    // $fmobile=$obj->con->real_escape_string(htmlspecialchars($_POST['fmobile']));
    $mobile=$obj->con->real_escape_string(htmlspecialchars($_POST['mobile']));
    $category="boarding";
    $house = $obj->con->real_escape_string(htmlentities($_POST['house']));
    $kcpe=$obj->con->real_escape_string(htmlspecialchars($_POST['kcpe']));


    //form validation
    if(empty($names) || empty($admission))
    {
        $error="Please fill in all fields";
    }
    else if(!$obj->validate_string($names))
    {
        $error="Only characters and white string allowed in name fields";
    }
    // else if(!empty($fname) && (!$obj->validate_string($fname)))
    // {
    //     $error="Only characters and whitespace allowed in fathers names";
    // }
    // else if(!empty($mname) && (!$obj->validate_string($mname)))
    // {
    //     $error="Only characters and whitespace allowed in mothers names";
    // }
    else if($obj->validate_string($class) || $class=="Select class")
    {
        $error="Please select valid class";

    }
    else if(!is_numeric($admission))
    {
        $error="Admission must be number only";
    }
    else if(!is_numeric($kcpe))
    {
        $error="KCPE marks must be number only";
    }
    else if($house == "Select House")
    {
        $error="Please select house";
    }

    else
    {
        //if form validated
        $password=md5($admission);


        $check_if_exist=array("AdmissionNumber"=>$admission);

        //checking if student already exists
        if($obj->fetch_records("student",$check_if_exist))
        {
            $error="Student with this admission number exists";
        }
        else
        {

            //querying school fees table for billing
            $sql="SELECT amount FROM fee_structure WHERE form='$class[0]' AND category='$category' ORDER BY id DESC LIMIT 1";
            $execute=mysqli_query($obj->con,$sql);
            if(mysqli_num_rows($execute)>0)
            {
                while($fetch_fee=mysqli_fetch_assoc($execute))
                {
                    $fee_balance=$fetch_fee['amount'];

                }
            }
            else
            {
                $fee_balance=0;
            }

            //initialize all student data to be saved


            $data=array(

                "AdmissionNumber"=>$admission,
                "names"=>$names,
                "house"=>$house,
                "class"=>$class,
                "stream"=>substr($class, 1),
                "form"=>$class[0],
                "category"=>$category,
                "account"=>"active",
                "password"=>$password,
                "mobile"=>$mobile,
                "fee_owed"=>$fee_balance,
                "kcpe"=>$kcpe,
                "fee_paid"=>0

            );

            //dumping data to database
            if($obj->insert_record("student",$data))
            {
                $success="$names saved successfully";
                $names=$admission=$fname=$mname=$fmobile=$mmobile='';
            }
            else
            {
                $error="Could not save record";
            }

        }
    }
}

//trigger query if delete student form is submitted
if(isset($_POST['delete']))
{
    $key=$_POST['delete'];
    $where=array("AdmissionNumber"=>$key);

    if($obj->delete_record("student",$where))
    {
        $success="Account deleted successfully";
    }
    else
    {
        $error="Error in deleting account";
    }
}

//trigger query if update student form is submitted
if(isset($_POST['update']))
{
    $stu_names=$obj->con->real_escape_string(htmlspecialchars($_POST['names']));
    $stu_admission=$obj->con->real_escape_string(htmlspecialchars($_POST['admission']));
    $stu_class=$obj->con->real_escape_string(htmlspecialchars($_POST['class']));
    $category=$obj->con->real_escape_string(htmlspecialchars($_POST['category']));
    // $fname=$obj->con->real_escape_string(htmlspecialchars($_POST['fname']));
    // $mname=$obj->con->real_escape_string(htmlspecialchars($_POST['mname']));
    // $fmobile=$obj->con->real_escape_string(htmlentities($_POST['fmobile']));
    $mobile=$obj->con->real_escape_string(htmlentities($_POST['mobile']));


    //form validation
    if(empty($stu_names) || empty($stu_admission))
    {
        $error="Please fill in all fields";
    }
    else if($stu_class=="Select class")
    {
        $error="Please select valid class";

    }
    else if(!is_numeric($stu_admission))
    {
        $error="Admission must be number only";
    }
    else if(!$obj->validate_string($stu_names))
    {
        $error="Only letters allowed in student names (Please do not use apostrophe)";
    }
    else if(!empty($fname) && (!$obj->validate_string($fname)))
    {
        $error="Only characters and whitespace allowed in fathers names";
    }
    else if(!empty($mname) && (!$obj->validate_string($mname)))
    {
        $error="Only characters and whitespace allowed in mothers names";
    }
    else
    {
        //updating

        $update_where=array(
            "AdmissionNumber"=>$stu_admission
        );

        $update_data=array(

            "names"=>$stu_names,
            "class"=>$stu_class,
            "stream"=>substr($stu_class, 1),
            "form"=>$stu_class[0],
            "category"=>$category,
            // "fathersNames"=>$fname,
            // "mothersNames"=>$mname,
            // "fathersmobile"=>$fmobile,
            "mobile"=>$mobile

        );

        if($obj->update_record("student",$update_where,$update_data))
        {
            $success="Update successful";
        }
        else
        {

            $error="Error updating student details";

        }

    }

}


//activating or deactivating student accounts
if(isset($_POST['activate_account']))
{

    $adm = $_POST['activate_account'];
    $where = array("AdmissionNumber"=>$adm);


    $account_status = $obj->fetch_records("student",$where);

    foreach($account_status as $row)
    {
        $userAccount = $row['account'];
    }



    if($userAccount == "active")
    {
        $data = array("account" => "inactive");

        if($obj->update_record("student",$where,$data))
        {
            $success="Student account deactivated";
        }
    }
    else if($userAccount == "inactive")
    {
        $data = array("account" => "active");

        if($obj->update_record("student",$where,$data))
        {
            $success="Student account activated";
        }
    }
}


//for promoting students

if(isset($_POST['promote']))
{

    $sql = "UPDATE student SET form=form+1 WHERE form<5";
    $execute = mysqli_query($obj->con,$sql);

    if($execute)
    {

        $sql1="UPDATE student SET class=concat(form,stream) WHERE form<5";
        $exe = mysqli_query($obj->con,$sql1);

        if($exe)
        {

            //saving graduates to graduates table
            $where = array('form'=>5);
            $get_graduates = $obj->fetch_records("student",$where);

            if($get_graduates)
            {

                foreach($get_graduates as $row)
                {
                    $names = $row['names'];
                    $admission_number = $row['AdmissionNumber'];
                    $feepaid = $row['fee_owed']-$row['fee_paid'];

                    $data = array(
                        'names'=>$names,
                        'admission'=>$admission_number,
                        'fees'=>$feepaid
                    );

                    //after saving to graduates table, delete from students table
                    if($obj->insert_record("graduates",$data))
                    {

                        if($obj->delete_record("student",$where))
                        {

                            $success= "Students promoted successfully. Those in form 4 have now been transferred to graduates table with their fee details";

                        }
                        else
                        {
                            $error = mysqli_error($obj->con);
                        }

                    }
                    else
                    {
                        $error = mysqli_error($obj->con);
                    }
                }
            }

            //incase there was no form 4 student
            else
            {
                $success="Students promoted successfully";
            }



        }
        else
        {
            $error = mysqli_error($obj->con);
        }

    }
    else
    {
        $error = mysqli_error($obj->con);
    }



}
