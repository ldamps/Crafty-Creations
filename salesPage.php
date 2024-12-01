<?php

include 'db.php';
include 'navBar.php';

if (isset($_SESSION['LoggedIn']) && ($_SESSION["LoggedIn"] === "Shop Assistant" || $_SESSION["LoggedIn"] === "Supervisor" || $_SESSION["LoggedIn"] === "Manager" || $_SESSION['LoggedIn'] === "Assistant Manager")):
    
    $role = $_SESSION["LoggedIn"];
    $userID = $_SESSION["ID"];

    $totalOnlineSales = 0;
    $totalShopSales = 0;

    if ($role === "Supervisor" || $role === "Shop Assistant") {
        
        //online sales query
        $queryOnlineSales = "SELECT SUM(COALESCE(Price, 0)) AS TotalOnlineSales 
                             FROM (
                                 SELECT DISTINCT OrderID, Price 
                                 FROM ShopEmployeeView 
                                 WHERE OrderStatus IN ('Processing', 'Dispatched', 'Delivered')
                             ) AS UniqueOrders";

        $stmtOnlineSales = $mysql->prepare($queryOnlineSales);
        $stmtOnlineSales->execute();
        $totalOnlineSales = $stmtOnlineSales->fetch(PDO::FETCH_ASSOC)['TotalOnlineSales'] ?? 0;

        //shop sales query
        $queryShopSales = "SELECT SUM(COALESCE(shopPrice, 0)) AS TotalShopSales 
                           FROM (
                               SELECT DISTINCT PurchaseID, shopPrice 
                               FROM ShopEmployeeView 
                               WHERE OrderStatus IN ('Processing', 'Dispatched', 'Delivered')
                           ) AS UniqueShopPurchases";

        $stmtShopSales = $mysql->prepare($queryShopSales);
        $stmtShopSales->execute();
        $totalShopSales = $stmtShopSales->fetch(PDO::FETCH_ASSOC)['TotalShopSales'] ?? 0;
    } else if ($role === "Manager" || $role === "Assistant Manager") {

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
    <body>
        <div class="container">
            <h1>Sales Summary</h1>
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
        </div>
    </body>
    </html>


<?php else: ?>
    <div class="container">
        <h2>Unauthorized Access</h2>
        <p>You are not authorized to view this page. Return to homepage: <a href="index.php">Back to Homepage</a></p>
    </div>
<?php endif; ?>
