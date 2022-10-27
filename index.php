<?php
    session_start();
    require 'includes/connect.php';
    require 'includes/login.php';
    require 'includes/logout.php';

    if(isset($_POST['submit'])){
        $username = stripslashes($_POST['username']);
        $email = stripslashes($_POST['email']);
        $password = stripslashes($_POST['password']);


        $username =mysqli_real_escape_string($conn,  $username) ;
        $email =mysqli_real_escape_string($conn, $email);
        $password  =mysqli_real_escape_string($conn, $password);

        $select = mysqli_query($conn, "SELECT * from `naturalisregistration` WHERE email= '$email' AND username='$username';") or die('Check Data or Connection');

        if(mysqli_num_rows($select)>0){
            $message[] = 'Already exists';
            header("Location: index.php?error=Username+email+already+exist");
            exit();
           
        }
        else{

            if(empty($username) || empty($email) || empty($password)){
                echo '<script> alert(Registration Form not yet filled or completed); </script>';
                $message[] = 'Empty Field Unfilled';
                header("Location: ./index.php?user=username or password not filled");
                exit();
                
            }

            else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $message[] = 'Error with Username';
                header ("Location: ./index.php?email_error=not_validated" . $email);
                exit();
                
            }
            else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
                $message[] = 'Wrong Symbol used';
                header("Location: index.php?username_error=Pls check your input". $username);
                exit();
                
                
            }
            else{

                

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $submit = mysqli_query($conn, "INSERT INTO `naturalisregistration`(username, email, pass_word) VALUES('$username', '$email', '$hashedPassword')") or die('Unable to register User');

                if($submit){
                    $user_id = $_SESSION['id'];
                    $set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		            $code=substr(str_shuffle($set), 0, 12);

                    $from = 'support@naturalisbeau.com';
                    $to = $email;
                    $subject = 'Contact Message: '. $username;  
                    $msg = "
                            <html>
                            <head>
                            <title>Verification Code</title>
                            </head>
                            <body>
                            <h2>Thank you for Registering.</h2>
                            <p>Your Account:</p>
                            <p>Email: ".$email."</p>
                            <p>Password: ".$_POST['password']."</p>
                            <p>Please click the link below to activate your account.</p>
                            <h4><a href='http://localhost/send_mail/code_less.php?uid=$user_id&code=$code'>Activate My Account</h4>
                            </body>
                            </html>
                        ";

                    $header = "MIME-Version: 1.0" . "/r/n";
                    $header .= "Content-Type: text/html; Charset:UTF-8 " . "/r/n";
                    $header .= "From:" . $username . "<" . $email . ">" . "/r/n";

                    if(mail($from, $to, $subject, $msg, $header)){
                        $message[] = 'Email Confirmation message sent, Pls check your inbox';

                    }
                    else{
                        $message[] = 'Email not successfully sent';
                    }

                    echo '<script> alert(User Register Successful...); </script>';
                    header('Location:index.php');
                    exit();
                   
                }

            }
            
        }
    }

    if(isset($_SESSION['username'])){
        echo 'you have logged in';
    }

    
?>

