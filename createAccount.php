<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations Create Account</title>
    
    <style>
        Body {
            background-color: #afebf1;
        }

        .createContainer {
            padding: 25px;
            background-color: #ebfdff;
        }

        form {
            border: 3px solid #000;
        }

        input[type=text], input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid #000;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 15px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
            background-color: #afebf1;
        }

        select {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        label {
            display: flex;
            margin: 0 auto;
        }

    </style>
</head>
<body>

<?php
session_start(); // start the session

include 'db.php'; //database connection

//if a form is submitted
if (isset($_POST['submit'])) {

        // prepare the SQL statement
        $stmt = $mysql->prepare("INSERT INTO Customer (Title, FirstName, LastName, PhoneNumber, EmailAddress, Password)
        VALUES (:Title, :FirstName, :LastName, :PhoneNumber, :EmailAddress, :Password)");
        

        // bind parameters
        $stmt->bindParam(":Title", $title);
        $stmt->bindParam(":FirstName", $firstname);
        $stmt->bindParam(":LastName", $lastname);
        $stmt->bindParam(":PhoneNumber", $phonenumber);
        $stmt->bindParam(":EmailAddress", $emailaddress);
        $stmt->bindParam(":Password", $password);

        // assign variables
        $title = $_POST['title'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phonenumber = $_POST['phonenumber'];
        $emailaddress = $_POST['emailaddress'];
        $password = $_POST['password']; 

        // execute the statement
        $stmt->execute();

         // store the success message 
        $_SESSION['message'] = "Account created successfully";

        // prevent resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); 
}
?>
 <center><h1>Create an Account</h1></center>
 
 <?php
// check if the message is stored in the session
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // clears the message
}
?>
    <form name="Add New Customer Account" method="post" action="">
        <div class="createContainer">
            <label for="title">Title: </label>
            <select name="title" id="title" required>
                <option value="Miss">Miss</option>
                <option value="Mrs">Mrs</option>
                <option value="Ms">Ms</option>
                <option value="Dr">Dr</option>
                <option value="Mr">Mr</option>
            </select><br>

            <label for="firstname">First Name: </label>
            <input type="text" id="firstname" name="firstname" placeholder="First name..." required><br>

            <label for="lastname">Last Name: </label>
            <input type="text" id="lastname" name="lastname" placeholder="Last name..." required><br>

            <label for="emailaddress">Email: </label>
            <input type="text" id="emailaddress" name="emailaddress" placeholder="Email address..." required><br>

            <label for="phonenumber">Phone Number: </label>
            <input type="text" id="phonenumber" name="phonenumber" placeholder="Phone number..." required><br>

            <label for="password">Password: </label>
            <input type="password" id="password" name="password" placeholder="Choose password..." required><br>

            <label for="repeatpassword">Repeat Password: </label>
            <input type="password" id="repeatpassword" name="repeatpassword" placeholder="Repeat password..." required><br>

            <button type="submit" name="submit" value="submit">Create</button>
            
        </div>
    </form>
</body>