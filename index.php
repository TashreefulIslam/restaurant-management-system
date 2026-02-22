<?php
session_start();
include("includes/config.php");
?>

<!DOCTYPE html>
<html>
<head>
  <title>PizzaBurg - Home</title>
  <style>
    /* Reset & basic */
    *{margin:0; padding:0; box-sizing:border-box;}
    body{font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#fff; color:#333;}
    a{text-decoration:none; color:inherit;}

    /* Header */
    header{
      background:#e4002b;
      color:white;
      padding:15px 30px;
      position:sticky;
      top:0;
      display:flex;
      justify-content:space-between;
      align-items:center;
      z-index:100;
      box-shadow:0 3px 6px rgba(0,0,0,0.1);
    }
    header h1{
      font-size:28px;
      font-weight:bold;
      letter-spacing:1px;
    }
    nav a{
      margin-left:20px;
      font-weight:bold;
      font-size:16px;
      padding:6px 12px;
      border-radius:4px;
      transition:0.3s;
    }
    nav a:hover{background:#a3001c;}

    /* Welcome message */
    .welcome-msg{
      background:#f8d7da;
      color:#721c24;
      font-size:18px;
      padding:15px;
      text-align:center;
      border-left:5px solid #e4002b;
    }

    /* Hero section */
    .hero{
      background:url('assets/images/hero.jpg') center/cover no-repeat;
      height:500px;
      display:flex;
      justify-content:center;
      align-items:center;
      color:white;
      font-size:3em;
      font-weight:bold;
      text-align:center;
      text-shadow:3px 3px 8px rgba(0,0,0,0.7);
      position:relative;
    }
    .hero::after{
      content:"";
      position:absolute;
      top:0; left:0; right:0; bottom:0;
      background:rgba(0,0,0,0.3);
    }
    .hero span{
      position:relative;
      z-index:1;
    }

    /* Sections */
    .section{
      padding:60px 20px;
      text-align:center;
      max-width:1200px;
      margin:auto;
    }
    .section h2{
      color:#e4002b;
      margin-bottom:40px;
      font-size:36px;
      letter-spacing:1px;
    }

    /* Menu cards */
    .menu-container{
      display:flex;
      flex-wrap:wrap;
      gap:25px;
      justify-content:center;
    }
    .card{
      border-radius:15px;
      width:260px;
      background:white;
      overflow:hidden;
      box-shadow:0 8px 20px rgba(0,0,0,0.1);
      transition:0.3s;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
    }
    .card:hover{
      transform:translateY(-8px);
      box-shadow:0 12px 25px rgba(0,0,0,0.2);
    }
    .card img{
      width:100%;
      height:180px;
      object-fit:cover;
      border-bottom:5px solid #e4002b;
    }
    .card h3{
      font-size:22px;
      margin:10px 0;
      color:#e4002b;
    }
    .card p{
      padding:0 15px;
      font-size:15px;
      color:#555;
    }
    .price{
      color:#e4002b;
      font-weight:bold;
      margin:12px 0;
      font-size:18px;
    }
    .btn{
      margin:15px;
      background:#e4002b;
      color:white;
      padding:12px 20px;
      border-radius:8px;
      font-weight:bold;
      transition:0.3s;
    }
    .btn:hover{background:#a3001c;}

    /* Why choose us */
    .why{
      background:#f9f9f9;
      padding:60px 20px;
      border-top:5px solid #e4002b;
      border-bottom:5px solid #e4002b;
    }
    .why p{
      margin:15px 0;
      font-size:18px;
    }

    /* Footer */
    footer{
      background:#333;
      color:white;
      padding:25px;
      text-align:center;
      font-size:14px;
    }

    /* Responsive */
    @media screen and (max-width:1024px){
      .menu-container{gap:20px;}
      .card{width:220px;}
    }
    @media screen and (max-width:768px){
      header{flex-direction:column;align-items:flex-start;}
      nav{margin-top:10px;}
      nav a{margin-left:0;margin-right:15px;font-size:14px;}
      .hero{font-size:2em;height:350px;}
      .card{width:90%;}
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

<?php if(isset($_SESSION['customer_id'])): ?>
<div class="welcome-msg">
  Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!
</div>
<?php endif; ?>

<div class="hero">
  <span>Delicious Burgers & Pizza Delivered Fast!</span>
</div>

<div class="section">
  <h2>Featured Menu</h2>
  <div class="menu-container">
    <?php
      $result = mysqli_query($conn, "SELECT * FROM menu ORDER BY created_at DESC LIMIT 6");
      while($row = mysqli_fetch_assoc($result)){
        echo "<div class='card'>
                <img src='assets/images/{$row['image']}' alt='{$row['name']}'>
                <h3>{$row['name']}</h3>
                <p>{$row['description']}</p>
                <p class='price'>৳ {$row['price']}</p>
                <a href='menu.php' class='btn'>Order Now</a>
              </div>";
      }
    ?>
  </div>
</div>

<div class="section why">
  <h2>Why Choose PizzaBurg?</h2>
  <p>✅ Fresh Ingredients & Delicious Taste</p>
  <p>✅ Fast Delivery within 30 mins</p>
  <p>✅ Easy Online Ordering</p>
  <p>✅ Affordable Prices & Great Offers</p>
</div>

<footer>
  &copy; <?php echo date("Y"); ?> PizzaBurg. All Rights Reserved.
</footer>

</body>
</html>
