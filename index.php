<?php include 'navBar.php'; ?>

<?php
    require 'db.php';
    include 'index.html';

    // get number of products from the database
    $res = $mysql->query("SELECT COUNT(*) FROM Product");
    $numProducts = $res->fetchColumn();
    
    $increment = 40;
    if (!isset($_SESSION)){
        session_start();  
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // initialize variable on first run
        // session variables - https://www.w3schools.com/php/php_sessions.asp
        if (!isset($_SESSION['currentlyLoaded'])) {
            $_SESSION['currentlyLoaded'] = 6; // show 6 at a time but can make bigger later
            echo "currently loaded";
        }
        if (!isset($_SESSION['Search'])) {
            $_SESSION['Search'] = "";
        }
        if (!isset($_SESSION['Narrow'])) {
            $_SESSION['Narrow'] = "";
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

        // remove all extras
        if (isset($_POST['showLess'])) {
            $_SESSION['currentlyLoaded'] = 6; // set back to only 6
        }

        if (isset($_POST['Search'])){
            $_SESSION['Search'] = $_POST['Search'];
        }
        elseif (!isset($_SESSION['Search'])){
            $_SESSION['Search'] = "";
        }
        
        if (isset($_POST['ProductSearch'])){
            $_SESSION['Narrow'] = $_POST['ProductSearch'];
        }
        elseif (!isset($_SESSION['Narrow'])){
            $_SESSION['Narrow'] = "";
        }
        
        
    }

    /* Class for all products to be made into once read in */
    class Product {
        public $name;
        public $description;
        public $price;
    
        public $brand;
        public $image;

        public $id;
    } 

    $products = array();

    //Get product from database that match the search criteria.
    if (isset($_SESSION['Search']) && $_SESSION['Search'] != "") {
        $type = $_SESSION['Search'];
        $query = $mysql->prepare("SELECT DISTINCT ProductID FROM Product WHERE ProductName LIKE '%$type%' OR Brand LIKE '%$type%'");
        $query->execute();
        $result = $query->fetchAll();
        $results = array();

        foreach($result as $item){
            $id = $item["ProductID"];
            $query = $mysql->prepare("SELECT ProductName,ProductDescription,Price,Brand,ProductID FROM Product WHERE ProductID = '$id'");
            $query->execute();
            $result = $query->fetchAll();
            
            array_push($results, ["ProductName" => $result[0]["ProductName"], "ProductDescription" => $result[0]["ProductDescription"], "Price" => $result[0]["Price"], "Brand" => $result[0]["Brand"], "ProductID" => $result[0]["ProductID"]]);
        }
    }
    //Get all products from the database of a single type.
    elseif (isset($_SESSION['Narrow']) && $_SESSION['Narrow'] != ""){
        $narrow = $_SESSION['Narrow'];
        $query = $mysql->prepare("SELECT DISTINCT ProductID FROM Product WHERE ProductName LIKE '%$narrow%'");
        $query->execute();
        $result = $query->fetchAll();
        $results = array();

        foreach($result as $item){
            $id = $item["ProductID"];
            $query = $mysql->prepare("SELECT ProductName,ProductDescription,Price,Brand,ProductID FROM Product WHERE ProductID = '$id'");
            $query->execute();
            $result = $query->fetchAll();
            
            array_push($results, ["ProductName" => $result[0]["ProductName"], "ProductDescription" => $result[0]["ProductDescription"], "Price" => $result[0]["Price"], "Brand" => $result[0]["Brand"], "ProductID" => $result[0]["ProductID"]]);
        }
    }
    //Otherwise, get all products from the database.
    else{
        $query = $mysql->prepare("SELECT ProductName,ProductDescription,Price,Brand,ProductID FROM Product");
        $query->execute();
        $results = $query->fetchAll();
    }

   
    //Create a product object for each product and add it to the array.
    foreach($results as $item)
    {
        $product = new Product();
        $product->name = $item["ProductName"];
        $product->description = $item["ProductDescription"];
        $product->price = $item["Price"];
        $product->brand = $item["Brand"];
        $product->id = $item["ProductID"];
        array_push($products, $product);  
    }
    echo "<div id='productContainer'>";
    echo "<div>";
    echo '<input type="hidden" id="mydata">';

    if ($_SESSION['currentlyLoaded'] > count($products)){
       $maxShow = count($products);
    }
    else{
        $maxShow = $_SESSION['currentlyLoaded'];
    }

    //Display each product in a 3*n grid. N is the number of products allowed to be displayed by 'currentlyLoaded'.
    for($i = 0; $i <($maxShow); $i++) {
        // echo $_SESSION['currentSearch'];
        $PID = $products[$i]->id - 1;
        echo "<div class='product' id=$PID>";
        echo "<img class=productImage src=''/img>";
        echo "<h1 class = productName >".$products[$i]->name."</h1>";
        echo "<p class = productInfo>".$products[$i]->description."</p>";
        echo "<p class = productInfo>£".$products[$i]->price."</p>";
        echo "<p class = productInfo>Brand: ".$products[$i]->brand."</p>";
        echo "<p class = productInfo>Brand: ".$products[$i]->id."</p>";
        echo "</div>";
        
        if (($i+1) % 3 == 0) {
            echo "</div>";
            echo "<div>";
        }
    }
    
    echo "</div>";
    

    // only display load more if all products have not already been loaded

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
