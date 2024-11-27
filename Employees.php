<?php
include 'db.php';
include 'navBar.php';

$employeeID = $_SESSION['EmployeeID'];

//shop ID where manager works using stored procedure
$queryShopID = "CALL GetShopWorkedAt(:employeeID)";
$stmtShopID = $mysql->prepare($queryShopID);
$stmtShopID->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
$stmtShopID->execute();
$shopID = $stmtShopID->fetchColumn();
$stmtShopID->closeCursor();

/*SELECT E.EmployeeID, E.FirstName, E.Surname, E.Role, E.EmailAddress, E.HoursWorked, E.HourlyPay
                   FROM Employee E
                   INNER JOIN ShopEmployee SE ON E.EmployeeID = SE.Employee_EmployeeID
                   WHERE SE.Shop_ShopID = :shopID";*/
//employees working at same shop
$queryEmployees = "SELECT DISTINCT empID, empFirst, empSur, empRole, empEmail, empHours, empPay FROM ManagerView ORDER BY empID";
$stmtEmployees = $mysql->prepare($queryEmployees);
//$stmtEmployees->bindParam(':shopID', $shopID, PDO::PARAM_INT);
$stmtEmployees->execute();
$employeesData = $stmtEmployees->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            padding: 0 15px; 
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        td {
            background-color: #fff;
        }

        .section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employees Dashboard</h1>
        <br></br>


        <!-- Employees Information Section -->
        <div class="section">
            <h2>Employees Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>First Name</th>
                        <th>Surname</th>
                        <th>Role</th>
                        <th>Email Address</th>
                        <th>Hours Worked</th>
                        <th>Hourly Pay (£)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employeesData as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['empID']); ?></td>
                            <td><?php echo htmlspecialchars($employee['empFirst']); ?></td>
                            <td><?php echo htmlspecialchars($employee['empSur']); ?></td>
                            <td><?php echo htmlspecialchars($employee['empRole']); ?></td>
                            <td><?php echo htmlspecialchars($employee['empEmail']); ?></td>
                            <td><?php echo htmlspecialchars($employee['empHours']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($employee['empPay'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>


</body>
</html>
