<?php
include 'db.php';
include 'navBar.php';

if (isset($_SESSION['LoggedIn']) && ($_SESSION["LoggedIn"]==="Shop Assistant" || $_SESSION["LoggedIn"]=== "Supervisor" || $_SESSION["LoggedIn"]==="Manager" || $_SESSION['LoggedIn']=== "Assistant Manager")):
    if (isset($_SESSION['ID'])) {
        $employeeID = $_SESSION['ID'];

        // fetching shop ID where employee works
        // use stored procedure to get shop worked at
        // because this is needed to create the view based on the Shop ID, it needs to happen from the actual tables rather than the view
        $queryShopID = "CALL GetShopWorkedAt(:employeeID)";
        $stmtShopID = $mysql->prepare($queryShopID);
        $stmtShopID->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
        $stmtShopID->execute();
        $shopID = $stmtShopID->fetchColumn();
        $stmtShopID->closeCursor();
        $shopID = "1";
        // if shop ID exiss
        if ($shopID) {
            //detailed stock info
            

            // handling stock order request
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(!isset($_SESSION['stockSearch'])){
                    $_SESSION['stockSearch'] = "";
                }

                if(isset($_POST['orderProductID'])){
                    $orderProductID = $_POST['orderProductID'];
                    $orderQuantity = $_POST['orderQuantity'];

                    // inserting new supply order
                    $queryOrder = "INSERT INTO SupplyOrder (ProductType, Supplier_SupplierID, ShopID)
                            VALUES ((SELECT Type FROM Product WHERE ProductID = :productID),
                                    (SELECT SupplierID FROM Supplier WHERE Name = (SELECT Supplier FROM Product WHERE ProductID = :productID)),
                                    :shopID);";
                    $stmtOrder = $mysql->prepare($queryOrder);
                    $stmtOrder->bindParam(':productID', $orderProductID, PDO::PARAM_INT);
                    $stmtOrder->bindParam(':shopID', $shopID, PDO::PARAM_INT);
                    $stmtOrder->execute();
                    $stmtOrder->closeCursor();


                    $supplyOrderID = $mysql->lastInsertId();

                    // linking product to supply order
                    $queryProductOrder = "INSERT INTO Product_has_SupplyOrder (Product_ProductID, SupplyOrder_SupplyOrderID)
                                    VALUES (:productID, :supplyOrderID)";
                    $stmtProductOrder = $mysql->prepare($queryProductOrder);
                    $stmtProductOrder->bindParam(':productID', $orderProductID, PDO::PARAM_INT);
                    $stmtProductOrder->bindParam(':supplyOrderID', $supplyOrderID, PDO::PARAM_INT);
                    $stmtProductOrder->execute();


                    //updating product availability
                    $queryUpdateAvailability = "UPDATE ProductAvailability
                                            SET Availability = Availability + :orderQuantity
                                            WHERE Product_ProductID = :productID AND Shop_ShopID = :shopID";
                    $stmtUpdateAvailability = $mysql->prepare($queryUpdateAvailability);
                    $stmtUpdateAvailability->bindParam(':orderQuantity', $orderQuantity, PDO::PARAM_INT);
                    $stmtUpdateAvailability->bindParam(':productID', $orderProductID, PDO::PARAM_INT);
                    $stmtUpdateAvailability->bindParam(':shopID', $shopID, PDO::PARAM_INT);
                    $stmtUpdateAvailability->execute();


                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }

                if(isset($_POST['reset'])){
                    unset($_SESSION['stockSearch']);
                    unset($_POST['stockSearch']);
                    unset($_POST['reset']);
                }

                if(isset($_POST['stockSearch']) && $_POST['stockSearch'] != ""){
                    $_SESSION['stockSearch'] = $_POST['stockSearch'];

                }                
            }
            if (isset($_SESSION['stockSearch']) && $_SESSION['stockSearch'] != "") {
                $search = $_SESSION['stockSearch'];
                $queryStock = "SELECT DISTINCT ProductName, ProductID, Availability, Type, Brand, Supplier FROM ShopEmployeeStockView WHERE ProductName LIKE '%$search%' OR Type LIKE '%$search%' OR Brand LIKE '%$search%' OR Supplier LIKE '%$search%'";
            }
            else{
                $queryStock = "SELECT DISTINCT ProductName, ProductID, Availability, Type, Brand, Supplier FROM ShopEmployeeStockView";
            }

            $stmtStock = $mysql->prepare($queryStock);
                $stmtStock->execute();
                $stockData = $stmtStock->fetchAll(PDO::FETCH_ASSOC);
                $stmtStock->closeCursor();

        }
        else {
            echo "Shop information not found for the logged-in employee.";
        } 


    } 
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Stock Dashboard</title>
        <link rel="stylesheet" href="style.css">

        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f7f6;
            }

            .container {
                width: 85%;
                max-width: 1400px;
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
            <h1>Stock Dashboard</h1>
            <form method="post"><input id="stockSearchInput" placeholder="Search for stock item"><button
            id="stockSearchButton" class="button" >Search</button></form>
            <br><form method='post'><button class='button' id = 'stockResetSearch'>Reset Search</button></form></br>
            <!-- Stock Information Section -->
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Supplier</th>
                        <th>Availability</th>
                        <th>Order More</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (!empty($stockData)): ?>
                        <?php foreach ($stockData as $stock): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($stock['ProductID']); ?></td>
                                <td><?php echo htmlspecialchars($stock['ProductName']); ?></td>
                                <td><?php echo htmlspecialchars($stock['Type']); ?></td>
                                <td><?php echo htmlspecialchars($stock['Brand']); ?></td>
                                <td><?php echo htmlspecialchars($stock['Supplier']); ?></td>
                                <td><?php echo htmlspecialchars($stock['Availability']); ?></td>
                                <td>

                                    <form class="order-form" method="post" action="">
                                        <input type="hidden" name="orderProductID"
                                            value="<?php echo htmlspecialchars($stock['ProductID']); ?>">
                                        <input type="number" name="orderQuantity" min="1" value="1" required>
                                        <button class="button" type="submit">Order</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    <?php else: ?>
                        <tr>
                            <td colspan="7">No stock information available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>



        </div>
    </body>
    <?php else: ?>
    <div class="container">
        <h2>Unauthorised Access</h2>
        <p>You are not authorised to view this page. Return to homepage: <a style="text-decoration:underline"
                href="index.php">Back to Homepage</a></p>
    </div>
    <?php endif; ?>
    <script type="text/javascript" src="script.js"></script>
    </html>



<?php include 'footer.php'; ?>