<?php

include 'db.php';
include 'navBar.php';

$role = $_SESSION["LoggedIn"];
$userID = $_COOKIE["ID"];

    // === Queries for Customer ===
    // the ID happens in the view creation, so the view here will only have things relating to their ID
    $queryCustomerInfo = "SELECT * FROM CustomerView WHERE CustomerID = :userID";
    $stmtCustomer = $mysql->prepare($queryCustomerInfo);
    $stmtCustomer->execute([':userID' => $userID]);

    // fetch all rows for the customer's order history
    $customerInfo = $stmtCustomer->fetchAll(PDO::FETCH_ASSOC);





    //handle returns here
    // check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];

    // prepare the sql query 
    $queryUpdateStatus = "UPDATE OnlineOrder SET OrderStatus = 'Returned' WHERE OrderID = :orderID AND OrderStatus = 'Delivered'";
    $stmtUpdate = $mysql->prepare($queryUpdateStatus);

    $stmtUpdate->execute([':orderID' => $orderID]);
    // check if any rows were updated
    if ($stmtUpdate->rowCount() > 0) {
        echo "<script>location.reload();</script>"; // reload page immediately to update table
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

    <title>My Orders</title>
</head>
<body>
    <div class="container">
        <h1>My Orders</h1>
        <!-- order history -->
        <?php if ($role === "customer"): ?>
        <div class="section">
            <h2>Order History</h2>
            <table class="order-history">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Tracking Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customerInfo as $order): ?>
                        <tr>
                            <td><?php echo $order['OrderID']; ?></td>
                            <td><?php echo '£' . number_format($order['Price'], 2); ?></td>
                            <td><?php echo $order['OrderStatus']; ?></td>
                            <td><?php echo $order['TrackingNo']; ?></td>
                            <td>
                                <?php if (strtolower($order['OrderStatus']) === 'delivered'): ?>
                                    <form action="orderHistory.php" method="POST">
                                        <input type="hidden" name="orderID" value="<?php echo $order['OrderID']; ?>">
                                        <button type="submit" class="return-button">Return</button>
                                    </form>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>