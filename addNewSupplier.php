<?php include 'navBar.php'; ?>

<?php
require 'db.php';
if (isset($_SESSION['LoggedIn']) && ($_SESSION["LoggedIn"] === "Manager" || $_SESSION['LoggedIn'] === "Assistant Manager"|| ($_SESSION["LoggedIn"] === "CEO") || ($_SESSION["LoggedIn"] === "Human Resources") || ($_SESSION["LoggedIn"] === "Payroll") || ($_SESSION["LoggedIn"] === "IT Support") || ($_SESSION["LoggedIn"] === "Administration") || ($_SESSION["LoggedIn"] === "Website Development"))):


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // echo "<p>post</p>";
    if(isset($_POST['addSupplier'])){
        // echo "<h1>Supplie</h1>";
        $name = $_POST['name'];
        $supplyType = $_POST['type'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $_SESSION['addSupplier'] = $_POST['addSupplier'];
    }
    if(isset($_SESSION['addSupplier'])){
        $newSupplier = explode(",",$_SESSION['addSupplier']);
    }
}
if (isset($_SESSION['addSupplier']) && !($name == "" || $supplyType == "" || $email == "" || $address == "")){

    $query = "INSERT INTO Supplier (Name, Address, ProductTypeSupplied, Email) VALUES ('$name', '$address', '$supplyType', '$email')";
    $mysql->exec($query);
    // echo "<h1>Supplier Added</h1>";
    // echo "<a class = 'return' href = 'suppliers.php'><h4>Click to return to suppliers page</h4></a>";

    unset($_SESSION['addSupplier']);
    unset($_POST['addSupplier']);
    header("Refresh:0; url=supplierPage.php");
}
include 'addNewSupplier.html';?>
<?php else: ?>
    <div class="container">
        <h2>Unauthorised Access</h2>
        <p>You are not authorised to view this page. Return to homepage: <a style="text-decoration:underline"
                href="index.php">Back to Homepage</a></p>
    </div>
<?php endif; ?>
<script type="text/javascript" src="script.js"></script>
<?php include'footer.php';
?>


