<?php
$pdo = include 'dbconfig.in.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cancel_order'])) {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');

        // Execute the SQL statement
        $stmt->execute(['Cancelled', $_POST['order_id']]);
    } elseif (isset($_POST['set_to_shipped'])) {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');

        // Execute the SQL statement
        $stmt->execute(['shipped', $_POST['order_id']]);
    }

    // Redirect to the same page to reflect the changes
    header('Location: order_details.php?id=' . $_POST['order_id']);
    exit;
}

if (isset($_GET['id'])) {
    // Prepare the SQL statement
    $stmt = $pdo->prepare('SELECT orders.id, customer.name, orders.status FROM orders INNER JOIN customer ON orders.gov_id = customer.id WHERE orders.id = ?');

    // Execute the SQL statement
    $stmt->execute([$_GET['id']]);

    // Fetch the order details
    $order = $stmt->fetch();

    // Display the order details
    echo '<html>';
    echo '<head>';
    echo '<link rel="stylesheet" type="text/css" href="styles/cart.css">';
    echo '</head>';
    echo '<body>';

    echo '<h2>Order Details</h2>';
    echo 'Order ID: ' . $order['id'] . '<br>';
    echo 'Customer Name: ' . $order['name'] . '<br>';
    echo 'Status: ' . $order['status'] . '<br>';

    // Prepare the SQL statement
    $stmt = $pdo->prepare('SELECT product.name, ord_prod.quantity FROM ord_prod INNER JOIN product ON ord_prod.prod_id = product.id WHERE ord_prod.ord_id = ?');

    // Execute the SQL statement
    $stmt->execute([$_GET['id']]);

    // Display the order items
    echo '<table>';
    echo '<tr><th>Product Name</th><th>Quantity</th></tr>';
    while ($row = $stmt->fetch()) {
        echo '<tr>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="order_id" value="' . $order['id'] . '">';
    echo '<input type="submit" name="cancel_order" value="Cancel Order">';
    
    $stmt = $pdo->prepare('SELECT isemp FROM account WHERE cust_id = ?');
    $stmt->execute([$_SESSION['userid']]);
    $user = $stmt->fetch();
    
    if (isset($user) && $user['isemp'] == 1) {
        // Display the "Set to Shipped" button
        echo '<input type="submit" name="set_to_shipped" value="Set to Shipped">';
    }

    echo '</form>';

    echo '</body>';
    echo '</html>';
} else {
    // Redirect to the index page if the id is not set
    header('Location: index.php');
    exit;
}
?>