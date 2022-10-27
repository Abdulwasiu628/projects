<?php
    require 'connect.php';
    require 'logout.php';
    if(isset($_POST['login-submit'])){
        
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if(empty($username) || empty($password)){
            echo 'Username or password error';
            header("Location: ./index.php?username_error&password_error");
            
        }
        else{
            $query = "SELECT * from `naturalisregistration` WHERE username='$username';";
            $getQuery = mysqli_query($conn, $query);
            $check = mysqli_num_rows($getQuery);

            if($check == 1){
                $row = mysqli_fetch_assoc($getQuery);
                $_SESSION['id'] = $row['id'];
                echo 'You are now logged in';
                
            }
            else{
                $messageOne[] = "Incorrect Username or Password";
            }



        }
        
    }

?>