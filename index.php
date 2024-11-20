<?php
    require 'db.php'; 
    include 'index.html';
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // initialize variable on first run
        // session variables - https://www.w3schools.com/php/php_sessions.asp
        if (!isset($_SESSION['currentlyLoaded'])) {
            $_SESSION['currentlyLoaded'] = 6; // show 6 at a time but can make bigger later
        }

        // if the button is pressed, load another 6
        if (isset($_POST['loadMore'])) {
            $_SESSION['currentlyLoaded'] += 6; // increment value by 6

        }

        // remove all extras
        if (isset($_POST['showLess'])) {
            $_SESSION['currentlyLoaded'] = 6; // set back to only 6
        }
    }


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
    
    
        for($i = 0; $i <($_SESSION['currentlyLoaded']); $i++) {
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
    
    echo "<div>";
    echo "<center><form method='post'><button class = 'button'  type='submit' name='loadMore'>Show 6 More</button></form></center>";
    echo "<center><form method='post'> <button class='button' type='submit' name='showLess'>Show Less</button></form></center>";
    echo "</div>";
    include 'footer.html';

?>
<script type="text/javascript" src="script.js"></script>
