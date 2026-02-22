<?php
session_start();
include("../includes/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Check if menu ID is provided
if(!isset($_GET['id'])){
    header("Location: menu_manage.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch menu item
$result = mysqli_query($conn, "SELECT * FROM menu WHERE id='$id'");
if(mysqli_num_rows($result) == 0){
    echo "Menu item not found!";
    exit();
}
$item = mysqli_fetch_assoc($result);

// Update menu item
if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    
    $image = $_FILES['image']['name'];
    if($image){
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");
        $image_sql = ", image='$image'";
    } else {
        $image_sql = "";
    }

    mysqli_query($conn, "UPDATE menu SET name='$name', category='$category', description='$description', price='$price' $image_sql WHERE id='$id'");
    header("Location: menu_manage.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Menu Item - Admin - PizzaBurg</title>
  <style>
    body{font-family:Arial; background:#fff; padding:20px;}
    h2{text-align:center;color:#e4002b;}
    form{width:50%;margin:auto;border:1px solid #ccc;padding:20px;border-radius:10px;}
    input, select, textarea{width:100%;padding:8px;margin:5px 0;border-radius:5px;border:1px solid #ccc;}
    input[type=submit]{background:#e4002b;color:white;border:none;cursor:pointer;padding:10px;font-size:16px;border-radius:5px; transition:0.3s;}
    input[type=submit]:hover{background:#a3001c;}
    
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
        <a href="menu_manage.php" class="btn">Back to Menu</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>

<h2>Edit Menu Item</h2>

<form method="post" enctype="multipart/form-data">
    <label>Food Name</label>
    <input type="text" name="name" value="<?php echo $item['name']; ?>" required>

    <label>Category</label>
    <select name="category" required>
      <option value="burger" <?php if($item['category']=='burger') echo 'selected'; ?>>Burger</option>
      <option value="pizza" <?php if($item['category']=='pizza') echo 'selected'; ?>>Pizza</option>
      <option value="drink" <?php if($item['category']=='drink') echo 'selected'; ?>>Drink</option>
    </select>

    <label>Description</label>
    <textarea name="description" required><?php echo $item['description']; ?></textarea>

    <label>Price</label>
    <input type="text" name="price" value="<?php echo $item['price']; ?>" required>

    <label>Image (Leave blank to keep existing)</label>
    <input type="file" name="image">

    <input type="submit" name="update" value="Update Item">
</form>

</body>
</html>
