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
    
    // prep SQL statement preventing SQL injection
    $stmt = $mysql->prepare("SELECT Password, CustomerID FROM Customer WHERE EmailAddress = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    //DEBUG
    //echo "Rows found: " . $stmt->rowCount() . "<br>";

    //if email exists in database
    if ($stmt->rowCount() == 1) {
        // fetch hashed password from database
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $db_password = $row['Password'];
        $db_id = $row['CustomerID'];

        // DEBUG
        //echo "Fetched password from DB: $db_password<br>";

        
        // verifying entered password with hashed pass
        if (password_verify($password, $db_password)) {  // used password_verify() cause hashing is used
            //$message = "Login successful. Welcome!";
            $_SESSION["LoggedIn"] = "customer";

            // create a cookie of the users name and ID
            $cookie_name = "ID";
            $cookie_value = $db_id;
            // set log in valid for 2 hours
            setcookie($cookie_name, $cookie_value, time() + (7200), "=/");
            echo $_COOKIE["ID"];
            // reload and go back to home page
            header("Refresh:0; url=index.php");

            
            // redirect to protected page or user dashboard here
        } else {
            //$message = "Invalid password. Please try again.";
        }
    } else {
        //$message = "No account found with that email.";

        // DEBUG
        $errorInfo = $stmt->errorInfo();
        echo "SQLSTATE Error Code: " . $errorInfo[0] . "<br>";
        echo "Driver-specific Error Code: " . $errorInfo[1] . "<br>";
        echo "Driver-specific Error Message: " . $errorInfo[2] . "<br>";
        
    }
}

include 'loginPage.html';
include 'footer.html';
?>


