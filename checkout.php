<?php
session_start();
include("includes/config.php");

// Check if customer logged in
if(!isset($_SESSION['customer_id'])){
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$cart = $_SESSION['cart'] ?? [];

if(isset($_POST['checkout']) && count($cart) > 0){
    $total = 0;
    foreach($cart as $id => $qty){
        $query = mysqli_query($conn, "SELECT price FROM menu WHERE id='$id'");
        $item = mysqli_fetch_assoc($query);
        $total += $item['price'] * $qty;
    }

    // Insert into orders table
    $insert_order = mysqli_query($conn, "INSERT INTO orders (customer_id, total) VALUES ('$customer_id', '$total')");
    $order_id = mysqli_insert_id($conn);

    // Insert order items
    foreach($cart as $id => $qty){
        $query = mysqli_query($conn, "SELECT price FROM menu WHERE id='$id'");
        $item = mysqli_fetch_assoc($query);
        mysqli_query($conn, "INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES ('$order_id', '$id', '$qty', '{$item['price']}')");
    }

    // Clear cart
    unset($_SESSION['cart']);
    $success = "Order placed successfully! Your order ID: #$order_id";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout - PizzaBurg</title>
  <style>
    body{margin:0;font-family:Arial,sans-serif;background:#fff;}
    header{background:#e4002b;color:white;padding:15px 30px;position:sticky;top:0;display:flex;justify-content:space-between;align-items:center;z-index:100;}
    header h1{margin:0;font-size:28px;}
    nav a{color:white;text-decoration:none;margin-left:20px;font-weight:bold;font-size:16px;}
    nav a:hover{text-decoration:underline;}
    h2{text-align:center;color:#e4002b;margin-top:30px;}
    .message{color:green;text-align:center;margin:20px;}
    table{width:80%;margin:auto;border-collapse:collapse;margin-top:20px;}
    th, td{border:1px solid #ccc;padding:10px;text-align:center;}
    th{background:#e4002b;color:white;}
    .btn{background:#e4002b;color:white;padding:5px 10px;text-decoration:none;border-radius:5px;margin:2px;}
    .btn:hover{background:#a3001c;}
    .total{text-align:right;margin-right:10%;margin-top:15px;font-size:18px;font-weight:bold;}
    
    @media screen and (max-width:768px){
      header{flex-direction:column;align-items:flex-start;}
      nav{margin-top:10px;}
      nav a{margin-left:0;margin-right:15px;font-size:14px;}
      table{width:95%;}
      .total{text-align:center;margin-right:0;}
    }
  </style>
</head>
<body>

<header>
  <h1>PizzaBurg</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</a>
    <?php if(isset($_SESSION['customer_id'])): ?>
      <a href="my_orders.php">My Orders</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </nav>
</header>



<h2>Checkout</h2>

<?php if(isset($success)){ echo "<p class='message'>$success</p>"; } ?>

<?php if(count($cart) > 0): ?>
  <table>
    <tr>
      <th>Food</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
    </tr>
    <?php
      $total = 0;
      foreach($cart as $id => $qty){
          $query = mysqli_query($conn, "SELECT * FROM menu WHERE id='$id'");
          $item = mysqli_fetch_assoc($query);
          $subtotal = $item['price'] * $qty;
          $total += $subtotal;
          echo "<tr>
                  <td>{$item['name']}</td>
                  <td>৳ {$item['price']}</td>
                  <td>$qty</td>
                  <td>৳ $subtotal</td>
                </tr>";
      }
    ?>
  </table>
  <p class="total"><strong>Total: ৳ <?php echo $total; ?></strong></p>

  <form method="post" style="text-align:center;margin-top:20px;">
    <input type="submit" name="checkout" value="Place Order" class="btn">
  </form>
<?php else: ?>
  <p style="text-align:center; font-size:16px; margin-top:20px;">
    Your cart is empty. <a href="menu.php">Go to Menu</a>
  </p>
<?php endif; ?>


</body>
</html>
