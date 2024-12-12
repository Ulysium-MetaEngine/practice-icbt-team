<?php
// Include the database connection file
require_once 'dbh.php';

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Initialize variables
    $errors = [];
    $response = ["status" => "error", "errors" => []];

    // Sanitize inputs
    $fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

    // Validate Full Name
    if (empty($fullName)) {
        $errors[] = "Full Name is required.";
    } elseif (strlen($fullName) < 2 || strlen($fullName) > 100) {
        $errors[] = "Full Name must be between 2 and 100 characters.";
    }

    // Validate Email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check email uniqueness in the database
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "This email is already registered.";
        }
    }

    // Validate Password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors[] = "Password must be at least 8 characters long and contain at least one letter and one number.";
    }

    // Validate Confirm Password
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }

    // If validation passes, insert user into the database
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (:fullName, :email, :password)");
        $stmt->execute([
            'fullName' => $fullName,
            'email' => $email,
            'password' => $hashedPassword,
        ]);

        // Return success response
        $response = ["status" => "success", "message" => "Registration successful!"];
        echo json_encode($response);
        exit;
    } catch (PDOException $e) {
        $response['errors'][] = "Error saving data: " . $e->getMessage();
        echo json_encode($response);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}
?>