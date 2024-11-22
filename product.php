<?php include('navBar.php'); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Name</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="heroProductContainer">
        <h1 id="heroProductName">Product Name</h1>
        <div id="heroProductImages">
            <img id="heroProductHeroImage" src="placeholder-image.png">
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
        <div id="heroProductInfo"></div>
    </div>
</body>
<script src="script.js"></script>

</html>