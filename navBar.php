<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> <!--allow jquery-->
    <script src="https://kit.fontawesome.com/f06d9443ee.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav id ="help" class="topnav">
        <a class="company" href="index.php">Crafty Creations</a>
        <ul>
        <?php
            session_start();
            //unset($_SESSION["LoggedIn"]);
             // remove all extras
            if (isset($_POST['logout'])) {
                // delete user cookie
                setcookie("CustomerID", "", time() - 3600);
            }

            if (isset($_COOKIE["CustomerID"])) {
                
                echo "<a class='button' type = 'submit' href='userProfile.php'>Profile <i class='fa-regular fa-user'></i></a>";

                // https://forums.phpfreaks.com/topic/71426-solved-sending-post-data-using-a-hyperlink/
                echo "<form id='form' name='logoutForm' method='post'><input type='hidden' name='logout' value='true'></form>
                <a id='logout' class='button' onclick='submit();' href='javascript:;' >log out</a>";
            }
            else if (isset($_COOKIE["ShopEmployeeID"]))
            {
                "<a class='button' href='stock.php'>Stock Levels</a>";
                "<a class='button' href='orderHistory.php'>Order History</a>";
            }
            else if (isset($_COOKIE["ManagerID"]))
            {
                "<a class='button' href='supplier.php'>Supplier</a>";
                "<a class='button' href='stock.php'>Stock Levels</a>";
                "<a class='button' href='orderHistory.php'>Order History</a>";
            }
            else if (isset($_COOKIE["OfficeEmployeeID"]))
            {
                "<a class='button' href='payroll.php'>Payroll</a>";
            }
            else
            {      /*
            if (isset($_SESSION["LoggedIn"])) {
                echo "<a class='button' type='submit' href='userProfile.php'>Profile <i class='fa-regular fa-user'></i></a>";
                echo "<form method='post'><button class='logoutBtn' class='button' name='logout' href='index.php'>Log Out</button></form>";

                //getting role of user
                $role = $_SESSION["LoggedIn"]; 

                //change this later
                //role specific buttons
                if ($role === "customer") { 
                    echo "<a class='button' href='orderHistory.php'>My Orders</a>";
                    echo "<a class='button' href='cart.php'>Cart</a>";
                } elseif ($role === "Manager" || $role === "CEO") { 
                    echo "<a class='button' href='storeManager.php'>Manage Store</a>";
                } elseif ($role === "IT Support" || $role === "Website Development") { 
                    echo "<a class='button' href='systemStatus.php'>System Status</a>";
                    echo "<a class='button' href='updateWebsite.php'>Update Website</a>";
                } else { 
                    echo "<a class='button' href='employeeDashboard.php'>Dashboard</a>";
                }
            } else {
              */
                echo "<a class='button' href='loginPage.php'>log in | sign up</a>";
            }
        ?>
        </ul>
    </nav>


    <nav class="selection">

        <form method="post"><input type="text" placeholder="Search.." id="Search"></form>

        <div class="yarn button">
            <form method = "post"><button class="Selector dropbtn">Yarn<i class="fa fa-caret-down"></i></button></form>
            <div class="yarn-selection">
                <a href="#">Show All</a>
                <a href="#">Acrylic Yarn</a>
            </div>
        </div>

        <div class="Selector fabric button">
            <form method = "post"><button class="dropbtn">Fabric<i class="fa fa-caret-down"></i></button></form>
            <div class="fabric-selection">
                <a href="#">Show All</a>
            </div>
        </div>

        <div class="Selector paint button">
            <form method ="post"><button class="dropbtn">Paint<i class="fa fa-caret-down"></i></button></form>
            <div class="paint-selection">
                <a href="#">Show All</a>
            </div>
        </div>

        <div class="Selector Tool button">
            <form method = "post"><button class="dropbtn">Tool<i class="fa fa-caret-down"></i></button></form>
            <div class="Tool-selection">
                <a href="#">Show All</a>
            </div>
        </div>
    </nav>
</body>

</html>
<script>

function submit()
{
    // took this from here - https://stackoverflow.com/questions/1960240/jquery-ajax-submit-form
    // add logout to form data
    var formData = new FormData();
    formData.append('logout', 'true');
    $.ajax({
        type: "POST",
        url: "index.php", // post to same page
        data: formData,
        processData: false,
        contentType: false,
        error: function(jqXHR, textStatus, errorMessage) {
            console.log(errorMessage); 
        },
        success: function(data) {window.location.reload();} 
    });
}
</script>