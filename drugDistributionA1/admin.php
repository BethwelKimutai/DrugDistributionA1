<?php
session_start();

$host = 'localhost';
$db = 'ddapp';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$users = array();

try {
    $stmt = $conn->prepare("SELECT * FROM patients");
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($patients as $patient) {
        $users[] = array(
            'id' => $patient['ssn'],
            'username' => $patient['name'],
            'type' => 'Patient'
        );
    }

    $stmt = $conn->prepare("SELECT * FROM doctors");
    $stmt->execute();
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($doctors as $doctor) {
        $users[] = array(
            'id' => $doctor['ssn'],
            'username' => $doctor['name'],
            'type' => 'Doctor'
        );
    }

    $stmt = $conn->prepare("SELECT * FROM pharmaceutical_companies");
    $stmt->execute();
    $pharmaceuticalCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pharmaceuticalCompanies as $company) {
        $users[] = array(
            'id' => $company['id'],
            'username' => $company['name'],
            'type' => 'Pharmaceutical Company'
        );
    }

    $stmt = $conn->prepare("SELECT * FROM pharmacies");
    $stmt->execute();
    $pharmacies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pharmacies as $pharmacy) {
        $users[] = array(
            'id' => $pharmacy['id'],
            'username' => $pharmacy['name'],
            'type' => 'Pharmacy'
        );
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_drug'])) {
        $trade_name = $_POST['trade_name'];
        $formula = $_POST['formula'];
        $pharmaceutical_company_id = $_POST['pharmaceutical_company_id'];
        $category = $_POST['category'];

        // Handle drug image upload
        $targetDirectory = __DIR__ . "/uploads/";
        $targetFile = $targetDirectory . basename($_FILES["drug_image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image
        $check = getimagesize($_FILES["drug_image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            exit();
        }

        // Move the uploaded image to the target directory
        if (!move_uploaded_file($_FILES["drug_image"]["tmp_name"], $targetFile)) {
            echo "Error uploading image.";
            exit();
        }        

        // Insert drug details into the database, including the image path
        $stmt = $conn->prepare("INSERT INTO drugdetails (trade_name, formula, pharmaceutical_company_id, category, image_path) VALUES (:trade_name, :formula, :pharmaceutical_company_id, :category, :image_path)");

        $stmt->bindParam(':trade_name', $trade_name);
        $stmt->bindParam(':formula', $formula);
        $stmt->bindParam(':pharmaceutical_company_id', $pharmaceutical_company_id);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image_path', $targetFile);

        try {
            $stmt->execute();
            echo "Drug added successfully!";
        } catch (PDOException $e) {
            echo "Error adding drug: " . $e->getMessage();
        }
    } elseif (isset($_POST['update_user'])) {
        $user_type = $_POST['user_type'];
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate and sanitize user input as needed

    switch ($user_type) {
        case 'Patient':
            $stmt = $conn->prepare("UPDATE patients SET name = :username, password = :password WHERE ssn = :id");
            break;

        case 'Doctor':
            $stmt = $conn->prepare("UPDATE doctors SET name = :username, password = :password WHERE ssn = :id");
            break;

        case 'Pharmaceutical Company':
            $stmt = $conn->prepare("UPDATE pharmaceutical_companies SET name = :username, password = :password WHERE id = :id");
            break;

        case 'Pharmacy':
            $stmt = $conn->prepare("UPDATE pharmacies SET name = :username, password = :password WHERE id = :id");
            break;

        default:
            
            break;
    }

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();
        echo "User updated successfully!";
    } catch (PDOException $e) {
        echo "Error updating user: " . $e->getMessage();
    }


        
    } elseif (isset($_POST['update_drug'])) {
        $id = $_POST['id'];
    $trade_name = $_POST['trade_name'];
    $formula = $_POST['formula'];
    $pharmaceutical_company_id = $_POST['pharmaceutical_company_id'];

    // Validate and sanitize user input as needed

    $stmt = $conn->prepare("UPDATE drugdetails SET trade_name = :trade_name, formula = :formula, pharmaceutical_company_id = :pharmaceutical_company_id WHERE id = :id");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':trade_name', $trade_name);
    $stmt->bindParam(':formula', $formula);
    $stmt->bindParam(':pharmaceutical_company_id', $pharmaceutical_company_id);

    try {
        $stmt->execute();
        echo "Drug updated successfully!";
    } catch (PDOException $e) {
        echo "Error updating drug: " . $e->getMessage();
    }

        
    }
}

