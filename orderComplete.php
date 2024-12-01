<?php include 'navBar.php'; 
?>

<?php
if (isset($_SESSION['trackingNo'])) {
    $trackingNo = $_SESSION['trackingNo'];
} else {
    $trackingNo = "Tracking number not available.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Complete</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div id="orderCompleteBox">
    <h1>Order Complete!</h1>
    <h3>You're tracking number is: </h3>
    <h2>#<?php echo htmlspecialchars($trackingNo); ?></h2> 
    <h3>Try not to lose it!</h3>
</div>

</body>

<script src="script.js"></script>
<?php include 'footer.html'; ?>

</html>

