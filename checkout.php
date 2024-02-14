<?php
$pdo = include 'dbconfig.in.php';

session_start();

if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
    header('Location: login.php');
    exit;
}


// Fetch the gov_id from the account table
$stmt = $pdo->prepare('SELECT gov_id FROM account WHERE cust_id = ?');
$stmt->execute([$_SESSION['userid']]);
$result = $stmt->fetch();


$gov_id = $result['gov_id'];

// Now use the gov_id to fetch the user details from the customer table
$stmt = $pdo->prepare('SELECT * FROM customer WHERE id = ?');
$stmt->execute([$gov_id]);
$user = $stmt->fetch();
echo '<head>';
echo '<link rel="stylesheet" type="text/css" href="styles/cart.css">';
echo '</head>';

echo '<body>';
echo '<form action="confirmation.php" method="post">';
echo 'Name: ' . $user['name'] . '<br>';
echo 'Shipping Address: ' . $user["h_num"] . " " . $user["street"] . ", " . $user["city"] . ", " . $user["country"] . '<br>';
echo 'Credit Card Number: ' . $user['card_num'] . '<br>';

echo '<table>';
echo '<tr><th>Product Name</th><th>Quantity</th><th>Total Price</th></tr>';

$total = 0;

foreach ($_SESSION['cart'] as $productId => $quantity) {
    // Fetch the product details from the database
    $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        $totalPrice = $product['price'] * $quantity;
        $total += $totalPrice;

        echo '<tr>';
        echo '<td>' . $product['name'] . '</td>';
        echo '<td>' . $quantity . '</td>';
        echo '<td>' . $totalPrice . '</td>';
        echo '</tr>';
    }
}

echo '</table>';
$_SESSION['total'] = $total;
echo 'Total: ' . $total . '<br>';

echo '<input type="submit" value="Confirm">';
echo '</form>';
echo '</body>';
?>