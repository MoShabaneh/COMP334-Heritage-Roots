<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'];
    $quantity = $_POST['quantity'];

    if ($quantity != 0 && $quantity != 1 && $quantity < 0) {
        $quantity = 0;
    }
    
    // If the product is in the cart, update the quantity
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    }
    
    // Store a message in the session
    $_SESSION['message'] = 'Cart updated';
}

// Redirect back to the cart page
header('Location: cart.php');
?>