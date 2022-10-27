<?php
	include('connect.php');
	session_start();
	if(isset($_GET['code'])){
	$user=$_GET['user_id'];
	$code=$_GET['code'];
 
	$query=mysqli_query($conn,"select * from naturalisregistration where username='$username'");
	$row=mysqli_fetch_array($query);
 
	if($row['code']==$code){
		//activate account
		mysqli_query($conn,"update user set verify='1' where username='$username'");
		?>
		<p>Account Verified!</p>
		<p><a href="index.php">Login Now</a></p>
		<?php
	}
	else{
		$message[] = 'Wrong Symbol used';
  		header('location:index.php');
	}
	}
	else{
		header('location:index.php');
	}
?>