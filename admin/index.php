<?php
session_start();
include("../includes/config.php");

// Check admin login
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Update order status
if(isset($_GET['update_order'])){
    $order_id = (int)$_GET['order_id'];
    $status = $_GET['status'];
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id='$order_id'");
    header("Location: index.php");
    exit();
}

// Fetch all orders
$orders = mysqli_query($conn, "SELECT o.*, c.name AS customer_name FROM orders o JOIN customer c ON o.customer_id=c.customer_id ORDER BY o.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - PizzaBurg</title>
  <style>
    body{font-family:Arial; background:#fff; padding:20px;}
    h2{text-align:center;color:#e4002b;}
    table{width:90%;margin:auto;border-collapse:collapse;margin-top:20px;}
    th, td{border:1px solid #ccc;padding:10px;text-align:center;}
    th{background:#e4002b;color:white;}
    .btn{background:#e4002b;color:white;padding:5px 10px;text-decoration:none;border-radius:5px;}
    .status.pending{color:orange;font-weight:bold;}
    .status.approved{color:blue;font-weight:bold;}
    .status.delivered{color:green;font-weight:bold;}
    .status.declined{color:red;font-weight:bold;}
    
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
  </style>
</head>
<body>

<div class="top-bar">
    <div class="logo">PizzaBurg</div>
    <div class="nav-links">
        Welcome, <?php echo $_SESSION['admin_name']; ?> |
        <a href="logout.php" class="btn">Logout</a>
        <a href="menu_manage.php" class="btn">Manage Menu</a>
        <a href="customer_details.php" class="btn">Customer Details</a>
        <a href="order_details.php" class="btn">Order Details</a>
    </div>
</div>

<h2>All Orders</h2>

<?php if(mysqli_num_rows($orders) > 0): ?>
  <table>
    <tr>
      <th>Order ID</th>
      <th>Customer</th>
      <th>Total</th>
      <th>Status</th>
      <th>Items</th>
      <th>Action</th>
      <th>Date</th>
    </tr>
    <?php while($order = mysqli_fetch_assoc($orders)): ?>
      <tr>
        <td>#<?php echo $order['id']; ?></td>
        <td><?php echo $order['customer_name']; ?></td>
        <td>৳ <?php echo $order['total']; ?></td>
        <td class="status <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></td>
        <td>
          <?php
            $items = mysqli_query($conn, "SELECT oi.quantity, m.name FROM order_items oi JOIN menu m ON oi.menu_id=m.id WHERE oi.order_id='{$order['id']}'");
            while($item = mysqli_fetch_assoc($items)){
                echo "{$item['name']} (x{$item['quantity']})<br>";
            }
          ?>
        </td>
        <td>
          <a href="index.php?update_order=1&order_id=<?php echo $order['id']; ?>&status=approved" class="btn">Approve</a>
          <a href="index.php?update_order=1&order_id=<?php echo $order['id']; ?>&status=declined" class="btn">Decline</a>
          <a href="index.php?update_order=1&order_id=<?php echo $order['id']; ?>&status=delivered" class="btn">Delivered</a>
        </td>
        <td><?php echo $order['created_at']; ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p style="text-align:center;">No orders found.</p>
<?php endif; ?>

</body>
</html>
