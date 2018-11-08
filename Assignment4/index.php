<?php
/**
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
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Secure Login: Log In</title>
        <link rel="stylesheet" href="styles/main.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
        <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
          <style>
          * {
              box-sizing: border-box;
          }

          /* Create two equal columns that floats next to each other */
          .column {
              float: left;
              width: 50%;
              padding: 10px;
              height: 300px; /* Should be removed. Only for demonstration */
          }

          /* Clear floats after the columns */
          .row:after {
              content: "";
              display: table;
              clear: both;
          }
          </style>
    </head>
    <body background="b.gif" style="color: white;">
        <div class="row">
          <div class="column">
            <h1>Secure Login</h1>
          </div>
          <div class="column">
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<p class="error">Error Logging In!</p>';
                    }
                    ?> 
                    <form action="includes/process_login.php" method="post" name="login_form">  
                    <div class="form-group" style="width: 50%">     
                        Email: <input type="email" name="email" placeholder="Enter email" class="form-control"/>
                        Password: <input type="password" placeholder="Enter password"
                                         name="password" class="form-control"
                                         id="password"/>
                        <input type="button" 
                               value="Login" class="btn btn-default"
                               onclick="formhash(this.form, this.form.password);" /> 
                           </div>
                    </form>
                    <p>If you don't have a login, please <a href="register.php">register</a></p>
                    <p>If you are done, please <a href="includes/logout.php">log out</a>.</p>
                    <p> <a href="includes/forgot.php">Forgot Password</a>.<p>
                <p>You are currently logged <?php echo $logged ?>.</p>
          </div>
        </div>
        

    </body>
</html>
