    <?php
    session_start();
    if(!$_SESSION['account'])
    {
        header('location:../index.php');
    }
    if(!$_SESSION['term'])
    {
        header('location:results.php');
    }
    require('functions/fpdf/fpdf.php');
    require('functions/actions.php');

    $obj=new DataOperations();
    $admission=$_SESSION['student-result'];
    $year_term=$_SESSION['term'];
    $opening_date = '';
    $closing_date = '';
    //$fees='';
    //$fee_overpayment='';
    $student=array("AdmissionNumber"=>$admission);

    //fetching student details
    $fetch_student=$obj->fetch_records("student",$student);

    foreach($fetch_student as $row)
    {
        $names=$row['names'];
        $class=$row['class'];
        $form=$row['form'];
        $category=$row['category'];
        $kcpe = $row['kcpe'];
        $gender = $row['gender'];
    }

    //get class during that period
    $where_class = array('period'=>$year_term,'admission'=>$admission);
    $get_class = $obj->fetch_records('final_result',$where_class);
    foreach($get_class as $row)
    {
        $result_class = $row['class'];
    }

    //get total students
    $sql_class="SELECT * FROM final_result WHERE class='$result_class' AND period='$year_term'";
    $sql_form="SELECT * FROM final_result WHERE form='$result_class[0]' AND period='$year_term'";

    $execute_class=mysqli_query($obj->con,$sql_class);
    $total_in_class=mysqli_num_rows($execute_class);
    $execute_form=mysqli_query($obj->con,$sql_form);
    $total_in_form=mysqli_num_rows($execute_form);

    //get class rank
    $query = "SELECT admission, average FROM final_result WHERE class='$result_class' AND period='$year_term' ORDER BY average DESC ";
    $exe = mysqli_query($obj->con,$query);

    $rank = 0;
    $student = array();
    while($res = mysqli_fetch_array($exe)){
        ++$rank;
        $student[$res['admission']] = $rank;

    }
    if($rank !== 0){
        $class_position=$student[$admission];
    }

    //get form rank
    $sql = "SELECT admission, average FROM final_result WHERE form='$result_class[0]' AND period='$year_term' ORDER BY average DESC ";
    $execute = mysqli_query($obj->con,$sql);

    $form_rank = 0;
    $student = array();
    while($res = mysqli_fetch_array($execute)){
        ++$form_rank;
        $student[$res['admission']] = $form_rank;
    }
    if($form_rank !== 0){
        $form_position=$student[$admission];
    }


    //fetch totals
    $where=array("period"=>$_SESSION['term'],"admission"=>$admission);
    $fetch_total=$obj->fetch_records("final_result",$where);
    foreach($fetch_total as $row)
    {
        $totalmarks=$row['total'];
        $average=$row['average'];
        $overall_grade=$row['grade'];
        $count=$row['count'];
        $points = $row['points'];
    }



    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();

    //set font to arial,bold,14pt
    $pdf->SetFont('Arial','B','12');

    //cella(width,height,text,border,endline,[align])
    //LOGO
    $pdf->Image("functions/fpdf/keveye.png",8,2,30,30);

    //headers


    $pdf->SetX(37);
    $pdf->Cell(130  ,1,'FRIENDS SCHOOL KEVEYE GIRLS',0,1);
    $pdf->SetFont('Arial','i','9');
    $pdf->SetX(37);
    $pdf->Cell(130  ,10,'P.o Box 865 MARAGOLI.',0,1);
    $pdf->SetY(20);
    $pdf->SetX(37);
    $pdf->Cell(15,2,'keveyegirls@yahoo.com.',0,1);
    $pdf->SetX(82);
    $pdf->Cell(130,-11,'Tel:  0707174835',0,1);
    $pdf->SetFont('Arial','b','9');
    $pdf->SetX(37);
    $pdf->Cell(130 ,32,'DETERMINED TO EXCEL',0,1);
    $pdf->SetY(28);
    $pdf->SetX(70);
    $pdf->SetFont('Arial','b','11');
    $pdf->Cell(130 ,16,'TERM '.$year_term[0].' ACADEMIC REPORT '.substr($year_term,0,4),0,1);


    $pdf->SetFont('Arial','b','8');
    $pdf->Rect(10,42,192,6,'D');
    $pdf->Cell(15  ,2,'NAMES:',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,$names,0,0);
    $pdf->SetFont('Arial','b','8');
    $pdf->SetX(65);
    $pdf->Cell(15  ,2,'ADM.NO:',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,$admission,0,0);
    $pdf->SetFont('Arial','b','8');
    $pdf->SetX(105);
    $pdf->Cell(15  ,2,'FORM:',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,$class,0,0);
    $pdf->SetX(150);
    $pdf->SetFont('Arial','b','9');
    $pdf->Cell(15  ,2,'HOUSE:',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,'elgon',0,0);


      if($fetch_total)
      {

          //get min subjects
          $sql="SELECT * FROM minimum_subjects WHERE form=$form";
          $execute=mysqli_query($obj->con,$sql);

          if(mysqli_num_rows($execute)>0)
          {
              $get_min=mysqli_fetch_assoc($execute);
              $min=$get_min['minimum'];

          }

          //calculate points
          $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND period='$year_term'";

          $exe = mysqli_query($obj->con,$sql);

          while($get_total = mysqli_fetch_assoc($exe))
          {
              $tpoints = $get_total['SUM(points)'];
              $mpoints = round($tpoints/$min);
          }

          $pdf->Rect(10,48,192,6,'D');
          $pdf->SetFont('Arial','b','8');
          $pdf->SetY(50);
          $pdf->Cell(15  ,2,'FORM POS:',0,0);
          $pdf->SetFont('Arial','','9');
          $pdf->SetX(28);
          $pdf->Cell(15  ,2,$form_position,0,0);
          $pdf->SetX(50);
          $pdf->SetFont('Arial','b','8');
          $pdf->Cell(15  ,2,'OUT OF:       ',0,0);
          $pdf->SetFont('Arial','','9');

          $pdf->Cell(20  ,2,$total_in_form,0,0);
          $pdf->SetX(100);
          $pdf->SetFont('Arial','b','8');
          $pdf->Cell(15  ,2,'CLASS POS:',0,0);
          $pdf->SetFont('Arial','','9');
          $pdf->SetX(120);
          $pdf->Cell(15  ,2,$class_position,0,0);
          $pdf->SetX(150);
          $pdf->SetFont('Arial','b','8');
          $pdf->Cell(15  ,2,'OUT OF:   ',0,0);
          $pdf->SetFont('Arial','','9');
          $pdf->Cell(15  ,2,$total_in_class,0,0);


          $pdf->Rect(10,54,192,6,'D');
          $pdf->SetFont('Arial','b','8');
          $pdf->SetY(56);
          $pdf->Cell(15  ,2,'T.MARKS:     ',0,0);
          $pdf->SetFont('Arial','','9');
          $pdf->Cell(15  ,2,$totalmarks,0,0);
          $pdf->SetX(50);
          $pdf->SetFont('Arial','b','8');
          $pdf->Cell(15  ,2,'M.MARKS:       ',0,0);
          $pdf->SetFont('Arial','','9');
          $pdf->Cell(20  ,2,$average,0,0);
          $pdf->SetX(100);
          $pdf->SetFont('Arial','b','8');
          $pdf->Cell(15  ,2,'T.POINTS:   ',0,0);
          $pdf->SetFont('Arial','','9');
          $pdf->Cell(15  ,2,$tpoints,0,0);
          $pdf->SetX(150);
          $pdf->SetFont('Arial','b','8');
          $pdf->Cell(15  ,2,'M.POINTS:   ',0,0);
          $pdf->SetFont('Arial','','9');
          $pdf->Cell(15  ,2,$mpoints,0,0);


          $pdf->SetFont('Arial','b','7');
          $pdf->Cell(5,8,'',0,1);
          $pdf->Cell(40  ,6,'SUBJECT',1,0);
          $pdf->Cell(17  ,6,'CYCLE 1 %',1,0);
          $pdf->Cell(17  ,6,'CYCLE 2 %',1,0);
          $pdf->Cell(17  ,6,'AV %',1,0);
          $pdf->Cell(17  ,6,'GRADE',1,0);
          $pdf->Cell(17  ,6,'POINTS',1,0);
          $pdf->Cell(50  ,6,'REMARKS',1,0);
          $pdf->Cell(17  ,6,'INITIALS',1,1);

          $get_subjects=$obj->fetch_all_records("subject");

          foreach($get_subjects as $row) {
              $subject = $row['SubjectName'];
              $where = array("admission" => $admission, "period" => $year_term, "subject" => $row['SubjectName']);
              $fetch_results = $obj->fetch_records("results", $where);
              $cat='';
              $end='';
              $total='';
              $grade='';
              $points='';
              $remarks='';
              $initials='';

              foreach ($fetch_results as $row) {
                  $cat = $row['cat'];
                  $end=$row['mid'];
                  $total=$row['total'];
                  $grade=$row['grade'];
                  $points=$row['points'];
                  $remarks=$row['remarks'];
                  $initials=$row['initials'];

              }
              $pdf->SetFont('Arial', '', '9');
              $pdf->Cell(40, 5, $subject, 1, 0);
              $pdf->Cell(17, 5, $cat, 1, 0);
              $pdf->Cell(17, 5, $end, 1, 0);
              $pdf->Cell(17, 5, $total, 1, 0);
              $pdf->Cell(17, 5, $grade, 1, 0);
              $pdf->Cell(17, 5, $points, 1, 0);
              $pdf->Cell(50, 5, $remarks, 1, 0);
              $pdf->Cell(17, 5, $initials, 1, 1);


          }
          $pdf->SetFont('Arial', 'b', '9');
          $pdf->Cell(40, 7, 'KCPE MARKS', 1, 0);
          $pdf->Cell(17, 7, '', 0, 0);
          $pdf->Cell(17, 7, '', 0, 0);
          $pdf->SetFont('Arial', '', '9');
          $pdf->Cell(17, 7, $totalmarks, 1, 0);
          $pdf->Cell(17, 7, $overall_grade, 1, 0);
          $pdf->Cell(17, 7, $tpoints, 1, 0);
          $pdf->Cell(40, 7, '', 0, 0);
          $pdf->Cell(17, 7, '', 0, 1);
          $pdf->SetFont('Arial', '', '9');
          $pdf->Cell(40, 7, $kcpe, 1, 0);

          $pdf->Cell(17, 7, '', 0, 0);
          $pdf->Cell(17, 7, '', 0, 0);
          $pdf->SetFont('Arial', 'b', '9');
          $pdf->Cell(17, 7, $min*100, 1, 0);
          $pdf->Cell(17, 7, 'A', 1, 0);
          $pdf->Cell(17, 7, $min*12, 1, 1);
          $pdf->Cell(17, 7,'', 0, 1);

          $f1t1m = '-';
          $f1t2m = '-';
          $f1t3m = '-';
          $f1t1g = '-';
          $f1t2g = '-';
          $f1t3g = '-';
          $f2t1m = '-';
          $f2t2m = '-';
          $f2t3m = '-';
          $f2t1g = '-';
          $f2t2g = '-';
          $f2t3g = '-';
          $f3t1m = '-';
          $f3t2m = '-';
          $f3t3m = '-';
          $f3t1g = '-';
          $f3t2g = '-';
          $f3t3g = '-';
          $f4t1m = '-';
          $f4t2m = '-';
          $f4t3m = '-';
          $f4t1g = '-';
          $f4t2g = '-';
          $f4t3g = '-';

          //form 1 term 1 results
          $where = array('form'=>1,'admission'=>$admission,'term'=>1);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f1t1m = $row['average'];
              $f1t1g = $row['grade'];
          }

          //form 1 term 2 results
          $where = array('form'=>1,'admission'=>$admission,'term'=>2);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f1t2m = $row['average'];
              $f1t2g = $row['grade'];
          }

          //form 1 term 3 results
          $where = array('form'=>1,'admission'=>$admission,'term'=>3);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f1t3m = $row['average'];
              $f1t3g = $row['grade'];
          }

          //form 2 term 1 results
          $where = array('form'=>2,'admission'=>$admission,'term'=>1);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f2t1m = $row['average'];
              $f2t1g = $row['grade'];
          }

          //form 2 term 2 results
          $where = array('form'=>2,'admission'=>$admission,'term'=>2);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f2t2m = $row['average'];
              $f2t2g = $row['grade'];
          }

          //form 2 term 3 results
          $where = array('form'=>2,'admission'=>$admission,'term'=>3);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f2t3m = $row['average'];
              $f2t3g = $row['grade'];
          }

          //form 3 term 1 results
          $where = array('form'=>3,'admission'=>$admission,'term'=>1);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f3t1m = $row['average'];
              $f3t1g = $row['grade'];
          }

          //form 3 term 2 results
          $where = array('form'=>3,'admission'=>$admission,'term'=>2);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f3t2m = $row['average'];
              $f3t2g = $row['grade'];
          }

          //form 3 term 3 results
          $where = array('form'=>3,'admission'=>$admission,'term'=>3);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f3t3m = $row['average'];
              $f3t3g = $row['grade'];
          }

          //form 4 term 1 results
          $where = array('form'=>4,'admission'=>$admission,'term'=>1);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f4t1m = $row['average'];
              $f4t1g = $row['grade'];
          }

          //form 4 term 2 results
          $where = array('form'=>4,'admission'=>$admission,'term'=>2);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f4t2m = $row['average'];
              $f4t2g = $row['grade'];
          }

          //form 4 term 3 results
          $where = array('form'=>4,'admission'=>$admission,'term'=>3);
          $fetch = $obj->fetch_records('final_result',$where);
          foreach($fetch as $row)
          {
              $f4t3m = $row['average'];
              $f4t3g = $row['grade'];
          }




          $pdf->Cell(48,6,'Form 1',1,0);
          $pdf->Cell(48,6,'Form 2',1,0);
          $pdf->Cell(48,6,'Form 3',1,0);
          $pdf->Cell(48,6,'Form 4',1,1);
          $pdf->SetFont('Arial','','8');
          $pdf->Cell(10,6,'Term',1,0);
          $pdf->Cell(20,6,'M.MARKS',1,0);
          $pdf->Cell(18,6,'M.GRADE',1,0);
          $pdf->Cell(10,6,'Term',1,0);
          $pdf->Cell(20,6,'M.MARKS',1,0);
          $pdf->Cell(18,6,'M.GRADE',1,0);
          $pdf->Cell(10,6,'Term',1,0);
          $pdf->Cell(20,6,'M.MARKS',1,0);
          $pdf->Cell(18,6,'M.GRADE',1,0);
          $pdf->Cell(10,6,'Term',1,0);
          $pdf->Cell(20,6,'M.MARKS',1,0);
          $pdf->Cell(18,6,'M.GRADE',1,1);
          $pdf->Cell(10,6,'1',1,0);
          $pdf->Cell(20,6,$f1t1m,1,0);
          $pdf->Cell(18,6,$f1t1g,1,0);
          $pdf->Cell(10,6,'1',1,0);
          $pdf->Cell(20,6,$f2t1m,1,0);
          $pdf->Cell(18,6,$f2t1g,1,0);
          $pdf->Cell(10,6,'1',1,0);
          $pdf->Cell(20,6,$f3t1m,1,0);
          $pdf->Cell(18,6,$f3t1g,1,0);
          $pdf->Cell(10,6,'1',1,0);
          $pdf->Cell(20,6,$f4t1m,1,0);
          $pdf->Cell(18,6,$f4t1g,1,1);
          $pdf->Cell(10,6,'2',1,0);
          $pdf->Cell(20,6,$f1t2m,1,0);
          $pdf->Cell(18,6,$f1t2g,1,0);
          $pdf->Cell(10,6,'2',1,0);
          $pdf->Cell(20,6,$f2t2m,1,0);
          $pdf->Cell(18,6,$f2t2g,1,0);
          $pdf->Cell(10,6,'2',1,0);
          $pdf->Cell(20,6,$f3t2m,1,0);
          $pdf->Cell(18,6,$f3t2g,1,0);
          $pdf->Cell(10,6,'2',1,0);
          $pdf->Cell(20,6,$f4t2m,1,0);
          $pdf->Cell(18,6,$f4t2g,1,1);
          $pdf->Cell(10,6,'3',1,0);
          $pdf->Cell(20,6,$f1t3m,1,0);
          $pdf->Cell(18,6,$f1t3g,1,0);
          $pdf->Cell(10,6,'3',1,0);
          $pdf->Cell(20,6,$f2t3m,1,0);
          $pdf->Cell(18,6,$f2t3g,1,0);
          $pdf->Cell(10,6,'3',1,0);
          $pdf->Cell(20,6,$f3t3m,1,0);
          $pdf->Cell(18,6,$f3t3g,1,0);
          $pdf->Cell(10,6,'3',1,0);
          $pdf->Cell(20,6,$f4t3m,1,0);
          $pdf->Cell(18,6,$f4t3g,1,1);

          $pdf->Cell(18,6,'',0,1);


          //calculate fees
          //get term
          $where=array("state"=>"In progress");
          $get_term=$obj->fetch_records("terms",$where);

          foreach($get_term as $row)
          {
              $term=$row['term'];
              $opening_date = $row['opening_date'];
              $closing_date = $row['closing_date'];
          }

          //get fees
          $where=array("term"=>$term,"category"=>$category,"form"=>$form);
          $get_fees=$obj->fetch_records("fee_structure",$where);

          if($get_fees)
          {
              foreach($get_fees as $row)
              {
                  $fees=$row['amount'];

              }
          }
          else
          {
              $fees="Not set";
          }

          //get fee balance
          $where=array("AdmissionNumber"=>$admission);
          $get_balance=$obj->fetch_records("student",$where);
          $arreas = 0;
          $fee_overpayment = 0;


          foreach($get_balance as $row)
          {
              $fee_paid=$row['fee_paid'];
              $fee_owed=$row['fee_owed'];


              if($fee_paid>=$fee_owed)
              {
                  $fee_balance=0;
                  $fee_overpayment=$fee_paid-$fee_owed;
                  $total_balance = 0;


              }
              else if($fee_paid<$fee_owed)
              {
                  $fee_overpayment=0;
                  $total_balance=$fee_owed-$fee_paid;

                  if($get_fees)
                  {
                      $arreas = $total_balance - $fees;
                  }
                  else{
                      $arreas = $total_balance;
                  }

                  if($arreas<0){
                      $arreas = $arreas*-1;
                      $arreas = "($arreas)";
                  }


              }
          }




          $pdf->Cell(39,6,'Fees arrears Ksh',1,0);
          $pdf->Cell(39,6,$arreas,1,1);
          $pdf->Cell(39,6,'Next term fees Ksh',1,0);
          $pdf->Cell(39,6,$fees,1,1);
          $pdf->Cell(39,6,'Total Ksh',1,0);
          $pdf->Cell(39,6,$total_balance,1,1);
          $pdf->Cell(39,6,'',0,1);
          $pdf->SetFont('Arial','b','9');
          $pdf->Cell(39,5,'Class teacher remarks',0,0);
          $pdf->Cell(60,10,'',0,0);
          $pdf->Cell(39,5,"Principal's remarks",0,1);
          $pdf->Cell(93,40,'',1,0);
          $pdf->Cell(6,10,'',0,0);
          $pdf->Cell(93,40,'',1,1);
          $pdf->Cell(20,6,'',0,1);
          $pdf->Cell(28,5,'School closed on',0,0);
          $pdf->SetFont('Arial','i','9');
          $pdf->Cell(20,5,$closing_date,0,0);
          $pdf->SetFont('Arial','b','9');
          $pdf->Cell(26,5,'',0,0);
          $pdf->Cell(37,5,'Next term begins on',0,0);
          $pdf->SetFont('Arial','i','9');
          $pdf->Cell(28,5,$opening_date,0,1);
          $pdf->SetFont('Arial','','9');
          $pdf->Cell(26,5,'',0,1);
          $pdf->Cell(19,5,"Parent's sign",0,0);
          $pdf->Cell(39,5,'...........................................',0,0);
          $pdf->Cell(8,5,'Date',0,0);
          $pdf->Cell(15,5,'...........................................',0,0);





      }

    $pdf->Output();
    ?>