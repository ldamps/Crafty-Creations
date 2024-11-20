<?php

include 'db.php';

//$customerID = $_SESSION['CustomerID']; // for when the user is logged in and their CustomerID is stored in session
$customerID = 1;   // hardcoded the CustomerID for now

// personal information
$queryPersonal = "SELECT * FROM Customer WHERE CustomerID = :customerID";
$stmtPersonal = $mysql->prepare($queryPersonal);
$stmtPersonal->execute([':customerID' => $customerID]);
$personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);

//  address
$queryAddress = "SELECT * FROM CustomerAddress WHERE Customer_CustomerID = :customerID";
$stmtAddress = $mysql->prepare($queryAddress);
$stmtAddress->execute([':customerID' => $customerID]);
$addressInfo = $stmtAddress->fetch(PDO::FETCH_ASSOC);

// payment methods
$queryPayment = "SELECT * FROM PaymentMethods WHERE Customer_CustomerID = :customerID";
$stmtPayment = $mysql->prepare($queryPayment);
$stmtPayment->execute([':customerID' => $customerID]);
$paymentInfo = $stmtPayment->fetch(PDO::FETCH_ASSOC);

// order history
$queryOrders = "SELECT * FROM OnlineOrder WHERE Customer_CustomerID = :customerID";
$stmtOrders = $mysql->prepare($queryOrders);
$stmtOrders->execute([':customerID' => $customerID]);
$orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
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
<nav class="topnav">
        <a class="company" href="index.php">Crafty Creations</a>
        <ul>
            <a id='logInText' class="button" href="loginPage.php">log in | sign up</a>
        </ul>
    </nav>
    <nav class="selection">
        <input type="text" placeholder="Search..">

        <div class="yarn button">
            <button class="dropbtn">Yarn
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="yarn-selection">
                <a href="#">Show All</a>
                <a href="#">Acrylic Yarn</a>
            </div>
        </div>

        <div class="fabric button">
            <button class="dropbtn">Fabric
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="fabric-selection">
                <a href="#">Show All</a>
            </div>
        </div>

        <div class="paint button">
            <button class="dropbtn">Paint
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="paint-selection">
                <a href="#">Show All</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1>Account Details</h1>
        
        <!-- personal information -->
        <div class="section">
            <h2>Personal Information</h2>
            <p><strong>Name:</strong> <?php echo $personalInfo['Title'] . ' ' . $personalInfo['FirstName'] . ' ' . $personalInfo['LastName']; ?></p>
            <p><strong>Email:</strong> <?php echo $personalInfo['EmailAddress']; ?></p>
            <p><strong>Phone:</strong> <?php echo $personalInfo['PhoneNumber']; ?></p>
        </div>

        <!-- address -->
        <div class="section">
            <h2>Address</h2>
            <p><strong>Street:</strong> <?php echo $addressInfo['HouseNumber'] . ' ' . $addressInfo['StreetName']; ?></p>
            <p><strong>City:</strong> <?php echo $addressInfo['City']; ?></p>
            <p><strong>Postcode:</strong> <?php echo $addressInfo['Postcode']; ?></p>
        </div>

        <!-- payment method - only diplays last 4 digits of card-->
        <div class="section">
            <h2>Payment Method</h2>
            <p><strong>Card Number:</strong> **** **** **** <?php echo substr($paymentInfo['CardNumber'], -4); ?></p>
            <p><strong>Expiry Date:</strong> <?php echo $paymentInfo['ExpiryDate']; ?></p>
            <p><strong>CVV:</strong> ***</p> 
        </div>

        <!-- order history -->
        <div class="section">
            <h2>Order History</h2>
            <table class="order-history">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Tracking Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['OrderID']; ?></td>
                            <td><?php echo '$' . number_format($order['Price'], 2); ?></td>
                            <td><?php echo $order['OrderStatus']; ?></td>
                            <td><?php echo $order['TrackingNo']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
