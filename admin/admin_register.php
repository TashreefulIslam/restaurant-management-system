<?php
include("../includes/config.php");

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // plain password first
    $confirm_password = $_POST['confirm_password'];
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);

    // Check if passwords match
    if($password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // hash password

        // Check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $error = "Email already registered!";
        } else {
            $insert = mysqli_query($conn, "INSERT INTO admins (name,email,password,contact) VALUES ('$name','$email','$hashed_password','$contact')");
            if($insert){
                $success = "Admin registered successfully! <a href='admin_login.php'>Login here</a>";
            } else {
                $error = "Registration failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Registration - PizzaBurg</title>
  <style>
    body{font-family:Arial; background:#fff; padding:50px;}
    .form-container{max-width:400px;margin:auto;border:1px solid #ccc;padding:30px;border-radius:10px;}
    input[type=text], input[type=email], input[type=password]{width:100%; padding:10px;margin:10px 0;border-radius:5px;border:1px solid #ccc;}
    input[type=submit]{background:#e4002b;color:white;padding:10px;border:none;border-radius:5px;cursor:pointer;width:100%;}
    .message{color:red; text-align:center; margin:10px 0;}
    .success{color:green;}
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Admin Registration</h2>
    <?php if(isset($error)){echo "<p class='message'>$error</p>";} ?>
    <?php if(isset($success)){echo "<p class='message success'>$success</p>";} ?>
    <form method="post">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <input type="text" name="contact" placeholder="Contact Number">
      <input type="submit" name="register" value="Register">
    </form>
    <p style="text-align:center;margin-top:10px;">Already have an account? <a href="admin_login.php">Login here</a></p>
  </div>
</body>
</html>
