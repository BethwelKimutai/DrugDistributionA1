<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'pharmaceutical_company') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ddapp";
$conn = mysqli_connect($servername, $username, $password, $dbname);


$pharmacy_name = $_SESSION['pharmacy_name'];
$query = "SELECT * FROM pharmacies WHERE name = '$pharmacy_name'";
$result = mysqli_query($conn, $query);
$pharmacy = mysqli_fetch_assoc($result);


$query = "SELECT drugs.trade_name, drugs.formula, pharmacy_drugs.price 
          FROM drugs 
          INNER JOIN pharmacy_drugs ON drugs.id = pharmacy_drugs.drug_id 
          WHERE pharmacy_drugs.pharmacy_id = '$pharmacy_id'";
$result = mysqli_query($conn, $query);
$drugs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Landing Page</title>
</head>
<body>
    <h2>Welcome, <?php echo $pharmacy['name']; ?>!</h2>
    <p>Address: <?php echo $pharmacy['address']; ?></p>
    <p>Phone Number: <?php echo $pharmacy['phone_number']; ?></p>

    <h3>Available Drugs</h3>
    <?php if (!empty($drugs)) { ?>
        <table>
            <tr>
                <th>Trade Name</th>
                <th>Formula</th>
                <th>Price</th>
            </tr>
            <?php foreach ($drugs as $drug): ?>
                <tr>
                    <td><?php echo $drug['trade_name']; ?></td>
                    <td><?php echo $drug['formula']; ?></td>
                    <td><?php echo $drug['price']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php } else { ?>
        <p>No drugs available at the pharmacy.</p>
    <?php } ?>
</body>
</html>
