<?php include 'navBar.php'; ?>

<?php
require 'db.php';
include 'addNewSupplier.html';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // echo "<p>post</p>";
    if(isset($_POST['addSupplier'])){
        // echo "<h1>Supplie</h1>";
        $_SESSION['addSupplier'] = $_POST['addSupplier'];
    }
    if(isset($_SESSION['addSupplier'])){
        $newSupplier = explode(",",$_SESSION['addSupplier']);
    }
}


if (isset($_SESSION['addSupplier'])){
    // echo "<h1>Supplier Details</h1>";
    $name = $newSupplier[0];
    $address = $newSupplier[3];
    $email = $newSupplier[2];
    $supplyType = $newSupplier[1];
    // echo $name." ".$address." ".$email." ".$supplyType;

    $query = "INSERT INTO Supplier (Name, Address, ProductTypeSupplied, Email) VALUES ('$name', '$address', '$supplyType', '$email')";
    $mysql->exec($query);
    // echo "<h1>Supplier Added</h1>";
    // echo "<a class = 'return' href = 'suppliers.php'><h4>Click to return to suppliers page</h4></a>";

    unset($_SESSION['addSupplier']);
    unset($_POST['addSupplier']);
}
?>

<script type="text/javascript" src="script.js"></script>
