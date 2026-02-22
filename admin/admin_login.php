<?php
session_start();
include("../includes/config.php");

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
    if(mysqli_num_rows($query) > 0){
        $admin = mysqli_fetch_assoc($query);
        if(password_verify($password, $admin['password'])){
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: index.php"); // admin dashboard
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not registered!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Login - PizzaBurg</title>
  <style>
    body{font-family:Arial; background:#fff; padding:50px;}
    .form-container{max-width:400px;margin:auto;border:1px solid #ccc;padding:30px;border-radius:10px;}
    input[type=email], input[type=password]{width:100%; padding:10px;margin:10px 0;border-radius:5px;border:1px solid #ccc;}
    input[type=submit]{background:#e4002b;color:white;padding:10px;border:none;border-radius:5px;cursor:pointer;width:100%;}
    .message{color:red; text-align:center; margin:10px 0;}
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Admin Login</h2>
    <?php if(isset($error)){echo "<p class='message'>$error</p>";} ?>
    <form method="post">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" name="login" value="Login">
    </form>
    <p style="text-align:center;margin-top:10px;">Don't have an account? <a href="admin_register.php">Register here</a></p>
  </div>
</body>
</html>
