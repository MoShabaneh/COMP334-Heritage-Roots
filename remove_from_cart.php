<?php
session_start();

if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productId = $_GET['id'];

    // If the product is in the cart, remove it
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    // Store a message in the session
    $_SESSION['message'] = 'Product removed from cart';
}

// Redirect back to the cart page
header('Location: cart.php');
?>