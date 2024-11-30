<?php include 'navBar.php'; ?>


<?php
    require 'db.php';
    include 'index.html';
    //unset($_SESSION['currentlyLoaded']);
    echo "<br><form method='post'><button class='button' id = 'resetButton'>Reset Search</button></form></br>";
    // get number of products from the database
    $res = $mysql->query("SELECT COUNT(*) FROM Product");
    $numProducts = $res->fetchColumn();
    
    $increment = 40;
    if (!isset($_SESSION)){
        session_start();  
    }
    if (isset($_SESSION['LoggedIn'])){
        $role = $_SESSION["LoggedIn"];
        $userID = $_COOKIE["ID"];
        if ($role === "customer")
        {
            //echo "customer";
             // create view dynamically so it is based on the customers ID and they only see their own information
             $viewSQL = "DROP VIEW IF EXISTS CustomerView;
             Create OR REPLACE View CustomerView
             AS 
             SELECT c.CustomerID, c.LastName, c.FirstName, c.EmailAddress, c.PhoneNumber, c.Password, c.Title, 
             o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, o.Customer_CustomerID,
             p.CardNumber, p.CVV, p.ExpiryDate, 
             a.City, a.HouseNumber, a.Postcode, a.StreetName,
             r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID
             From Customer c  
             LEFT JOIN OnlineOrder o ON c.CustomerID = o.Customer_CustomerID
             LEFT JOIN CustomerAddress a ON c.CustomerID = a.Customer_CustomerID
             LEFT JOIN PaymentMethods p ON c.CustomerID = p.Customer_CustomerID
             LEFT JOIN OnlineReturn r ON c.CustomerID = r.Customer_CustomerID
             WHERE CustomerID = :userID
             UNION
             SELECT c.CustomerID, c.LastName, c.FirstName, c.EmailAddress, c.PhoneNumber, c.Password, c.Title, 
             o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, o.Customer_CustomerID,
             p.CardNumber, p.CVV, p.ExpiryDate, 
             a.City, a.HouseNumber, a.Postcode, a.StreetName,
             r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID
             From Customer c 
             RIGHT JOIN OnlineOrder o ON c.CustomerID = o.Customer_CustomerID 
             RIGHT JOIN CustomerAddress a ON c.CustomerID = a.Customer_CustomerID
             RIGHT JOIN PaymentMethods p ON c.CustomerID = p.Customer_CustomerID
             RIGHT JOIN OnlineReturn r ON c.CustomerID = r.Customer_CustomerID
             WHERE CustomerID = :userID";  
            $stmtCustomerView = $mysql->prepare($viewSQL);
            $stmtCustomerView->execute(["userID" => $userID]);
            $stmtCustomerView->closeCursor();
        }
        else if ($role === "Shop Assistant" || $role === "Supervisor")
        {
            // get the employee's manager using stored procedure
            $queryManager = "CALL GetManager(:employeeID)";
            $stmtManager = $mysql->prepare($queryManager);
            $stmtManager->bindParam(':employeeID', $userID, PDO::PARAM_INT);
            $stmtManager->execute();
            $ManagerInfo = $stmtManager->fetch();
            $ManFirst = $ManagerInfo[0];
            $ManLast = $ManagerInfo[1];
            //echo $ManFirst . $ManLast;
            $stmtManager->closeCursor();

            // get the shop the employee works at using stored procedure
            $queryShopWorked = "CALL GetShopWorkedAt(:employeeID)";
            $stmtShopWorked = $mysql->prepare($queryShopWorked);
            $stmtShopWorked->bindParam(':employeeID', $userID, PDO::PARAM_INT);
            $stmtShopWorked->execute();
            $ShopWorkedInfo = $stmtShopWorked->fetchColumn();
            $stmtShopWorked->closeCursor();

            // create stock view dynamically based on Shop ID
            $queryStockView = "DROP VIEW IF EXISTS ShopEmployeeStockView;
                Create OR REPLACE View ShopEmployeeStockView
                AS 
                SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, P.ProductDescription, PA.Availability,
                op.Quantity, op.OnlineOrder_OrderID, op.Product_ProductID,
                sp.Quantity AS spQuantity, sp.Purchase_PurachseID, sp.Product_ProductID as spProduct
                    FROM Product P
                    LEFT JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
                    LEFT JOIN OnlineOrder_has_Product op ON P.ProductID = op.Product_ProductID
                    LEFT JOIN ShopPurchase_has_Product sp ON P.ProductID = sp.Product_ProductID
                    WHERE PA.Shop_ShopID = 1
                UNION
                SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, P.ProductDescription, PA.Availability,
                op.Quantity, op.OnlineOrder_OrderID, op.Product_ProductID,
                sp.Quantity AS spQuantity, sp.Purchase_PurachseID, sp.Product_ProductID as spProduct
                    FROM Product P
                    RIGHT JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
                    RIGHT JOIN OnlineOrder_has_Product op ON P.ProductID = op.Product_ProductID
                    RIGHT JOIN ShopPurchase_has_Product sp ON P.ProductID = sp.Product_ProductID
                            WHERE PA.Shop_ShopID = :SID";
            $stmtStockView = $mysql->prepare($queryStockView);
            $stmtStockView->bindParam(':SID', $shopID, PDO::PARAM_INT);
            $stmtStockView->execute();
            $StockView = $stmtStockView->fetchColumn();
            $stmtStockView->closeCursor();

            // create main employee view
            $viewEmployeeSQL = "DROP VIEW IF EXISTS ShopEmployeeView;
            Create OR REPLACE View ShopEmployeeView
            AS 
            SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
            bd.AccountName, bd.AccountNo, bd.SortCode,
            o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, o.OrderDate, o.Customer_CustomerID AS customerID,
            s.StreetName, s.Postcode, s.City, s.NumEmployees, s.TotalSales,
            m.FirstName as ManagerFirstName, m.Surname AS ManagerSurname,
            r.OnlineReturnID, r.Reason, r.AmountToReturn, r.Customer_CustomerID,	
            sp.PurchaseID, sp.Price AS shopPrice, sp.PurchaseDate, sp.Customer_CustomerID AS shopCustomer, sp.Shop_shopID AS SID,
            sr.ShopReturnID, sr.AmountToReturn as ShopAmountToReturn, sr.Reason AS shopReason, sr.Customer_CustomerID AS ShopReturnCustomer
            From Employee e
            INNER JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
            INNER JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
            INNER JOIN Shop s ON se.Shop_shopID = s.ShopID
            INNER JOIN ShopEmployee mse ON mse.Shop_ShopID = s.ShopID
            INNER JOIN Employee m ON m.FirstName = :ManFirst AND m.Surname = :ManLast
            INNER JOIN OnlineOrder o ON se.Shop_ShopID =  s.ShopID
            INNER JOIN ShopPurchase sp ON sp.Shop_shopID = s.ShopID
            INNER JOIN ShopReturn sr ON sp.Shop_shopID = s.ShopID
            INNER JOIN OnlineReturn r ON (r.Shop_shopID = s.ShopID)
                        WHERE e.EmployeeID = :userID AND s.ShopID = :shopID";
            $stmtEmployeeView = $mysql->prepare($viewEmployeeSQL);
            $stmtEmployeeView->execute(["userID" => $userID, "ManFirst" => $ManFirst, "ManLast" => $ManLast, "shopID" =>$ShopWorkedInfo ]);
            $stmtEmployeeView->closeCursor();
            //["userID" => $userID, "ManFirst" => $ManFirst, "ManLast" => $ManLast, "shopID" =>$ShopWorkedInfo ]
        }
        else if ($role === "Manager" || $role === "Assistant Manager")
        {
            echo $userID;
            

            // get the shop the employee works at using stored procedure
            $queryShopWorked = "CALL GetShopWorkedAt(:employeeID)";
            $stmtShopWorked = $mysql->prepare($queryShopWorked);
            $stmtShopWorked->bindParam(':employeeID', $userID, PDO::PARAM_INT);
            $stmtShopWorked->execute();
            $ShopWorkedInfo = $stmtShopWorked->fetchColumn();
            $stmtShopWorked->closeCursor();

            // get the employee's manager using stored procedure
            $queryManager = "CALL GetManager(:shopID)";
            $stmtManager = $mysql->prepare($queryManager);
            $stmtManager->bindParam(':shopID', $ShopWorkedInfo, PDO::PARAM_INT);
            $stmtManager->execute();
            $ManagerInfo = $stmtManager->fetch();
            $ManFirst = $ManagerInfo[0];
            $ManLast = $ManagerInfo[1];
            echo $ManFirst . $ManLast;
            $stmtManager->closeCursor();
            //echo "shop worked at: " . $ShopWorkedInfo;
            //echo "userID: " . $userID;
            // create manager view
            //echo "creating manager view";
            $viewManagerSQL = "DROP VIEW IF EXISTS ManagerView;
            Create OR REPLACE View ManagerView
            AS 
            SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
            bd.AccountName, bd.AccountNo, bd.SortCode,
            E.EmployeeID AS empID, E.FirstName AS empFirst, E.Surname AS empSur, E.Role as empRole, E.EmailAddress AS empEmail, E.HoursWorked AS empHours, E.HourlyPay AS empPay,
            o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, o.OrderDate, o.Customer_CustomerID AS customerID,
            s.StreetName, s.Postcode, s.City, s.NumEmployees, s.TotalSales,
            m.FirstName as ManagerFirstName, m.Surname AS ManagerSurname,
            r.OnlineReturnID, r.Reason, r.AmountToReturn, r.Customer_CustomerID,	
            sp.PurchaseID, sp.Price AS shopPrice, sp.PurchaseDate, sp.Customer_CustomerID AS shopCustomer, sp.Shop_shopID AS SID,
            sr.ShopReturnID, sr.AmountToReturn as ShopAmountToReturn, sr.Reason AS shopReason, sr.Customer_CustomerID AS ShopReturnCustomer
            From Employee e
            INNER JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
            INNER JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
            INNER JOIN Shop s ON se.Shop_shopID = s.ShopID
            INNER JOIN ShopEmployee mse ON mse.Shop_ShopID = s.ShopID
            INNER JOIN Employee m ON m.FirstName = :ManFirst AND m.Surname = :ManLast
            INNER JOIN Employee E ON E.employeeID IN (SELECT Employee_EmployeeID FROM ShopEmployee WHERE Shop_ShopID = :shopID)
            LEFT JOIN OnlineOrder o ON o.Shop_ShopID =  s.ShopID
            LEFT JOIN ShopPurchase sp ON sp.Shop_shopID = s.ShopID
            LEFT JOIN ShopReturn sr ON sr.Shop_shopID = s.ShopID
            LEFT JOIN OnlineReturn r ON (r.Shop_shopID = s.ShopID)
            WHERE e.EmployeeID = :userID AND s.ShopID = :shopID";
            $stmtManagerView = $mysql->prepare($viewManagerSQL);
            $stmtManagerView->execute(["userID" => $userID, "ManFirst" => $ManFirst, "ManLast" => $ManLast, "shopID" =>$ShopWorkedInfo ]);
            $stmtManagerView->closeCursor();

            //echo "manager view created";

            // create view for manager to view supplier info
            $viewManagerSupplier = "DROP VIEW IF EXISTS ManagerSupplierView;
            CREATE VIEW ManagerSupplierView
            As
            # this bit gets all of the supplier orders, but not all suppliers
            SELECT sup.SupplierID, sup.Name, sup.ProductTypeSupplied, sup.Address, sup.Email,
            so.SupplyOrderID, so.ProductType, so.ShopID
            FROM Supplier sup
            LEFT JOIN SupplyOrder so ON so.Supplier_SupplierID = sup.SupplierID AND so.ShopID = :shopID";
            $stmtManagerSupplierView = $mysql->prepare($viewManagerSupplier);
            $stmtManagerSupplierView->execute(["shopID" =>$ShopWorkedInfo ]);
            $stmtManagerSupplierView->closeCursor();

            // create stock view dynamically based on Shop ID
            $queryStockView = "DROP VIEW IF EXISTS ShopEmployeeStockView;
                Create OR REPLACE View ShopEmployeeStockView
                AS 
                SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, P.ProductDescription, PA.Availability,
                op.Quantity, op.OnlineOrder_OrderID, op.Product_ProductID,
                sp.Quantity AS spQuantity, sp.Purchase_PurachseID, sp.Product_ProductID as spProduct
                    FROM Product P
                    LEFT JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
                    LEFT JOIN OnlineOrder_has_Product op ON P.ProductID = op.Product_ProductID
                    LEFT JOIN ShopPurchase_has_Product sp ON P.ProductID = sp.Product_ProductID
                    WHERE PA.Shop_ShopID = :SID 
                UNION
                SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, P.ProductDescription, PA.Availability,
                op.Quantity, op.OnlineOrder_OrderID, op.Product_ProductID,
                sp.Quantity AS spQuantity, sp.Purchase_PurachseID, sp.Product_ProductID as spProduct
                    FROM Product P
                    RIGHT JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
                    RIGHT JOIN OnlineOrder_has_Product op ON P.ProductID = op.Product_ProductID
                    RIGHT JOIN ShopPurchase_has_Product sp ON P.ProductID = sp.Product_ProductID
                            WHERE PA.Shop_ShopID = :SID";
            $stmtStockView = $mysql->prepare($queryStockView);
            $stmtStockView->bindParam(':SID', $ShopWorkedInfo, PDO::PARAM_INT);
            $stmtStockView->execute();
            $StockView = $stmtStockView->fetchAll();
            echo sizeOf($StockView);
            $stmtStockView->closeCursor();
        }
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
        
        if (isset($_POST['reset'])){
            echo "reset";
            unset($_SESSION['Search']);
            unset($_SESSION['Narrow']);
            unset($_POST['reset']);
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
        $query = $mysql->prepare("SELECT DISTINCT ProductID FROM LoggedOutView WHERE ProductName LIKE '%$type%' OR Brand LIKE '%$type%' OR Type LIKE '%$type%'");
        $query->execute();
        $result = $query->fetchAll();
        $results = array();

        foreach($result as $item){
            $id = $item["ProductID"];
            $query = $mysql->prepare("SELECT ProductName,ProductDescription,Price,Brand,ProductID FROM LoggedOutView WHERE ProductID = '$id'");
            $query->execute();
            $result = $query->fetchAll();
            
            array_push($results, ["ProductName" => $result[0]["ProductName"], "ProductDescription" => $result[0]["ProductDescription"], "Price" => $result[0]["Price"], "Brand" => $result[0]["Brand"], "ProductID" => $result[0]["ProductID"]]);
        }
    }
    //Get all products from the database of a single type.
    elseif (isset($_SESSION['Narrow']) && $_SESSION['Narrow'] != ""){
        $narrow = $_SESSION['Narrow'];
        $query = $mysql->prepare("SELECT DISTINCT ProductID FROM Product WHERE Type LIKE '%$narrow%'");
        $query->execute();
        $result = $query->fetchAll();
        $results = array();

        foreach($result as $item){
            $id = $item["ProductID"];
            $query = $mysql->prepare("SELECT ProductName,ProductDescription,Price,Brand,ProductID FROM LoggedOutView WHERE ProductID = '$id'");
            $query->execute();
            $result = $query->fetchAll();
            
            array_push($results, ["ProductName" => $result[0]["ProductName"], "ProductDescription" => $result[0]["ProductDescription"], "Price" => $result[0]["Price"], "Brand" => $result[0]["Brand"], "ProductID" => $result[0]["ProductID"]]);
        }
    }
    //Otherwise, get all products from the database.
    else{
        $query = $mysql->prepare("SELECT DISTINCT ProductName,ProductDescription,Price,Brand,ProductID FROM LoggedOutView ORDER BY ProductID");
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
        $PID = $products[$i]->id;
        echo "<a href='product.php?ID=$PID'>"; 
        echo "<img class='productImage' src='images/".$products[$i]->id.".png' />";
        echo "<h1 class = productName >".$products[$i]->name."</h1>";
        echo "<p class = productInfo>".$products[$i]->description."</p>";
        $formattedPrice = number_format($products[$i]->price, 2);  // format the price with 2 decimal places
        echo "<p class = productInfo>£".$formattedPrice."</p>";  
        echo "<p class = productInfo>Brand: ".$products[$i]->brand."</p>";
        //echo "<p class = productInfo>Brand: ".$products[$i]->id."</p>";
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

    
?>
<?php include 'footer.html'; ?>
<script type="text/javascript" src="script.js"></script>
