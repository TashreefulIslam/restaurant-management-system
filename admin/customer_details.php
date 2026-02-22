<?php
session_start();
include("../includes/config.php");

// Check admin login
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Delete customer
if(isset($_GET['delete_customer'])){
    $customer_id = (int)$_GET['delete_customer'];
    mysqli_query($conn,"DELETE FROM customer WHERE customer_id='$customer_id'");
    header("Location: customer_details.php");
    exit();
}

// Fetch all customers
$customers = mysqli_query($conn,"SELECT * FROM customer ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer Details - Admin - PizzaBurg</title>
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

    table{width:90%;margin:auto;border-collapse:collapse;margin-top:20px;}
    th, td{border:1px solid #ccc;padding:10px;text-align:center;}
    th{background:#e4002b;color:white;}
    .btn{background:#e4002b;color:white;padding:5px 10px;text-decoration:none;border-radius:5px;margin:2px; transition:0.3s;}
    .btn.delete{background:red;}
    .btn:hover{opacity:0.8;}
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

<h2>Registered Customers</h2>

<?php if(mysqli_num_rows($customers) > 0): ?>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Contact</th>
    <th>Address</th>
    <th>Registered At</th>
    <th>Action</th>
  </tr>
  <?php while($cust = mysqli_fetch_assoc($customers)): ?>
  <tr>
    <td><?php echo $cust['customer_id']; ?></td>
    <td><?php echo $cust['name']; ?></td>
    <td><?php echo $cust['email']; ?></td>
    <td><?php echo $cust['contact']; ?></td>
    <td><?php echo $cust['address']; ?></td>
    <td><?php echo $cust['created_at']; ?></td>
    <td>
      <a href="customer_details.php?delete_customer=<?php echo $cust['customer_id']; ?>" class="btn delete" onclick="return confirm('Are you sure to delete this customer?');">Delete</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>
<?php else: ?>
<p style="text-align:center;">No customers found.</p>
<?php endif; ?>

</body>
</html>
