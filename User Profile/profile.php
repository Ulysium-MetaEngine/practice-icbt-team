<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database helper file
if (!file_exists('db_helper.php')) {
    die("Error: db_helper.php file not found!");
}
include 'db_helper.php';

// Check if the database connection is established
if (!isset($conn)) {
    die("Error: Database connection not established!");
}

// Replace this with the session user ID or a dynamic parameter
$userId = 1;

// Prepare and execute the SQL statement to fetch user details
$sql = "SELECT full_name, email, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $fullName = htmlspecialchars($user['full_name']);
        $email = htmlspecialchars($user['email']);
        $profilePicture = !empty($user['profile_picture']) ? $user['profile_picture'] : 'images/default_profile.png';
    } else {
        $fullName = "N/A";
        $email = "N/A";
        $profilePicture = 'images/default_profile.png';
    }

    $stmt->close();
} else {
    die("Error: Failed to prepare SQL statement.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles/profile_styles.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-picture">
            <img src="<?php echo $profilePicture; ?>" alt="Profile Picture">
        </div>
        <div class="profile-info">
            <h2><?php echo $fullName; ?></h2>
            <p>Email: <?php echo $email; ?></p>
        </div>
        <a href="profile_edit.php" class="edit-profile-btn">Edit Profile</a>
    </div>
</body>
</html>
