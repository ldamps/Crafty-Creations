<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations Forgot Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .forgotContainer {
            padding: 25px;
            background-color: #ffff;
        }

        form {
            width: 75%;
            border: 3px solid #000;
            margin: auto;
        }

        input[type=text1], input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid #000;
            box-sizing: border-box;
        }


    </style>
</head>

<body>
    <nav class="topnav">
        <a class="company">Crafty Creations</a>
        <ul>
            <a id='logInText' class="button" href="loginPage.html">log in | sign up</a>
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
    <br>
    <center><h1>Forgot Password</h1></center>
    <br>
    <form>
        <div class="forgotContainer">
            <label>Enter the email which your account is linked too: </label>
            <input type="text1" placeholder="Email address..." required>
            <br>
            <center><ul>
                <a id='logInText' class="button" href="loginPage.php">Continue</a>
            </ul></center>
        </div>
    </form>
</body>
