<?php

// ------------------------------------
// 1. Database and Server Configuration
// ------------------------------------
$servername = "localhost";
$username = "root";    
$password = "@Rahul123"; 
$dbname = "Portfolio_Database";    

// ------------------------------------
// 2. Validate Request Method
// ------------------------------------
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Prevent direct script access
    header("Location: index.html");
    exit();
}

// ------------------------------------
// 3. Connect to MySQL
// ------------------------------------
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and handle failure securely
if ($conn->connect_error) {
    // Log the error for developer review (instead of exposing it to the user)
    error_log("MySQL Connection Failed: " . $conn->connect_error);
    
    // Redirect with a generic error status
    header("Location: index.html#contact?status=error&msg=" . urlencode("Connection Error: Server could not process your request."));
    exit();
}

// ------------------------------------
// 4. Sanitize and Prepare Data
// ------------------------------------
// Check if all required fields are set
if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Use prepared statements to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, message) VALUES (?, ?, ?)");
    
    // "sss" means three parameters, all strings
    $stmt->bind_param("sss", $name, $email, $message);

    // ------------------------------------
    // 5. Execute Query and Handle Response
    // ------------------------------------
    if ($stmt->execute()) {
        // Success: Redirect back to the contact section with status=success
        header("Location: index.html#contact?status=success");
    } else {
        // Query Error
        error_log("MySQL Query Failed: " . $stmt->error);
        
        // Redirect with a specific error message
        header("Location: index.html#contact?status=error&msg=" . urlencode("Database Error: Failed to save your message."));
    }

    $stmt->close();
    
} else {
    // Missing fields error
    header("Location: index.html#contact?status=error&msg=" . urlencode("Please fill out all required fields."));
}

$conn->close();
exit();

?>
