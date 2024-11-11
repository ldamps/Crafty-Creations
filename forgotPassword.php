<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations Forgot Password</title>
    
    <style>
        Body {
            background-color: #afebf1;
        }

        .forgotContainer {
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

        label {
            display: flex;
            margin: 0 auto;
        }

    </style>
</head>
<body>
    <center><h1>Forgot Password</h1></center>
    <form>
        <div class="forgotContainer">
            
            <label>Email: </label>
            <input type="text" placeholder="Email address..." required>

            <button type="submit">Continue</button>

        </div>
    </form>
</body>