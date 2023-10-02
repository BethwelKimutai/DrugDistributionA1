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
  $id = $_POST["id"];
  $password = $_POST["password"];


  $query = "SELECT * FROM pharmaceutical_companies WHERE id = '$id' LIMIT 1";


  $result = mysqli_query($conn, $query);

  if ($result) {

    $company = mysqli_fetch_assoc($result);

    if ($company && password_verify($password, $company['password'])) {

      session_start();
      $_SESSION["id"] = $id;

     
      header("Location: pharmaceutical_company.php");
      exit();
    } else {
      echo "<p style='color: red;'>Invalid ID or password</p>";
    }
  } else {
    echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
  }


  mysqli_close($conn);
}
?>