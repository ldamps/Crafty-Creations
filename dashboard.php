<?php
session_start();

 // if user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: loginPage.php");
    exit;
}
$email = $_SESSION['email'];
$role = $_SESSION['role'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <p>Logged in as: <strong><?= htmlspecialchars($email) ?></strong></p>

    
    <!--  different views based on role -->
<?php if ($role === 'Supervisor' || $role === 'Shop Assistant' || $role === 'IT Support' || $role === 'Administration'): ?>
        <h2>Store Employees View</h2>
        <p></p>
        <!-- employee-specific features -->
    <?php elseif ($role === 'staff'): ?>
        <h2>Store Managers View</h2>
        <p></p>
        <!-- manager-specific features -->
    <?php elseif ($role === 'manager' || 'Assistant Manager'): ?>
        <h2>Main Office Staff View</h2>
        <p></p>
        <!-- main office staff-specific features -->
    <?php else: ?>
        <h2>Customer View</h2>
        <p></p>
        <!-- customer-specific features -->
    <?php endif; ?>

</body>
</html>




