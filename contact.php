<?php include_once 'functions.php'?>
<?php
if (isset($_POST['send'])){

    if(isset($_POST['name']) && isset($_POST['contact']) &&  isset($_POST['subject']) && isset($_POST['message'])){
        $name=$connect->real_escape_string(htmlentities($_POST['name']));
        $contact=$connect->real_escape_string(htmlentities($_POST['contact']));
        $subject=$connect->real_escape_string(htmlentities($_POST['subject']));
        $message=$connect->real_escape_string(htmlentities($_POST['message']));

        $error=array();

        if(empty($_POST['name']) || empty($_POST['contact']) || empty($_POST['subject'])  || empty($_POST['message'])){
            $error[]='<div class="alert alert-danger" role="alert"> Fill all fields!</div>';
        }



        if(!empty($error[0])){
            $no_success= $error[0];
        }else
        {
            //sent to email
            $to = "kennedykimweli@gmail.com";
            $subject = $subject;
            $mailContent = 'Dear '.$name.',
            <br/>'.$message.'.
            <br/><br/>Regards,
            <br/>'.$name;

            //set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            //additional headers
            $headers .= 'From: '.$contact. "\r\n";
            //send email
            $sentmail = mail($to,$subject,$mailContent,$headers);
            if($sentmail)
            {
            //insert into database
            $message_query="INSERT INTO messages (name,contact,subject,message) VALUES ('$name','$contact','$subject','$message') ";
            $run_message=mysqli_query($connect,$message_query);
            if($run_message){

                $success= '<div class="alert alert-primary" role="alert"> Message sent,we will send a feedback as fast as we can, thank you </div>';
            }
            }
            else
            {
                echo mysqli_error($connect);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/contact_styles.css">
    <link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">

</head>
<body>

<div class="super_container">

    <!-- Header -->

   <?php include_once 'includes/navbar.php'?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/news_2.jpg)"></div>
        </div>
        <div class="home_content">
            <h1>Contact</h1>
        </div>
    </div>

    <!-- Contact -->

    <div class="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">

                    <!-- Contact Form -->
                    <div class="contact_form">
                        <div class="contact_title">Get in touch</div>

                        <div class="contact_form_container">
                            <?php
                            if (isset($no_success)){
                                echo $no_success;
                            }elseif (isset($success)){
                                echo $success;
                            }
                            ?>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input id="contact_form_name" class="input_field contact_form_name" type="text" placeholder="Name"  data-error="Name is required." name="name">
                                <input id="contact_form_email" class="input_field contact_form_email" type="text" placeholder="email/phone number"  data-error="Valid email is required." name="contact">
                                <input id="contact_form_email" class="input_field contact_form_email" type="text" placeholder="subject"  data-error="Valid email is required." name="subject">
                                <textarea id="contact_form_message" class="text_field contact_form_message" name="message" placeholder="Message"  data-error="Please, write us a message."></textarea>
                                <button id="contact_send_btn" type="submit" class="contact_send_btn trans_200" value="Submit" name="send">send message</button>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">
                    <div class="about">


                        <div class="contact_info">
                            <ul>
                                <li class="contact_info_item">
                                    <div class="contact_info_icon">
                                        <span class="fa fa-location-arrow" style="color: #8B4513;"></span>
                                    </div>
                                    Keveye Girls High School
                                </li>
                                <li class="contact_info_item">
                                    <div class="contact_info_icon">
                                        <span class="fa fa-phone" style="color: #8B4513;"></span>
                                    </div>
                                    +254 712 599273
                                </li>
                                <li class="contact_info_item">
                                    <div class="contact_info_icon">
                                        <span class="fa fa-envelope" style="color: #8B4513;"></span>
                                    </div>keveye@provider.com
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Google Map -->

            <div class="row">
                <div class="col">
                    <div id="google_map">
                        <div class="map_container">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->

    <?php include_once 'includes/footer.php'?>

</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/scrollTo/jquery.scrollTo.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCIwF204lFZg1y4kPSIhKaHEXMLYxxuMhA"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/contact_custom.js"></script>

</body>
</html>