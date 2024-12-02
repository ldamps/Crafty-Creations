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
        <a class="company" href="index.php">Crafty Creations <i class="fa-solid fa-house fa-2xs"></i></a>
        <ul>
        <?php
            session_start();
            //unset($_SESSION["LoggedIn"]);
             // remove all extras
            if (isset($_POST['logout'])) {
                session_destroy();
                session_start();

            }

           
            if (isset($_SESSION["LoggedIn"])) {
                echo "<a class='button' type = 'submit' href='userProfile.php'>Profile <i class='fa-regular fa-user'></i></a>";

                //getting role of user
                $role = $_SESSION["LoggedIn"]; 

                //change this later
                //role specific buttons
                if ($role === "customer") { 
                    echo "<a class='button' href='orderHistory.php'>My Orders</a>";
                    echo    '<a class="button" href="basket.php" >Basket <i class="fa-solid fa-basket-shopping"></i></a>';
                } elseif ($role === "Manager" || $role === "Assisstant Manager") { 
                    echo "<a class='button' href='Employees.php'>Employees</a>";
                    echo "<a class='button' href='stockPage.php'>Stock Levels</a>";
                    echo "<a class='button' href='ShopOrderHistory.php'>Shop Order History</a>";
                    echo "<a class='button' href='supplierPage.php'>Supplier</a>";
                    echo "<a class='button' href='salesPage.php'>Sales</a>";
                } elseif ($role === "IT Support" || $role === "Website Development" ||$role ==="Payroll" || $role === "Administration" || $role === "Human Resources" || $role=== "CEO") { 
                    echo "<a class='button' href='Employees.php'>Employee Details</a>";
                    echo "<a class='button' href='payroll.php'>Payroll</a>";
                    echo "<a class='button' href='salesPage.php'>Sales</a>";
                    echo "<a class='button' href='supplierPage.php'>Suppliers</a>";
                    echo "<a class='button' href='IT.php'>IT</a>";
                } else { 
                    echo "<a class='button' href='stockPage.php'>Stock Levels</a>";
                    echo "<a class='button' href='ShopOrderHistory.php'>Shop Order History</a>";
                }
                // https://forums.phpfreaks.com/topic/71426-solved-sending-post-data-using-a-hyperlink/
                echo "<form id='form' name='logoutForm' method='post'><input type='hidden' name='logout' value='true'></form>
                <a id='logout' class='button' onclick='submit();' href='javascript:;' >Log Out <i class='fa-solid fa-right-from-bracket'></i></a>";
            } else {
                echo "<a class='button' href='loginPage.php'>log in | sign up <i class='fa-solid fa-lock'></i></a>";
                echo    '<a class="button" href="basket.php" >Basket <i class="fa-solid fa-basket-shopping"></i></a>';
            }
        ?>
        </ul>
    </nav>


    <nav class="selection">

        <form method="post"><input type="text" placeholder="Search.." id="Search">
        <a id="searchButton" class="button" onclick="submit();">⌕</a>

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
            <form method = "post"><button class="dropbtn">Tools<i class="fa fa-caret-down"></i></button></form>
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
    console.log("submit");
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
        success: function(data) {
            window.location.reload();
            
        } 
    });
}

function searchButton(){
    var search = new FormData();
    
    search.append('Search', document.getElementById('Search').value);
    console.log(search);
    $.ajax({
        type: "POST",
        url: "index.php", // post to same page
        data: formData,
        processData: false,
        contentType: false,
        error: function(jqXHR, textStatus, errorMessage) {
            console.log(errorMessage); 
        },
        success: function(data) {
            window.location.reload();
            searchButtonClick();
        }
    });
    
}

async function searchButtonClick()
{
    await new Promise(r => setTimeout(r, (500)));
    if (searchButton.innerHTML == 'X')
    {
        searchButton.innerHTML = '⌕'
    }
    else
    {
        searchButton.innerHTML = 'X'
    }
}
</script>