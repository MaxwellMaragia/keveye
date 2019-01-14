<?php

$connect=mysqli_connect('localhost','root','','keveye');

function display($t_name,$limit){
    global $connect;
    $query="SELECT * FROM $t_name ORDER BY id ASC LIMIT $limit";
    $run_query=mysqli_query($connect,$query);
    return $run_query;
}

function display_specific($t_name,$column,$condition_variable){
    global $connect;
    $query="SELECT * FROM $t_name WHERE $column='$condition_variable' ";
    $run_query=mysqli_query($connect,$query);
    return $run_query;
}

function table_data(){
    global $connect;
    global $disp_username;
    $admission=$disp_username['adm'];

    $t_query="SELECT student.adm,results.term,results.rank,results.maths,results.eng,results.swa,results.marks FROM student INNER JOIN results ON student.adm=results.adm WHERE student.adm='$admission' ORDER BY results.id DESC";
    $run_t_query=mysqli_query($connect,$t_query);

}

?>