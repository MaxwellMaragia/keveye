<?php include_once 'functions.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gallery</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/courses_styles.css">
    <link rel="stylesheet" type="text/css" href="styles/courses_responsive.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f1f1f1;
            padding: 20px;
            font-family: Arial;
        }

        /* Center website */
        .main {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            font-size: 50px;
            word-break: break-all;
        }

        .row {
            margin: 8px -16px;
        }

        /* Add padding BETWEEN each column (if you want) */
        .row,
        .row > .column {
            padding: 8px;
        }

        /* Create three equal columns that floats next to each other */
        .column {
            float: left;
            width: 33.33%;
            display: none; /* Hide columns by default */
        }

        /* Clear floats after rows */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Content */
        .content {
            background-color: white;
            padding: 10px;
        }

        /* The "show" class is added to the filtered elements */
        .show {
            display: block;
        }

        /* Style the buttons */
        .btn {
            border: none;
            outline: none;
            padding: 12px 16px;
            background-color: white;
            cursor: pointer;
        }

        /* Add a grey background color on mouse-over */
        .btn:hover {
            background-color: #ddd;
        }

        /* Add a dark background color to the active button */
        .btn.active {
            background-color: #666;
            color: white;
        }
    </style>
    <?php include_once 'includes/head.php'?>
</head>
<body>

<div class="super_container">

    <!-- Header -->
    <?php include_once 'includes/navbar.php'?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/course_6.jpg)"></div>
        </div>
        <div class="home_content">
            <h1>Gallery</h1>
        </div>
    </div>


    <!-- Popular -->

    <div class="popular page_section" style="padding-top: 80px">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title text-center">
                        <h1>Memorable moments</h1>
                    </div>
                </div>
            </div>

            <div class="row course_boxes">

<script>
    filterSelection("all") // Execute the function and show all columns
    function filterSelection(c) {
        var x, i;
        x = document.getElementsByClassName("column");
        if (c == "all") c = "";
        // Add the "show" class (display:block) to the filtered elements, and remove the "show" class from the elements that are not selected
        for (i = 0; i < x.length; i++) {
            w3RemoveClass(x[i], "show");
            if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
        }
    }

    // Show filtered elements
    function w3AddClass(element, name) {
        var i, arr1, arr2;
        arr1 = element.className.split(" ");
        arr2 = name.split(" ");
        for (i = 0; i < arr2.length; i++) {
            if (arr1.indexOf(arr2[i]) == -1) {
                element.className += " " + arr2[i];
            }
        }
    }

    // Hide elements that are not selected
    function w3RemoveClass(element, name) {
        var i, arr1, arr2;
        arr1 = element.className.split(" ");
        arr2 = name.split(" ");
        for (i = 0; i < arr2.length; i++) {
            while (arr1.indexOf(arr2[i]) > -1) {
                arr1.splice(arr1.indexOf(arr2[i]), 1);
            }
        }
        element.className = arr1.join(" ");
    }

    // Add active class to the current button (highlight it)
    var btnContainer = document.getElementById("myBtnContainer");
    var btns = btnContainer.getElementsByClassName("btn");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(){
            var current = document.getElementsByClassName("active");
            current[0].className = current[0].className.replace(" active", "");
            this.className += " active";
        });
    }
</script>


                <div id="myBtnContainer">

                    <button class="btn active" onclick="filterSelection('all')"> Show all</button>
                    <?php $cat=display('gallery_category','10') ?>
                    <?php while($disp_cat=mysqli_fetch_array($cat)): ?>
                        <?php $ct=$disp_cat['category'] ?>
                    <button class="btn" onclick="filterSelection('<?php echo $ct ?>')"><?php echo $disp_cat['category'] ?></button>
                    <?php endwhile;?>

                </div>


                <!-- Portfolio Gallery Grid -->
                <div class="row">
                    <?php $pics=display('gallery',3) ?>
                    <?php while($disp_pics=mysqli_fetch_array($pics)): ?>
                    <div class="column <?php echo $disp_pics['category'] ?>">
                        <div class="content">
                            <img src="<?php echo $disp_pics['image'] ?>" alt="Mountains" style="width:100%">
                            <h4><?php echo $disp_pics['category'] ?></h4>
                            <p></p>
                        </div>
                    </div>
                    <?php endwhile;?>


                    <!--<div class="column people">
                        <div class="content">
                            <img src="/w3images/people3.jpg" alt="People" style="width:100%">
                            <h4>Woman</h4>
                            <p>Lorem ipsum dolor..</p>
                        </div>
                    </div>-->
                    <!-- END GRID -->
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
<script src="plugins/easing/easing.js"></script>
<script src="js/courses_custom.js"></script>
<script src="js/elements_custom.js"></script>

</body>
</html>