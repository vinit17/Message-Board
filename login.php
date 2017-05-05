<html>
<head><title>Login page</title></head>
<?php

session_start();

$host='127.0.0.1:3306';
$db = 'board';
$user = 'root';
$pwd = '';

$obj =  'mysql:host='.$host.';dbname='.$db;
$pdo = new PDO($obj,$user, $pwd);


echo
"<body align=center>Enter your Login Details: <br><br>
<form action='login.php' method='GET' align = center> 
	UserName:
		<input type='text' name='username' placeholder='Username'><br><br>
	Password :
        <input type='password' name='password'  placeholder='Password'><br><br>
		<button type = 'submit'>Login";
		
		if (isset($_GET['username']) and isset($_GET['password'])){

$username = $_GET['username'];
$password = $_GET['password'];

$stmt = $pdo->prepare('SELECT * FROM users WHERE username= ? AND password = ?' );
$stmt->execute([$username,md5($password)]);
$user = $stmt->fetch();  
	if(!$user){
		die('user does not exist');
	}
	else{
		$_SESSION['username'] = $username;
		header('Location:board.php');
	}
		}
?> 

</html>