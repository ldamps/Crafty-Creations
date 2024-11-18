<?php
require 'db.php';  

$message = "";  // variable to store the feedback message

echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "<br>";

// if form was submitted
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    //capture email and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    //DEBUG
    //echo "Email entered: $email<br>";
    //echo "Password entered: $password<br>";
    
    // prep SQL statement preventing SQL injection
    $stmt = $mysql->prepare("SELECT Password FROM Customer WHERE EmailAddress = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    //DEBUG
    //echo "Rows found: " . $stmt->rowCount() . "<br>";

    //if email exists in database
    if ($stmt->rowCount() == 1) {
        // fetch hashed password from database
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $db_password = $row['Password'];

        // DEBUG
        //echo "Fetched password from DB: $db_password<br>";

        
        // verifying entered password with hashed pass
        if ($password === $db_password) {  // replace with password_verify() if hashing is used
            $message = "Login successful. Welcome!";
            // redirect to protected page or user dashboard here
        } else {
            $message = "Invalid password. Please try again.";
        }
    } else {
        $message = "No account found with that email.";

        // DEBUG
        $errorInfo = $stmt->errorInfo();
        echo "SQLSTATE Error Code: " . $errorInfo[0] . "<br>";
        echo "Driver-specific Error Code: " . $errorInfo[1] . "<br>";
        echo "Driver-specific Error Message: " . $errorInfo[2] . "<br>";
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations Login Page</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .loginContainer {
            padding: 25px;
            background-color: #ffff;
        }

        form {
            width: 75%;
            border: 3px solid #000;
            margin: auto;
        }

        input[type=text1], input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid #000;
            box-sizing: border-box;
        }


    </style>
</head>

<body>
    <nav class="topnav">
        <a class="company">Crafty Creations</a>
        <ul>
            <a id='logInText' class="button" href="loginPage.html">log in | sign up</a>
        </ul>
    </nav>

    <center><h1>Please Log In Below:</h1></center>

    <!-- feedback message -->
    <?php if ($message): ?>
        <p style="color: red; text-align: center;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="loginPage.php" method="post">
        <div class="loginContainer">
            <label>Email: </label>
            <input type="text1" name="email" placeholder="Email address..." required>
            <br>
            <label>Password: </label>
            <input type="password" name="password" placeholder="Password..." required>
            <br>
            <center><input type="submit" value="Log In" class="button"></center>
            <br>
            <center><ul>
                <a id='logInText' class="button" href="createAccount.php">Create Account</a>
                <a id='logInText' class="button" href="forgotPassword.php">Forgot Password</a>
            </ul></center>
        </div>
    </form>
</body>
</html>
