<html>
<head><title>Message Board</title></head>
<body>


<?php
try {
    $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname="","pwd","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //print_r($dbh);
    $dbh->beginTransaction();
    $dbh->exec('delete from users where username="smith"');
    $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
    or die(print_r($dbh->errorInfo(), true));
    $dbh->commit();

    $stmt = $dbh->prepare('select * from users');
    $stmt->execute();
    print "<pre>";
    while ($row = $stmt->fetch()) {
        // print_r($row);
    }


} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
?>
<?php
if(isset($_GET['random'] ) && isset($_GET['random1'])){
    $repmsg = $_GET['random'];
    $repid = $_GET['random1'];

    $replyto = $dbh->prepare("INSERT into posts VALUES(?,?,?,now(),?)");
    $replyto->execute([uniqid(),$repid,$_SESSION['username'],$repmsg]);
}
?>

<?php

		echo" 
			<form  align='center' method = 'GET'>
			<input  type='submit' value='Logout' name='logout'><br>
			</form>
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>	
			";
			
			if(isset($_GET['logout'])){
    unset($_SESSION['username']);
   header('Location:login.php');
    }
			echo"<form id = 'msglist' align='center' method = 'GET' action='board.php'>
			Message Area<br><br>
			<textarea placeholder='Message Box' name='message' id='rep' rows='10' cols='50'></textarea><br><br><br>
			<input type='submit' value='New Post' onclick='board.php'><br><br>
			</form>
		<font size='10'><b>Message Area: </b>";
		if(isset($_GET['message']) && !isset($_GET['random1'])){
			
			$msg = $_GET['message'];
			$id=uniqid();
			$uname = $_SESSION['username'];
			$res = $dbh->prepare("INSERT into posts VALUES(?,null,?,now(),?)");
			$res->execute([$id,$uname,$msg]);
			
		}
			
			$a = $dbh->prepare("SELECT * FROM posts,users WHERE posts.postedby=users.username ORDER BY datetime ASC");
			$a->execute();
			$b=$a->fetchAll();
			//print_r($b);
			
			
			foreach($b as $values){
			echo"<pre><br><form method = 'GET' action='board.php'><b>Message id: </b>".$values['id']."<br>
			<b>Full Name: </b>".$values['fullname']."<br>
			<b>UserName: </b>".$values['postedby']."<br>
			<b>Date: </b>".$values['datetime']."<br>
			<b>Message: </b>".$values['message']."<br>
			<input type='hidden' name='random' id='random_".$values['id']."'>;
			<input type='hidden' name='random1' value='".$values['id']."'>"
			?>
			
		<input type="submit" onclick="getreply('<?php echo $values['id'] ?>')" value = "Reply To"></pre>
		
		<?php
		echo "</form>
		---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			";
			}
	
		
		
?>

<script type="text/javascript">
function getreply(id) {

	var reply = document.getElementById("rep").value ;

	document.getElementById("random_"+id).value = reply ;
}  	

</script>

</body>
</html>
