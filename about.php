
<?php include('navBar.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafty Creations</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            padding: 5px;
        }
        footer {
            background-color: #E2F8FB;
            box-sizing: border-box;
            overflow: hidden;
            padding-left: 0.5%;
            padding-right: 0.5%;
            position:absolute;
            bottom:0;
        }

        h2 {
            text-decoration: underline;
            font-size: 15;
        }

        footer p {
            font-size: 12;
            color: black;
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
            background-color: darkgray;
        }
    </style>
</head>
<body>
    <br>
    <h1>About Crafty Creations</h1>
    <br>
    <p>Crafty Creations is a leading crafts chain retailer which sells a variety of craft supplies and tools, including yarn, paint, fabric and other crafting essentials. With a growing number of physical stores within the United Kingdom and an online store, Crafty Creations has become the go-to stop for both beginner and professional crafters. With its wide range of products, the company allows artists to craft their dreams into reality! </p>
    <br>
</body>
</html>

<?php include 'footer.html'; ?>