<?php
include 'db.php';
include 'navBar.php'; 



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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Supplier Dashboard</title>

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
    <h1>Supplier <span>Dashboard</span></h1>

    <br></br>


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



        </div>
    </div>


</body>
</html>
