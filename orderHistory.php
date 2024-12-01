<?php

include 'db.php';
include 'navBar.php';

if (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] === "customer"):
    $role = $_SESSION["LoggedIn"];
    $userID = $_SESSION["ID"];
    //echo $userID;
    // === Queries for Customer ===
    // the ID happens in the view creation, so the view here will only have things relating to their ID
    $queryCustomerInfo = "SELECT DISTINCT OrderID, Price, OrderStatus, TrackingNo FROM CustomerView WHERE CustomerID = :userID";
    $stmtCustomer = $mysql->prepare($queryCustomerInfo);
    $stmtCustomer->execute([':userID' => $userID]);

    // fetch all rows for the customer's order history
    $customerInfo = $stmtCustomer->fetchAll(PDO::FETCH_ASSOC);

    //handle returns here
    // check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderID']) && isset($_POST['reason'])) {
        $orderID = $_POST['orderID'];
        $reason = $_POST['reason'];

        $queryUpdateStatus = "UPDATE OnlineOrder SET OrderStatus = 'Returned' WHERE OrderID = :orderID AND OrderStatus = 'Delivered'";
        $stmtUpdate = $mysql->prepare($queryUpdateStatus);
        $stmtUpdate->execute([':orderID' => $orderID]);

        // if successfully updated, add an entry to the OnlineReturn table
        if ($stmtUpdate->rowCount() > 0) {
            echo "test";
            // calculate the amount to return
            $queryOrderDetails = "
            SELECT Price, Customer_CustomerID, Shop_ShopID 
            FROM CustomerView
            WHERE OrderID = :orderID";
            $stmtOrderDetails = $mysql->prepare($queryOrderDetails);
            $stmtOrderDetails->execute([':orderID' => $orderID]);
            $orderDetails = $stmtOrderDetails->fetch(PDO::FETCH_ASSOC);

            $amountToReturn = $orderDetails['Price'];
            $customerID = $orderDetails['Customer_CustomerID'];
            $shopID = $orderDetails['Shop_ShopID'];

            // insert a row into the OnlineReturn table
            $queryInsertReturn = "START TRANSACTION; INSERT INTO OnlineReturn (Reason, AmountToReturn, OnlineOrder_OrderID, Customer_CustomerID, Shop_ShopID) 
            VALUES (:reason, :amountToReturn, :orderID, :customerID, :shopID);
            COMMIT;";
            $stmtInsertReturn = $mysql->prepare($queryInsertReturn);
            $stmtInsertReturn->execute([
                ':reason' => $reason,
                ':amountToReturn' => $amountToReturn,
                ':orderID' => $orderID,
                ':customerID' => $customerID,
                ':shopID' => $shopID,
            ]);


            echo "<script>location.reload();</script>"; // reload page to see changes
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

            table,
            th,
            td {
                border: 1px solid #ddd;
            }

            th,
            td {
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

                table,
                th,
                td {
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
                                                <label for="reason-<?php echo $order['OrderID']; ?>">Reason:</label>
                                                <input type="text" id="reason-<?php echo $order['OrderID']; ?>" name="reason" required>
                                                <button type="submit" class="return-button button">Return</button>
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
    <?php  else:?>
        <div class="container">
            <h2>Unauthorised Access</h2>
            <p>You are not authorised to view this page. Return to homepage: <a style="text-decoration:underline" href="index.php">Back to Homepage</a></p>
            </div>
        <?php endif;?>
        <script type="text/javascript" src="script.js"></script>

</html>


<?php include 'footer.php'; ?>