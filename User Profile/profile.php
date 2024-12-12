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
            <!-- Placeholder for Profile Picture -->
            <img src="images/profile_image.jpg" alt="Profile Picture">
        </div>
        <div class="profile-info">
            <h2>Example 1</h2>
            <p>Email: test@gmail.com</p>
        </div>
        <a href="#" class="edit-profile-btn" onclick="toggleEditForm(event)">Edit Profile</a>
    </div>

    <div class="edit-form" id="editForm">
        <h3>Edit Profile</h3>
        <form>
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" placeholder="Enter full name">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter email">

            <label for="profilePicture">Profile Picture</label>
            <input type="file" id="profilePicture" name="profilePicture">

            <div class="form-actions">
                <button type="button" class="save-btn">Save</button>
                <button type="button" class="cancel-btn" onclick="toggleEditForm(event)">Cancel</button>
            </div>
        </form>
    </div>
    <script src="profile_script.js"></script>
</body>
</html>
