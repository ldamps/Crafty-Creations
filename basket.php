<?php include 'navBar.php'; ?>

<?php
include('db.php'); 

// initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
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
                                <h1>$productName</h1>
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
        // function to display the buy now button
        function addBuyNowHtml() {
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
                // Check if the user is logged in before proceeding
                <?php if (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']): ?>
                    displayCheckout(event);
                <?php else: ?>
                    window.location.href = "loginPage.php";  
                <?php endif; ?>
            });
        }

        addBuyNowHtml();
    </script>
</body>
<script src="script.js"></script>

</html>