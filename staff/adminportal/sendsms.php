<?php
session_start();

if(!$_SESSION['admin_login'])
{

    header('location:../index.php');

}
else
{
  include 'sending.php';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Sending sms</title>


		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php' ?>
</head>
<body>
<?php include'plugins/navigation.php';?>
<div id="page-wrapper">
  <div id="page-inner">
    <?php include 'plugins/secondarynav.php';?>
      <div class="row">

      <div class="panel panel-default">
	  
	  <div class="panel-heading">Send sms to parents/gurdians</div>
	  <div class="panel-body">
        <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>">
        	<div class="col-md-6 col-md-offset-2">

            <div class="form-group">
                <input type="text" name="to" class="form-control"  placeholder=" Receiver e.g. form 4 parents" name="sms" required/>
               </div>

              <div class="form-group">
                <textarea class="form-control" rows="12" placeholder="Type the sms here" name="sms" required></textarea>
               </div>

               <div class="form-group">
                    <button class="btn" style="background-color: #4d4dff;" name="send" type="submit" >Send sms</button>
                 </div>
        	</div>

        </form>

	  </div>
	</div>

  </div>
</div>
</div>

<?php include 'plugins/scripts.php'; ?>
</body>
</html>