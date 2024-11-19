<?php
require 'db.php'; 

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

include 'createAccount.html';

?>

<?php
// check if the message is stored in the session
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // clears the message
}
?>