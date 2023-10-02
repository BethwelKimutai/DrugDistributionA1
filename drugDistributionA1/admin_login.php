<?php


$hostname = "localhost";
$username = "root";
$password = "";
$database = "ddapp";


$conn = mysqli_connect($hostname, $username, $password, $database);


if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];


  $query = "SELECT * FROM admins WHERE username = '$username' LIMIT 1";


  $result = mysqli_query($conn, $query);

  if ($result) {

    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
  
      session_start();
      $_SESSION["username"] = $username;

  
      header("Location: admin.php");
      exit();
    } else {
      $error_message = "Invalid username or password";
    }
  } else {
    $error_message = "Error: " . mysqli_error($conn);
  }


  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f1f1f1;
    }

    h2 {
      text-align: center;
      margin-top: 30px;
    }

    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    label {
      display: block;
      margin-bottom: 10px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color:  #00abf0;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: blue;
    }

    .error-message {
      color: red;
      margin-top: 10px;
      text-align: center;
    }
    .logout-button {
      background-color:  #00abf0;
      color: #ffffff;
      border: none;
      padding: 8px 16px;
      font-size: 14px;
      border-radius: 4px;
      cursor: pointer;
    }

  </style>
</head>
<body>
  <div class="container">
    <h2>Admin Login</h2>
    <h2><a href="index.php" class="logout-button">Home</a></h2>
    <form method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>
      <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="admin_signup.php">Signup here</a></p>
    <?php if (isset($error_message)): ?>
      <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
  </div>
</body>
</html>

