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

if (!isset($_SESSION)){
    session_start();    
}
// else{
//     session_reset();
// }

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (!isset($_SESSION['pay'])){
        $_SESSION['pay'] = "";
    }
    if(isset($_POST['pay'])){
        $_SESSION['pay'] = $_POST['pay'];
    }

    if (!isset($_SESSION['PayeeSearch'])){
        $_SESSION['PayeeSearch'] = "";
    }

    if(isset($_POST['PayeeSearch'])){
        $_SESSION['PayeeSearch'] = $_POST['PayeeSearch'];
    }
    
    
}

$employees = array();

if(isset($_SESSION['PayeeSearch']) && $_SESSION['PayeeSearch'] != ""){
    $type = $_SESSION['PayeeSearch'];
    $query = $mysql->prepare("SELECT EmployeeID,FirstName,Surname,Role,HoursWorked FROM Employee WHERE FirstName LIKE '%$type%' OR Surname LIKE '%$type%' OR Role LIKE '%$type%'");
    $query->execute();
    $res = $query->fetchAll();
}
else{
    $query = $mysql->prepare("SELECT EmployeeID,FirstName,Surname,Role,HoursWorked FROM Employee");
    $query->execute();
    $res = $query->fetchAll();
}



foreach($res as $employee){
    $e = new Employee();
    $e->id = $employee['EmployeeID'];
    $e->FName = $employee['FirstName'];
    $e->SName = $employee['Surname'];
    $e->position = $employee['Role'];
    $e->hoursWorked = $employee['HoursWorked'];
    array_push($employees,$e);

}

if(isset($_SESSION['pay']) && $_SESSION['pay'] != ""){
    $type = $_SESSION['pay'];
    
}


// make table
echo "<table>";
echo "<thead>";
echo "<tr>";
echo "<th>First Name</th>";
echo "<th>Surname</th>";
echo "<th>Position</th>";
echo "<th>Hours Worked</th>";
echo "<th>Total Pay</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

foreach($employees as $employee){
    echo "<tr>";
    echo "<td>".$employee->FName."</td>";
    echo "<td>".$employee->SName."</td>";
    echo "<td>".$employee->position."</td>";
    echo "<td>".$employee->hoursWorked."</td>";
    echo "<td>".$employee->hoursWorked*$employee->payForPosition."</td>";
    echo "<td><button id =".$employee->id." class = 'payButton Button'>Pay</button></td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";




?>
<script type="text/javascript" src="script.js"></script>
