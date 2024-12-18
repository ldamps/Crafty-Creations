<?php include('navBar.php'); ?>


<?php

require 'db.php'; 

//if a form is submitted
if (isset($_POST['submit'])) {

        // assign variables
        $title = $_POST['title'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phonenumber = $_POST['phonenumber'];
        $emailaddress = $_POST['emailaddress'];
        $password = $_POST['password']; 
        $repeatpassword = $_POST['repeatpassword'];

    
     $errors = []; // array to store any erorrs


    // validate email
    //filter checks if the email follows the general format
    if (!filter_var($emailaddress, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // check if there is already an account associated with the email
    if (empty($errors)) {
        $stmt = $mysql->prepare("SELECT COUNT(*) FROM Customer WHERE EmailAddress = :EmailAddress");
        $stmt->bindParam(':EmailAddress', $emailaddress);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errors[] = "An account with this email address already exists.";
        }
    }

    // validate phone number
    if (!preg_match('/^\d{11}$/', $phonenumber)) {
        $errors[] = "Invalid phone number. It should contain exactly 11 digits.";
    }

    // validate password match
    if ($password !== $repeatpassword) {
        $errors[] = "Passwords do not match.";
    }

    // store errors in session 
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: createAccount.php"); 
        exit();
    }

     // hash the password 
     $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

    //only add an account if there are no errors
    if (empty($errors)) {

    // prepare the SQL statement
    $stmt = $mysql->prepare("START TRANSACTION; INSERT INTO Customer (Title, FirstName, LastName, PhoneNumber, EmailAddress, Password)
    VALUES (:Title, :FirstName, :LastName, :PhoneNumber, :EmailAddress, :Password);
    COMMIT;");
    

    // bind parameters
    $stmt->bindParam(":Title", $title);
    $stmt->bindParam(":FirstName", $firstname);
    $stmt->bindParam(":LastName", $lastname);
    $stmt->bindParam(":PhoneNumber", $phonenumber);
    $stmt->bindParam(":EmailAddress", $emailaddress);
    $stmt->bindParam(":Password", $hashedPassword);

    // execute the statement
    $stmt->execute();

     // store the success message 
    $_SESSION['message_success'] = "Account created successfully";

    // prevent resubmission
     header("Location: loginPage.php"); //redirect to the login page
    exit(); 
    }
}

include 'createAccount.html';

?>

<!-- modal for error messages-->
<?php if (isset($_SESSION['errors'])): ?>
    <div id="errorModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Error</h2>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<script>
    function closeModal() {
        document.getElementById('errorModal').style.display = 'none';
    }

    // If errors are set, show error modal on page load
    <?php if (isset($_SESSION['errors'])): ?>
        document.getElementById('errorModal').style.display = 'block';
    <?php endif; ?>
</script>
<script type="text/javascript" src="script.js"></script>
<?php include 'footer.php'; ?>