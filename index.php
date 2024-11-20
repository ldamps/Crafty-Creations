<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="topnav">
        <a class="company">Crafty Creations</a>
        <ul>
            <a id='logInText' class="button" href="loginPage.html">log in | sign up</a>
        </ul>
    </nav>

    <nav class="selection">
        <input type="text" placeholder="Search..">

        <div class="yarn button">
            <button class="dropbtn">Yarn
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="yarn-selection">
                <a href="#">Show All</a>
                <a href="#">Acrylic Yarn</a>
            </div>
        </div>

        <div class="fabric button">
            <button class="dropbtn">Fabric
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="fabric-selection">
                <a href="#">Show All</a>
            </div>
        </div>

        <div class="paint button">
            <button class="dropbtn">Paint
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="paint-selection">
                <a href="#">Show All</a>
            </div>
        </div>
    </nav>

</body>


<?php
require 'db.php'; 

class Product {
    public $name;
    public $description;
    public $price;

    public $brand;
    public $image;
}

$products = array();

$query = $mysql->prepare("SELECT ProductName,ProductDescription,Price,Brand FROM Product");
$query->execute();
$result = $query->fetchAll();

for ($i = 0; $i < count($result); $i++) {
    $product = new Product();
    $product->name = $result[$i]["ProductName"];
    $product->description = $result[$i]["ProductDescription"];
    $product->price = $result[$i]["Price"];
    $product->brand = $result[$i]["Brand"];
    $products[$i] = $product; 
}

echo "<div id='productContainer'>";
echo "<div>";
echo '<input type="hidden" id="mydata">';


    for($i = 0; $i < count($products); $i++) {
        echo "<div class='product' id=$i>";
        echo "<img class=productImage src=''/img>";
        echo "<h1 class = productName >".$products[$i]->name."</h1>";
        echo "<p class = productInfo>".$products[$i]->description."</p>";
        echo "<p class = productInfo>£".$products[$i]->price."</p>";
        echo "<p class = productInfo>Brand: ".$products[$i]->brand."</p>";
        echo "</div>";
        if (($i+1) % 3 == 0) {
            echo "</div>";
            echo "<div>";
        }
    }

echo "</div>";

// load more does not work
echo "<button id='loadMore' class='button' >Load More</button>";

?>

<script type = "text/javascript" src="script.js"></script>
</html>