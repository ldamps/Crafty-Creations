<?php include 'navBar.php'; ?>

<?php
require 'db.php';
include 'addNewSupplier.html';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_POST['addSupplier'])){
    }

    if(isset($_POST['addSupplier'])){
        $_SESSION['addSupplier'] = $_POST['addSupplier'];
        
    }
}


if (isset($_SESSION['addSupplier'])){

    $name = $_SESSION['addSupplier'][0];
    $address = $_SESSION['addSupplier'][3];
    $emial = $_SESSION['addSupplier'][2];
    $supplyType = $_SESSION['addSupplier'][1];
    // $query = "INSERT INTO Supplier (Name, Address, ProductTypeSupplied, Email) VALUES ('$name', '$address', '$supplyType', '$email')";
    // $query = $mysql->prepare($query);
    // $query->execute();
    echo "<h1>Supplier Added</h1>";
    echo "<a class = 'return' href = 'suppliers.php'><h4>Click to return to suppliers page</h4></a>";

    
    unset($_SESSION['addSupplier']);
    unset($_POST['addSupplier']);
}
?>

<script type="text/javascript" src="script.js"></script>
