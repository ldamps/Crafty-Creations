<?php include 'navBar.php'; ?>

<?php
include('db.php'); 



$availableShops = [];
$userAddress = [];
$userPayments = [];



// Function to check if a product is available in a specific store
function isProductAvailableInStore($productID, $storeID, $quantity) {
    global $mysql;

    $query = "SELECT Availability FROM ProductAvailability 
              WHERE Product_ProductID = :productID AND Shop_ShopID = :storeID";
    $stmt = $mysql->prepare($query);
    $stmt->execute([':productID' => $productID, ':storeID' => $storeID]);
    $availability = $stmt->fetch(PDO::FETCH_ASSOC);

    return $availability ? $availability['Availability'] >= $quantity : false;
}

// Function to find all stores that have the products of the entire cart
function findStoresForCart($cart) {
    global $mysql;

    // get all store details
    $storesQuery = "SELECT ShopID, StreetName, City, Postcode FROM Shop";
    $storesStmt = $mysql->prepare($storesQuery);
    $storesStmt->execute();
    $stores = $storesStmt->fetchAll(PDO::FETCH_ASSOC);

    $eligibleStores = [];

    foreach ($stores as $store) {
        $storeID = $store['ShopID'];
        $allProductsAvailable = true;

        // check each products availability in the current store
        foreach ($cart as $productID => $quantity) {
            if (!isProductAvailableInStore($productID, $storeID, $quantity)) {
                $allProductsAvailable = false;
                break;
            }
        }

        // if all products avaiable then add to the list
        if ($allProductsAvailable) {
            $eligibleStores[] = $store;
        }
    }

    return $eligibleStores;
}

// find all stores for the current cart
if (!empty($_SESSION['cart'])) {
    $stores = findStoresForCart($_SESSION['cart']);
    if (!empty($stores)) {
        $availableShops = $stores;
    } else {
        $availableShops = []; // no shops have all the products
    }
}

// Display cart products
if (!empty($_SESSION['cart'])) {
    echo "<h2>Your Cart</h2><ul>";
    foreach ($_SESSION['cart'] as $productID => $quantity) {
        $productDetails = getProductDetails($productID);
        echo "<li>{$productDetails['ProductName']} - Quantity: {$quantity} - Price: {$productDetails['Price']}</li>";
    }
    echo "</ul>";

    // Display available shops
    echo "<h3>Collection Stores:</h3><ul>";
    if (!empty($availableShops)) {
        foreach ($availableShops as $shop) {
            echo "<li>" . htmlspecialchars("{$shop['StreetName']}, {$shop['City']}, {$shop['Postcode']}") . "</li>";
        }
    } else {
        echo "<li>No single store has all products available for collection.</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// checks if the 'clearCart' parameter is set in the POST request
if (isset($_POST['clearCart']) && $_POST['clearCart'] === 'true') {
    // Clear the cart by unsetting the session variable
    unset($_SESSION['cart']);
    // session_destroy(); - can use this too
    
    echo "Cart cleared."; // respond to javascript
}

// Check if the user is logged in
if (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] === "customer") {
    $userID = $_SESSION['ID'] ?? null;

    if ($userID) {

        $queryPersonal = "SELECT * From CustomerView";
        $stmtPersonal = $mysql->prepare($queryPersonal);
        $stmtPersonal->execute();
        $customerInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
    
        $queryNumPostCodes = "SELECT Distinct HouseNumber, Postcode, StreetName, City From CustomerView";
        $stmtPost = $mysql->prepare($queryNumPostCodes);
        $stmtPost->execute();
        $addresses = $stmtPost->fetchAll(PDO::FETCH_ASSOC);
    
        $queryPayment = "SELECT Distinct CardNumber, CVV, ExpiryDate From CustomerView";
        $stmtPay = $mysql->prepare($queryPayment);
        $stmtPay->execute();
        $payments = $stmtPay->fetchAll(PDO::FETCH_ASSOC);
    }
}

// remove product
if (isset($_POST['removeProductID'])) {
    $productID = $_POST['removeProductID'];

    // remove the product from the cart
    if (isset($_SESSION['cart'][$productID])) {
        unset($_SESSION['cart'][$productID]);
    }

    // redirect back to the basket page after removing the product
    header("Location: basket.php");
    exit();
}


// add products
if (isset($_POST['productID']) && isset($_POST['quantity'])) {
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];

    // if product is already in the cart, update the quantity 
    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID] += $quantity;
    } else {
        // if not, add product with the quantity
        $_SESSION['cart'][$productID] = $quantity;
    }

    // redirect to the basket page after adding teh product
    header("Location: basket.php");
    exit();
}

// Function to get product details from the database
function getProductDetails($productID) {
    global $mysql;

    $query = "SELECT ProductName, Price FROM Product WHERE ProductID = :productID";
    $stmt = $mysql->prepare($query);
    $stmt->execute([':productID' => $productID]);
    return $stmt->fetch(PDO::FETCH_ASSOC); 
}

?>

<?php if (isset($userID)): ?>
    <div id="userIDBox">
        <h2>User ID</h2>
        <p><?php echo htmlspecialchars($userID); ?></p>
    </div>
