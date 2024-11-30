<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations</title>
    <link rel="stylesheet" href="style.css">

    <style>
        footer {
            box-sizing: border-box;
            overflow: hidden;
            padding-left: 0.5%;
            padding-right: 0.5%;
            margin-top: 200px;
        }

        footer h2 {
            font-size: 15;
        }

        footer p {
            font-size: 12;
        }

        footer a {
            font-size: 12;
        }

        footer a:hover {
            color: gray;
        }

        footer ul {
            font-size: 12;
        }

        .column {
            float: left;
            padding: 10px;
        }

        .About {
            width: 40%;
        }

        .Links {
            width: 20%;
        }

        .Contact {
            width: 35%;
        }

        .footerRow:after {
            content: "";
            display: table;
            clear: both;
        }

        

        @media screen and (max-width: 600px) {
            .column {
            width: 100%;
            }
        }

        .copyright {
            background-image: url(rainbowTexture1.png);
            padding: 10px;
        }
    </style>
</head>
<footer>
    <div class="footerRow">
        <div class="column About">
            <h2>about us</h2>
            <p>crafty creations is a leading crafts chain retailer which sells a variety of craft supplies and tools, including yarn, paint, fabric and other crafting essentials. With a growing number of physical stores within the UK and an online store, crafty creations has become the go-to stop for both beginner and professional crafters. with its wide range of products, the company allows artists to craft their dreams into reality!</p>
        </div>

        <ul>
            <?php
                if (isset($_SESSION["LoggedIn"])) {
                    //getting role of user
                    $role = $_SESSION["LoggedIn"]; 

                    if ($role === "customer") {
                        echo "<div class='column Links'><h2>quick links</h2></li><li><a href='index.php'>homepage</a></li><li><a href='about.php'>about us</a></li> <li><a href='#'>tools</a></li><li><a href='#'>paint</a></li><li><a href='#'>fabric</a></li><li><a href='#'>yarn</a></li><li><a href='termsConditions.php'>terms and conditions</a></li><li><a href='privacy.php'>privacy policy</a></li></div>";
                    } elseif ($role === "Manager" || $role === "Assisstant Manager") { 
                        echo "<div class='column Links'><h2>quick links</h2></li><li><a href='index.php'>homepage</a></li><li><a href='about.php'>about us</a></li><li><a href='Employees.php'>employees</a></li><li><a href='stockPage.php'>stock levels</a></li><li><a href='ShopOrderHistory.php'>shop order history</a></li><li><a href='supplierPage.php'>supplier</a></li><li><a href='salesPage.php'>sales</a></li><li><a href='termsConditions.php'>terms and conditions</a></li><li><a href='privacy.php'>privacy policy</a></li></div>";
                    } elseif ($role === "IT Support" || $role === "Website Development" ||$role ==="Payroll" || $role === "Administration" || $role === "Human Resources" || $role=== "CEO") { 
                        echo "<div class='column Links'><h2>quick links</h2><li><a href='index.php'>homepage</a></li><li><a href='about.php'>about us</a></li> <li><a href='employees.html'>employee details</a></li><li><a href='payroll.php'>payroll</a></li><li><a href='salesPage.php'>sales</a></li><li><a href='supplierPage.php'>suppliers</a></li><li><a href='termsConditions.php'>terms and conditions</a></li><li><a href='IT.php'>IT</a></li></div>";
                    } else {
                        echo "<div class='column Links'><h2>quick links</h2><li><a href='about.php'>about us</a></li> <li><a href='stockPage.html'>stock levels</a></li><li><a href='ShopOrderHistory.php'>shop order history</a></li><li><a href='termsConditions.php'>terms and conditions</a></li><li><a href='privacy.php'>privacy policy</a></li></div>";
                    }
                    // https://forums.phpfreaks.com/topic/71426-solved-sending-post-data-using-a-hyperlink/
                } else {
                    echo "<div class='column Links'><h2>quick links</h2><li><a href='loginPage.php'>log in</a></li><li><a href='index.php'>Homepage</a></li><li><a href='about.php'>about us</a></li> <li><a href='#'>tools</a></li><li><a href='#'>paint</a></li><li><a href='#'>fabric</a></li><li><a href='#'>yarn</a></li><li><a href='termsConditions.php'>terms and conditions</a></li><li><a href='privacy.php'>privacy policy</a></li></div>";
                }
            ?>
        </ul>

        <div class="column Contact">
            <h2>contact us</h2>
            <ul>craftyEnquires@craftycreations.com</ul>
            <br>
            <ul>alternatively if you would rather phone us, you can call us on the the following telephone number: +44 07856 987654</ul>
        </div>
    </div>

    <div class="copyright">
        <p class="copyright-text">Copyright &copy; 2017 All Rights Reserved by Crafty Creations</p>
    </div>
</footer>

<script>
    
    window.onload = checkScroll();
    window.resize = checkScroll();

    
    function checkScroll()
    {
        let html = document.documentElement;
        let footer = document.getElementsByTagName('footer')[0];
        console.log('scrollHeight: ' + html.scrollHeight);
        console.log('offsetHeight: ' + html.offsetHeight);
        if (html.scrollHeight > html.offsetHeight + 1)
        {
            footer.style.position = 'absolute';
            footer.style.bottom = '0';
        }
    }

</script>

</html>