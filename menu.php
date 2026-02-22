<?php
session_start();
include("includes/config.php");

// Add to cart logic
if(isset($_GET['add'])){
    $menu_id = (int)$_GET['add'];

    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if(isset($_SESSION['cart'][$menu_id])){
        $_SESSION['cart'][$menu_id]++;
    } else {
        $_SESSION['cart'][$menu_id] = 1;
    }

    header("Location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>PizzaBurg - Menu</title>
  <style>
    body{margin:0;font-family:Arial,sans-serif;background:#fff;}
    header{background:#e4002b;color:white;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100;}
    header h1{margin:0;font-size:28px;}
    nav a{color:white;text-decoration:none;margin-left:20px;font-weight:bold;font-size:16px;}
    nav a:hover{text-decoration:underline;}
    .section{padding:50px 20px;text-align:center;}
    .section h2{color:#e4002b;margin-bottom:30px;font-size:32px;}
    .tabs{margin-bottom:30px;}
    .tabs button{padding:10px 20px;margin:0 5px;border:none;border-radius:6px;background:#e4002b;color:white;cursor:pointer;transition:0.3s;}
    .tabs button:hover, .tabs button.active{background:#a3001c;}
    .menu-container{display:flex;flex-wrap:wrap;gap:20px;justify-content:center;}
    .card{border:1px solid #ccc;padding:15px;border-radius:12px;width:220px;text-align:center;background:white;transition:0.3s;box-shadow:0 0 10px #ccc;}
    .card:hover{box-shadow:0 0 20px #e4002b;transform:translateY(-5px);}
    .card img{width:100%;border-radius:12px;height:160px;object-fit:cover;}
    .price{color:#e4002b;font-weight:bold;margin:10px 0;font-size:18px;}
    .btn{display:inline-block;margin-top:10px;background:#e4002b;color:white;padding:8px 12px;text-decoration:none;border-radius:5px;transition:0.3s;}
    .btn:hover{background:#a3001c;}
    @media screen and (max-width:768px){
      .menu-container{flex-direction:column;align-items:center;}
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

<div class="section">
  <h2>Our Menu</h2>

  <div class="tabs">
    <button class="tab-btn active" onclick="filterMenu('all')">All</button>
    <button class="tab-btn" onclick="filterMenu('burger')">Burger</button>
    <button class="tab-btn" onclick="filterMenu('pizza')">Pizza</button>
    <button class="tab-btn" onclick="filterMenu('drink')">Drink</button>
  </div>

  <div class="menu-container" id="menuContainer">
    <?php
      $menu_items = mysqli_query($conn, "SELECT * FROM menu ORDER BY created_at DESC");
      while($row = mysqli_fetch_assoc($menu_items)){
        echo "<div class='card' data-category='{$row['category']}'>
                <img src='assets/images/{$row['image']}' alt='{$row['name']}'>
                <h3>{$row['name']}</h3>
                <p>{$row['description']}</p>
                <p class='price'>৳ {$row['price']}</p>
                <a href='menu.php?add={$row['id']}' class='btn'>Add to Cart</a>
              </div>";
      }
    ?>
  </div>
</div>

<script>
function filterMenu(category){
  let cards = document.querySelectorAll('.card');
  cards.forEach(card => {
    if(category === 'all' || card.getAttribute('data-category') === category){
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });

  // active button
  let btns = document.querySelectorAll('.tab-btn');
  btns.forEach(b => b.classList.remove('active'));
  event.target.classList.add('active');
}
</script>

</body>
</html>
