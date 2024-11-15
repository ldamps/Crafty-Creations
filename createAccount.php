<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations Create Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .createContainer {
            padding: 25px;
            background-color: #ffff;
        }

        form {
            width: 75%;
            border: 3px solid #000;
            margin: auto;
        }

        input[type=text], input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid #000;
            box-sizing: border-box;
        }

        select {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        label {
            display: flex;
            margin: 0 auto;
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
    <center><h1>Create an Account:</h1></center>
    <br>
    <form>
        <div class="createContainer">
            <label for="title">Title: </label>
            <select name="titles" id="title">
                <option value="miss">Miss</option>
                <option value="mrs">Mrs</option>
                <option value="ms">Ms</option>
                <option value="dr">Dr</option>
                <option value="mr">Mr</option>
            </select>

            <label>First name: </label>
            <input type="text" placeholder="First name..." required>

            <label>Last name: </label>
            <input type="text" placeholder="Last name..." required>

            <label>Email: </label>
            <input type="text" placeholder="Email address..." required>

            <label>Phone Number: </label>
            <input type="text" placeholder="Phone number..." required>

            <label>Password: </label>
            <input type="password" placeholder="Choose password..." required>

            <label>Repeat password: </label>
            <input type="password" placeholder="Repeat password..." required>
            
            <center><ul>
                <a id='logInText' class="button" href="#">Create</a>
            </ul></center>
        </div>
    </form>
    <br>
</body>
