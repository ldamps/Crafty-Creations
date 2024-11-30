<?php

include 'db.php';
include 'navBar.php';

if (isset($_SESSION['LoggedIn']) && ($_SESSION["LoggedIn"]==="Shop Assistant" || $_SESSION["LoggedIn"]=== "Supervisor" || $_SESSION["LoggedIn"]==="Manager" || $_SESSION['LoggedIn']=== "Assistant Manager")):
    $role = $_SESSION["LoggedIn"];
    $userID = $_SESSION["ID"];
    //echo "Role: " . $role;

    if ($role === "Supervisor" || $role === "Shop Assistant") {
        // === Queries for Shop Employee
        // select all online orders
        $queryOnlineOrders = "SELECT DISTINCT OrderID, Price, OrderStatus, TrackingNo, Shop_shopID, OrderDate, customerID FROM ShopEmployeeView";
        $stmtOnlineOrders = $mysql->prepare($queryOnlineOrders);
        $stmtOnlineOrders->execute();
        $onlineOrders = $stmtOnlineOrders->fetchAll(PDO::FETCH_ASSOC);

        // get all online returns
        $queryOnlineReturns = "SELECT DISTINCT OnlineReturnID, Reason, AmountToReturn, Customer_CustomerID FROM ShopEmployeeView";
        $stmtOnlineReturns = $mysql->prepare($queryOnlineReturns);
        $stmtOnlineReturns->execute();
        $OnlineReturns = $stmtOnlineReturns->fetchAll(PDO::FETCH_ASSOC);

        // get all shop returns
        $queryShopReturns = "SELECT DISTINCT ShopReturnID, shopReason, ShopAmountToReturn, ShopReturnCustomer FROM ShopEmployeeView";
        $stmtShopReturns = $mysql->prepare($queryShopReturns);
        $stmtShopReturns->execute();
        $ShopReturns = $stmtShopReturns->fetchAll(PDO::FETCH_ASSOC);

        // shop orders
        $queryShopOrders = "SELECT DISTINCT PurchaseID, shopPrice, PurchaseDate, shopCustomer, SID FROM ShopEmployeeView";
        $stmtShopOrders = $mysql->prepare($queryShopOrders);
        $stmtShopOrders->execute();
        $ShopOrders = $stmtShopOrders->fetchAll(PDO::FETCH_ASSOC);
    } else if ($role === "Manager" || $role === "Assistant Manager") {

        // === Queries for Manager
        // select all online orders
        $queryOnlineOrders = "SELECT DISTINCT OrderID, Price, OrderStatus, TrackingNo, Shop_shopID, OrderDate, customerID FROM ManagerView";
        $stmtOnlineOrders = $mysql->prepare($queryOnlineOrders);
        $stmtOnlineOrders->execute();
        $onlineOrders = $stmtOnlineOrders->fetchAll(PDO::FETCH_ASSOC);
        //echo !empty($OnlineOrders);
        //echo $onlineOrders;
        // get all online returns
        $queryOnlineReturns = "SELECT DISTINCT OnlineReturnID, Reason, AmountToReturn, Customer_CustomerID FROM ManagerView";
        $stmtOnlineReturns = $mysql->prepare($queryOnlineReturns);
        $stmtOnlineReturns->execute();
        $OnlineReturns = $stmtOnlineReturns->fetchAll(PDO::FETCH_ASSOC);

        // get all shop returns
        $queryShopReturns = "SELECT DISTINCT ShopReturnID, shopReason, ShopAmountToReturn, ShopReturnCustomer FROM ManagerView";
        $stmtShopReturns = $mysql->prepare($queryShopReturns);
        $stmtShopReturns->execute();
        $ShopReturns = $stmtShopReturns->fetchAll(PDO::FETCH_ASSOC);

        // shop orders
        $queryShopOrders = "SELECT DISTINCT PurchaseID, shopPrice, PurchaseDate, shopCustomer, SID FROM ManagerView";
        $stmtShopOrders = $mysql->prepare($queryShopOrders);
        $stmtShopOrders->execute();
        $ShopOrders = $stmtShopOrders->fetchAll(PDO::FETCH_ASSOC);
    }

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

        <title>Shop Order History</title>
    </head>

    <body>
        <div class="container">
            <h1>Shop Order History</h1>
            <!-- order history -->
            <?php if ($role === "Shop Assistant" || $role === "Supervisor" || $role === "Manager" || $role === "Assistant Manager"): ?>
                <div class="section">
                    <br>
                    <h2>Online Orders</h2>
                    <table class="order-history">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Information</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Tracking Number</th>
                                <th>Products</th>
                                <th>Date Ordered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //echo $onlineOrders[0]['OrderID'];
                            if (isset($onlineOrders[0]['OrderID'])):
                                foreach ($onlineOrders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['OrderID']; ?></td>
                                        <td>
                                            <?php
                                            $queryCustomer = "SELECT DISTINCT FirstName,LastName, CustomerID FROM EmployeeCustomerView WHERE CustomerID = :ID";
                                            $stmtCustomer = $mysql->prepare($queryCustomer);
                                            $stmtCustomer->execute(["ID" => $order['customerID']]);

                                            // fetch customer information
                                            $customer = $stmtCustomer->fetch(PDO::FETCH_ASSOC);
                                            ?>
                                            <table class="order-history">
                                                <thead>
                                                    <tr>
                                                        <th>First Name</th>
                                                        <th>Surname</th>
                                                        <th>ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo $customer["FirstName"] ?></td>
                                                        <td><?php echo $customer["LastName"] ?></td>
                                                        <td><?php echo $order['customerID'] ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><?php echo '£' . number_format($order['Price'], 2); ?></td>
                                        <td><?php echo $order['OrderStatus']; ?></td>
                                        <td><?php echo $order['TrackingNo']; ?></td>
                                        <td><?php
                                        // get all products in the order
                                        $productQuery = $mysql->prepare('CALL GetProductsInOrder(:ID)');
                                        $productQuery->bindParam(':ID', $order['OrderID'], PDO::PARAM_INT);
                                        $productQuery->execute();
                                        $products = $productQuery->fetchAll(PDO::FETCH_ASSOC);
                                        $productQuery->closeCursor();
                                        if ($productQuery->rowCount() > 0): ?>

                                                <table class="order-history">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($products as $product): ?>
                                                            <tr>
                                                                <td><?php echo $product["ProductName"] ?></td>
                                                                <td><?php echo $product["Price"] ?></td>
                                                                <td><?php echo $product["Quantity"] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $order["OrderDate"] ?>
                                        </td>
                                        <td>
                                            <?php if (strtolower($order['OrderStatus']) === 'delivered'): ?>
                                                <form action="orderHistory.php" method="POST">
                                                    <input type="hidden" name="orderID" value="<?php echo $order['OrderID']; ?>">
                                                    <button class="button" type="submit" class="return-button">Return</button>
                                                </form>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="8">No orders to display</td>
                                </tr>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <br>
                    <h2>Online Returns</h2>
                    <table class="order-history">
                        <thead>
                            <tr>
                                <th>Return ID</th>
                                <th>Amount</th>
                                <th>Reason</th>
                                <th>Customer Information</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            //echo !empty($OnlineReturns
                            if (isset($OnlineReturns[0]['OnlineReturnID'])):
                                foreach ($OnlineReturns as $order): ?>
                                    <tr>
                                        <td><?php echo $order['OnlineReturnID']; ?></td>
                                        <td><?php echo '£' . number_format($order['AmountToReturn'], 2); ?></td>
                                        <td><?php echo $order['Reason']; ?></td>
                                        <td><?php
                                        $queryCustomer = "SELECT DISTINCT FirstName,LastName, CustomerID FROM EmployeeCustomerView WHERE CustomerID = :ID";
                                        $stmtCustomer = $mysql->prepare($queryCustomer);
                                        $stmtCustomer->execute(["ID" => $order['Customer_CustomerID']]);

                                        // fetch customer information
                                        $customer = $stmtCustomer->fetch(PDO::FETCH_ASSOC); ?>
                                            <table class="order-history">
                                                <thead>
                                                    <tr>
                                                        <th>First Name</th>
                                                        <th>Surname</th>
                                                        <th>ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo $customer["FirstName"] ?></td>
                                                        <td><?php echo $customer["LastName"] ?></td>
                                                        <td><?php echo $customer["CustomerID"] ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="8">No orders to display</td>
                                </tr>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!--Shop Purchases-->
                    <br>
                    <h2>Shop Purchases</h2>
                    <table class="order-history">
                        <thead>
                            <tr>
                                <th>Purchase ID</th>
                                <th>Customer Information</th>
                                <th>Price</th>
                                <th>Purchse Date</th>
                                <th>Products</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($ShopOrders[0]['PurchaseID'])):
                                foreach ($ShopOrders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['PurchaseID']; ?></td>
                                        <td>
                                            <?php
                                            $queryCustomer = "SELECT DISTINCT FirstName,LastName, CustomerID FROM EmployeeCustomerView WHERE CustomerID = :ID";
                                            $stmtCustomer = $mysql->prepare($queryCustomer);
                                            $stmtCustomer->execute(["ID" => $order['shopCustomer']]);

                                            // fetch customer information
                                            $customer = $stmtCustomer->fetch(PDO::FETCH_ASSOC);
                                            ?>
                                            <table class="order-history">
                                                <thead>
                                                    <tr>
                                                        <th>First Name</th>
                                                        <th>Surname</th>
                                                        <th>ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo $customer["FirstName"] ?></td>
                                                        <td><?php echo $customer["LastName"] ?></td>
                                                        <td><?php echo $order['shopCustomer'] ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><?php echo '£' . number_format($order['shopPrice'], 2); ?></td>
                                        <td><?php echo $order['PurchaseDate']; ?></td>
                                        <td><?php
                                        // get all products in the order
                                        $productQuery = $mysql->prepare('CALL GetProductsInShopPurchase(:ID)');
                                        $productQuery->bindParam(':ID', $order['PurchaseID'], PDO::PARAM_INT);
                                        $productQuery->execute();
                                        $products = $productQuery->fetchAll(PDO::FETCH_ASSOC);
                                        $productQuery->closeCursor();
                                        if ($productQuery->rowCount() > 0): ?>

                                                <table class="order-history">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($products as $product): ?>
                                                            <tr>
                                                                <td><?php echo $product["ProductName"] ?></td>
                                                                <td><?php echo $product["Price"] ?></td>
                                                                <td><?php echo $product["spQuantity"] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="8">No orders to display</td>
                                </tr>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>


                    <!-- Shop Returns --->
            <br>
            <h2>Shop Returns</h2>
            <table class="order-history">
                <thead>
                    <tr>
                        <th>Return ID</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Customer Information</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($ShopReturns[0]['ShopReturnID'])):
                        foreach ($ShopReturns as $order): ?>
                    <tr>
                        <td>
                            <?php echo $order['ShopReturnID']; ?>
                        </td>
                        <td>
                            <?php echo '£' . number_format($order['ShopAmountToReturn'], 2); ?>
                        </td>
                        <td>
                            <?php echo $order['shopReason']; ?>
                        </td>
                        <td>
                            <?php
                            $queryCustomer = "SELECT DISTINCT FirstName,LastName, CustomerID FROM EmployeeCustomerView WHERE CustomerID = :ID";
                            $stmtCustomer = $mysql->prepare($queryCustomer);
                            $stmtCustomer->execute(["ID" => $order['ShopReturnCustomer']]);

                            // fetch customer information
                            $customer = $stmtCustomer->fetch(PDO::FETCH_ASSOC); ?>
                            <table class="order-history">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Surname</th>
                                        <th>ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $customer["FirstName"] ?>
                                        </td>
                                        <td>
                                            <?php echo $customer["LastName"] ?>
                                        </td>
                                        <td>
                                            <?php echo $order['ShopReturnCustomer'] ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach;
                    else: ?>
                    <tr>
                        <td colspan="8">No orders to display</td>
                    </tr>
                    </tr>
                    <?php endif; ?>
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

<?php include 'footer.html'; ?>