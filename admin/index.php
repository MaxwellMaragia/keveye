<?php
session_start();

if(!$_SESSION['username'])
{

    header('location:login.php');

}
else
{
	include 'functions/actions.php';
	$obj = new DataOperations();

	$sql = "SELECT * FROM messages WHERE status=1 ORDER BY id DESC LIMIT 10";
	$res = mysqli_query($obj->con,$sql);
	
	$sql2 = "SELECT * FROM messages WHERE status=0 ORDER BY id DESC";
	$result = mysqli_query($obj->con,$sql2);
	$count = mysqli_num_rows($result);

	if(isset($_POST['update']))
	{
     $where = array("status"=>0);
     $data = array("status"=>1);
     $read = $obj->update_record("messages",$where,$data);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>ADMIN PANEL</title>


		<!-- CSS -->        
        
	 <?php include 'plugins/styles.php' ?>
</head>
<body>
<?php include'plugins/navigation.php';?>
<div id="page-wrapper">
  <div id="page-inner">
      <div class="row">
        <?php
           if(isset($_POST['delete']))
           {
            $confirm_id = $_POST['delete'];
            $where = array("id"=>$confirm_id);
            $delete_sms = $obj->delete_record("messages",$where);
            if($delete_sms)
            {
              echo "<div style='color:green; float:center;'>Deleted successfully!</div>";
            }
           }

           ?>
     <?php
     if($count>0)
     {
     echo "You have ".$count." unread messages, click <form action='index.php' method='post'>
     <button class='btn' type='submit' name='update'>Read</button>
      </form>
      ";
     }
     while($get_sms = mysqli_fetch_array($res))
     {
      $name = $get_sms['name'];
      $contact = $get_sms['contact'];
      $sub = $get_sms['subject'];
      $sms = $get_sms['message'];
      $id_no = $get_sms['id'];

      ?>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
           <small><strong><?php echo $sub;?></strong></small>
           <p><small style="color: grey; font-size: 14px;"><?php echo $sms;?></small></p>
           <em style="margin-left: 380px; font-size: 10px; color: grey;"><?php echo "By: ".$name." email: ".$contact;?>
           <a href="#clear<?php echo $id_no;?>" data-toggle="modal"><button type="button" title="delete this message" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></button> </a>
           </em>
           <hr >

         <div class="modal fade" id="clear<?php echo $id_no;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog modal-sm" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                    
                                                      <p style="color: red; font-size: 18px;">Confirm deleting this message!</p>
                                                    
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-sm"  data-dismiss="modal">Cancel</button>
                                                      <button name="delete" class="btn btn-sm" value="<?php echo $id_no;?>">Yes</button>
                                                  </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                  </div>

        </div>
      </div>
      <?php
     }

     ?>

   </div>
</div>
</div>


<?php include 'plugins/scripts.php'; ?>
</body>
</html>