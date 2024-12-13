<?php
// Temporarily remove session security as login is not implemented yet
// session_start();

// Redirect unauthorized users to the login page
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Start the session to check session variables
session_start();

// Redirect unauthorized users to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database helper file
require_once 'db-helper.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Initialize error and success messages
$errorMessage = "";
$successMessage = "";

// Fetch user details from the database
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
        $profilePicture = !empty($user['profile_picture']) ? $user['profile_picture'] : 'images/profile_image.jpg';
    }

    $stmt->close();
} else {
    die("Error: Failed to prepare SQL statement.");
}

// Handle profile picture upload and update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $profilePicturePath = $profilePicture; // Default to the current profile picture

    // Validate the file if an image is uploaded
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
        $file = $_FILES['profilePicture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB max size

        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            $errorMessage = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
        }
        // Validate file size
        elseif ($file['size'] > $maxSize) {
            $errorMessage = 'File size exceeds the 5MB limit.';
        } else {
            // Generate a unique file name and move the uploaded file to the server
            $uploadDir = 'uploads/profile_pictures/';
            $fileName = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // File uploaded successfully, update the profile picture path
                $profilePicturePath = $filePath;
            } else {
                $errorMessage = 'There was an error uploading the file. Please try again.';
            }
        }
    }

    // Check if the email is unique
    if (empty($errorMessage)) {
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $email, $userId);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errorMessage = "The email address is already in use. Please choose another one.";
            }
            $stmt->close();
        }
    }

    // Proceed with updating the profile in the database if no errors
    if (empty($errorMessage)) {
        $sql = "UPDATE users SET full_name = ?, email = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssi", $fullName, $email, $profilePicturePath, $userId);
            if ($stmt->execute()) {
                $successMessage = "Profile updated successfully!";
            } else {
                $errorMessage = "Error: Could not update the profile.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles/profile_styles.css">
    <script>
        // Client-side validation for Full Name and Email
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profileForm');
            form.addEventListener('submit', function(event) {
                // Clear previous error messages
                document.getElementById('fullNameError').textContent = '';
                document.getElementById('emailError').textContent = '';

                // Get form values
                const fullName = document.getElementById('fullName').value;
                const email = document.getElementById('email').value;
                let isValid = true;

                // Validate Full Name
                if (fullName.length < 2 || fullName.length > 100) {
                    document.getElementById('fullNameError').textContent = 'Full Name must be between 2 and 100 characters.';
                    isValid = false;
                }

                // Validate Email
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (!emailPattern.test(email)) {
                    document.getElementById('emailError').textContent = 'Please enter a valid email address.';
                    isValid = false;
                }

                // Prevent form submission if validation fails
                if (!isValid) {
                    event.preventDefault();
                }
            });

            // Cancel Button Functionality
            const cancelButton = document.getElementById('cancelBtn');
            cancelButton.addEventListener('click', function() {
                // Reset form fields to original values
                document.getElementById('fullName').value = "<?php echo $fullName; ?>";
                document.getElementById('email').value = "<?php echo $email; ?>";
                document.getElementById('profilePicture').value = ""; // Reset the file input field

                // Redirect to profile view
                window.location.href = "profile.php";
            });
        });
    </script>
</head>
<body>
    <div class="profile-container">
        <!-- Display success message -->
        <?php if ($successMessage): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- Display error message -->
        <?php if ($errorMessage): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="profile-picture">
            <img src="<?php echo $profilePicturePath; ?>" alt="Profile Picture">
        </div>
        <div class="profile-info">
            <h2><?php echo $fullName; ?></h2>
            <p>Email: <?php echo $email; ?></p>
        </div>

        <form id="profileForm" action="profile_update.php" method="POST" enctype="multipart/form-data">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo $fullName; ?>" required>
            <span id="fullNameError" class="error-message"></span>
            <br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <span id="emailError" class="error-message"></span>
            <br>

            <label for="profilePicture">Profile Picture:</label>
            <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
            <br>

            <button type="submit">Update Profile</button>
            <button type="button" id="cancelBtn">Cancel</button> <!-- Cancel Button -->
        </form>

        <a href="profile.php" class="edit-profile-btn">Back to Profile</a>
    </div>
</body>
</html>
