<?php

include 'db.php';
include 'navBar.php';


if (isset($_SESSION['LoggedIn']))
{
    $role = $_SESSION["LoggedIn"];
    $userID = $_COOKIE["ID"];
}
else
{
    header("Refresh:0; url=index.php");
}

if ($role === "customer") {
    
    // === Queries for Customer ===
    // the ID happens in the view creation, so the view here will only have things relating to their ID
    $queryPersonal = "SELECT * From CustomerView";
    $stmtPersonal = $mysql->prepare($queryPersonal);
    $stmtPersonal->execute();

    $customerInfo = $stmtPersonal->fetchAll(PDO::FETCH_ASSOC);

    $queryNumPostCodes = "SELECT Distinct HouseNumber, Postcode, StreetName, City From CustomerView";
    $stmtPost = $mysql->prepare($queryNumPostCodes);
    $stmtPost->execute();
    $addresses = $stmtPost->fetchAll(PDO::FETCH_ASSOC);

    $queryPayment = "SELECT Distinct CardNumber, CVV, ExpiryDate From CustomerView";
    $stmtPay = $mysql->prepare($queryPayment);
    $stmtPay->execute();
    $payments = $stmtPay->fetchAll(PDO::FETCH_ASSOC);

    //echo "size: " . sizeof($personalInfo) ."";
    //echo "size: " . sizeof($customerInfo) ."";

}else {
    echo "else";
    $queryPersonal = "SELECT * FROM Employee WHERE EmployeeID = :userID";
    $stmtPersonal = $mysql->prepare($queryPersonal);
    $stmtPersonal->execute([':userID' => $userID]);
    $personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);

    $query = "
    SELECT 
        e.FirstName AS EmployeeFirstName, 
        e.Surname AS EmployeeSurname, 
        e.Role, 
        e.hoursWorked, 
        s.StreetName, 
        s.City, 
        s.Postcode, 
        s.NumEmployees, 
        s.TotalSales, 
        m.FirstName AS ManagerFirstName, 
        m.Surname AS ManagerSurname
    FROM Employee e
    JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
    JOIN Shop s ON se.Shop_ShopID = s.ShopID
    LEFT JOIN Employee m ON m.EmployeeID = (
        SELECT Employee_EmployeeID
        FROM ShopEmployee
        WHERE Shop_ShopID = s.ShopID
        AND m.Role = 'Manager'
        LIMIT 1
    )
    WHERE e.EmployeeID = :userID;
";
$stmt = $mysql->prepare($query);
$stmt->execute([':userID' => $userID]);
$employeeDetails = $stmt->fetch(PDO::FETCH_ASSOC);


}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 30px auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #333;
}

h2 {
    color: #555;
    font-size: 1.5em;
}

.section {
    margin-bottom: 20px;
}

p {
    font-size: 1em;
    color: #555;
}

/* order history table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f0f0f0;
    color: #333;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* makes the page responsive */
@media screen and (max-width: 768px) {
    .container {
        width: 95%;
    }

    table, th, td {
        font-size: 0.9em;
    }
}
    </style>

    <title>My Account</title>
</head>
<body>
    <div class="container">
        <h1>Account Details</h1>
        
        <!-- personal information -->
        <div class="section">
        <h2>Personal Information</h2>
            <?php if (isset($_SESSION["LoggedIn"])):
             if ($role === "customer"): ?> 
                <p><strong>Name:</strong> <?php echo $customerInfo[0]['Title'] . ' ' . $customerInfo[0]['FirstName'] . ' ' . $customerInfo[0]['LastName']; ?></p>
                <p><strong>Email:</strong> <?php echo $customerInfo[0]['EmailAddress']; ?></p>
                <p><strong>Phone:</strong> <?php echo $customerInfo[0]['PhoneNumber']; ?></p>
            <?php else: ?> 
                <p><strong>Name:</strong> <?php echo $personalInfo['FirstName'] . ' ' . $personalInfo['Surname']; ?></p>
                <p><strong>Email:</strong> <?php echo $personalInfo['EmailAddress']; ?></p>
                <p><strong>Role:</strong> <?php echo ucfirst($role); ?></p>
                <p><strong>Hours Worked:</strong> <?php echo $personalInfo['hoursWorked']; ?></p>
            <?php endif; 
            endif; ?>

        </div>
        
        <?php if ($role === "Shop Assistant" || $role === "Supervisor" || $role === "Manager" || $role === "Assistant Manager"): ?>
        <div class="section">
        <h2>Workplace Information</h2>
        <?php if ($employeeDetails): ?>
        <p><strong>Store Address:</strong> <?php echo $employeeDetails['StreetName'] . ', ' . $employeeDetails['City'] . ', ' . $employeeDetails['Postcode']; ?></p>
        <p><strong>Number of Employees:</strong> <?php echo $employeeDetails['NumEmployees']; ?></p>
        <p><strong>Total Sales:</strong> $<?php echo number_format($employeeDetails['TotalSales'], 2); ?></p>
        <p><strong>Store Manager:</strong> <?php echo $employeeDetails['ManagerFirstName'] . ' ' . $employeeDetails['ManagerSurname']; ?></p>
         <?php else: ?>
        <p>No workplace information available.</p>
        <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- address -->
        <?php if ($role === "customer"): ?>
        <div class="section">
            <h2>Address</h2>
            <?php if (sizeof($addresses) == 0): ?>
                <p>No addresses saved.</p>
            <?php endif; $i = 0;?>
            <?php foreach ($addresses as $address): ?>
                    <p><strong>Street:</strong> <?php echo $address['HouseNumber'] . ' ' . $address['StreetName']; ?></p>
                    <p><strong>City:</strong> <?php echo $address['City']; ?></p>
                    <p><strong>Postcode:</strong> <?php echo $address['Postcode']; ?></p>
                    <br>
                <?php endforeach; ?>
        </div>
        <?php endif; ?>
  
        <!-- payment method - only diplays last 4 digits of card-->
        <?php if ($role === "customer"): ?>
        <div class="section">
            <h2>Payment Methods</h2>
            <?php if (count($payments) == 0): ?>
                <p>No payment methods saved.</p>
            <?php endif; ?>
            <?php foreach ($payments as $payment): ?>
                
                <p><strong>Card Number:</strong> **** **** **** <?php echo substr($payment['CardNumber'], -4); ?></p>
                <p><strong>Expiry Date:</strong> <?php echo $payment['ExpiryDate']; ?></p>
                <p><strong>CVV:</strong> ***</p>
                <br>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
