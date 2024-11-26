<?php include 'navBar.php'; ?>

<?php
require 'db.php';
include 'suppliers.html';

class Supplier{
    var $name;
    var $supplierID;
    var $address;
    var $supplyType;
}

if (!isset($_SESSION)){
    session_start();  
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // if (!isset($_SESSION['supplierDetails'])){
    //     $_SESSION['supplierDetails'] = "";
    // }

    if(isset($_POST['supplierDetails'])){
        echo "<h1>Supplier Details</h1>";
        $_SESSION['supplierDetails'] = $_POST['supplierDetails'];
    }

}

if(isset($_SESSION['supplierDetails'])){
    $id = $_SESSION['supplierDetails'];
    $query = "SELECT * FROM Supplier WHERE SupplierID = '$id'";
    $query = $mysql->prepare($query);
    $query->execute();
    $supplierDetails = $query->fetchAll();
    
}

$query = "SELECT Name,ProductTypeSupplied,SupplierID FROM Supplier";
$query = $mysql->prepare($query);
$query->execute();
$result = $query->fetchAll();
$suppliers = array();

foreach($result as $item){
    $supplier = new Supplier();
    $supplier->name = $item["Name"];
    $supplier->supplyType = $item["ProductTypeSupplied"];
    $supplier->supplierID = $item["SupplierID"];
    array_push($suppliers, $supplier);
}

if(isset($_SESSION['supplierDetails'])){

    echo "<br><h2>Selected Supplier Details</h2>";
    echo "<h4>Company: ".$supplierDetails[0]["Name"]."</h3>";
    echo "<h4>Address: ".$supplierDetails[0]["Address"]."</h3>";
    echo "<h4>Supply Type: ".$supplierDetails[0]["ProductTypeSupplied"]."</h3>";
    echo "<h4>Email: ".$supplierDetails[0]["Email"]."</h3></br>";    

    // unset($_SESSION['supplierDetails']);
}


$supplier = null;
if (count($suppliers) != 0){
    // make table
    echo "<br><table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Company</th>";
    echo "<th>Supply Type</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    //For each supplier, display their details as a table row
    foreach($suppliers as $supplier){
        echo "<tr>";
        echo "<td>".$supplier->name."</td>";
        echo "<td>".$supplier->supplyType."</td>";
        echo "<td><form method = 'post'><button  class = 'supplierButton Button' id =".$supplier->supplierID.">Get Details</button></form></td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table></br>";
    }
else{
    echo "<br><h2>No suppliers found</h2></br>";
}

?>
<script type="text/javascript" src="script.js"></script>
