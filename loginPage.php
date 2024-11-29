<?php include('navBar.php'); ?>


<?php
require 'db.php';

//$message = "";  // variable to store the feedback message

//echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "<br>";

// if form was submitted   

if (!isset($_SESSION['LoggedIn'])) {
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
            }
        }

        // employee table
        if (!$isLoggedIn) {
            #echo "checking employee table";
            $stmtEmployee = $mysql->prepare("SELECT Password, Role, EmployeeID FROM Employee WHERE EmailAddress = :email");
            $stmtEmployee->bindParam(':email', $email);
            $stmtEmployee->execute();
            $numRows = $stmtEmployee->rowCount();
            #echo $numRows;
            if ($stmtEmployee->rowCount() === 1) {
                $row = $stmtEmployee->fetch(PDO::FETCH_ASSOC);
                $db_password = $row['Password'];
                $role = $row['Role'];
                $db_id = $row['EmployeeID'];

                // debug 
                #echo "Employee found: Role = $role, ID = $db_id<br>";

                if (password_verify($password, $db_password)) {
                    $isLoggedIn = true;
                    #echo "Password verified for employee.<br>"; 
                }

            }
        }

        if ($isLoggedIn) {
            $_SESSION["LoggedIn"] = $role; // storing role in session
            $_SESSION['ID'] = $db_id; // store ID in session
            header("Refresh:0; url=index.php"); // main page, nav changing dynamically
            exit();
        } else {
            echo "Invalid email or password. Please try again.";
        }
    }
}
else
{
    echo '<div class="container">
            <h2>Already logged in</h2>
            <p>You are already logged in! To log in with another account, please log out first. Return to homepage: <a style="text-decoration:underline" href="index.php">Back to Homepage</a></p>
            </div>';
}
//php -S localhost:8000
//http://localhost:8000/loginPage.php


include 'loginPage.html';
echo '<script type="text/javascript" src="script.js"></script>';
include 'footer.html';
?>