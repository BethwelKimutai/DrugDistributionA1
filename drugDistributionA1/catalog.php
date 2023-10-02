<?php
session_start();
if (!isset($_SESSION["name"])) {
  header("Location: patientLogin.php");
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
  <title>Main Page</title>
  <link rel="stylesheet" href="mainPage.css">
  <link rel="stylesheet" href = "https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

  <title>Doctor's panel</title>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li>
                    <a href="#">
                        <i class="fas fa-clinic-medical"></i> 
                        <div class="title"><h3> <?php  echo $_SESSION['name']; ?></h3></div>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="fas fa-th-large"></i> 
                        <div class="title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="doctors.php">
                        <i class="fas fa-stethoscope"></i> 
                        <div class="title">Doctors</div>
                    </a>
                </li>
                <li>
                    <a href="about.php">
                        <i class="fas fa-user-md"></i> 
                        <div class="title">About</div>
                    </a>
                </li>
                <li>
                    <a href="contact.php">
                        <i class="fas fa-puzzle-piece"></i> 
                        <div class="title">Contact</div>
                    </a>
                </li>
                <li>
                    <a href="catalog.php">
                        <i class="fas fa-hand-holding-usd"></i> 
                        <div class="title">Medicines</div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-cog"></i> 
                        <div class="title">Settings</div>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fas fa-question"></i> 
                        <div class="title">Logout</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="top-bar">
                <div class="search">
                    <input type="text" name="search" placeholder="search here">
                    <label for = "search"><i class = "fas fa-search"></i></label>
                </div>
                <i class="fas fa-bell"></i>
                <div class="user"> 
                <a href="profilepic.php"><img src="user.png" alt=""></a> 

                </div>
            </div>
            <div class="cards">
                <div class="card">
                    <div class="card-content">
                        <div class="number">2</div>
                        <div class="card-name">Appointments</div>
                    </div>
                    <div class="icon-box">
                        <i class = "fas fa-solid fa-calendar-check"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="number">3</div>
                        <div class="card-name">Pending Approvals</div>
                    </div>
                    <div class="icon-box">
                        <i class = "fas fa-solid fa-quote-right"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="number">8</div>
                        <div class="card-name">Approved Medicines</div>
                    </div>
                    <div class="icon-box">
                        <i class = "fas fa-solid fa-check"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="number">$4500</div>
                        <div class="card-name">Amount Paid</div>
                    </div>
                    <div class="icon-box">
                        <i class = "fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="tables">
                <div class="last-appointments">
                    <div class="heading">
                        <h2>Our Medicine</h2>
                        
                    </div><table class = "visiting" id="medicine_table">
                    <?php
$host = 'localhost';
$db = 'ddapp';
$user = 'root';
$password = '';

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




 foreach ($drugs as $drug): ?>
    <div class="drug-item">
        <img src="<?php echo $drug['image_path']; ?>" alt="<?php echo $drug['drug_name']; ?>">
        <a href="details.php?drug_id=<?php echo $drug['id']; ?>">View Details</a>
    </div>
<?php endforeach; ?>
                    
             




                              
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Function to fetch and display medicine data
    function loadMedicines() {
        $.ajax({
            url: 'get_medicines.php', // URL of your PHP script
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Clear existing table rows
                $('#medicine-table tbody').empty();

                // Populate the table with medicine data
                $.each(data, function(index, medicine) {
                    $('#medicine-table tbody').append(
                        '<tr>' +
                            '<td>' + medicine.trade_name + '</td>' +
                            '<td>' + medicine.formula + '</td>' +
                            // Add more table cells for additional fields
                            '</tr>'
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX error: ' + status + ' - ' + error);
            }
        });
    }

    // Initial load of medicine data
    loadMedicines();
});
</script>

</html>