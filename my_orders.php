<?php
session_start();
include("includes/config.php");

if(!isset($_SESSION['customer_id'])){
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE customer_id='$customer_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>PizzaBurg - My Orders</title>
  <style>
    body{margin:0;font-family:Arial,sans-serif;background:#fff;}
    header{background:#e4002b;color:white;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100;}
    header h1{margin:0;font-size:28px;}
    nav a{color:white;text-decoration:none;margin-left:20px;font-weight:bold;font-size:16px;}
    nav a:hover{text-decoration:underline;}
    h2{text-align:center;color:#e4002b;margin-top:30px;}
    
    table{width:90%;margin:auto;border-collapse:collapse;margin-bottom:30px;margin-top:20px;}
    th, td{border:1px solid #ccc;padding:12px;text-align:center;}
    th{background:#e4002b;color:white;}
    .btn{background:#e4002b;color:white;padding:5px 10px;text-decoration:none;border-radius:5px;margin:2px;}
    .btn:hover{background:#a3001c;}
    .badge{padding:5px 10px;border-radius:5px;color:white;font-weight:bold;}
    .pending{background:#f0ad4e;}
    .approved{background:#5bc0de;}
    .declined{background:#d9534f;}
    .delivered{background:#5cb85c;}
    p{font-size:16px;text-align:center;}
    @media screen and (max-width:768px){
      table, th, td{font-size:14px;}
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
    <?php if(isset($_SESSION['customer_id'])): ?>
      <a href="my_orders.php">My Orders</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </nav>
</header>

<h2>My Orders</h2>

<?php if(mysqli_num_rows($orders) > 0): ?>
  <table>
    <tr>
      <th>Order ID</th>
      <th>Date</th>
      <th>Total</th>
      <th>Status</th>
      <th>Details</th>
    </tr>
    <?php while($order = mysqli_fetch_assoc($orders)): ?>
    <tr>
      <td><?php echo $order['id']; ?></td>
      <td><?php echo date("d M Y, H:i", strtotime($order['created_at'])); ?></td>
      <td>৳ <?php echo $order['total']; ?></td>
      <td>
        <span class="badge <?php echo $order['status']; ?>">
          <?php echo ucfirst($order['status']); ?>
        </span>
      </td>
      <td><a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn">View</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p>You have not placed any orders yet. <a href="menu.php" class="btn">Order Now</a></p>
<?php endif; ?>

</body>
</html>
