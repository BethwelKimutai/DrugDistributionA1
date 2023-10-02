

<!DOCTYPE html>
<html>
<head>
  <title>Pharmaceutical Company Login</title>
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
    input[type="password"] {
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
    <h2>Pharmaceutical Company Login</h2>
    <form action="checkphrrmalogin.php" method="post">
      <label for="id">ID:</label>
      <input type="text" id="id" name="id" required><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>
      <input type="submit" value="Login">
      <p>Don't have an account? <a href="pharmaceutical_company_signup.php">Signup here</a></p>
    </form>
  </div>
</body>
</html>