<?php
    require 'includes/connect.php';
    require 'includes/login.php';
    require 'includes/logout.php';
   
    if(isset($_SESSION['id'])){
        $user_id = $_SESSION['id'];

        if($user_id){
            header('Location:index.php');
            exit();
        }

    }

    

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;1,600&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Combo&family=Poppins:ital,wght@0,400;0,500;1,600&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/style.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/font_awesome.css'>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-secondary bg-primary menu">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/logoindex.png" alt="logo" width="50" height="50" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarScroll">
                <ul class="navbar-nav ">
                    <li class="nav-item">

                        <a class="nav-link active" aria-current="page" href="#"><img
                                src="images/logoindex.png" />Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="images/logoindex.png" />Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="images/logoindex.png" />Certification</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="images/logoindex.png" />Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#"><img src="images/logoindex.png" />Our
                            Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="images/logoindex.png" />Chat With Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="login" name="login"><img src="images/logoindex.png" />Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="register"><img src="images/logoindex.png" />Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logout" style="display:none"><img src="images/logoindex.png" />logout</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <section class="intro">
        <div class="container">
            <div class="row">
                <div class="video-for">
                    <video autoplay muted loop id="myVid">
                        <source src="videos/back1.mp4" type="video/mp4">

                    </video>

                </div>

                <div class="col-lg-12 intro-check">
                    <div class="col-lg-6 col-sm-8 col-md-6  text-in">
                        <p class="lead">
                            <img src="images/naturalis.png" width="150" height="150" alt="Naturalis" />
                        </p>
                    </div>
                    <div class="col-lg-6 col-sm-4 col-md-6  pic-in" id="userRegister" style="visibility:visible;">
                        <p id="welcome-text" style="color: whitesmoke; font-size: 1.6rem; font-weight: 600; margin-left: 31rem; text-transform: capitalize">
                            <?php
                                if(isset($_SESSION['username'])){
                                    $getUser = mysqli_query($conn, "SELECT * FROM naturalisregistration WHERE username = '$username';");
                                    $user = mysqli_num_rows($getUser);
                                    if($user==1){
                                        $fetchUser = mysqli_fetch_assoc($getUser);
                                        echo "Welcome" . " " . $fetchUser['username'];
                                    }

                                }
                                

                            ?>
                    
                        </p>
                        <?php 
                            require 'includes/connect.php';
                            
                            if(isset($message)):

                                foreach($message as $messages){
                                    echo '<small div="danger" style="color:white; background-color: red; ">$messages</small>';

                                }

                            endif;
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <input type="text" class="form-control" id="exampleInputUser" aria-describedby="userHelp"
                                placeholder="Username" name="username" required="required" >

                    

                            <input type="email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Email address" name="email" required='required'>

                            
                                

                            <input type="password" class="form-control" id="exampleInputPassword1"
                                placeholder="Password" name="password" required="required">
                                

                            <button type="submit" class="btn btn-secondary" id="submitButton" name="submit">Submit</button>


                        </form>

                    </div>
                    <div class="col-lg-6 col-sm-4 col-md-6  pic-in" id="userLogIn" style="position: relative; margin-left:-50%; visibility: hidden;">
                        <p id="welcome-text" style="color: whitesmoke; font-size: 1.6rem; font-weight: 600; margin-left: 31rem; text-transform: capitalize"></p>
                        <?php 
                            
                            if(isset($messageOne)):

                                foreach($messageOne as $message){
                                    echo "<small div='danger' style='color:white; background-color: red; '>$message</small>";

                                }

                            endif;
                        ?>
                        <form action="" method="post">
                            <input type="text" class="form-control" id="exampleInputUser" aria-describedby="userHelp"
                                placeholder="Username" name="username" required="required" >
                                
                            <input type="password" class="form-control" id="exampleInputPassword1"
                                placeholder="Password" name="password" required="required">
                                

                            <button type="submit" class="btn btn-secondary" id="submitButton" name="login-submit">Log In</button>


                        </form>

                    </div>
                </div>
    </section>
    <section class="infomatics">
        <div class="container">
            <div class="row-1 g-0">

                <div class="col-12 p-md-0 p-0 g-0 m-0 info-class">

                    <div class="col-12 col-lg-6 col-xl-6 col-sm-12 col-md-12 col-xxl-6 info-pic">
                        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-bs-interval="3000">
                                    <img src="images/natures/fall-1.jpg" width="749" class="img-fluid"
                                        alt="First slide">
                                </div>
                                <div class="carousel-item" data-bs-interval="3000">
                                    <img src="images/natures/fall-2.jpg" width="749" class="img-fluid"
                                        alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="images/natures/fall-3.png" width="749" class="img-fluid"
                                        alt="Third slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="images/natures/fall-4.jpg" width="749" class="img-fluid"
                                        alt="Third slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="images/natures/fall-5.jpg" width="749" class="img-fluid"
                                        alt="Third slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="images/natures/fall-6.jpg" width="749" class="img-fluid"
                                        alt="Third slide">
                                </div>
                            </div>


                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                    </div>
                    <div class="col-12 col-lg-6 col-xl-6 col-sm-12 col-md-12 col-xxl-6 info-message">

                        <div class="summary g-0">
                            <div>
                                <center>
                                    <blockquote>
                                        <p>
                                            A healthy environment is essential for all people and all 17 Sustainable
                                            Development Goals. .This is now new
                                            to our environment to change what it has. This is because of people role in
                                            the community and adversely it has affected us to the
                                            point of destruction.
                                        </p>



                                    </blockquote>

                                </center>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="#212529" fill-opacity="1"
                                    d="M0,160L34.3,160C68.6,160,137,160,206,186.7C274.3,213,343,267,411,282.7C480,299,549,277,617,256C685.7,235,754,213,823,186.7C891.4,160,960,128,1029,133.3C1097.1,139,1166,181,1234,176C1302.9,171,1371,117,1406,90.7L1440,64L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                                </path>
                            </svg>

                        </div>

                    </div>



                </div>


            </div>
        </div>

    </section>
    <section class="service">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-sm-12 col-md-12 col-xl-12 backyard">

                </div>
            </div>
            <div class="row-1">
                <div class="col-12 col-md-12 col-lg-6 col-sm-12 sec-1">
                    <p><a href="pages/Cloud.html">Cloud</a></p>
                    <p><a href="pages/mountain.html">Mountain</a></p>
                    <p><a href="pages/waterfalls.html">Waterfalls</a></p>
                    <p><a href="pages/valley.html">Valley</a></p>
                    <p><a href="pages/ocean.html">Ocean</a></p>
                    <p><a href="pages/sea.html">Sea</a></p>
                    <p><a href="pages/soil.html">Soil</a></p>
                    <p><a href="pages/trees.html">Trees</a></p>
                    <p><a href="pages/rainfall.html">Rainfall</a></p>
                    <p><a href="pages/snow.html">Snow</a></p>
                    <p><a href="pages/sleet.html">Sleet</a></p>
                    <p><a href="pages/hail.html">Hail</a></p>

                </div>
                <div class="col-12 col-md-12 col-lg-6 col-sm-12 sec-2">
                    <p><a href="pages/water.html">Water</a></p>
                    <p><a href="pages/birds.html">Birds</a></p>
                    <p><a href="pages/Reptiles.html">Reptiles</a></p>
                    <p><a href="pages/Widelife.html">Wildlife</a></p>
                    <p><a href="pages/domestical.html">Domestic Animals</a></p>
                    <p><a href="pages/flies.html">Flies & Insects</a></p>
                    <p><a href="pages/fisheries.html">Fisheries & Aquarium</a></p>
                    <p><a href="pages/grasses.html">Grasses</a></p>
                    <p><a href="pages/mammals.html">Mammals</a></p>
                </div>
            </div>


        </div>

    </section>
    <section class="cert">
        <div class="container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#212529" fill-opacity="1"
                    d="M0,192L10.9,192C21.8,192,44,192,65,170.7C87.3,149,109,107,131,96C152.7,85,175,107,196,138.7C218.2,171,240,213,262,208C283.6,203,305,149,327,128C349.1,107,371,117,393,117.3C414.5,117,436,107,458,112C480,117,502,139,524,165.3C545.5,192,567,224,589,224C610.9,224,633,192,655,170.7C676.4,149,698,139,720,160C741.8,181,764,235,785,234.7C807.3,235,829,181,851,165.3C872.7,149,895,171,916,160C938.2,149,960,107,982,90.7C1003.6,75,1025,85,1047,96C1069.1,107,1091,117,1113,117.3C1134.5,117,1156,107,1178,133.3C1200,160,1222,224,1244,234.7C1265.5,245,1287,203,1309,170.7C1330.9,139,1353,117,1375,122.7C1396.4,128,1418,160,1429,176L1440,192L1440,320L1429.1,320C1418.2,320,1396,320,1375,320C1352.7,320,1331,320,1309,320C1287.3,320,1265,320,1244,320C1221.8,320,1200,320,1178,320C1156.4,320,1135,320,1113,320C1090.9,320,1069,320,1047,320C1025.5,320,1004,320,982,320C960,320,938,320,916,320C894.5,320,873,320,851,320C829.1,320,807,320,785,320C763.6,320,742,320,720,320C698.2,320,676,320,655,320C632.7,320,611,320,589,320C567.3,320,545,320,524,320C501.8,320,480,320,458,320C436.4,320,415,320,393,320C370.9,320,349,320,327,320C305.5,320,284,320,262,320C240,320,218,320,196,320C174.5,320,153,320,131,320C109.1,320,87,320,65,320C43.6,320,22,320,11,320L0,320Z">
                </path>
            </svg>
            <div class="row">
                <div class="column col-12 col-lg-12 col-sm-6">
                    <div class="project">
                        <center>
                            <p><u>Our Project</u></p>
                            <p id="note">
                                <em>
                                    <block>
                                        Our Project is to build an environment which embeds all environmental factors at
                                        it's natural state. We plan to build a Sustainable
                                        model for our environment in a better way to revive our dying nature.
                                    </block>
                                </em>
                            </p>
                        </center>

                    </div>
                    <img src="images/natures/NaturalHome.jpg" alt="Project" width="800" height="500">
                </div>
            </div>
        </div>
    </section>
    <section class="footer">
        <div class="container p-0 m-0">
            <div class="row">
                <div class="col-12 p-0 pb-0">
                    <footer class="bg-dark text-center text-black">
                        <!-- Grid container -->
                        <div class="container p-0 pb-0">
                            <!-- Section: Social media -->
                            <section class="mb-4">
                                <!-- Facebook -->
                                <a class="btn text-white btn-floating m-1" style="background-color: #3b5998;" href="#!"
                                    role="button"><i class="fab fa-facebook-f"></i></a>

                                <!-- Twitter -->
                                <a class="btn text-white btn-floating m-1" style="background-color: #55acee;" href="#!"
                                    role="button"><i class="fab fa-twitter"></i></a>

                                <!-- Google -->
                                <a class="btn text-white btn-floating m-1" style="background-color: #dd4b39;" href="#!"
                                    role="button"><i class="fab fa-google"></i></a>

                                <!-- Instagram -->
                                <a class="btn text-white btn-floating m-1" style="background-color: #ac2bac;" href="#!"
                                    role="button"><i class="fab fa-instagram"></i></a>

                                <!-- Linkedin -->
                                <a class="btn text-white btn-floating m-1" style="background-color: #0082ca;" href="#!"
                                    role="button"><i class="fab fa-linkedin-in"></i></a>
                                <!-- Github -->
                                <a class="btn text-white btn-floating m-1" style="background-color: #333333;" href="#!"
                                    role="button"><i class="fab fa-github"></i></a>
                            </section>
                            <!-- Section: Social media -->
                        </div>
                        <!-- Grid container -->

                        <!-- Copyright -->
                        <div class="text-center p-3 fw-bolder fs-3 text-decoration-none"
                            style="background-color: rgba(0, 0, 0, 0.2);">
                            © <?php echo date('Y'); ?> Copyright:
                            <a class="text-white text-decoration-none" href="#"> naturalis.com</a>
                        </div>
                        <!-- Copyright -->
                    </footer>
                </div>
            </div>
        </div>
    </section>


    </div>

    <script src="/assets/js/bootstrap.js"></script>
    <script src="/assets/js/jquery-3.6.1.min.js"></script>
    
    
    <script>
        $(function() {
            var user = $("#exampleInputUser");
            var email = $("#exampleInputEmail1");
            var pass = $("#exampleInputPassword1");
            var butt = $("#submitButton");
            var login = $("#login");
            var register = $("#register");
            var logout = $("#logout");

            var userLogIn = $("#userLogIn");
            var userRegister = $("#userRegister");
            

            


            //Activating the login path
            login.click(
                function() {
                    userLogIn.css("visibility", "visible");
                    userRegister.css("visibility", "hidden");

                
                });
            //activating the Register path
            register.click(
                function() {
                    userLogIn.css("visibility", "hidden");
                    userRegister.css("visibility", "visible");
                });

        });
    </script>

</body>

</html>