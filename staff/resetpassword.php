<?php 
include 'reset.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KIPASI</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" href="assets/css/layout.css"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"/>
    <script type="text/javascript" src="assets/bootstrap/js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.js"></script>
</head>

<body>

<div class="container" style="margin-top: 100px;">
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
                <input type="password" class="form-control" placeholder="New Password" name="password" required="required">
                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_pass" required="required">
                
                <input type="hidden" name="fp_code" value="<?php echo $_REQUEST['fp_code']; ?>"/>
                <button class="btn btn-primary" name="update" type="submit">Submit</button>
            </form>

        </div>
    </div>
</div>
</body>
</html>