<?php endif; ?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="style.css">
</head>

    <!-- Added for debugging-->
<div id="loggedInStatus">
    <h2>Login Status</h2>
    <p>
        <?php
        if (isset($_SESSION['LoggedIn'])) {
            echo htmlspecialchars("Logged In as: {$_SESSION['LoggedIn']}");
        } else {
            echo "Not Logged In.";
        }
        ?>
    </p>
</div>
    <?php if (!empty($customerInfo) && !empty($addresses) && !empty($payments)): ?>
            <div id="userDetails">
                <h2>Address</h2>
                <?php foreach ($addresses as $address): ?>
                    <p><?php echo htmlspecialchars("{$address['HouseNumber']} {$address['StreetName']}, {$address['City']}, {$address['Postcode']}"); ?></p>
                <?php endforeach; ?>

                <h2>Payment Methods</h2>
                <?php if (count($payments) == 0): ?>
                    <p>No payment methods saved.</p>
                <?php endif; ?>
                <?php foreach ($payments as $payment): ?>
                    
                    <p><strong>Card Number:</strong> **** **** **** <?php echo substr($payment['CardNumber'], -4); ?></p>
                    <p><strong>Expiry Date:</strong> <?php echo $payment['ExpiryDate']; ?></p>
                    <p><strong>CVV:</strong> ***</p>
                    <br>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Please log in to view your address and payment details.</p>
        <?php endif; ?>
    <div id="basketContentsBox">
    <?php
        $totalPrice = 0; // initialize total price
        // loop through the cart items and display them
        foreach ($_SESSION['cart'] as $productID => $quantity) {
            $productDetails = getProductDetails($productID); //get the product details
            $productName = $productDetails['ProductName'];
            $productPrice = $productDetails['Price'];
            $imagePath = "images/" . $productID . ".png"; 

            // calculate the total price for this item
            $itemTotalPrice = $productPrice * $quantity;
            $totalPrice += $itemTotalPrice; // ddd to the total cart price

            
            echo "<div class='basketItemBox'>
                    <div class='basketItem button' href='product.html?productId=$productID'>
                        <div class='basketItemInnerTable'>
                            <div class='basketItemImageBox'>
                            
                                <img src='$imagePath' alt='$productName' class='basketItemImageBox'>
                            </div>
                            <div class='basketItemNameBox'>
                                <h2>$productName</h2>
                            </div>
                            <div class='basketQuantity'>
                                <h1>$quantity</h1>
                            </div>
                        </div>
                    </div>
                     <div class='basketItemRemoveBox'>
                    <form action='basket.php' method='POST'>
                      <input type='hidden' name='removeProductID' value='$productID'>
                        <button type='submit' class='removeButton'>X</button>  
                         </form>
                       </div>

                    <div class='basketItemPriceBox'>
                        <div class='basketItemPrice'>
                            <h1>£" . number_format($itemTotalPrice, 2) . "</h1> 
                        </div>
                    </div>
                </div>";
        }
        ?>
        <div id="basketTotalBox">
            <div id="basketTotal">
                <div id="basketTotalText">
                    <h1>Total</h1>
                </div>
                <div id="basketTotalPrice">
                <h1>£<?php echo number_format($totalPrice, 2); ?></h1> 
                </div>
            </div>
        </div>
        
    </div>

     <script>

    // function to display the buy now button only if the cart is not empty
     function addBuyNowHtml() {
      // check if the cart is empty 
       let cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
    
       // If cart is not empty, display the Buy Now button
       if (Object.keys(cartItems).length > 0) {
        let body = document.getElementsByTagName('body')[0];

        let buyNowBox = document.createElement('div');
        buyNowBox.setAttribute('id', 'buyNowBox');
        buyNowBox.classList.add('button');
        let buyNowText = document.createElement('h1');
        buyNowText.innerHTML = "Buy Now";
        buyNowBox.appendChild(buyNowText);
        body.appendChild(buyNowBox);

        // add click event listener to the button
        buyNowBox.addEventListener('click', function() {
            // check if the user is logged in 
            <?php if (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']): ?>
                displayCheckout(event);
            <?php else: ?>
                window.location.href = "loginPage.php";  
            <?php endif; ?>
        });
       }
    }


        addBuyNowHtml();


    </script>
    
    <script type="text/javascript">
    //need to pass the price, customerID, shop_ID?
    //so we can make an entry in the table at ordercomplete? 
    var userAddress = <?php echo json_encode($addresses); ?>;
    var userPayments = <?php echo json_encode($payments); ?>;
    var price = <?php echo json_encode($totalPrice); ?>;
    var customerID = <?php echo json_encode($userID); ?>;
    var availableShops = <?php echo json_encode($availableShops); ?>;
    
   
    // debugging
    console.log('User Address:', userAddress);
    console.log('User Payments:', userPayments);
    console.log('Price:', price);
    console.log('Customer ID:', customerID);
    console.log('Shops available for collection:', availableShops); 

  </script>

    <div id="emptyBox">
    </div>

</body>
<script src="script.js"></script>

</html>

<?php include 'footer.php'; ?>