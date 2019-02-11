<?php

   include 'functions/actions.php';
   $obj = new DataOperations();

       $sql = "SELECT sum(points),sum(total) FROM results WHERE admission='314' AND period = '2018 term 1'";
       $exe = mysqli_query($obj->con,$sql);
       $get_points = mysqli_fetch_array($exe);
       $points = $get_points['sum(points)'];
       $total = $get_points['sum(total)'];



       //biology
       $sql = "SELECT points,total FROM results WHERE subject='Biology' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_bio = mysqli_fetch_array($exe);
       $biology = $get_bio['points'];
       $bio = $get_bio['total'];

       //physics
       $sql = "SELECT points,total FROM results WHERE subject='Physics' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_phy = mysqli_fetch_array($exe);
       $physics = $get_phy['points'];
       $phy = $get_phy['total'];

       //chemistry
       $sql = "SELECT points,total FROM results WHERE subject='Chemistry' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_chem = mysqli_fetch_array($exe);
       $chemistry = $get_chem['points'];
       $chem = $get_chem['total'];


       //agriculture
       $sql = "SELECT points,total FROM results WHERE subject='Agriculture' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_agri = mysqli_fetch_array($exe);
       $agriculture = $get_agri['points'];
       $agri = $get_agri['total'];

       //business
       $sql = "SELECT points,total FROM results WHERE subject='Business' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_bus = mysqli_fetch_array($exe);
       $business = $get_bus['points'];
       $bus = $get_bus['total'];

       //geography
       $sql = "SELECT points,total FROM results WHERE subject='Geography' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_geo = mysqli_fetch_array($exe);
       $geography = $get_geo['points'];
       $geo = $get_geo['total'];

       //history
       $sql = "SELECT points,total FROM results WHERE subject='History' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_hist = mysqli_fetch_array($exe);
       $history = $get_hist['points'];
       $his = $get_hist['total'];

       //cre
       $sql = "SELECT points,total FROM results WHERE subject='CRE' AND admission='314' AND period='2018 term 1'";
       $exe = mysqli_query($obj->con, $sql);
       $get_cre = mysqli_fetch_array($exe);
       $cre = $get_cre['points'];
       $cr = $get_cre['total'];

       if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
       {
             if($get_bus)
             {
                 $technical = $business;
                 $tech = $bus;
             }
             if($get_agri)
             {
                 $technical = $agriculture;
                 $tech = $agri;
             }

             $lowest_science = min($chemistry,$physics,$biology,$technical);
             $lowest_science_mark = min($chem,$phy,$bio,$tech);
             $points = $points - $lowest_science;
             $total = $total - $lowest_science_mark;

       }
       if($get_cre || $get_geo || $get_hist)
       {
             $points = $points - $history - $cre - $geography;
             $total = $total - $his - $cr - $geo;
             $points = $points + max($history,$cre,$geography);
             $total = $total + max($his,$cr,$geo);

       }
echo $total;