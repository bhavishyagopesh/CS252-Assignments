<html>
<head>
    <title>
        Search Employees
    </title>
    <style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
    </style>
    <script>
    function isEmpty(str) {
        return (!str || 0 === str.length);
    }
    function validateForm() {
        // validation function checks that exactly one field is filled
        var data = document.forms["search_it"];
        var number = data["number"].value;
        var lastname = data["lastname"].value;
        var department = data["department"].value;
        if(isEmpty(number) && isEmpty(lastname) && isEmpty(department)) {
            alert("Please fill any one field");
            return false;
        }
        else if(!isEmpty(number) && !isEmpty(lastname) && !isEmpty(department)) {
            alert("Please fill only one field");
            return false;
        }
        else if(!isEmpty(number) && !isEmpty(lastname) && isEmpty(department)) {
            alert("Please fill only one field");
            return false;
        }
        else if(!isEmpty(number) && isEmpty(lastname) && !isEmpty(department)){
            alert("Please fill only one field");
            return false;
        }
        else if(isEmpty(number) && !isEmpty(lastname) && !isEmpty(department)) {
            alert("Please fill only one field");
            return false;
        }
        else
            return true;
    }
    </script>
</head>

<body>
<center>
    <h2>Backspace Search Engine</h2>
    <form name="search_it" action="<?php echo $_SERVER['PHP_SELF'];?>"  method="post" >
     	Employee ID: <br/>
        <input type="text" name="number">
        <br/>
        Last Name: <br/>
        <input type="text" name="lastname">
        <br/>
        Department Number: <br/>
        <input type="text" name="department">
        <br/>
     	<input type="submit" value="Search" name="search">
        <br/>
        <br>
        Want to know department with highest employee count?
        <input type="submit" value="Click here!" name="highest">
        <br><br>
        To sort employees by tenure, enter dept Number:
        <br>
        <input type="text" name="dept_no">
        <br>
        <input type="submit" value="Tenure" name="tenure">
        <br><br>
        Get gender ratio in any department: <br>
        <input type="text" name="gender_dept_no">
        <br>
        <input type="submit" value="Gender" name="gender">
        <br>
        <br>
        Gender pay ratio: <br>
        <input type="text" name="gender_pay_dept_no">
        <br>
        <input type="submit" value="Gender" name="pay">
    </form>
</center>

