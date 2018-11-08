<?php
/**
 *m Copyright (C) 2013 peredur.net
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
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <link rel="stylesheet" href="styles/main.css" />
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
    <body background="c.jpg" style="color: white;">
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
        <h1>Register with us</h1>
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
        <ul>
            <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
            <li>Emails must have a valid email format</li>
            <li>Passwords must be at least 6 characters long</li>
            <li>Passwords must contain
                <ul>
                    <li>At least one upper case letter (A..Z)</li>
                    <li>At least one lower case letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </li>
            <li>Your password and confirmation must match exactly</li>
        </ul>
        <form method="post" name="registration_form" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" style="width: 50%" class="form-horizontal">
          <div class="form-group">
                <label class="control-label col-sm-2" for="username">Username:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
                </div>
              </div>
            <div class="form-group">
                  <label class="control-label col-sm-2" for="username">Email:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                  </div>
                </div>
          <div class="form-group">
                <label class="control-label col-sm-2" for="username">Password:</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                </div>
              </div>

          <div class="form-group">
                <label class="control-label col-sm-2" for="username">Confirm password:</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="confirmpwd" placeholder="Enter password again" name="confirmpwd">
                </div>
              </div>
        <div class="form-group">
                <label class="control-label col-sm-2" for="username">Security Question:</label>
                <div class="col-sm-10">
                  <select name="question" id="question" class="form-control">
        <option value="1">Name of Best friend</option>
        <option value="2">Pet Name</option>
      <option value="3">School Name</option>
      <option value="4">First crush</option>
    </select>
                </div>
              </div>
        <div class="form-group">
                <label class="control-label col-sm-2" for="username">Answer:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="ans2" placeholder="Enter answer" name="ans1">
                </div>
              </div>
            <input type="button" 
                   value="Register" 
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd,
				   this.form.ans1);" style="color: black;" /> 
        </form>
        <p>Return to the <a href="index.php" style="color: white;">login page</a>.</p>
    </body>
</html>
