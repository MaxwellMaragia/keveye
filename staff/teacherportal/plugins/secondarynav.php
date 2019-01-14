
<nav class="navbar navbar-inverse">
    <div class="container-fluid">

        <ul class="nav navbar-nav navbar-right">

            <li class="dropdown">
       
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php

                    $where_teacher = array('username'=>$_SESSION['account']);
                    $fetch_teacher = $obj->fetch_records('staff_accounts',$where_teacher);
                    foreach($fetch_teacher as $row)
                    {
                        echo $row['names'];
                    }

                    ?>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="changepassword.php"><i class="fa fa-user fa-fw"></i>Change password</a>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>