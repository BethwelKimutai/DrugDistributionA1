<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION["name"])) {
    header("Location: doctor_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Replace these database credentials with your actual values
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ddapp";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get doctor's SSN from session
    $doctor_ssn = $_SESSION["doctor_ssn"];

    // Get the data from the form submission
    $patient_ssn = $_POST["patient_ssn"];
    $drug_id = $_POST["drug_id"];
    $date_prescribed = $_POST["date_prescribed"];
    $quantity = $_POST["quantity"];

    // Perform data validation (Example: Checking if the patient SSN and drug ID exist)
    $patientExists = false;
    $drugExists = false;

    $stmt = $conn->prepare("SELECT ssn FROM patients WHERE ssn = ?");
    $stmt->bind_param("i", $patient_ssn);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $patientExists = true;
    }
    $stmt->close();

    $stmt = $conn->prepare("SELECT id FROM drugs WHERE id = ?");
    $stmt->bind_param("i", $drug_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $drugExists = true;
    }
    $stmt->close();

    if (!$patientExists || !$drugExists) {
        // Redirect back to the prescription form with an error message if patient or drug does not exist
        header("Location: prescription_form.php?error=1");
        exit();
    }

    // Prepare and execute the SQL query to insert prescription data (with SQL injection prevention)
    $stmt = $conn->prepare("INSERT INTO prescriptions (patient_ssn, doctor_ssn, drug_id, date_prescribed, quantity)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisi", $patient_ssn, $doctor_ssn, $drug_id, $date_prescribed, $quantity);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect to the patient landing page after successful prescription
        header("Location: patients.php");
        exit();
    } else {
        // Handle the case when the prescription insertion fails
        $stmt->close();
        $conn->close();
        // Redirect back to the prescription form with an error message if needed
        header("Location: prescription_form.php?error=2");
        exit();
    }
} else {
    // Redirect to the patient landing page if the form is not submitted through POST method
    header("Location: patients.php");
    exit();
}
