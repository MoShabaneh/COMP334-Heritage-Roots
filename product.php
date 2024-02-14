<?php
$pdo = include 'dbconfig.in.php';

$id = $_GET['id'];

$query = 'SELECT * FROM product INNER JOIN prod_img ON product.id = prod_img.prod_id WHERE product.id = ?';
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

$product = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles/product.css">
</head>

<body>
    <div class="product-container">
        <div class="product-details">
            <h1><?php echo $product['name']; ?></h1>
            <p>Description: <?php echo $product['descript']; ?></p>
            <p>Category: <?php echo $product['category']; ?></p>
            <p>Type: <?php echo $product['type']; ?></p>
            <p>Price: <?php echo $product['price']; ?></p>
            <p>Quantity: <?php echo $product['quantity']; ?></p>
            <p>Size: <?php echo $product['size']; ?></p>
            <p>Remark: <?php echo $product['remark']; ?></p>
        </div>
        <img src="itemsImages/<?php echo $product['img']; ?>" alt="Product Image" class="product-image" width="200" height="200">
    </div>
</body>

</html>