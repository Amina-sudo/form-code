<?php
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_applications";

// Create Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve personal information
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $cnic = $_POST['cnic'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $email = $_POST['email'] ?? '';
    $degree = $_POST['degree'] ?? '';
    $year = $_POST['year'] ?? '';
    $major = $_POST['major'] ?? '';
    $position = $_POST['position'] ?? '';
    $company = $_POST['company'] ?? '';
    $years = $_POST['years'] ?? '';

    // Handle file uploads
    $cvPath = $eduPath = $expPath = $photoPath = '';

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
        $cvPath = 'uploads/' . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);
    }

    if (isset($_FILES['edu_documents']) && $_FILES['edu_documents']['error'] === 0) {
        $eduPath = 'uploads/' . basename($_FILES['edu_documents']['name']);
        move_uploaded_file($_FILES['edu_documents']['tmp_name'], $eduPath);
    }

    if (isset($_FILES['exp_documents']) && $_FILES['exp_documents']['error'] === 0) {
        $expPath = 'uploads/' . basename($_FILES['exp_documents']['name']);
        move_uploaded_file($_FILES['exp_documents']['tmp_name'], $expPath);
    }

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $photoPath = 'uploads/' . basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $photoPath);
    }

    // SQL Query to Insert Data
    $sql = "INSERT INTO applications (fname, lname, cnic, contact, email, degree, year, major, position, company, years, cv, edu_documents, exp_documents, profile_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and Bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $fname, $lname, $cnic, $contact, $email, $degree, $year, $major, $position, $company, $years, $cvPath, $eduPath, $expPath, $photoPath);

    // Execute and Check
    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
