<?php
include 'db.php';
include 'navBar.php';
if (isset($_SESSION['LoggedIn'])) {
    $role = $_SESSION["LoggedIn"];
    $employeeID = $_SESSION['EmployeeID'];
    if ($role == "Manager" || $role == "Assistant Manager") {

        //shop ID where manager works using stored procedure
        $queryShopID = "CALL GetShopWorkedAt(:employeeID)";
        $stmtShopID = $mysql->prepare($queryShopID);
        $stmtShopID->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
        $stmtShopID->execute();
        $shopID = $stmtShopID->fetchColumn();
        $stmtShopID->closeCursor();

        //employees working at same shop
        $queryEmployees = "SELECT DISTINCT empID, empFirst, empSur, empRole, empEmail, empHours, empPay FROM ManagerView ORDER BY empID";
        $stmtEmployees = $mysql->prepare($queryEmployees);
        //$stmtEmployees->bindParam(':shopID', $shopID, PDO::PARAM_INT);
        $stmtEmployees->execute();
        $employeesData = $stmtEmployees->fetchAll(PDO::FETCH_ASSOC);
    } else if ($role === "IT Support" || $role === "Website Development" || $role === "Payroll" || $role === "Administration" || $role === "Human Resources" || $role === "CEO") {
        $queryEmployees = "SELECT DISTINCT EmployeeID, FirstName, Surname, Role, EmailAddress, hoursWorked, HourlyPay, ShopID FROM OfficeEmployeeView ORDER BY ShopID, EmployeeID";
        $stmtEmployees = $mysql->prepare($queryEmployees);
        //$stmtEmployees->bindParam(':shopID', $shopID, PDO::PARAM_INT);
        $stmtEmployees->execute();
        $employeesData = $stmtEmployees->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    header("Refresh:0; url=index.php");
} ?>

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

        th,
        td {
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
         <?php if ($role === "IT Support" || $role === "Website Development" || $role === "Payroll" || $role === "Administration" || $role === "Human Resources" || $role === "CEO"):?>
        <div class="section">
            <h2>Employees Information</h2>
            <!-- Set initial shop to 1-->
            <?php $currentShop = 1;
            // there are ten shops, so create a new table for each shop
            while ($currentShop <= 10): 
            // get the city of the current shop
            $queryShopName = "SELECT DISTINCT City FROM OfficeEmployeeView WHERE ShopID = :currentShop";
            $stmtShopName = $mysql->prepare($queryShopName);
            //$stmtEmployees->bindParam(':shopID', $shopID, PDO::PARAM_INT);
            $stmtShopName->execute(['currentShop'=> $currentShop]);
            $shopName = $stmtShopName->fetchColumn();?>
             <?php echo "<br><h1>$shopName Shop</h1>"?>
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
                                <?php if ($employee['ShopID'] === $currentShop): ?>
                                    <td><?php echo htmlspecialchars($employee['EmployeeID']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['FirstName']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['Surname']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['Role']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['EmailAddress']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['hoursWorked']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($employee['HourlyPay'], 2)); ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                $currentShop = $currentShop + 1;
                ?>
            <?php endwhile; ?>
        </div>
        <?php endif ?>
        <?php if ($role == "Manager" || $role == "Assistant Manager"): ?>
            <div class="section">
            <h2>Employees Information</h2>
            <!-- Set initial shop to 1-->
            <?php // get the city of the current shop
            $queryShopName = "SELECT DISTINCT City FROM OfficeEmployeeView WHERE ShopID = :currentShop";
            $stmtShopName = $mysql->prepare($queryShopName);
            //$stmtEmployees->bindParam(':shopID', $shopID, PDO::PARAM_INT);
            $stmtShopName->execute(['currentShop'=> $currentShop]);
            $shopName = $stmtShopName->fetchColumn();?>
             <?php echo "<br><h1>$shopName Shop</h1>"?>
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
                                    <td><?php echo htmlspecialchars($employee['EmployeeID']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['FirstName']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['Surname']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['Role']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['EmailAddress']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['hoursWorked']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($employee['HourlyPay'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                $currentShop = $currentShop + 1;
                ?>
        </div>
        <?php endif; ?>
    </div>


</body>

</html>