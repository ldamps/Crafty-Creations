<?php
    require 'db.php';
    include 'index.html';

    // get number of products from the database
    $res = $mysql->query("SELECT COUNT(*) FROM Product");
    $numProducts = $res->fetchColumn();
    // echo $numProducts;

    class Product {
        public $name;
        public $description;
        public $price;
    
        public $brand;
        public $image;

        public $id;
    }
    
    $products = array();
    
    $query = $mysql->prepare("SELECT ProductName,ProductDescription,Price,Brand,ProductID FROM Product");
    $query->execute();
    $result = $query->fetchAll();
    
    foreach($result as $item){
        $product = new Product();
        $product->name = $item["ProductName"];
        $product->description = $item["ProductDescription"];
        $product->price = $item["Price"];
        $product->brand = $item["Brand"];
        $product->id = $item["ProductID"];
        array_push($products, $product);  

    }


    $increment = 40;
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // initialize variable on first run
        // session variables - https://www.w3schools.com/php/php_sessions.asp
        if (!isset($_SESSION['currentlyLoaded'])) {
            $_SESSION['currentlyLoaded'] = 6; // show 6 at a time but can make bigger later
        }

        // if the button is pressed, load another 6
        if (isset($_POST['loadMore'])) {

            // check that incrementing won't put over the number of available products
            if ($_SESSION['currentlyLoaded']+$increment <= $numProducts)
            {
                $_SESSION['currentlyLoaded'] += $increment; // increment value by 6
            }
            else
            {
                // only load as many as will not go over the number of products in the table
                while ($_SESSION['currentlyLoaded'] < $numProducts)
                {
                    $_SESSION['currentlyLoaded'] += 1;
                }
            }
        }

        // Searching for a product. 
        $product = null; // This should be overwritten anyway. This is to just make sure it is empty, and doesn't have a Product class in it. 
        $searchProducts = array(); // New array to hold the products that have been searched for. 

        if (isset($_POST['Search'])){
            foreach ($products as $product){
                $class_vars = $product;
                if (str_contains($product->name, $_POST['Search'])){
                    array_push($searchProducts, $product);
                }
            }
        }

        // remove all extras
        if (isset($_POST['showLess'])) {
            $_SESSION['currentlyLoaded'] = 6; // set back to only 6
        }

        
    }
    // echo "<center><form method='post'> <button class='button' type='submit' name='Search'>Search</button></form></center>";

    echo "<div id='productContainer'>";
    echo "<div>";
    echo '<input type="hidden" id="mydata">';
    
    
    
    for($i = 0; $i <($_SESSION['currentlyLoaded']); $i++) {
        if (isset($_POST['Search'])){
            $products_to_show = $searchProducts;
        }
        else{
            $products_to_show = $products;
        }

        echo "<div class='product' id=$i>";
        echo "<img class=productImage src=''/img>";
        echo "<h1 class = productName >".$products_to_show[$i]->name."</h1>";
        echo "<p class = productInfo>".$products_to_show[$i]->description."</p>";
        echo "<p class = productInfo>£".$products_to_show[$i]->price."</p>";
        echo "<p class = productInfo>Brand: ".$products_to_show[$i]->brand."</p>";
        echo "<p class = productInfo>Brand: ".$products_to_show[$i]->id."</p>";
        echo "</div>";
        if (($i+1) % 3 == 0) {
            echo "</div>";
            echo "<div>";
        }
    }
    
    echo "</div>";
    

    // only display load more if all products have not already been loaded
    if($_SESSION){
            
    }

    if ($_SESSION['currentlyLoaded'] < $numProducts)
    {
        echo "<center><form method='post'><button class = 'button'  type='submit' name='loadMore'>Show 6 More</button></form></center>";
    }
    else
    {
        echo "<center><p>All Items Loaded</p></center>";
    }

    if($_SESSION['currentlyLoaded'] > 6){ // only show the show less button if more than 6 items are loaded 
        echo "<center><form method='post'> <button class='button' type='submit' name='showLess'>Show Less</button></form></center>";
    }

    
    
    
    include 'footer.html';

?>
<script type="text/javascript" src="script.js"></script>
