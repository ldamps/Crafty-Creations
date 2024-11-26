<?php include('navBar.php'); ?>


<?php
require 'db.php';  

//$message = "";  // variable to store the feedback message

//echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "<br>";

// if form was submitted
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    //capture email and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    //DEBUG
    //echo "Email entered: $email<br>";
    //echo "Password entered: $password<br>";
    
    $isLoggedIn = false;
    echo "$isLoggedIn";
    $role = "";
    $db_id = "";

    // prep SQL statement preventing SQL injection
    $stmtCustomer = $mysql->prepare("SELECT Password, CustomerID FROM Customer WHERE EmailAddress = :email");
    $stmtCustomer->bindParam(':email', $email);
    $stmtCustomer->execute();
    
    //DEBUG
    //echo "Rows found: " . $stmt->rowCount() . "<br>";

    //if email exists in database
    if ($stmtCustomer->rowCount() == 1) {
        // fetch hashed password from database
        $row = $stmtCustomer->fetch(PDO::FETCH_ASSOC);
        $db_password = $row['Password'];
        $db_id = $row['CustomerID'];

        // DEBUG
        //echo "Fetched password from DB: $db_password<br>";

        // verifying entered password with hashed pass
        if (password_verify($password, $db_password)) {  // used password_verify() cause hashing is used
            $isLoggedIn = true;
            $role = "customer";
            $viewSQL = "DROP VIEW IF EXISTS CustomerView;
                Create OR REPLACE View CustomerView
                AS 
                SELECT c.CustomerID, c.LastName, c.FirstName, c.EmailAddress, c.PhoneNumber, c.Password, c.Title, 
                o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, 
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
                o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, 
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
            $stmtCustomerView->execute(["userID" => $db_id]);
        }   
    } 

    // employee table
    if (!$isLoggedIn) { 
        echo "checking employee table";
        $stmtEmployee = $mysql->prepare("SELECT Password, Role, EmployeeID FROM Employee WHERE EmailAddress = :email"); 
        $stmtEmployee->bindParam(':email', $email);
        $stmtEmployee->execute();
        $numRows = $stmtEmployee->rowCount();
        echo $numRows;
        if ($stmtEmployee->rowCount() === 1) {
            $row = $stmtEmployee->fetch(PDO::FETCH_ASSOC);
            $db_password = $row['Password'];
            $role = $row['Role'];
            $db_id = $row['EmployeeID'];
        
            // debug 
            echo "Employee found: Role = $role, ID = $db_id<br>";
        
            if (password_verify($password, $db_password)) {
                $isLoggedIn = true;
                echo "Password verified for employee.<br>"; 
                $_SESSION["EmployeeID"] = $db_id; 

                if ($role = "Shop Assistant")
                {
                    // get the employee's manager using stored procedure
                    $queryManager = "CALL GetManager(:employeeID)";
                    $stmtManager = $mysql->prepare($queryManager);
                    $stmtManager->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
                    $stmtManager->execute();
                    $ManagerInfo = $stmtShopID->fetch();
                    $ManFirst = $ManagerInfo[0];
                    $ManLast = $ManagerInfo[1];
                    echo $ManFirst . $ManLast;
                    $stmtShopID->closeCursor();


                    $viewEmployeeSQL = "DROP VIEW IF EXISTS ShopEmployeeView;
                    Create OR REPLACE View ShopEmployeeView
                    AS 
                    SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
                    bd.AccountName, bd.AccountNo, bd.SortCode,
                    o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID,
                    s.StreetName, s.Postcode, s.City, s.NumEmployees,
                    m.FirstName as MFirst, m.Surname AS MSur
                    #r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID
                    From Employee e
                    LEFT JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
                    LEFT JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
                    LEFT JOIN Shop s ON se.Shop_shopID = s.ShopID
                    LEFT JOIN ShopEmployee mse ON mse.Shop_ShopID = s.ShopID
                    LEFT JOIN Employee m ON  m.FirstName = :ManFirst AND m.Surname = :ManLast
                    LEFT JOIN OnlineOrder o ON se.Shop_ShopID =  o.Shop_shopID
                    #LEFT JOIN OnlineReturn r ON se.Shop_shopID = r.Shop_shopID
                    WHERE e.EmployeeID = :userID
                    UNION
                    SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
                    bd.AccountName, bd.AccountNo, bd.SortCode,
                    o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID,
                    s.StreetName, s.Postcode, s.City, s.NumEmployees,
                    m.FirstName AS manFirst, m.Surname AS manSur
                    #r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID
                    From Employee e
                    RIGHT JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
                    RIGHT JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
                    RIGHT JOIN Shop s ON se.Shop_shopID = s.ShopID
                    RIGHT JOIN Employee m ON  m.FirstName = :ManFirst AND m.Surname = :ManLast
                    RIGHT JOIN OnlineOrder o ON se.Shop_ShopID =  o.Shop_shopID
                    #RIGHT JOIN OnlineReturn r ON se.Shop_shopID = r.Shop_shopID
                    WHERE e.EmployeeID = :userID;";
                    $stmtEmployeeView = $mysql->prepare($viewEmployeeSQL);
            $stmtEmployeeView->execute(["userID" => $db_id, "ManFirst" => $ManFirst, "ManLast" => $ManLast]);
                }

            } 
        } 
        
    }

    
    if ($isLoggedIn) {
        $_SESSION["LoggedIn"] = $role; // storing role in session
        setcookie("ID", $db_id, time() + (7200), "=/"); // 2-hour cookie
        header("Location: index.php"); // main page, nav changing dynamically
        exit();
    } else {
        echo "Invalid email or password. Please try again.";
    }

    //php -S localhost:8000
    //http://localhost:8000/loginPage.php

    
}

include 'loginPage.html';
include 'footer.html';
?>



