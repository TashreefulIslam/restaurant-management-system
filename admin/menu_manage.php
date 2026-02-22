<?php
session_start();
include("../includes/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Add new menu item
if(isset($_POST['add'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $image = $_FILES['image']['name'];

    if($image){
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");
    }

    mysqli_query($conn, "INSERT INTO menu (name, category, description, price, image) VALUES ('$name','$category','$description','$price','$image')");
    header("Location: menu_manage.php");
    exit();
}

// Delete menu item
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM menu WHERE id='$id'");
    header("Location: menu_manage.php");
    exit();
}

// Fetch all menu items
$menu_items = mysqli_query($conn, "SELECT * FROM menu ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Menu - Admin - PizzaBurg</title>
  <style>
    body{font-family:Arial; background:#fff; padding:20px;}
    h2{text-align:center;color:#e4002b;}
    table{width:90%;margin:auto;border-collapse:collapse;margin-top:20px;}
    th, td{border:1px solid #ccc;padding:10px;text-align:center;}
    th{background:#e4002b;color:white;}
    .btn{background:#e4002b;color:white;padding:5px 10px;text-decoration:none;border-radius:5px; transition:0.3s;}
    .btn:hover{background:#a3001c;}
    form{width:50%;margin:auto;border:1px solid #ccc;padding:20px;border-radius:10px;}
    input, select, textarea{width:100%;padding:8px;margin:5px 0;border-radius:5px;border:1px solid #ccc;}
    input[type=submit]{background:#e4002b;color:white;border:none;cursor:pointer;}
    
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
        <a href="index.php" class="btn">Dashboard</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>

<h2>Manage Menu</h2>

<form method="post" enctype="multipart/form-data">
    <h3>Add New Item</h3>
    <input type="text" name="name" placeholder="Food Name" required>
    <select name="category" required>
      <option value="burger">Burger</option>
      <option value="pizza">Pizza</option>
      <option value="drink">Drink</option>
    </select>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="text" name="price" placeholder="Price" required>
    <input type="file" name="image" required>
    <input type="submit" name="add" value="Add Item">
</form>

<h3 style="text-align:center;margin-top:30px;">Existing Menu Items</h3>
<table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Category</th>
      <th>Price</th>
      <th>Image</th>
      <th>Action</th>
    </tr>
    <?php while($item = mysqli_fetch_assoc($menu_items)): ?>
      <tr>
        <td><?php echo $item['id']; ?></td>
        <td><?php echo $item['name']; ?></td>
        <td><?php echo ucfirst($item['category']); ?></td>
        <td>৳ <?php echo $item['price']; ?></td>
        <td><img src="../assets/images/<?php echo $item['image']; ?>" width="60"></td>
        <td>
            <a href="menu_edit.php?id=<?php echo $item['id']; ?>" class="btn">Edit</a>
            <a href="menu_manage.php?delete=<?php echo $item['id']; ?>" class="btn">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