if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['type'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($action === 'delete') {
        switch ($type) {
            case 'Drug':
                try {
                    $stmt = $conn->prepare("DELETE FROM drugdetails WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    echo "Drug deleted successfully!";
                } catch (PDOException $e) {
                    echo "Error deleting drug: " . $e->getMessage();
                }
                break;

            default:
               
                try {
                    switch ($type) {
                        case 'Patient':
                            $stmt = $conn->prepare("DELETE FROM patients WHERE ssn = :id");
                            break;

                        case 'Doctor':
                            $stmt = $conn->prepare("DELETE FROM doctors WHERE ssn = :id");
                            break;

                        case 'Pharmaceutical Company':
                            $stmt = $conn->prepare("DELETE FROM pharmaceutical_companies WHERE id = :id");
                            break;

                        case 'Pharmacy':
                            $stmt = $conn->prepare("DELETE FROM pharmacies WHERE id = :id");
                            break;

                        default:
                            break;
                    }

                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    echo "User deleted successfully!";
                } catch (PDOException $e) {
                    echo "Error deleting user: " . $e->getMessage();
                }
                break;
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    h1, h2 {
        color: #333;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }

    th,
    td {
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #00abf0;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    a {
        text-decoration: none;
        color: #000;
    }

    .logout-button {
        background-color: blue;
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="text"],
    input[type="number"],
    input[type="password"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #00abf0;
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
    }

    .success-message {
        color: green;
        margin-top: 10px;
    }

    .error-message {
        color: red;
        margin-top: 10px;
    }
</style>

</head>

<body>
<?php

    if (isset($_SESSION['username'])) {
        $loggedInUsername = $_SESSION['username'];
    } else {
       
        header("Location: login.php");
        exit();
    }
    ?>
    <h1>Welcome, <?php echo $loggedInUsername; ?> - Admin Dashboard </h1>
    <form method="post" action="logout.php">
        <button class="logout-button" type="submit">Logout</button>
    </form>

    <h2>User List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Type</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['type']; ?></td>
                <td>
                    <a href="admin.php?action=edit&id=<?php echo $user['id']; ?>&type=<?php echo $user['type']; ?>">Edit</a> |
                    <a href="admin.php?action=delete&id=<?php echo $user['id']; ?>&type=<?php echo $user['type']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>


    <h2>Add Drug</h2>
<form action="admin.php" method="POST" enctype="multipart/form-data">
    <label for="trade_name">Drug Name:</label>
    <input type="text" id="trade_name" name="trade_name" required><br>
    <label for="formula">Formula:</label>
    <input type="text" id="formula" name="formula" required><br>
    <label for="pharmaceutical_company_id">Pharmaceutical Company ID:</label>
    <input type="number" id="pharmaceutical_company_id" name="pharmaceutical_company_id" required><br>
    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br>
    <label for="drug_image">Drug Image:</label>
    <input type="file" id="drug_image" name="drug_image" accept="image/*" required><br>
    <input type="submit" name="add_drug" value="Add Drug">
</form>

    

    <h2>Drug List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Drug Name</th>
            <th>Formula</th>
            <th>Pharmaceutical Company ID</th>
            <th>Action</th>
        </tr>
        <?php
        $stmt = $conn->prepare("SELECT * FROM drugs");
        $stmt->execute();
        $drugs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($drugs as $drug) :
        ?>
            <tr>
                <td><?php echo $drug['id']; ?></td>
                <td><?php echo $drug['trade_name']; ?></td>
                <td><?php echo $drug['formula']; ?></td>
                <td><?php echo $drug['pharmaceutical_company_id']; ?></td>
                <td>
                    <a href="admin.php?action=edit&id=<?php echo $drug['id']; ?>&type=Drug">Edit</a> |
                    <a href="admin.php?action=delete&id=<?php echo $drug['id']; ?>&type=Drug">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) && isset($_GET['type'])) {
        $id = $_GET['id'];
        $type = $_GET['type'];
        $user = null;
    
        switch ($type) {
            case 'Patient':
                $stmt = $conn->prepare("SELECT * FROM patients WHERE ssn = :id");
                break;
    
            case 'Doctor':
                $stmt = $conn->prepare("SELECT * FROM doctors WHERE ssn = :id");
                break;
    
            case 'Pharmaceutical Company':
                $stmt = $conn->prepare("SELECT * FROM pharmaceutical_companies WHERE id = :id");
                break;
    
            case 'Pharmacy':
                $stmt = $conn->prepare("SELECT * FROM pharmacies WHERE id = :id");
                break;
    
            default:
                break;
        }
    
        $stmt->bindParam(':id', $id);
    
        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching user details: " . $e->getMessage();
        }
    
        if ($user) {
            echo '<h2>Edit User</h2>';
            echo '<form action="admin.php" method="POST">';
            echo '<input type="hidden" name="user_type" value="' . $type . '">';
            echo '<input type="hidden" name="id" value="' . $id . '">';
            echo '<label for="username">Username:</label>';
            echo '<input type="text" id="username" name="username" value="' . $user['name'] . '" required><br>';
            echo '<label for="password">Password:</label>';
            echo '<input type="password" id="password" name="password" required><br>';
            echo '<input type="submit" name="update_user" value="Update User">';
            echo '</form>';
        } else {
            echo "User not found!";
        }
    }
    
    ?>

    <?php
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) && $_GET['type'] === 'Drug') {
        $id = $_GET['id'];
        $drug = null;

        $stmt = $conn->prepare("SELECT * FROM drugs WHERE id = :id");
        $stmt->bindParam(':id', $id);

        try {
            $stmt->execute();
            $drug = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching drug details: " . $e->getMessage();
        }

        if ($drug) {
            echo '<h2>Edit Drug</h2>';
            echo '<form action="admin.php" method="POST">';
            echo '<input type="hidden" name="id" value="' . $id . '">';
            echo '<label for="trade_name">Drug Name:</label>';
            echo '<input type="text" id="trade_name" name="trade_name" value="' . $drug['trade_name'] . '" required><br>';
            echo '<label for="formula">Formula:</label>';
            echo '<input type="text" id="formula" name="formula" value="' . $drug['formula'] . '" required><br>';
            echo '<label for="pharmaceutical_company_id">Pharmaceutical Company ID:</label>';
            echo '<input type="number" id="pharmaceutical_company_id" name="pharmaceutical_company_id" value="' . $drug['pharmaceutical_company_id'] . '" required><br>';
            echo '<input type="submit" name="update_drug" value="Update Drug">';
            echo '</form>';
        } else {
            echo "Drug not found!";
        }
    }
    ?>

</body>

</html>


