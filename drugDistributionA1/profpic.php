<?php
session_start();
if (!isset($_SESSION["name"])) {
  header("Location: patient_login.php");
  exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ddapp";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
    $name = $_SESSION["name"];
    $file_name = $_FILES["profile_picture"]["name"];
    $file_tmp = $_FILES["profile_picture"]["tmp_name"];
    $file_size = $_FILES["profile_picture"]["size"];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

   
    if ($file_size > 5242880) { 
      $error_message = "File size exceeds the allowed limit (5MB).";
    } else {
    
      $new_file_name = uniqid("profile_", true) . "." . $file_ext;

   
      $upload_path = "profile_pictures/" . $new_file_name;

   
      if (move_uploaded_file($file_tmp, $upload_path)) {
      
        $query = "INSERT INTO patient_profile_pictures (patient_ssn, file_name, file_path) 
                  SELECT ssn, '$file_name', '$upload_path' FROM patients WHERE name = '$name'";

   
        if (mysqli_query($conn, $query)) {
          $success_message = "Profile picture uploaded successfully!";
        } else {
          $error_message = "Error: " . mysqli_error($conn);
        }
      } else {
        $error_message = "Failed to upload the profile picture.";
      }
    }
  } else {
    $error_message = "Please choose a file to upload.";
  }
}

$name = $_SESSION["name"];
$query = "SELECT file_path FROM patient_profile_pictures 
          JOIN patients ON patient_profile_pictures.patient_ssn = patients.ssn
          WHERE patients.name = '$name' ORDER BY patient_profile_pictures.id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$profile_picture = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
  <title>Patient Landing Page</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="copyright" content="MACode ID, https://macodeid.com/">
  <link rel="stylesheet" href="../ddapp/assets/css/maicons.css">
  <link rel="stylesheet" href="../ddapp/assets/css/bootstrap.css">
  <link rel="stylesheet" href="../ddapp/assets/vendor/owl-carousel/css/owl.carousel.css">
  <link rel="stylesheet" href="../ddapp/assets/vendor/animate/animate.css">
  <link rel="stylesheet" href="vidstyle.css">
  <link rel="stylesheet" href="../ddapp/assets/css/theme.css">
  <link rel="stylesheet" href="MainPages.css">
  <link rel="stylesheet" href = "https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
    }

    .container-1 {
      max-width: 800px;
      margin: 0 auto;
      padding: 40px;
      background-color: #ffffff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .logout-button {
      background-color: green;
      color: #ffffff;
      border: none;
      padding: 8px 16px;
      font-size: 14px;
      border-radius: 4px;
      cursor: pointer;
    }

    .profile-section {
      display: flex;
      align-items: center;
    }

    .profile-picture {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 20px;
    }

    .profile-info {
      font-size: 16px;
      font-weight: bold;
    }

    .upload-form {
      margin-top: 0px;
    }

    .success-message {
      color: green;
      margin-top: 10px;
    }

    .error-message {
      color: red;
      margin-top: 10px;
    }

    table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
  </style>
</head>
<body>

  
  <div class="back-to-top"></div>

  <header>
    <div class="topbar">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 text-sm">
            <div class="site-info">
              
              <a href="tel:+254740860629"><span class="mai-call text-primary"></span> +254740860629</a>
              <span class="divider">|</span>
              <a href="mailto:admin@ondoc.com"><span class="mai-mail text-primary"></span> admin@ondoc.com</a>
            </div>
          </div>
          <div class="col-sm-4 text-right text-sm">
            <div class="social-mini-button">
              
             
          </div>
        </div> 
      </div> 
    </div> 

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="home.php"><span class="text-primary">On</span>Doc</a>
      </div> 
      <div class="profile-section">
        <?php if ($profile_picture && file_exists($profile_picture["file_path"])) : ?>
          <img class="profile-picture" src="<?php echo $profile_picture["file_path"]; ?>" alt="Profile Picture">
        <?php else : ?>
          <img class="profile-picture" src="default_profile_picture.png" alt="Profile Picture">
        <?php endif; ?>
        <div class="profile-info">
          Welcome, <?php echo $_SESSION["name"]; ?>!
        </div>
      </div>
      
    </div>
 
    </nav>
  </header>
</body>
</html>