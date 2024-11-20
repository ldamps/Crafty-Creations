<?php
session_start();

// check if there is a success message in the session
if (isset($_SESSION['message_success'])):
?>

<!-- success modal -->
<div id="successModal" class="modal" style="display: block;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Success</h2>
        <p><?= htmlspecialchars($_SESSION['message_success']) ?></p>
    </div>
</div>

<?php 
    // stops it showing on page reload
    unset($_SESSION['message_success']);
endif;
?>

<script>
    function closeModal() {
        document.getElementById('successModal').style.display = 'none';
    }

    // if there is a success message, display it when loading the page
    <?php if (isset($_SESSION['message_success'])): ?>
        document.getElementById('successModal').style.display = 'block';
    <?php endif; ?>
</script>


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
        if (password_verify($password, $db_password)) {  // used password_verify() cause hashing is used
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

include 'loginPage.html';
include 'footer.html';
?>


