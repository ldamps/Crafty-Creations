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
    <nav class="topnav">
        <a class="company" href="index.php">Crafty Creations</a>
        <ul>
        <?php
            session_start();
            //unset($_SESSION["LoggedIn"]);
             // remove all extras
            if (isset($_POST['logout']) || isset($_POST['my_form'])) {
                unset($_SESSION["LoggedIn"]);
                // delete user cookie
                setcookie("ID", "", time() - 3600);
            }
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
                    echo "<a class='button' href='manageEmployees.php'>Manage Employees</a>";
                    echo "<a class='button' href='salesReports.php'>Sales Reports</a>";
                } elseif ($role === "IT Support" || $role === "Website Development") { 
                    echo "<a class='button' href='systemStatus.php'>System Status</a>";
                    echo "<a class='button' href='updateWebsite.php'>Update Website</a>";
                } else { 
                    echo "<a class='button' href='employeeDashboard.php'>Dashboard</a>";
                }
            } else {
                echo "<a class='button' href='loginPage.php'>log in | sign up</a>";
            }
        ?>
        </ul>
    </nav>


    <nav class="selection">
        <input type="text" placeholder="Search..">

        <div class="yarn button">
            <button class="dropbtn">Yarn
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="yarn-selection">
                <a href="#">Show All</a>
                <a href="#">Acrylic Yarn</a>
            </div>
        </div>

        <div class="fabric button">
            <button class="dropbtn">Fabric
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="fabric-selection">
                <a href="#">Show All</a>
            </div>
        </div>

        <div class="paint button">
            <button class="dropbtn">Paint
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="paint-selection">
                <a href="#">Show All</a>
            </div>
        </div>

    </nav>
</body>

</html>
