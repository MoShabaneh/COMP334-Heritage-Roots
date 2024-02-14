<?php
$pdo = include 'dbconfig.in.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare('SELECT * FROM account WHERE username = :username AND pass = :password');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['userid'] = $user['cust_id']; // Fetch userid from the database
        header('Location: index.php'); 
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password";
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles/form.css">
</head>

<body>
    <div style="text-align: center;">
        <a href="index.php">
            <img src="pics/logo.png" alt="Logo" width="200" height="200">
        </a>
        <form method="POST" action="">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
        <p>Don't have an account? <a href="register.php" id="styled-link">Register</a></p>
        <?php if (isset($_SESSION['error'])) : ?>
            <div id="error">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>