<?php 
include 'reset.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KEVEYESCHOOL</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" href="assets/css/layout.css"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"/>
    <script type="text/javascript" src="assets/bootstrap/js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.js"></script>
</head>

<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">KEVEYESCHOOL</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php">Home</a></li>
                

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


<div class="container">
    <div class="col-md-6 col-md-offset-3 login">
        <h3 style="text-align: center;">
            Password Recovery
        </h3>
        <hr>
        <div class="form-group">
            <?php
               if($success)
               {
                $obj->successDisplay($success);
               }elseif($error)
               {
                $obj->errorDisplay($error);
               }
               ?>
            
            <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>">
                <p>Enter your email address, and we shall send you a recovery link to your email</p>
                <input type="text" class="form-control" placeholder="Email Address" name="email" required="required">
                

                <button class="btn btn-primary" name="resetsubmit" type="submit">Submit</button>
            </form>

        </div>
    </div>
</div>
</body>
</html>