<?php
session_start();
include("includes/config.php");

if(!isset($_SESSION['customer_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: my_orders.php");
    exit();
}

$order_id = (int)$_GET['id'];
$customer_id = $_SESSION['customer_id'];

// Fetch order info
$order_res = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id' AND customer_id='$customer_id'");
if(mysqli_num_rows($order_res) == 0){
    echo "Order not found!";
    exit();
}
$order = mysqli_fetch_assoc($order_res);

// Fetch order items
$items_res = mysqli_query($conn, "SELECT oi.*, m.name, m.image FROM order_items oi JOIN menu m ON oi.menu_id=m.id WHERE oi.order_id='$order_id'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>PizzaBurg - Order Details</title>
  <style>
    body{margin:0;font-family:Arial,sans-serif;background:#f9f9f9;}
    header{background:#e4002b;color:white;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100;}
    header h1{margin:0;font-size:28px;}
    nav a{color:white;text-decoration:none;margin-left:20px;font-weight:bold;font-size:16px;}
    nav a:hover{text-decoration:underline;}
    
    .container{max-width:900px;margin:50px auto;padding:20px;background:white;border-radius:10px;box-shadow:0 0 15px #ccc;}
    h2{color:#e4002b;margin-bottom:20px;text-align:center;}
    table{width:100%;border-collapse:collapse;margin-bottom:30px;}
    th, td{border:1px solid #ccc;padding:12px;text-align:center;}
    th{background:#e4002b;color:white;}
    img{width:60px;height:60px;border-radius:6px;}
    .badge{padding:5px 10px;border-radius:5px;color:white;font-weight:bold;}
    .pending{background:#f0ad4e;}
    .approved{background:#5bc0de;}
    .declined{background:#d9534f;}
    .delivered{background:#5cb85c;}
    .btn{background:#e4002b;color:white;padding:8px 14px;text-decoration:none;border-radius:5px;transition:0.3s;margin-top:10px;display:inline-block;}
    .btn:hover{background:#a3001c;}
    @media screen and (max-width:768px){
      table, th, td{font-size:14px;}
      img{width:50px;height:50px;}
      header{flex-direction:column;align-items:flex-start;}
      nav{margin-top:10px;}
      nav a{margin-left:0;margin-right:15px;font-size:14px;}
    }
  </style>
</head>
<body>

<header>
  <h1>PizzaBurg</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cart.php">Cart (<?php echo isset($_SESSION['cart'])?array_sum($_SESSION['cart']):0; ?>)</a>
    <a href="my_orders.php">My Orders</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">
  <h2>Order #<?php echo $order['id']; ?> Details</h2>
  <p><strong>Status:</strong> <span class="badge <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
  <p><strong>Order Date:</strong> <?php echo date("d M Y, H:i", strtotime($order['created_at'])); ?></p>
  <p><strong>Total:</strong> ৳ <?php echo $order['total']; ?></p>

  <table>
    <tr>
      <th>Image</th>
      <th>Item</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
    </tr>
    <?php
      while($item = mysqli_fetch_assoc($items_res)):
        $subtotal = $item['price'] * $item['quantity'];
    ?>
    <tr>
      <td><img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"></td>
      <td><?php echo $item['name']; ?></td>
      <td>৳ <?php echo $item['price']; ?></td>
      <td><?php echo $item['quantity']; ?></td>
      <td>৳ <?php echo $subtotal; ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <a href="my_orders.php" class="btn">Back to Orders</a>
</div>

</body>
</html>
