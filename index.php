<?php
require 'db.php'; 

include 'index.html';

class Product {
    public $name;
    public $description;
    public $price;
    public $image;
}

$products = array();

$query = $mysql->prepare("SELECT ProductName,ProductDescription FROM Product");
$query->execute();
$result = $query->fetchAll();

for ($i = 0; $i < count($result); $i++) {
    $product = new Product();
    $product->name = $result[$i]["ProductName"];
    $product->description = $result[$i]["ProductDescription"];
    $products[$i] = $product; 
}
echo "<div id='productContainer'>";
echo "<div>";

    for($i = 0; $i < count($products); $i++) {
        
        echo "<div class='product'>";
        echo "<img src='placeholder-image.png'";
        echo "<h1>".$products[$i]->name."</h1>";
        echo "<p>".$products[$i]->description."</p>";
        echo "<p>£".$products[$i]->price."</p>";
        echo "</div>";
        if (($i+1) % 3 == 0) {
            echo "</div>";
            echo "<div>";
        }
    }

echo "</div>";
include 'footer.html';
?>

<script type = "text/javascript" src="script.js"></script>

