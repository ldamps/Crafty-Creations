<?php

include 'db.php';
include 'navBar.php';

// check for successful login first
if (isset($_SESSION['LoggedIn']) && ($_SESSION["LoggedIn"] === "customer" || $_SESSION["LoggedIn"] === "Shop Assistant" || $_SESSION["LoggedIn"] === "Supervisor" || $_SESSION["LoggedIn"] === "Manager" || $_SESSION['LoggedIn'] === "Assistant Manager" || $_SESSION["LoggedIn"] === "CEO" || $_SESSION["LoggedIn"] === "Human Resources" || $_SESSION["LoggedIn"] === "Payroll" || $_SESSION["LoggedIn"] === "IT Support" || $_SESSION["LoggedIn"] === "Administration" || $_SESSION["LoggedIn"] === "Website Development")):
    $role = $_SESSION["LoggedIn"];
    $userID = $_SESSION["ID"];
    //echo $role;
    if ($role === "customer") {
        //echo "customer";
        // === Queries for Customer ===
        // the ID happens in the view creation, so the view here will only have things relating to their ID
        $queryPersonal = "SELECT * From CustomerView";
        $stmtPersonal = $mysql->prepare($queryPersonal);
        $stmtPersonal->execute();
        $customerInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);

        $queryNumPostCodes = "SELECT Distinct AddressID, HouseNumber, Postcode, StreetName, City From CustomerView";
        $stmtPost = $mysql->prepare($queryNumPostCodes);
        $stmtPost->execute();
        $addresses = $stmtPost->fetchAll(PDO::FETCH_ASSOC);

        $queryPayment = "SELECT Distinct CardNumber, CVV, ExpiryDate From CustomerView";
        $stmtPay = $mysql->prepare($queryPayment);
        $stmtPay->execute();
        $payments = $stmtPay->fetchAll(PDO::FETCH_ASSOC);

        // handle the form for updating personal information
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["name"]) && isset($_POST["phone"])) {
            $name = $_POST["name"];
            $phone = $_POST["phone"];

            $name = trim($_POST["name"]); // removes extra spacess from the input
            $nameParts = preg_split('/\s+/', $name, 3);

            // check if there are at least 3 parts
            if (count($nameParts) < 3) {
                echo "Please enter your full name with a title, first name, and last name.";
                return;
            }

            $title = $nameParts[0];
            $firstname = $nameParts[1];
            $lastname = $nameParts[2];


            // prepare the SQL query to update the user's information
            $sql = "UPDATE Customer SET Title = :title, FirstName = :firstname, LastName = :lastname, PhoneNumber = :phone 
            WHERE CustomerID = :userID";

            $stmtUpdate = $mysql->prepare($sql);
            $stmtUpdate->bindParam(':title', $title, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':firstname', $firstname, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':userID', $userID, PDO::PARAM_INT);

            // check if the update was successful
            if ($stmtUpdate->execute()) {
                //redirect to the same page to reload
                //header('Location: ' . $_SERVER['PHP_SELF']);
                echo "Your information has been updated successfully!";
            } else {
                echo "Error updating your information: " . $stmtUpdate->errorInfo()[2];
            }
        }

        // handle the form for updating the address
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["street"]) && isset($_POST["city"]) && isset($_POST["postcode"]) && isset($_POST["addressID"])) {
            $street = $_POST["street"];
            $city = $_POST["city"];
            $postcode = $_POST["postcode"];
            $addressID = $_POST["addressID"];


            // Handle the form submission for updating the address
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["street"]) && isset($_POST["city"]) && isset($_POST["postcode"]) && isset($_POST["addressID"])) {
                $street = $_POST["street"];
                $city = $_POST["city"];
                $postcode = $_POST["postcode"];
                $addressID = $_POST["addressID"];
                $customerID = $_SESSION["ID"];

                $street = trim($_POST["street"]);  // removes extra spaces from the input

                $streetParts = preg_split('/\s+/', $street, 2); // split by spaces into at least 2 parts

                // check if there are at least 2 parts
                //possibly add more validation to check if the address is valid
                if (count($streetParts) < 2) {
                    echo "Please enter an address with at least a House Number and Street Name.";
                    return;
                }

                $houseNo = $streetParts[0];
                $streetName = $streetParts[1];

                $sql = "UPDATE CustomerAddress SET HouseNumber = :houseNo, StreetName = :streetName, City = :city, Postcode = :postcode, Customer_CustomerID = :customerID 
                WHERE AddressID = :addressID";

                $stmtUpdate = $mysql->prepare($sql);
                $stmtUpdate->bindParam(':addressID', $addressID, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':houseNo', $houseNo, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':streetName', $streetName, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':city', $city, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':postcode', $postcode, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':customerID', $customerID, PDO::PARAM_STR);

                // check if update was successful
                if ($stmtUpdate->execute()) {
                    // redirect to the same page to reload
                    //header('Location: ' . $_SERVER['PHP_SELF']);
                    echo "Your address has been updated successfully!";
                } else {
                    echo "Error updating your address: " . $stmtUpdate->errorInfo()[2];
                }
            }
        }



    } else {
        if ($role === "Manager" || $role === "Assistant Manager") {
            //echo "help2";
            $queryPersonal = "SELECT *  FROM ManagerView";
            $stmtPersonal = $mysql->prepare($queryPersonal);
            $stmtPersonal->execute();
            $personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
            echo $personalInfo["FirstName"];
        } else if ($role === "Shop Assistant" || $role === "Supervisor") {
            $queryPersonal = "SELECT FirstName, Surname, EmailAddress, hoursWorked, StreetName, City, Postcode, NumEmployees, TotalSales, ManagerFirstName, ManagerSurname FROM ShopEmployeeView";
            $stmtPersonal = $mysql->prepare($queryPersonal);
            $stmtPersonal->execute();
            $personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
        } else if ($role === "IT Support" || $role === "Website Development" || $role === "Payroll" || $role === "Administration" || $role === "Human Resources" || $role === "CEO") {
            $queryPersonal = "SELECT FirstName, Surname, EmailAddress, hoursWorked, OfficeName, Location FROM OfficeEmployeeView WHERE EmployeeID = :ID";
            $stmtPersonal = $mysql->prepare($queryPersonal);
            $stmtPersonal->execute(["ID" => $userID]);
            $personalInfo = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
        }
    }


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 80%;
                margin: 30px auto;
                padding: 20px;
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }

            h1 {
                text-align: center;
                color: #333;
            }

            h2 {
                color: #555;
                font-size: 1.5em;
            }

            .section {
                margin-bottom: 20px;
            }

            p {
                font-size: 1em;
                color: #555;
            }

            /* order history table */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            table,
            th,
            td {
                border: 1px solid #ddd;
            }

            th,
            td {
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #f0f0f0;
                color: #333;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            /* makes the page responsive */
            @media screen and (max-width: 768px) {
                .container {
                    width: 95%;
                }

                table,
                th,
                td {
                    font-size: 0.9em;
                }
            }
        </style>

        <title>My Account</title>
    </head>

    <body>
        <div class="container">
            <h1>Account Details</h1>
            <!-- personal information -->
            <div class="section">
                <h2>Personal Information</h2>
                <?php if (isset($_SESSION["LoggedIn"]) && $role === "customer"): ?>
                    <form id="personalInfoForm" method="POST" action="userProfile.php">
                        <p><strong>Name:</strong>
                            <span
                                id="nameDisplay"><?php echo $customerInfo['Title'] . ' ' . $customerInfo['FirstName'] . ' ' . $customerInfo['LastName']; ?></span>
                            <input type="text" id="nameInput" name="name"
                                value="<?php echo $customerInfo['Title'] . ' ' . $customerInfo['FirstName'] . ' ' . $customerInfo['LastName']; ?>"
                                style="display:none;">
                        </p>
                        <p><strong>Email:</strong>
                            <span id="emailDisplay"><?php echo $customerInfo['EmailAddress']; ?></span>
                            <input type="email" id="emailInput" name="email"
                                value="<?php echo $customerInfo['EmailAddress']; ?>" style="display:none;">
                        </p>
                        <p><strong>Phone:</strong>
                            <span id="phoneDisplay"><?php echo $customerInfo['PhoneNumber']; ?></span>
                            <input type="text" id="phoneInput" name="phone" value="<?php echo $customerInfo['PhoneNumber']; ?>"
                                style="display:none;">
                        </p>

                        <!-- Edit button -->
                        <button type="button" class="edit-btn" onclick="enableEditing()">Edit</button>
                        <button type="submit" class="edit-btn" style="display:none;" id="saveBtn" onclick="reload()">Save</button>
                    </form>
                    <?php else: ?> 
                    <p><strong>Name:</strong> <?php echo $personalInfo['FirstName'] . ' ' . $personalInfo['Surname']; ?></p>
                    <p><strong>Email:</strong> <?php echo $personalInfo['EmailAddress']; ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst($role); ?></p>
                    <p><strong>Hours Worked:</strong> <?php echo $personalInfo['hoursWorked']; ?></p>
                <?php endif; ?>
            </div>
            <?php if ($role === "Shop Assistant" || $role === "Supervisor" || $role === "Manager" || $role === "Assistant Manager"): ?>
                <div class="section">
                    <h2>Workplace Information</h2>
                    <?php if ($personalInfo): ?>
                        <p><strong>Store Address:</strong>
                            <?php echo $personalInfo['StreetName'] . ', ' . $personalInfo['City'] . ', ' . $personalInfo['Postcode']; ?>
                        </p>
                        <p><strong>Number of Employees:</strong> <?php echo $personalInfo['NumEmployees']; ?></p>
                        <p><strong>Total Sales:</strong> $<?php echo number_format($personalInfo['TotalSales'], 2); ?></p>
                        <p><strong>Store Manager:</strong>
                            <?php echo $personalInfo['ManagerFirstName'] . ' ' . $personalInfo['ManagerSurname']; ?></p>
                    <?php else: ?>
                        <p>No workplace information available.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($role === "CEO" || $role === "IT Support" || $role === "Website Development" ||$role ==="Payroll" || $role === "Administration" || $role === "Human Resources"): ?>
                <h2>Workplace Information</h2>
                <?php if ($personalInfo): ?>
                    <p><strong>Office Name:</strong> <?php echo $personalInfo['OfficeName'] ?></p>
                    <p><strong>Location:</strong> <?php echo $personalInfo['Location']; ?></p>
                <?php endif ?>
            <?php endif; ?>


            <!-- address -->
            <?php if ($role === "customer"): ?>
                <div class="section">
                    <h2>Address</h2>
                    <?php if (sizeof($addresses) == 0): ?>
                        <p>No addresses saved.</p>
                    <?php endif;
                    $i = 0; ?>
                    <?php foreach ($addresses as $address): ?>
                        <form id="addressForm<?php echo $i; ?>" method="POST" action="userProfile.php">
                            <!-- Hidden AddressID input -->
                            <input type="hidden" id="addressID<?php echo $i; ?>" name="addressID"
                                value="<?php echo $address['AddressID'] ?>" <p><strong>Street:</strong>
                            <span
                                id="streetDisplay<?php echo $i; ?>"><?php echo $address['HouseNumber'] . ' ' . $address['StreetName']; ?></span>
                            <input type="text" id="streetInput<?php echo $i; ?>" name="street"
                                value="<?php echo $address['HouseNumber'] . ' ' . $address['StreetName']; ?>" style="display:none;">
                            </p>
                            <p><strong>City:</strong>
                                <span id="cityDisplay<?php echo $i; ?>"><?php echo $address['City']; ?></span>
                                <input type="text" id="cityInput<?php echo $i; ?>" name="city"
                                    value="<?php echo $address['City']; ?>" style="display:none;">
                            </p>
                            <p><strong>Postcode:</strong>
                                <span id="postcodeDisplay<?php echo $i; ?>"><?php echo $address['Postcode']; ?></span>
                                <input type="text" id="postcodeInput<?php echo $i; ?>" name="postcode"
                                    value="<?php echo $address['Postcode']; ?>" style="display:none;">
                            </p>

                            <!-- Edit button -->
                            <button type="button" class="edit-btn" onclick="enableAddressEditing(<?php echo $i; ?>)">Edit</button>
                            <button type="submit" class="edit-btn" style="display:none;" id="saveBtn<?php echo $i; ?>">Save</button>
                        </form>
                        <br>
                        <?php $i++; endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- payment method - only diplays last 4 digits of card-->
            <?php if ($role === "customer"): ?>
                <div class="section">
                    <h2>Payment Methods</h2>
                    <?php if (count($payments) == 0): ?>
                        <p>No payment methods saved.</p>
                    <?php endif; ?>
                    <?php foreach ($payments as $payment): ?>

                        <p><strong>Card Number:</strong> **** **** **** <?php echo substr($payment['CardNumber'], -4); ?></p>
                        <p><strong>Expiry Date:</strong> <?php echo $payment['ExpiryDate']; ?></p>
                        <p><strong>CVV:</strong> ***</p>
                        <br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </body>
<?php else: ?>
    <div class="container">
        <h2>Unauthorised Access</h2>
        <p>You are not authorised to view this page. Return to homepage: <a style="text-decoration:underline"
                href="index.php">Back to Homepage</a></p>
    </div>
<?php endif; ?>


<script>
    // Function to enable editing for personal information
    function enableEditing() {
        // hide the display text and show input fields for personal info
        document.getElementById("nameDisplay").style.display = "none";
        document.getElementById("nameInput").style.display = "inline";

        document.getElementById("phoneDisplay").style.display = "none";
        document.getElementById("phoneInput").style.display = "inline";

        // Show the save button and hide the edit button
        document.getElementById("saveBtn").style.display = "inline";
        document.querySelector(".edit-btn").style.display = "none";
    }

    // Function to enable editing for address fields
    function enableAddressEditing(i) {
        // Address fields
        document.getElementById("streetDisplay" + i).style.display = "none";
        document.getElementById("streetInput" + i).style.display = "inline";

        document.getElementById("cityDisplay" + i).style.display = "none";
        document.getElementById("cityInput" + i).style.display = "inline";

        document.getElementById("postcodeDisplay" + i).style.display = "none";
        document.getElementById("postcodeInput" + i).style.display = "inline";

        // show the save button for this specific address and hide the edit button
        document.getElementById("saveBtn" + i).style.display = "inline";
        document.querySelectorAll(".edit-btn")[i].style.display = "none";
    }

    function reload() {
        window.location.reload();
    }

</script>

<script type="text/javascript" src="script.js"></script>

</html>



<?php include 'footer.php'; ?>