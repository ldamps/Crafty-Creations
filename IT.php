<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .container {
        width: 80%;
        margin: 30px auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    
    .section {
        margin-bottom: 20px;
    }
</style>

<body>
    <?php include 'navBar.php'?>
    <?php require 'db.php';
    // check they are from the main office before allowing access to website
    if (isset($_SESSION['LoggedIn']) && $_SESSION["LoggedIn"] === "CEO"):?>
    
    <div class="container">
    <h1 >IT Service</h1>
    <br>
    <p>Updates and technical details for the website can be found here: <a style="text-decoration:underline" href="https://github.com/ldamps/Crafty-Creations">IT Information</a></p>
    
    </div>
    <?php else:?>
        <div class="container">
        <p>Unauthorised Access: <a style="text-decoration:underline" href="index.php">Back to Homepage</a></p>
        </div>
    <?php endif;?>
</body>

</html>
<?php include 'footer.html'?>