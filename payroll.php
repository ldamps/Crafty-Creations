<?php include 'navbar.php'?>

<?php
require 'db.php';
include 'payroll.html';

class Employee{
    public $FName;
    public $SName;
    public $position;
    public $hoursWorked;
    public $payForPosition;
}

//Make sure session is started
if (!isset($_SESSION)){
    session_start();    
}

//Same idea as in index.php, to reset the page when a post is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (!isset($_SESSION['pay'])){
        $_SESSION['pay'] = "";
    }
    if(isset($_POST['Pay'])){
        $_SESSION['pay'] = $_POST['Pay'];
    }

    if (!isset($_SESSION['details'])){
        $_SESSION['details'] = "";
    }

    if(isset($_POST['Details'])){
        $_SESSION['details'] = $_POST['Details'];
    }

    if (!isset($_SESSION['PayeeSearch'])){
        $_SESSION['PayeeSearch'] = "";
    }

    if(isset($_POST['PayeeSearch'])){
        $_SESSION['PayeeSearch'] = $_POST['PayeeSearch'];
    }
    
    
}
//If a 'details' button is pressed, get the employee details and display them
if (isset($_SESSION['pay']) && $_SESSION['pay'] != ""){
    unset($_SESSION['details'], $_SESSION['pay']);
}
elseif(isset($_SESSION['details']) && $_SESSION['details'] != ""){
    $id = $_SESSION['details'];
    $query = $mysql->prepare("SELECT FirstName,Surname,HoursWorked FROM Employee WHERE EmployeeID = '$id'");
    $query->execute(); 
    $res = $query->fetchAll();
    $employeeToPay = $res[0];

    $query = $mysql->prepare("SELECT * FROM BankDetails WHERE Employee_EmployeeID = '$id'");
    $query->execute();
    $res = $query->fetchAll();
    $bankDetails = $res[0];

    echo "<br></br>";
    echo "<h2>".$employeeToPay['FirstName']." ".$employeeToPay['Surname']."</h2>";
    echo "<h3>Hours Worked: ".$employeeToPay['HoursWorked']."</h3>";
    echo "<h3>Accont Number: ".$bankDetails['AccountNo']."</h3>";
    echo "<h3>Sort Code: ".$bankDetails['SortCode']."</h3>";
    echo "<form method = 'post'><button id = ".$id." class = 'payButton Button'>Pay</button></form>";
    echo "<br></br>";

    //After paying, update the hours worked and set to 0
    //Not doing this as need to have a way of setting them in the first place
    // $query = $mysql->prepare("UPDATE Employee SET HoursWorked = 0 WHERE EmployeeID = '$id'");
}


$employees = array();
//If a search is made, get the employees that match the search in any combination of first name, surname or role
if(isset($_SESSION['PayeeSearch']) && $_SESSION['PayeeSearch'] != ""){
    $type = $_SESSION['PayeeSearch'];
    $query = $mysql->prepare("SELECT EmployeeID,FirstName,Surname,Role,HoursWorked FROM Employee WHERE FirstName LIKE '%$type%' OR Surname LIKE '%$type%' OR Role LIKE '%$type%'");
    $query->execute();
    $res = $query->fetchAll();
}
//Otherwise, get all employees
else{
    $query = $mysql->prepare("SELECT EmployeeID,FirstName,Surname,Role,HoursWorked FROM Employee");
    $query->execute();
    $res = $query->fetchAll();
}


//For each employee, create an employee object and add it to the array
foreach($res as $employee){
    $e = new Employee();
    $e->id = $employee['EmployeeID'];
    $e->FName = $employee['FirstName'];
    $e->SName = $employee['Surname'];
    $e->position = $employee['Role'];
    $e->hoursWorked = $employee['HoursWorked'];
    array_push($employees,$e);

}



if (count($employees) != 0){
// make table
echo "<br><table>";
echo "<thead>";
echo "<tr>";
echo "<th>First Name  </th>";
echo "<th>Surname     </th>";
echo "<th>Position    </th>"; //This need styling
echo "<th>Hours Worked</th>";
echo "<th>Total Pay   </th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
//For each employee, display their details as a table row
foreach($employees as $employee){
    echo "<tr>";
    echo "<td>".$employee->FName."</td>";
    echo "<td>".$employee->SName."</td>";
    echo "<td>".$employee->position."</td>";
    echo "<td>".$employee->hoursWorked."</td>";
    echo "<td>".$employee->hoursWorked*$employee->payForPosition."</td>";
    echo "<td><form method = 'post'><button id =".$employee->id." class = 'detailsButton Button'>Get Details</button></form></td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table></br>";
}
else{
    echo "<br><h2>No employees found</h2></br>";
}
?>
<script type="text/javascript" src="script.js"></script>
