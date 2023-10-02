<!DOCTYPE html>
<html>
<head>
  <title>Pharmacy Management System - Dashboard</title>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
    }
  
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 40px;
      background-color: #ffffff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  
    h2 {
      text-align: center;
    }
  
    .logout-button {
      display: block;
      width: 100px;
      margin: 20px auto;
      background-color: green;
      color: #ffffff;
      border: none;
      padding: 8px;
      border-radius: 4px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
    }
  
    .content {
      margin-top: 20px;
    }
  
    table {
      width: 100%;
      border-collapse: collapse;
    }
  
    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
  
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, Pharmacist!</h2>
    <a href="logout.php" class="logout-button">Logout</a>
    <div class="content">
      <h3>Prescriptions</h3>
      <table>
        <tr>
          <th>Prescription ID</th>
          <th>Patient SSN</th>
          <th>Doctor SSN</th>
          <th>Drug ID</th>
          <th>Date Prescribed</th>
          <th>Quantity</th>
        </tr>
        <?php
       
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ddapp";
      
      
        $conn = mysqli_connect($servername, $username, $password, $dbname);
      
       
        if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
        }
      
        $query = "SELECT * FROM prescriptions";
        $result = mysqli_query($conn, $query);
      
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['patient_ssn'] . "</td>";
            echo "<td>" . $row['doctor_ssn'] . "</td>";
            echo "<td>" . $row['drug_id'] . "</td>";
            echo "<td>" . $row['date_prescribed'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='6'>No prescriptions found</td></tr>";
        }
       
        
        mysqli_close($conn);
        ?>
      </table>
    </div>
  </div>


</body>
</html>

