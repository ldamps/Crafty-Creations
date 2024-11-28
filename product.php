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
// customers see from logged out view
if (isset($_SESSION['LoggedIn']) && $_SESSION["LoggedIn"] == "customer") {
    $role = $_SESSION["LoggedIn"];
    // fetch the product details
    $query = "SELECT DISTINCT ProductName, ProductDescription, Price, Brand FROM LoggedOutView WHERE ProductID = :productID";
    $stmt = $mysql->prepare($query);
    $stmt->execute([':productID' => $productID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} else if (isset($_SESSION['LoggedIn'])) {
    // all shop employees see from the shop stock view
    $query = "SELECT DISTINCT ProductName, ProductDescription, Price, Brand, Supplier, Availability FROM ShopEmployeeStockView WHERE ProductID = :productID";
    $stmt = $mysql->prepare($query);
    $stmt->execute([':productID' => $productID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // take from logged out view
    $role = "loggedOut";
    // fetch the product details
    $query = "SELECT DISTINCT ProductName, ProductDescription, Price, Brand FROM LoggedOutView WHERE ProductID = :productID";
    $stmt = $mysql->prepare($query);
    $stmt->execute([':productID' => $productID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}


// if the product exists
if ($product) {
    $productName = $product['ProductName'];
    $productDescription = $product['ProductDescription'];
    $productPrice = $product['Price'];
    $productBrand = $product['Brand'];
    $imagePath = "images/" . $productID . ".png";

    if ($role === "customer" || $role === "loggedOut") {
        // query to get the total availability across all stores
        $availabilityQuery = "SELECT SUM(Availability) AS totalAvailability
        FROM LoggedOutView
        WHERE Product_ProductID = :productID";
        $stmt = $mysql->prepare($availabilityQuery);
        $stmt->execute([':productID' => $productID]);
        $availability = $stmt->fetch(PDO::FETCH_ASSOC);
        // total availability value
        $totalAvailability = $availability ? $availability['totalAvailability'] : 0;
    } else {
        $productSupplier = $product['Supplier'];
        $productAvailability = $product['Availability'];
    }



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
        <div id="heroProductInfo">
            <div id="heroProductDescription">
                <h2>Product Description</h2>
                <p><?php echo $productDescription; ?></p>
            </div>
            <div id="productPrice">
                <h2>Price: </h2>
                <?php echo "<p>£ ";
                echo number_format($productPrice, 2);
                "<p>" ?></h2>
            </div>
            <div id="productBrand">
                <h2>Brand:</h2>
                <?php echo "<p> $productBrand " ?></p>
            </div>
            <!-- this could maybe only be displayed if the stock is low-->
            <?php if ($role === "customer" || $role === "loggedIn"): ?>
                <div id="productAvailability">
                    <h2>In stock: </h2>
                    <?php if ($totalAvailability < 20):
                        echo "<p class='urgent'>Only $totalAvailability left in stock!!!</p>";
                    else:
                        echo "<p>In stock</p>";
                    endif ?>
                    <!-- Shop employees can see everything available at their store and also supplier -->
                </div>
            <?php else: ?>
                <div id="productBrand">
                    <h2>Supplier:</h2>
                    <?php echo "<p> $productSupplier " ?></p>
                </div>
                <div id="productBrand">
                    <h2>In Stock at Store:</h2>
                    <?php echo "<p> $productAvailability " ?></p>
                </div>
        </div>
            <?php endif; ?>
    </div>
    <?php if ($role === "customer" || $role === "loggedOut"): ?>
        <div id="heroProductPurchasing">
            <div id="purchasingBox">
                <div id="quantitySelector">
                    <div id="quantityDown" class="button" onclick="quantityAdjust(event)">
                        <h1>-</h1>
                    </div>
                    <div id="quantity">
                        <h1 id="quantityText">1</h1>
                    </div>
                    <div id="quantityUp" class="button" onclick="quantityAdjust(event)">
                        <h1>+</h1>
                    </div>
                </div>

                <form action="basket.php" method="POST">
                    <input type="hidden" name="productID" value="<?php echo $productID; ?>">
                    <input type="hidden" name="quantity" id="quantityInput" value="1">

                    <!-- works with a button-->
                    <button type="submit" id="addToCart" class="button">
                        <h1>add to cart</h1>
                    </button>
                    <div id="buy" class="button">
                        <h1>buy now</h1>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</body>
<script src="script.js"></script>

</html>

<?php include 'footer.html'; ?>