<?php
$pdo = include 'dbconfig.in.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $remarks = $_POST['remarks'];

    // Prepare an SQL statement
    $stmt = $pdo->prepare("INSERT INTO product (name, descript, price, category, type, quantity, size, remark) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([$name, $description, $price, $category, $type, $quantity, $size, $remarks]);

    $productID = $pdo->lastInsertId();

    // Handle the image upload if a file was submitted
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
       
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $newFilename = "item{$productID}img1.{$extension}";

        // Move the file to the itemsImages directory
        move_uploaded_file($_FILES['image']['tmp_name'], "itemsImages/{$newFilename}");

        // Insert the image name into the prod_img table
        $stmt = $pdo->prepare("INSERT INTO prod_img (prod_id, img) VALUES (?, ?)");
        $stmt->execute([$productID, $newFilename]);
    }

    // Display a confirmation message
    header("Location: product_confirmation.php?productID=" . $productID);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/form.css">
    <title>Add Product</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="description">Product Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required><br>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="new arrival">New Arrival</option>
            <option value="on sale">On Sale</option>
            <option value="featured">Featured</option>
            <option value="high demand">High Demand</option>
            <option value="normal" selected>Normal</option>
        </select><br><br>

        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="handcraft">handcraft</option>
            <option value="ceramic">ceramic</option>
            <option value="natural product">natural product</option>
            <option value="food">food</option>
            <option value="other" selected>Other</option>
        </select><br><br>

        
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br>

        <label for="size">Size:</label>
        <input type="text" id="size" name="size" required><br>

        <label for="remarks">Remarks:</label>
        <textarea id="remarks" name="remarks" required></textarea><br><br>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" required><br><br>

        <input type="submit" value="Add Product">
    </form>
</body>
</html>