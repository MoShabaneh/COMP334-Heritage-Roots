<?php
$pdo = include 'dbconfig.in.php';

session_start();


// Initialize the cart if it's not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
        // Redirect to login page
        header('Location: login.php');
        exit();
    }

    $productId = $_POST['product_id'];

    // If the product is already in the cart, increment the quantity
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        // Otherwise, add the product to the cart with a quantity of 1
        $_SESSION['cart'][$productId] = 1;
    }

    // Store a message in the session
    $_SESSION['message'] = 'Product added to cart';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <link rel="icon" href="pics/logo.png">
    <title>Online Store</title>
</head>

<body>

    <header>
        <div id="logo">
            <a href="index.php">
                <img src="pics/logo.png" alt="Logo" class="logo">
            </a>
        </div>
        <h1>Heritage Roots</h1>
        <div class="left-div">
            <div id="cart-icon">
                <a href="cart.php">
                    <img src="pics/cart_icon.png" alt="Cart" class="cart-icon">
                </a>
            </div>
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    echo "<p style='margin-bottom: 0;'>Welcome, " . $_SESSION['username'] . "!</p>";
                    echo '<br><a href="logout.php">Logout</a>';
                } else {
                    echo '<a href="login.php">Login</a> or <a href="register.php">Register</a>';
                }
                ?>
        </div>
    </header>
        
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';

        // Clear the message from the session
        unset($_SESSION['message']);
    }
    ?>


    <div class="search-bar">
        <form action="index.php" method="get">
            <input type="search" id="id-search" name="id" placeholder="Product ID">
            <input type="search" id="name-search" name="name" placeholder="Product name">
            <input type="search" id="min-price-search" name="min_price" placeholder="Min price">
            <input type="search" id="max-price-search" name="max_price" placeholder="Max price">
            <button id="search-button" type="submit">Search</button>
        </form>
    </div>
    <?php
    $stmt = $pdo->prepare('SELECT isemp FROM account WHERE username = :username');
    $stmt->bindParam(':username', $_SESSION['username']);  // Assuming the username is stored in the session
    $stmt->execute();
    $user = $stmt->fetch();
    
    if (isset($user) && is_array($user) && $user['isemp'] == 1) {
        echo '<a href="add_product.php">Add product</a>';
        echo '<br><a href="orders_emp.php">Orders</a>';
    } elseif (isset($user) && is_array($user) && $user['isemp'] == 0) {
        echo '<a href="orders.php">Orders</a>';
    }
    try {
        $name = $_GET['name'] ?? '';
        $min_price = $_GET['min_price'] ?? '';
        $max_price = $_GET['max_price'] ?? '';
        $id = $_GET['id'] ?? '';

        $query = 'SELECT product.id, product.name, product.category, product.price, prod_img.img FROM product INNER JOIN prod_img ON product.id = prod_img.prod_id WHERE 1=1';
        if ($name) {
            $query .= " AND product.name LIKE '%$name%'";
        }
        if ($min_price) {
            $query .= " AND product.price >= $min_price";
        }
        if ($max_price) {
            $query .= " AND product.price <= $max_price";
        }
        if ($id) {
            $query .= " AND product.id = $id";
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            echo '<div class="product-card">';
            echo '<div class="product-info">';
            echo '<h2><a href="product.php?id=' . $row['id'] . '">' .  $row['name'] . '</a></h2>';
            if (isset($user) && is_array($user) && $user['isemp'] == 1) {
                echo '<h5 id="hid"><a href="update_product.php?id=' . $row['id'] . '">' .  $row['id'] . '</a></h5>';
            }
            echo '<p>Category: ' .  $row['category'] . '</p>';
            echo '<p>Price: ' .  $row['price'] . '</p>';
            echo '<form action="index.php" method="post">';
            echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
            echo '<input type="submit" value="Add to Cart">';
            echo '</form>';
            echo '</div>';
            echo '<img src="itemsImages/' .  $row['img'] . '" alt="Product Image" class="images" width="200" height="200" >';
            echo '</div>';
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    ?>

    <br><br>
    
    <footer id="myfooter">
        <div id="footer-content">
            <div id="footer-logo">
                <a href="index.php">
                    <img src="pics/logo.png" alt="Logo" class="logo">
                </a>
            </div>
            <p>&copy; 2024 Heritage Roots</p>
            <p>1234, Al-intikhabat St, Ramallah, Palestine</p>
            <p>Email: 1201297@students.birzeit.edu</p>
            <p>Phone: +970598363203</p>
            <a href="contact_us.php">Contact Us</a>
        </div>
    </footer>

</body>

</html>