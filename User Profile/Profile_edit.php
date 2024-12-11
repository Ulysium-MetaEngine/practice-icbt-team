<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
require 'db_connection.php';

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $errors = [];

    // Validate inputs
    if (empty($name) || strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = "Full Name must be between 2 and 100 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check email uniqueness
        $query = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Email is already in use.";
        }
    }

    // Handle profile picture upload
    $profile_picture = $user['profile_picture'];
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $profile_picture = $target_file;
        } else {
            $errors[] = "Error uploading the profile picture.";
        }
    }

    // Update database if no errors
    if (empty($errors)) {
        $query = "UPDATE users SET name = ?, email = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssi', $name, $email, $profile_picture, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully.";
            header('Location: profile.php');
            exit();
        } else {
            $errors[] = "Error updating profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="profile_edit_styles.css">
    
</head>
<body>
<div class="form-container">
    <h2>Edit Profile</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture">
        </div>
        <div class="form-actions">
            <button type="submit" class="save-btn">Save</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='profile.php';">Cancel</button>
        </div>
    </form>
</div>
</body>
</html>
