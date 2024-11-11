<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations Create Account</title>
    
    <style>
        Body {
            background-color: #afebf1;
        }

        .createContainer {
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
    <center><h1>Create an account </h1></center>
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

            <button type="submit">Create</button>

        </div>
    </form>
</body>