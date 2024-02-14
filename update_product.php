<?php
$pdo = include 'dbconfig.in.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // This is the form submission, update the product quantity
    $id = $_POST['id'];
    $quantityToAdd = $_POST['quantity'];

    echo '<input type="number" name="quantity" min="1" step="1" required>';

    $stmt = $pdo->prepare('UPDATE product SET quantity = quantity + :quantity WHERE id = :id');
    $stmt->bindParam(':quantity', $quantityToAdd, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: index.php');
    exit;
} else {
    // This is the initial page load, display the form
    $id = $_GET['id'];

    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '<link rel="stylesheet" href="styles/form.css">';
    echo '</head>';
    echo '<body>';
    echo '<form action="update_product.php" method="post">';
    echo '<input type="hidden" name="id" value="' . $id . '">';
    echo '<input type="number" name="quantity" min="1" required>';
    echo '<input type="submit" value="Update Quantity">';
    echo '</form>';
    echo '</body>';
    echo '</html>';
}