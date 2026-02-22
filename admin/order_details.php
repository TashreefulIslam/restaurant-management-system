<?php
session_start();
include("../includes/config.php");

// Check admin login
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Fetch all orders
$orders = mysqli_query($conn,"SELECT o.*, c.name AS customer_name FROM orders o JOIN customer c ON o.customer_id=c.customer_id ORDER BY o.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Details - Admin - PizzaBurg</title>
  <style>
    body{font-family:Arial; background:#fff; padding:20px;}
    h2{text-align:center;color:#e4002b;}

    /* Navigation bar */
    .top-bar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:#e4002b;
        padding:10px 20px;
        color:white;
        border-radius:5px;
        margin-bottom:20px;
    }
    .top-bar .logo{
        font-size:22px;
        font-weight:bold;
    }
    .top-bar .nav-links a{
        margin-left:15px;
        color:white;
        text-decoration:none;
        font-weight:bold;
        padding:6px 12px;
        border-radius:4px;
        transition:0.3s;
    }
    .top-bar .nav-links a:hover{
        background:#a3001c;
    }

    table{width:95%;margin:auto;border-collapse:collapse;margin-top:20px;}
    th, td{border:1px solid #ccc;padding:10px;text-align:center;}
    th{background:#e4002b;color:white;}
    .btn{background:#e4002b;color:white;padding:5px 10px;text-decoration:none;border-radius:5px; transition:0.3s;}
    .btn:hover{opacity:0.8;}
    .badge{padding:3px 8px;border-radius:5px;color:white;font-weight:bold;}
    .pending{background:#f0ad4e;}
    .approved{background:#5bc0de;}
    .delivered{background:#5cb85c;}
    .declined{background:#d9534f;}
  </style>
</head>
<body>

<div class="top-bar">
    <div class="logo">PizzaBurg</div>
    <div class="nav-links">
        
        <a href="index.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<h2>All Orders Details</h2>

<?php if(mysqli_num_rows($orders) > 0): ?>
<table>
  <tr>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Items</th>
    <th>Total</th>
    <th>Status</th>
    <th>Date</th>
  </tr>
  <?php while($order = mysqli_fetch_assoc($orders)): ?>
  <tr>
    <td>#<?php echo $order['id']; ?></td>
    <td><?php echo $order['customer_name']; ?></td>
    <td>
      <?php
      $items = mysqli_query($conn,"SELECT oi.quantity, m.name FROM order_items oi JOIN menu m ON oi.menu_id=m.id WHERE oi.order_id='{$order['id']}'");
      while($item = mysqli_fetch_assoc($items)){
          echo "{$item['name']} (x{$item['quantity']})<br>";
      }
      ?>
    </td>
    <td>৳ <?php echo $order['total']; ?></td>
    <td><span class="badge <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
    <td><?php echo $order['created_at']; ?></td>
  </tr>
  <?php endwhile; ?>
</table>
<?php else: ?>
<p style="text-align:center;">No orders found.</p>
<?php endif; ?>

</body>
</html>
