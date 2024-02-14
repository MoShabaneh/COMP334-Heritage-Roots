<?php
$pdo = include 'dbconfig.in.php';

session_start();

// Prepare the SQL statement
$stmt = $pdo->prepare('SELECT gov_id FROM account WHERE cust_id = ?');

// Execute the SQL statement
$stmt->execute([$_SESSION['userid']]);
$result = $stmt->fetch();

// Prepare the SQL statement
$stmt = $pdo->prepare('SELECT * FROM orders WHERE gov_id = ? AND status != ? ORDER BY ord_date DESC');

// Execute the SQL statement
$stmt->execute([$result['gov_id'], 'Cancelled']);

// Fetch all the orders
$orders = $stmt->fetchAll();

// Display the orders
echo '<html>';
echo '<head>';
echo '<link rel="stylesheet" type="text/css" href="styles/cart.css">';
echo '</head>';
echo '<body>';

echo '<table>';
echo '<tr><th>Order ID</th><th>Order Date</th><th>Total Amount</th><th>Status</th></tr>';

foreach ($orders as $order) {
    echo '<tr>';
    echo '<td><a href="order_details.php?id=' . $order['id'] . '" target="_blank">' . $order['id'] . '</a></td>';
    echo '<td>' . $order['ord_date'] . '</td>';
    echo '<td>' . $order['total'] . '</td>';
    echo '<td>' . $order['status'] . '</td>';
    echo '</tr>';
}

echo '</table>';

echo '</body>';
echo '</html>';
?>