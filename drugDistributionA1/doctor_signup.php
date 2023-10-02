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
  $specialty = $_POST["specialty"];
  $yearsOfExperience = $_POST["years_of_experience"];
  $password = $_POST["password"];


  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


  $query = "INSERT INTO doctors (name, specialty, years_of_experience, password)
            VALUES ('$name', '$specialty', '$yearsOfExperience', '$hashedPassword')";


  if (mysqli_query($conn, $query)) {
    echo "Doctor registered successfully!";
  } else {
    echo "Error: " . mysqli_error($conn);
  }

 
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Doctor Signup</title>
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
    
    select {
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
    <h2>Doctor Signup</h2>
    <h2><a href="index.php" class="logout-button">Home</a></h2>
    <form method="post">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required><br><br>
      <label for="specialty">Specialty:</label>
      <select id="specialty" name="specialty" required>
        <option>General Health</option>
        <option>Cardiology</option>
        <option>Dental</option>
        <option>Neurology</option>
        <option>Orthopaedics</option>
      </select><br><br>
      <label for="years_of_experience">Years of Experience:</label>
      <input type="number" id="years_of_experience" name="years_of_experience" required><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>
      <input type="submit" value="Signup">
      <p>Already have an account? <a href="doctor_login.php">Login here</a></p>
    </form>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      var signupForm = document.getElementById("signup-form");
      var successMessage = document.getElementById("success-message");

      signupForm.addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Serialize form data to send via POST request
        var formData = new FormData(signupForm);

        // Send the form data via a POST request to your server
        fetch("your_server_endpoint.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          // Assuming the registration was successful
          // Display the success message
          successMessage.style.display = "block";

          // Hide the success message after 1 second (1000 milliseconds)
          setTimeout(function() {
            successMessage.style.display = "none";
          }, 1000);
        })
        .catch(error => {
          console.error("Error:", error);
          // Handle errors here
        });
      });
    });
    </script>
  </div>
</body>
</html>
