<?php

/*
 * Copyright (C) 2013 peredur.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.
if (isset($_POST['username'])) {
    $uname = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
        if ($stmt = $mysqli->prepare("SELECT id, username, password, salt, question, ans1
                      FROM members 
                                      WHERE username = ? LIMIT 1")) {
            $stmt->bind_param('s', $uname);  // Bind "$email" to parameter.
            $stmt->execute();    // Execute the prepared query.
            $stmt->store_result();

            // get variables from result.
            $stmt->bind_result($user_id, $username, $db_password, $salt, $question, $ans1);
            $stmt->fetch();
            $mp = array("fraud","Name of Best friend", "Pet Name", "School Name", "First Crush");
            echo "<h1> Reset Password</h1>
                <b>Question: </b>
            ";
            echo $mp[(int)$question] . "<br><br>";
            $addr = $_SERVER['PHP_SELF'];
            echo '<form name="reset" action="'. $addr . '"  method="post" >
        <input type="text" name="ans1">
        <br>
        <input type="hidden" name="corr" value = "' . $ans1 . '">
        <input type="hidden" name="uname" value = "' . $uname . '">
        <input type="submit" value="Submit" name="submit">
    </form>';
        }

}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST['submit']) {
        echo $_REQUEST['corr'];
        if($_REQUEST['ans1'] == $_REQUEST['corr']) {
            header('Location: change_pwd.php?uname=' . $_REQUEST['uname']);
        }
        else {
            header('Location: ../forgot.php?err=Answer do not match, try again');
            exit();
        }
    }
}
