<?php
include 'dbhelper.php'; 

$userId = 1; // Replace with session user ID or parameter
$sql = "SELECT full_name, email, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql); 
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
    $profilePicture = 'images/profile_image.jpg';
}

$stmt->close();
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
