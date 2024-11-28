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
    <h1>Sales <span>Dashboard</span></h1>

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

</body>
</html>

<?php include 'footer.html'; ?>
