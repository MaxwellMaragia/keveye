<?php
if(isset($_SESSION['student'])){
    $adm=$_SESSION['student'];
    $username=display_specific('student','adm',$adm);
    $disp_username=mysqli_fetch_assoc($username);
    $field='name';
    $logout='student_logout.php';
}elseif(isset($_SESSION['parent'])){
    $adm=$_SESSION['parent'];
    $username=display_specific('student','adm',$adm);
    $disp_username=mysqli_fetch_assoc($username);
    $field='parent-name';
    $logout='parent_logout.php';
}elseif(isset($_SESSION['teacher'])){
    $adm=$_SESSION['teacher'];
    $username=display_specific('teacher','id',$adm);
    $disp_username=mysqli_fetch_assoc($username);
    $field='name';
    $logout='teacher_logout.php';
}

?>


<!-- Header -->

<header class="header d-flex flex-row" >
    <div class="header_content d-flex flex-row align-items-center">
        <!-- Logo -->
        <div class="logo_container">
            <div class="logo">
                <img src="images/Machakos-Boys-Logo.fw_.png" alt="" style="width:50px ">

            </div>

        </div>
        <h3 style="color:brown;">Keveye Girls School</h3>
        <!-- Main Navigation -->
        <nav class="main_nav_container">
            <div class="main_nav">
                <ul class="main_nav_list">
                    <li class="main_nav_item ">
                        <a class=" dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Logged in as: <?php echo $disp_username[$field]; ?></a>
                        <div style="border-radius: unset" class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item blue-text" href="student_logout.php">Logout</a>

                        </div>
                    </li>
                </ul>

            </div>
        </nav>
    </div>
    <div class="header_side d-flex flex-row justify-content-center align-items-center" style="background-color: white">
        <!--<span class="fa fa-phone" style="color:#4285f4;"></span>
        <span style="color:#4285f4;">+254 712 599 273</span>-->
    </div>

    <!-- Hamburger -->
    <div class="hamburger_container">
        <i class="fas fa-bars trans_200"></i>
    </div>

</header>

<!-- Menu -->
<div class="menu_container menu_mm">

    <!-- Menu Close Button -->
    <div class="menu_close_container">
        <div class="menu_close"></div>
    </div>

    <!-- Menu Items -->
    <div class="menu_inner menu_mm">
        <div class="menu menu_mm">
            <ul class="menu_list menu_mm">
                <li class="main_nav_item ">
                    <a class=" dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Logged in as: <?php echo $disp_username[$field]; ?></a>
                    <div style="border-radius: unset" class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item blue-text" href="student_logout.php">Logout</a>

                    </div>
                </li>
            </ul>




        </div>

    </div>

</div>