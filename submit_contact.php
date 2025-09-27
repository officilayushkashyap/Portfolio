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
    header("Location: index.html");
    exit();
}

// ------------------------------------
// 3. Connect to MySQL
// ------------------------------------
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and handle failure securely
if ($conn->connect_error) {
    // ⚠️ Security enhancement: Log the error on the server, and redirect the user
    error_log("MySQL Connection Failed: " . $conn->connect_error);
    header("Location: index.html#contact?status=error&msg=" . urlencode("Server connection failed."));
    exit();
}

// ------------------------------------
// 4. Sanitize and Prepare Data
// ------------------------------------
if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Use prepared statements
    $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, message) VALUES (?, ?, ?)");
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
        header("Location: index.html#contact?status=error&msg=" . urlencode("Failed to save message."));
    }

    $stmt->close();
    
} else {
    // Missing fields error
    header("Location: index.html#contact?status=error&msg=" . urlencode("Missing required form fields."));
}

$conn->close();
exit();

?>