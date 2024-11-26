<?php
include 'db.php';
include 'navBar.php'; 

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
    <h1>Stock Levels <span>Dashboard</span></h1>

    <br></br>





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