<?php
// connect to DB
$servername = "localhost";
$username = "root";
$password = "root";
$db = "employees";
// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if($_POST['search']) {
        // collect values from form
        $number = $_REQUEST['number'];
        $lastname = $_REQUEST['lastname'];
        $department = $_REQUEST['department'];

        if (!empty($number)) {
            $sql = "SELECT * from employees WHERE emp_no=" . $number . ";";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                echo "<table><tr><th>Employment No.</th><th>First Name</th><th>Last Name</th><th>Birth Date</th><th>Gender</th><th>Hire Date</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["emp_no"] . "</td>";
                    echo "<td>" . $row["first_name"] . "</td>";
                    echo "<td>" . $row["last_name"] . "</td>";
                    echo "<td>" . $row["birth_date"] . "</td>";
                    echo "<td>" . $row["gender"] . "</td>";
                    echo "<td>" . $row["hire_date"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            else
                echo "Not found";
        }

        elseif(!empty($lastname)) {
            $sql = 'SELECT * from employees WHERE last_name="' . $lastname . '";';
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                echo "<table><tr><th>Employment No.</th><th>First Name</th><th>Last Name</th><th>Birth Date</th><th>Gender</th><th>Hire Date</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["emp_no"] . "</td>";
                    echo "<td>" . $row["first_name"] . "</td>";
                    echo "<td>" . $row["last_name"] . "</td>";
                    echo "<td>" . $row["birth_date"] . "</td>";
                    echo "<td>" . $row["gender"] . "</td>";
                    echo "<td>" . $row["hire_date"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            else
                echo "Not found";
        }

        else {
            $sql = 'SELECT * from dept_emp WHERE dept_no="' . $department . '";';
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                echo "<table><tr><th>Employment No.</th><th>First Name</th><th>Last Name</th><th>Birth Date</th><th>Gender</th><th>Hire Date</th></tr>";
                while($row = $result->fetch_assoc()) {
                    $emp_no = $row["emp_no"];
                    $sql2 = "SELECT * from employees WHERE emp_no=" . $emp_no . ";";
                    $result2 = $conn->query($sql2);

                    while($row2 = $result2->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row2["emp_no"] . "</td>";
                        echo "<td>" . $row2["first_name"] . "</td>";
                        echo "<td>" . $row2["last_name"] . "</td>";
                        echo "<td>" . $row2["birth_date"] . "</td>";
                        echo "<td>" . $row2["gender"] . "</td>";
                        echo "<td>" . $row2["hire_date"] . "</td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
            }
            else
                echo "Not found";
        }
    
    }
    else if($_POST['tenure']) {
        $dept_no = $_REQUEST['dept_no'];
        $sql = 'SELECT * from dept_emp WHERE dept_no="' . $dept_no . '";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $sort_it = array();
            while($row = $result->fetch_assoc()) {   
                $emp_no = $row["emp_no"];
                $start = $row["from_date"]; $end = $row["to_date"];
                // echo $emp_no . " " . $start . " " . $end . "<br>";
                $data1 = explode("-", $start);
                $data2 = explode("-", $end);
                $days = ((int)$data2[0] - (int)$data1[0])*365 + ((int)$data2[1] - (int)$data1[1])*30 + ((int)$data2[2] - (int)$data1[2]);
                // echo $emp_no . " " . $days . "<br>";
                $sort_it[$emp_no] = $days;
            }
            asort($sort_it);
            $sort_it = array_reverse($sort_it, true);
            // var_dump($sort_it);
            // print_r($sort_it);
            echo "<table><tr><th>Employment No.</th><th>First Name</th><th>Last Name</th><th>Birth Date</th><th>Gender</th><th>Hire Date</th></tr>";
            foreach ($sort_it as $emp_id => $days) {
                $sql2 = "SELECT * from employees WHERE emp_no=" . $emp_id . ";";
                $result2 = $conn->query($sql2);
                while($row2 = $result2->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row2["emp_no"] . "</td>";
                    echo "<td>" . $row2["first_name"] . "</td>";
                    echo "<td>" . $row2["last_name"] . "</td>";
                    echo "<td>" . $row2["birth_date"] . "</td>";
                    echo "<td>" . $row2["gender"] . "</td>";
                    echo "<td>" . $row2["hire_date"] . "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
        else
            echo "Invaid department ID";
    }

    else if($_POST['gender']) {
        $dept_no = $_REQUEST['gender_dept_no'];
        echo "<h2> Gender results for department " . $dept_no . " are:</h2>";
        $sql = 'SELECT * from dept_emp WHERE dept_no="' . $dept_no . '";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $male = 0;
            $female = 0;
            while($row = $result->fetch_assoc()) {   
                $emp_no = $row["emp_no"];
                $sql2 = "SELECT * from employees WHERE emp_no=" . $emp_no . ";";
                $result2 = $conn->query($sql2);
                while($row2 = $result2->fetch_assoc()) {
                    if(strcmp($row2["gender"], "M") == 0) 
                        $male = $male + 1;
                    else
                        $female = $female + 1;
                }
            }
            $ratio = $female / $male;
            echo "Total female count = " . $female . "<br>Total male count = " . $male . "<br>Gender ratio(F/M) = " . $ratio;
        }
        else
            echo "Invaid department ID";
    }

    else if($_POST['highest']) {
        // script to show department with max number of employees
        echo "<h2> Department with highest employee count</h2>";
        $sql = "SELECT dept_no, COUNT(*) FROM dept_emp group by dept_no;";
        $result = $conn->query($sql);
        $count = 0;
        $dept = "dept";
        while($row = $result->fetch_assoc()) {
            if($row["COUNT(*)"] > $count) {
                $count = $row["COUNT(*)"];
                $dept_no = $row["dept_no"];
            }
        }
        $sql = 'SELECT * from departments WHERE dept_no="' . $dept_no . '";';
        $result = $conn->query($sql);
        $dept_name = "dept";
        while($row = $result->fetch_assoc())
            $dept_name = $row["dept_name"];

        echo $dept_name . "(" . $dept_no . ") has highest(" . $count . ") number of employees.<br/>";
    }

    else if($_POST['pay']) {
        // script to show department with max number of employees
        $dept_no = $_REQUEST['gender_pay_dept_no'];
        echo "<h2> Gender pay ratio for department " . $dept_no . " are:</h2>";
        $sql = 'SELECT * from dept_emp WHERE dept_no="' . $dept_no . '";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $gender_count_ratio = array();
            $gender_pay_ratio = array();
            while($row = $result->fetch_assoc()) {   
                $emp_no = $row["emp_no"];
                // get gender
                $sql2 = "SELECT * from employees WHERE emp_no=" . $emp_no . ";";
                $result2 = $conn->query($sql2);
                $gender = "somestring";
                while($row2 = $result2->fetch_assoc()) {
                    $gender = $row2["gender"];
                }
                // get title
                $sql3 = "SELECT * from titles WHERE emp_no=" . $emp_no . ";";
                $result3 = $conn->query($sql3);
                while($row3 = $result3->fetch_assoc()) {
                    $title = $row3["title"];
                }
                $gender_count_ratio[$title][$gender] = $gender_count_ratio[$title][$gender] + 1;

                // get salary
                $sql4 = "SELECT * from salaries WHERE emp_no=" . $emp_no . ";";
                $result4 = $conn->query($sql4);
                while($row4 = $result4->fetch_assoc()) {
                    $salary = $row4["salary"];
                }
                $gender_pay_ratio[$title][$gender] = $gender_pay_ratio[$title][$gender] + ((int)$salary);
            }
            echo "<table><tr><th>Title</th><th>Male count</th><th>Female count</th><th>Average Male salary</th><th>Average Female salary</th><th>Pay Ratio(F/M)</th></tr>";
            foreach($gender_count_ratio as $title => $gender) {
                echo "<tr>";
                echo "<td>" . $title . "</td>";
                echo "<td>" . $gender_count_ratio[$title]["M"] . "</td>";
                echo "<td>" . $gender_count_ratio[$title]["F"] . "</td>";
                echo "<td>" . $gender_pay_ratio[$title]["M"] / $gender_count_ratio[$title]["M"] . "</td>";
                echo "<td>" . $gender_pay_ratio[$title]["F"] / $gender_count_ratio[$title]["F"] . "</td>";
                echo "<td>" . $gender_pay_ratio[$title]["F"] / $gender_pay_ratio[$title]["M"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else
            echo "Invaid department ID";
    }


}
?>

</body>
