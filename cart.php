<?php
$pdo = include 'dbconfig.in.php';
$total = 0;

session_start();


if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
    header('Location: login.php');
    exit;
}

// Initialize the cart if it's not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

echo '<head>';
echo '<link rel="stylesheet" type="text/css" href="styles/cart.css">';
echo '</head>';

echo '<body>';

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    // Display a message and stop the script
    echo '<div class="message">';
    echo '<p>Your cart is empty. Please add some products to the cart first.</p>';
    echo '<a href="index.php">Go back to the shop</a>';
    echo '</div>';
    exit();
}

echo '<table>';
echo '<tr><th>Product Id</th><th>Product Name</th><th>Quantity</th><th>Total Price</th></tr>';

foreach ($_SESSION['cart'] as $productId => $quantity) {
    // Fetch the product details from the database
    $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        $totalPrice = $product['price'] * $quantity;
        $total += $totalPrice;

        echo '<tr>';
        echo '<td>' . $productId . '</td>';
        echo '<td>' . $product['name'] . '</td>';
        echo '<td>';
        echo '<form action="update_cart.php" method="post">';
        echo '<input type="hidden" name="id" value="' . $productId . '">';
        echo '<input type="number" name="quantity" value="' . $quantity . '">';
        echo '<input type="submit" value="Update">';
        echo '</form>'; 
        echo '</td>';
        echo '<td>' . $totalPrice . '</td>';
        echo '<td><a href="remove_from_cart.php?id=' . $productId . '">Remove</a></td>';
        echo '</tr>';
    }
}

echo '</table>';
echo '<br>Total: ' . $total . '<br><br><br>';
echo '<a href="checkout.php">Checkout</a>';
echo '<br><a href="index.php">Continue Shopping</a>';
echo '</body>';
?>