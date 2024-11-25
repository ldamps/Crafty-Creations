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

            //$message = "Login successful. Welcome!";
            
            // if customer
            $cookie_name = "CustomerID";
            $cookie_value = $db_id;
            // set log in valid for 2 hours
            setcookie($cookie_name, $cookie_value, time() + (7200), "=/");
            //$_SESSION["LoggedIn"] = "customer";

            // create a cookie of the users name and ID
            //$cookie_name = "ID";
            //$cookie_value = $db_id;
            // set log in valid for 2 hours
            //setcookie($cookie_name, $cookie_value, time() + (7200), "=/");

            //echo $_COOKIE["ID"];
            // reload and go back to home page
            //header("Refresh:0; url=index.php");

            // setting up different logins for different kinds of staff once we have that working
            // if shop employee
            /* $cookie_name = "ShopEmployeeID";
            $cookie_value = $db_id;
            // set log in valid for 2 hours
            setcookie($cookie_name, $cookie_value, time() + (7200), "=/");
            //echo $_COOKIE["ID"];
            // reload and go back to home page
            header("Refresh:0; url=index.php");*/

            // if store manager
            /* $cookie_name = "ManagerID";
            $cookie_value = $db_id;
            // set log in valid for 2 hours
            setcookie($cookie_name, $cookie_value, time() + (7200), "=/");
            //echo $_COOKIE["ID"];
            // reload and go back to home page
            header("Refresh:0; url=index.php");*/

            // if office emloyee
            /* $cookie_name = "OfficeEmployeeID";
            $cookie_value = $db_id;
            // set log in valid for 2 hours
            setcookie($cookie_name, $cookie_value, time() + (7200), "=/");
            //echo $_COOKIE["ID"];
            // reload and go back to home page
            header("Refresh:0; url=index.php");*/
            // redirect to protected page or user dashboard here
        } 
    } 

    // employee table
    if (!$isLoggedIn) { 
        $stmtEmployee = $mysql->prepare("SELECT Password, Role, EmployeeID FROM Employee WHERE EmailAddress = :email"); 
        $stmtEmployee->bindParam(':email', $email);
        $stmtEmployee->execute();

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



