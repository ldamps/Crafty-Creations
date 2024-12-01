<?php include 'navBar.php'; ?>

<?php
include('db.php'); 

$availableShops = [];
$userAddress = [];
$userPayments = [];


// fucntion to generate a random tracking number
function generateTrackingNo($length = 7) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $trackingNo = '';
    for ($i = 0; $i < $length; $i++) {
        $trackingNo .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $trackingNo;
}

// check if POST data for deliveryOption and paymentOption is received
if (isset($_POST['deliveryOption']) && isset($_POST['paymentOption'])) {
    $_SESSION['deliveryOption'] = $_POST['deliveryOption'];
    $_SESSION['paymentOption'] = $_POST['paymentOption'];

    $deliveryParts = explode(',', $_SESSION['deliveryOption']);
    $postcode = trim($deliveryParts[2]); // postcode is the third part

     $shopID = null;  // Set default value as null i.e it's a home delivery

     // query to select ShopID based on postcode of the Shop
     $query = "SELECT ShopID FROM Shop WHERE Postcode = :postcode";
     $stmt = $mysql->prepare($query);
     $stmt->execute([':postcode' => $postcode]);
     $result = $stmt->fetch(PDO::FETCH_ASSOC); 
 
     if ($result) {
         $shopID = $result['ShopID'];
     }

    // other variables needed to update the OnlineOrder Table
    $totalPrice = $_SESSION['totalPrice'];
    $orderStatus = 'Processing';
    $customerID = $_SESSION['ID'];
    $trackingNo = generateTrackingNo();
    // store the tracking number in the session to display on the order complete page
    $_SESSION['trackingNo'] = $trackingNo;
    $orderDate = date('Y-m-d'); 

    // prepare the insert query to insert into the OnlineOrder table
    $insertQuery = "
        INSERT INTO OnlineOrder (Price, OrderStatus, Customer_CustomerID, Shop_shopID, TrackingNo, OrderDate)
        VALUES (:price, :orderStatus, :customerID, :shopID, :trackingNo, :orderDate)
    ";

    $stmt = $mysql->prepare($insertQuery);
    $stmt->execute([
        ':price' => $totalPrice,
        ':orderStatus' => $orderStatus,
        ':customerID' => $customerID,
        ':shopID' => $shopID,
        ':trackingNo' => $trackingNo,
        ':orderDate' => $orderDate
    ]);

    // fetch the last inserted OrderID
    $orderID = $mysql->lastInsertId();

   // Iterate through the cart and update the tables
  // If shopID is null, that means home delivery, so update the first shop that has the available products
foreach ($_SESSION['cart'] as $productID => $quantity) {
    
    $remainingQuantity = $quantity; // track remaining quantity for each product

    if ($shopID === null) {
        // find the shops with availability for this product
        $findShopQuery = "
            SELECT Shop_ShopID, Availability 
            FROM ProductAvailability
            WHERE Product_ProductID = :productID AND Availability > 0
            ORDER BY Shop_ShopID
        ";
        $stmt = $mysql->prepare($findShopQuery);
        $stmt->execute([':productID' => $productID]);

        // track if a shop has been found that can fulfill the order
        $foundShop = false;

        while ($shop = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($remainingQuantity <= 0) break; // if remaining quantity is fulfilled then stop

            $shopID = $shop['Shop_ShopID'];
            $availableStock = $shop['Availability'];

            // ff the available stock is enough for the entire remaining quantity, remove it
            $deductQuantity = min($remainingQuantity, $availableStock);
            $remainingQuantity -= $deductQuantity;

            // remove stock and update the availability in the ProductAvailability table
            $updateAvailabilityQuery = "
                UPDATE ProductAvailability
                SET Availability = Availability - :deductQuantity
                WHERE Product_ProductID = :productID AND Shop_ShopID = :shopID
            ";
            $updateStmt = $mysql->prepare($updateAvailabilityQuery);
            $updateStmt->execute([
                ':deductQuantity' => $deductQuantity,
                ':productID' => $productID,
                ':shopID' => $shopID
            ]);

            // insert tvalues in the OnlineOrder_has_Product table
            $insertOrderProductQuery = "
                INSERT INTO OnlineOrder_has_Product (OnlineOrder_OrderID, Product_ProductID, Quantity)
                VALUES (:orderID, :productID, :deductQuantity)
            ";
            $insertStmt = $mysql->prepare($insertOrderProductQuery);
            $insertStmt->execute([
                ':orderID' => $orderID,
                ':productID' => $productID,
                ':deductQuantity' => $deductQuantity
            ]);

            // if the stock is deducted 
            //set foundShop to true to stop searching for the current product
            $foundShop = true;

            echo "Product ID $productID: Deducted $deductQuantity from Shop $shopID.<br>";

            // if the entire quantity has been found, break the loop for this paarticulat product
            if ($remainingQuantity <= 0) break;
        }

        // if no shop has the entire quantity display an error message
        if (!$foundShop) {
            echo "Insufficient stock for product ID $productID.<br>";
        }
    } else {
        // if shopID is provided update for that shop (e.g., home delivery case)
        $updateAvailabilityQuery = "
            UPDATE ProductAvailability
            SET Availability = Availability - :quantity
            WHERE Product_ProductID = :productID AND Shop_ShopID = :shopID
        ";
        $updateStmt = $mysql->prepare($updateAvailabilityQuery);
        $updateStmt->execute([
            ':quantity' => $quantity,
            ':productID' => $productID,
            ':shopID' => $shopID
        ]);

        // insert  values into OnlineOrder_has_Product table
        $insertOrderProductQuery = "
            INSERT INTO OnlineOrder_has_Product (OnlineOrder_OrderID, Product_ProductID, Quantity)
            VALUES (:orderID, :productID, :quantity)
        ";
        $insertStmt = $mysql->prepare($insertOrderProductQuery);
        $insertStmt->execute([
            ':orderID' => $orderID,
            ':productID' => $productID,
            ':quantity' => $quantity
        ]);

        echo "Product ID $productID: Deducted $quantity from Shop $shopID.<br>";
    }
}


    // debug the values
    echo "<script>
        console.log('deliveryOption: " . $_SESSION['deliveryOption'] . "');
        console.log('paymentOption: " . $_SESSION['paymentOption'] . "');
        console.log('postcode: " . $postcode . "');
        console.log('price: " . $totalPrice . "');
        console.log('orderStatus: " . $orderStatus . "');
        console.log('customerID: " . $customerID . "');
        console.log('shopID: " . $shopID . "');
        console.log('trackingNo: " . $trackingNo . "');
        console.log('orderDate: " . $orderDate . "');
    </script>";

    unset($_SESSION['cart']);   // clear the cart

    exit();  
}



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
 
              // Save the total price in the session
              $_SESSION['totalPrice'] = $totalPrice;
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