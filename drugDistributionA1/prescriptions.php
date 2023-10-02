<?php
// Connect to the database (same connection code as in index.php)
session_start();

if (!isset($_SESSION["name"])) {
  header("Location: doctor_login.php");
  exit();
}

$hostname = "localhost";
$username = "root";
$password = ""; 
$database = "ddapp"; 

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


$patientSSN = $_SESSION["patient_ssn"]; 
$prescriptionsQuery = "SELECT p.id, d.trade_name, p.date_prescribed, p.quantity
                       FROM prescriptions p
                       INNER JOIN drugs d ON p.drug_id = d.id
                       WHERE p.patient_ssn = $patientSSN";
$prescriptionsResult = mysqli_query($connection, $prescriptionsQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Prescriptions</title>
</head>
<body>
    <h1>Your Prescriptions</h1>
    <table>
        <tr>
            <th>Prescription ID</th>
            <th>Drug Name</th>
            <th>Date Prescribed</th>
            <th>Quantity</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($prescriptionsResult)) : ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["trade_name"]; ?></td>
                <td><?php echo $row["date_prescribed"]; ?></td>
                <td><?php echo $row["quantity"]; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
