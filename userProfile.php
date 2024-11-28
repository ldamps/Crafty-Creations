<?php

include 'db.php';
include 'navBar.php';


if (isset($_SESSION['LoggedIn'])):
    $role = $_SESSION["LoggedIn"];
    $userID = $_COOKIE["ID"];
    //echo $role;
    if ($role === "customer") {
        //echo "customer";
        // === Queries for Customer ===
        // the ID happens in the view creation, so the view here will only have things relating to their ID
        $queryPersonal = "SELECT * From CustomerView";
        $stmtPersonal = $mysql->prepare($queryPersonal);
        $stmtPersonal->execute();
        $customerInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
    
        $queryNumPostCodes = "SELECT Distinct HouseNumber, Postcode, StreetName, City From CustomerView";
        $stmtPost = $mysql->prepare($queryNumPostCodes);
        $stmtPost->execute();
        $addresses = $stmtPost->fetchAll(PDO::FETCH_ASSOC);
    
        $queryPayment = "SELECT Distinct CardNumber, CVV, ExpiryDate From CustomerView";
        $stmtPay = $mysql->prepare($queryPayment);
        $stmtPay->execute();
        $payments = $stmtPay->fetchAll(PDO::FETCH_ASSOC);

    
    }else {
        if ($role === "Manager" || $role === "Assistant Manager")
        {
            echo "help2";
            $queryPersonal = "SELECT *  FROM ManagerView";
            $stmtPersonal = $mysql->prepare($queryPersonal);
            $stmtPersonal->execute();
            $personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
            echo $personalInfo["FirstName"];
        }
        else if ($role === "Shop Assistant" || $role === "Supervisor")
        {
            $queryPersonal = "SELECT * FROM ShopEmployeeView";
            $stmtPersonal = $mysql->prepare($queryPersonal);
            $stmtPersonal->execute();
            $personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
        }
        else if ($role === "CEO")
        {
            $queryPersonal = "SELECT FirstName, Surname, EmailAddress, hoursWorked, OfficeName, Location FROM OfficeEmployeeView WHERE EmployeeID = :ID";
            $stmtPersonal = $mysql->prepare($queryPersonal);
            $stmtPersonal->execute(["ID"=> $userID]);
            $personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
        }
        
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
                 if ($role === "customer"): 
                    ##echo "customer "?> 
                    <p><strong>Name:</strong> <?php echo $customerInfo['Title'] . ' ' . $customerInfo['FirstName'] . ' ' . $customerInfo['LastName']; ?></p>
                    <p><strong>Email:</strong> <?php echo $customerInfo['EmailAddress']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $customerInfo['PhoneNumber']; ?></p>
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
            <?php if ($personalInfo): ?>
            <p><strong>Store Address:</strong> <?php echo $personalInfo['StreetName'] . ', ' . $personalInfo['City'] . ', ' . $personalInfo['Postcode']; ?></p>
            <p><strong>Number of Employees:</strong> <?php echo $personalInfo['NumEmployees']; ?></p>
            <p><strong>Total Sales:</strong> $<?php echo number_format($personalInfo['TotalSales'], 2); ?></p>
            <p><strong>Store Manager:</strong> <?php echo $personalInfo['ManagerFirstName'] . ' ' . $personalInfo['ManagerSurname']; ?></p> 
            <?php else: ?>
            <p>No workplace information available.</p>
            <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if ($role === "CEO"):?>
                <h2>Workplace Information</h2>
                <?php if ($personalInfo): ?>
                <p><strong>Office Name:</strong> <?php echo $personalInfo['OfficeName'] ?></p>
                <p><strong>Location:</strong> <?php echo $personalInfo['Location']; ?></p>
                <?php endif ?>
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
    
<?php 
else:
    header("Refresh:0; url=index.php");

endif; ?>

<?php include 'footer.html'; ?>

