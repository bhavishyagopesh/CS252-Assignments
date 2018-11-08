<?php
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.
$uname = filter_input(INPUT_GET, 'uname', $filter = FILTER_SANITIZE_STRING);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>New Password</title>
        <link rel="stylesheet" href="../styles/main.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
    </head>
    <body>
        <form name="search_it" action="<?php echo $_SERVER['PHP_SELF'];?>"  method="post" >
         	New Password: <input name="pass1" type="Password">
            <br/>
            Confirm Password: <input name="pass2" type="Password">
            <br/>
            <input type="hidden" value = "<?php echo $uname ?>" name="uname">
            <input type="submit" value="Confirm" name="change">
        </form>
    </body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    if($_POST['change']) {
    	if($_REQUEST['pass1'] != $_REQUEST['pass2'])
    		echo "Password do not match, enter again<br>";
    	else {
    		$uname = $_REQUEST['uname'];
    		// echo $uname;
    		if ($stmt = $mysqli->prepare("SELECT id, username, password, salt, question, ans1
    		              FROM members 
    		                              WHERE username = ? LIMIT 1")) {
    		    $stmt->bind_param('s', $uname);  // Bind "$email" to parameter.
    		    $stmt->execute();    // Execute the prepared query.
    		    $stmt->store_result();

    		    // get variables from result.
    		    $stmt->bind_result($user_id, $username, $db_password, $salt, $question, $ans1);
    		    $stmt->fetch();
    			echo $username;
    			$password = $_REQUEST['pass1'];
    			echo '<script type="text/javascript">alert("hello");</script>';
    		}
    	}
    }
}
?>
