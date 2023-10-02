<!DOCTYPE html>
<html>
<head>
    <title>OnDoc</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
            color: #333;
        }
        
        p {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        li {
            margin-bottom: 10px;
        }
        
        a {
            display: block;
            padding: 10px;
            background-color: #eee;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        
        a:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>OnDoc</h1>
        <h1>Select your role:</h1>
        <ul>
            <li><a href="doctor_login.php">Doctor</a></li>
            <li><a href="patient_login.php">Patient</a></li>
            <li><a href="pharmaceutical_company_login.php">Pharmaceutical Company</a></li>
            <li><a href="pharmacy_login.php">Pharmacy</a></li>
            <li><a href="admin_login.php">Admin</a></li>
        </ul>
    </div>
    <script>
        const links = document.querySelectorAll('a');

    links.forEach(link => {
    link.addEventListener('mouseover', () => {
        link.style.backgroundColor = '#00abf0';
        link.style.color = '#fff';
    });

    link.addEventListener('mouseout', () => {
        link.style.backgroundColor = '#eee';
        link.style.color = '#333';
    });
});
    </script>
</body>
</html>
