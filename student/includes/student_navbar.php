<?

?>

<header class="header d-flex flex-row" >
    <div class="header_content d-flex flex-row align-items-center">
        <!-- Logo -->
        <div class="logo_container">
            <div class="logo">
                <img src="../images/keveye_logo.png" alt="" style="width:50px ">

            </div>

        </div>
        <h3 style="color:#8C4200;">Keveye Girls School</h3>
        <!-- Main Navigation -->
        <nav class="main_nav_container">
            <div class="main_nav">
                <ul class="main_nav_list">
                    <li class="main_nav_item ">
<!--                    <li class="main_nav_item"><a href="dashboard.php">Home</a></li>-->
                       <li class="main_nav_item"><a href="student_result.php">Exam results</a></li>
<!--                    <li class="main_nav_item"><a href="fee.php">Fees</a></li>-->
                        <a class=" dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo "$names ( $admission ,$class )"; ?></a>
                        <div style="border-radius: unset" class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                            
                            <a class="dropdown-item blue-text" href="changepassword.php">Change password</a>
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
                    <a class=" dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo "$names ( $admission ,$class )"; ?>/a>
                    <div style="border-radius: unset" class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                       <a class="dropdown-item blue-text" href="changepassword.php">Change password</a>
                            <a class="dropdown-item blue-text" href="student_logout.php">Logout</a>

                    </div>
                </li>
            </ul>




        </div>

    </div>

</div>