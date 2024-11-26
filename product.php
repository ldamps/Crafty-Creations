<?php 
include('navBar.php'); 
include('db.php');

// get the productID from the URL
if (isset($_GET['ID'])) {
    $productID = $_GET['ID'];
} else {
    // if no product is found
    header("Location: index.php");
    exit();
}

// fetch the product details
$query = "SELECT ProductName, ProductDescription, Price, Brand FROM Product WHERE ProductID = :productID";
$stmt = $mysql->prepare($query);
$stmt->execute([':productID' => $productID]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

// if the product exists
if ($product) {
    $productName = $product['ProductName'];
    $productDescription = $product['ProductDescription']; 
    $productPrice = $product['Price'];  
    $productBrand = $product['Brand'];  
    $imagePath = "images/" . $productID . ".png"; 

    // query to get the total availability across all stores
    $availabilityQuery = "SELECT SUM(Availability) AS totalAvailability
                          FROM ProductAvailability
                          WHERE Product_ProductID = :productID";
    $stmt = $mysql->prepare($availabilityQuery);
    $stmt->execute([':productID' => $productID]);
    $availability = $stmt->fetch(PDO::FETCH_ASSOC);

    // total availability value
    $totalAvailability = $availability ? $availability['totalAvailability'] : 0;
} else {
    // if the product doesn't exist, redirect to home page
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $productName; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="heroProductContainer">
        <h1 id="heroProductName"><?php echo $productName; ?></h1>
        <div id="heroProductImages">
            <img id="heroProductHeroImage" src="<?php echo $imagePath; ?>">
            <div id="heroProductOtherImagesBox">
                <img class="heroProductOtherImage" src="placeholder-image.png">
                <img class="heroProductOtherImage" src="placeholder-image.png">
                <img class="heroProductOtherImage" src="placeholder-image.png">
            </div>
        </div>
        <div id="heroProductPurchasing">
            <div id="purchasingBox">
                <div id="quantitySelector">
                    <div id="quantityDown" class="button">
                        <h1>-</h1>
                    </div>
                    <div id="quantity">
                        <h1 id="quantityText">1</h1>
                    </div>
                    <div id="quantityUp" class="button">
                        <h1>+</h1>
                    </div>
                </div>
                <div id="addToCart" class="button">
                    <h1>add to cart</h1>
                </div>
                <div id="buy" class="button">
                    <h1>buy now</h1>
                </div>
            </div>
        </div>
        <div id="heroProductInfo">
        <h2>Product Description</h2>
        <p><?php echo $productDescription; ?></p>
        </div>
        <div id="productPrice">
        <h2>Price: £<?php echo number_format($productPrice, 2); ?></h2>
        </div>
        <div id="productBrand">
            <h2><p>Brand: <?php echo $productBrand; ?></p></h2>
        </div>
        <!-- this could maybe only be displayed if the stock is low-->
        <div id="productAvailability">
            <h2>In stock: <?php echo $totalAvailability; ?> units</h2>
        </div>
    </div>
</body>
<script src="script.js"></script>

</html>