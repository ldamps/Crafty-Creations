<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations Login Page</title>
    
    <style>
        Body {
            background-color: #afebf1;
        }

        .loginContainer {
            padding: 25px;
            background-color: #ebfdff;
        }

        form {
            border: 3px solid #000;
        }

        input[type=text], input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid #000;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 15px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
            background-color: #afebf1;
        }

    </style>
</head>
<body>
    <center><h1>Please Log In Below: </h1></center>
    <form>
        <div class="loginContainer">
            <label>Email: </label>
            <input type="text" placeholder="Email address..." required>
            <br>
            <label>Password: </label>
            <input type="password" placeholder="Password..." required>

            <button type="submit">Login</button>

            <center><a href="createAccount.php"> Create Account </a></center>
            <center><a href="forgotPassword.php">Forgot Password</a></center>
        </div>
    </form>
</body>