<?php
include 'db.php';
include 'navBar.php'; 

// getting total sales report
$querySales = "SELECT 
    Product.ProductName,
    COUNT(ShopPurchase_has_Product.Product_ProductID) AS TotalPurchases, 
    SUM(ShopPurchase.Price) AS TotalSales
FROM Product
INNER JOIN ShopPurchase_has_Product ON Product.ProductID = ShopPurchase_has_Product.Product_ProductID
INNER JOIN ShopPurchase ON ShopPurchase.PurchaseID = ShopPurchase_has_Product.Purchase_PurachseID
GROUP BY Product.ProductName";
$stmtSales = $mysql->prepare($querySales);
$stmtSales->execute();
$salesData = $stmtSales->fetchAll(PDO::FETCH_ASSOC);

// gettting supplier information
$querySuppliers = "SELECT 
    SupplyOrder.SupplyOrderID,
    SupplyOrder.ProductType,
    Supplier.Name AS SupplierName,
    SupplyOrder.ShopID
FROM SupplyOrder
INNER JOIN Supplier ON SupplyOrder.Supplier_SupplierID = Supplier.SupplierID";
$stmtSuppliers = $mysql->prepare($querySuppliers);
$stmtSuppliers->execute();
$supplierData = $stmtSuppliers->fetchAll(PDO::FETCH_ASSOC);

// getting low stock products
$queryLowStock = "SELECT 
    Product.ProductName,
    ProductAvailability.Availability
FROM Product
INNER JOIN ProductAvailability ON Product.ProductID = ProductAvailability.Product_ProductID
WHERE ProductAvailability.Availability < 5"; //threshold for low stock
$stmtLowStock = $mysql->prepare($queryLowStock);
$stmtLowStock->execute();
$lowStockData = $stmtLowStock->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Manager Dashboard</title>

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

        th, td {
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
    <h1>Manage Store <span>Dashboard</span></h1>

    <br></br>



        <!-- sales Report section -->

        <div class="section">
            <h2>Sales Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Total Quantity Sold</th>
                        <th>Total Sales (£)</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach ($salesData as $sale): ?>
                        <tr>
                            <td><?php echo $sale['ProductName']; ?></td>
                            <td><?php echo $sale['TotalPurchases']; ?></td>
                            <td><?php echo number_format($sale['TotalSales'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- supplier information section -->
        <div class="section">
            <h2>Supplier Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Type</th>
                        <th>Supplier Name</th>
                        <th>Shop ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($supplierData as $supplier): ?>
                        <tr>
                            <td><?php echo $supplier['SupplyOrderID']; ?></td>
                            <td><?php echo $supplier['ProductType']; ?></td>
                            <td><?php echo $supplier['SupplierName']; ?></td>
                            <td><?php echo $supplier['ShopID']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- low stock section -->
        <div class="section">
            <h2>Low Stock Items</h2>
            <table>
                <thead>
                    <tr>
                    
                        <th>Product Name</th>
                        <th>Quantity In Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lowStockData as $stock): ?>
                        <tr>
                            <td><?php echo $stock['ProductName']; ?></td>
                            <td><?php echo $stock['Availability']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


        </div>
    </div>


</body>
</html>

<?php include 'footer.php'; ?>
