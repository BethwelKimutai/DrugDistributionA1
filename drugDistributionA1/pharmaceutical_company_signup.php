<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ddapp";


$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $phone_number = $_POST["phone_number"];
  $password = $_POST["password"];


  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

 
  $query = "INSERT INTO pharmaceutical_companies (name, phone_number, password)
            VALUES ('$name', '$phone_number', '$hashedPassword')";


  if (mysqli_query($conn, $query)) {
    echo "Pharmaceutical company registered successfully!";
    header('location: pharmaceutical_company_login.php');
  } else {
    echo "Error: " . mysqli_error($conn);
  }


  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pharmaceutical Company Signup</title>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
    }
  
    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 40px;
      background-color: #ffffff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  
    h2 {
      text-align: center;
    }
  
    form {
      margin-top: 20px;
    }
  
    label {
      display: block;
      margin-bottom: 8px;
    }
  
    input[type="text"],
    input[type="password"],
    input[type="number"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
  
    input[type="submit"] {
      display: block;
      width: 100%;
      padding: 8px;
      margin-top: 20px;
      background-color:  #00abf0;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  
    input[type="submit"]:hover {
      background-color: blue;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Pharmaceutical Company Signup</h2>
    <form method="post" action="pharmaceutica">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required><br><br>
      <label for="phone_number">Phone Number:</label>
      <input type="text" id="phone_number" name="phone_number" required><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>
      <input type="submit" value="Signup">

    </form>
  </div>
</body>
</html>
