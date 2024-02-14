<?php
$pdo = include 'dbconfig.in.php';

session_start();


// Fetch the gov_id from the account table
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare('SELECT gov_id FROM account WHERE cust_id = ?');
    $stmt->execute([$_SESSION['userid']]);
    $result = $stmt->fetch();
    
    
    $gov_id = $result['gov_id'];
    
    // Prepare the SQL statement
    $stmt = $pdo->prepare('INSERT INTO orders (gov_id, status, ord_date, total) VALUES (?, ?, NOW(), ?)');

    // Calculate the total
    $total = $_SESSION['total']; 

    $stmt->execute([$gov_id, 'waiting for processing', $total]);

    // Get the ID of the last inserted order
    $order_id = $pdo->lastInsertId();

    // Prepare the SQL statement to insert order items
    $stmt = $pdo->prepare('INSERT INTO ord_prod (ord_id, prod_id, quantity) VALUES (?, ?, ?)');

    // Go through each product in the cart and insert into the order_item table
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $stmt->execute([$order_id, $productId, $quantity]);
    }

    // Remove all the products from the cart
    unset($_SESSION['cart']);

    echo '<html>';
    echo '<head>';
    echo '<link rel="stylesheet" type="text/css" href="styles/index.css">';
    echo '</head>';
    echo '<body>';


    echo 'Thank you for your purchase! Your order ID is <a href="order_details.php?id=' . $order_id . '" target="_blank">' . $order_id . '</a>.';

    // Return to the index page link
    echo '<br><a href="index.php">Continue Shopping</a>';

    echo '</body>';
    echo '</html>';
} else {
    header('Location: checkout.php');
    exit;
}
?>