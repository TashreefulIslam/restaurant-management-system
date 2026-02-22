<?php
session_start();
include("includes/config.php");

// Remove item
if(isset($_GET['remove'])){
    $id = (int)$_GET['remove'];
    if(isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// Clear cart
if(isset($_GET['clear'])){
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Cart - PizzaBurg</title>
  <style>
    body{margin:0;font-family:Arial,sans-serif;background:#fff;}
    header{background:#e4002b;color:white;padding:15px 30px;position:sticky;top:0;display:flex;justify-content:space-between;align-items:center;z-index:100;}
    header h1{margin:0;font-size:28px;}
    nav a{color:white;text-decoration:none;margin-left:20px;font-weight:bold;font-size:16px;}
    nav a:hover{text-decoration:underline;}
    h2{text-align:center;color:#e4002b;margin-top:30px;}
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

<!-- Welcome Message for Logged-in Customer -->


<h2>Your Cart</h2>

<?php if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0): ?>
  <table>
    <tr>
      <th>Food</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
      <th>Action</th>
    </tr>
    <?php
      $total = 0;
      foreach($_SESSION['cart'] as $id => $qty){
          $query = mysqli_query($conn, "SELECT * FROM menu WHERE id='$id'");
          $item = mysqli_fetch_assoc($query);
          $subtotal = $item['price'] * $qty;
          $total += $subtotal;
          echo "<tr>
                  <td>{$item['name']}</td>
                  <td>৳ {$item['price']}</td>
                  <td>$qty</td>
                  <td>৳ $subtotal</td>
                  <td><a href='cart.php?remove={$id}' class='btn'>Remove</a></td>
                </tr>";
      }
    ?>
  </table>
  <p class="total">Total: ৳ <?php echo $total; ?></p>
  <p style="text-align:center;">
    <a href="cart.php?clear=1" class="btn">Clear Cart</a>
    <a href="checkout.php" class="btn">Checkout</a>
  </p>
<?php else: ?>
  <p style="text-align:center; font-size:16px; margin-top:20px;">
    Your cart is empty. <a href="menu.php">Go to Menu</a>
  </p>
<?php endif; ?>



</body>
</html>
