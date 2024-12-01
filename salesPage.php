<?php

include 'db.php';
include 'navBar.php';

if (isset($_SESSION['LoggedIn']) && ($_SESSION["LoggedIn"] === "Manager" || $_SESSION['LoggedIn'] === "CEO" || $_SESSION['LoggedIn'] === "Assistant Manager" || ($_SESSION["LoggedIn"] === "Human Resources") || ($_SESSION["LoggedIn"] === "Payroll") || ($_SESSION["LoggedIn"] === "IT Support") || ($_SESSION["LoggedIn"] === "Administration") || ($_SESSION["LoggedIn"] === "Website Development"))):

    $role = $_SESSION["LoggedIn"];
    $userID = $_SESSION["ID"];

    $totalOnlineSales = 0;
    $totalShopSales = 0;

    if ($role === "CEO" || $role === "Website Development" || $role === "Human Resources" || $role === "Payroll" || $role === "IT support" || $role === "Administration") {
        $position = "office";


    } else if ($role === "Manager" || $role === "Assistant Manager") {
        $position = "manager";
        $queryOnlineSales = "SELECT SUM(COALESCE(Price, 0)) AS TotalOnlineSales 
                             FROM (
                                 SELECT DISTINCT OrderID, Price 
                                 FROM ManagerView 
                                 WHERE OrderStatus IN ('Processing', 'Dispatched', 'Delivered')
                             ) AS UniqueOrders";

        $stmtOnlineSales = $mysql->prepare($queryOnlineSales);
        $stmtOnlineSales->execute();
        $totalOnlineSales = $stmtOnlineSales->fetch(PDO::FETCH_ASSOC)['TotalOnlineSales'] ?? 0;


        $queryShopSales = "SELECT SUM(COALESCE(shopPrice, 0)) AS TotalShopSales 
                           FROM (
                               SELECT DISTINCT PurchaseID, shopPrice 
                               FROM ManagerView 
                               WHERE OrderStatus IN ('Processing', 'Dispatched', 'Delivered')
                           ) AS UniqueShopPurchases";

        $stmtShopSales = $mysql->prepare($queryShopSales);
        $stmtShopSales->execute();
        $totalShopSales = $stmtShopSales->fetch(PDO::FETCH_ASSOC)['TotalShopSales'] ?? 0;
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Sales Summary</title>
    </head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>

    <body>
        <div class="container">
            <h1>Sales Summary</h1>
            <?php if ($position === 'manager'): ?>
                <table>
                    <thead>

                        <tr>
                            <th>Category</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Online Orders</td>
                            <td><?php echo '£' . number_format($totalOnlineSales, 2); ?></td>
                        </tr>
                        <tr>
                            <td>In-Shop Purchases</td>
                            <td><?php echo '£' . number_format($totalShopSales, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php else:
                for ($i = 1; $i < 11; $i++):
                    // get the city of the current shop
                    $queryShopName = "SELECT DISTINCT City FROM OfficeEmployeeView WHERE ShopID = :currentShop";
                    $stmtShopName = $mysql->prepare($queryShopName);
                    //$stmtEmployees->bindParam(':shopID', $shopID, PDO::PARAM_INT);
                    $stmtShopName->execute(['currentShop' => $i]);
                    $shopName = $stmtShopName->fetchColumn(); ?>
                    <?php echo "<br><h1>$shopName Shop</h1>";

                    //online sales query for each shop
                    $queryOnlineSales = "SELECT SUM(COALESCE(Price, 0)) AS TotalOnlineSales 
                    FROM (
                        SELECT DISTINCT OrderID, Price 
                        FROM OfficeEmployeeView 
                        WHERE OrderStatus IN ('Processing', 'Dispatched', 'Delivered') AND Shop_ShopID = :shopID
                    ) AS UniqueOrders";

                    //shop sales query for each shop
                    $queryShopSales = "SELECT SUM(COALESCE(shopPrice, 0)) AS TotalShopSales 
                    FROM (
                        SELECT DISTINCT PurchaseID, shopPrice 
                        FROM OfficeEmployeeView
                        WHERE purchaseShopID = :currentShop
                    ) AS UniqueShopPurchases";

                    $stmtShopSales = $mysql->prepare($queryShopSales);
                    $stmtShopSales->execute(['currentShop' => $i]);
                    $totalShopSales = $stmtShopSales->fetch(PDO::FETCH_ASSOC)['TotalShopSales'] ?? 0;

                    $stmtOnlineSales = $mysql->prepare($queryOnlineSales);
                    $stmtOnlineSales->execute(["shopID" => $i]);
                    $totalOnlineSales = $stmtOnlineSales->fetch(PDO::FETCH_ASSOC)['TotalOnlineSales'] ?? 0; ?>
                    <table>
                        <thead>

                            <tr>
                                <th>Category</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Online Orders</td>
                                <td><?php echo '£' . number_format($totalOnlineSales, 2); ?></td>
                            </tr>
                            <tr>
                                <td>In-Shop Purchases</td>
                                <td><?php echo '£' . number_format($totalShopSales, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endfor;
            endif; ?>

        </div>
    </body>

    </html>


<?php else: ?>
    <div class="container">
        <h2>Unauthorized Access</h2>
        <p>You are not authorized to view this page. Return to homepage: <a href="index.php">Back to Homepage</a></p>
    </div>
<?php endif; ?>
<script type="text/javascript" src="script.js"></script>
<?php include 'footer.php';
?>