<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Register.css">
    <title>Registration</title>
</head>
<body>
    
    <form id="registrationForm" action="#" method="post">
    <h2>Register</h2>
        <!-- Full Name -->
        <label for="fullName">Full Name:</label><br>
        <input type="text" id="fullName" name="fullName" required><br><br>
        
        <!-- Email -->
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <!-- Password -->
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <!-- Confirm Password -->
        <label for="confirmPassword">Confirm Password:</label><br>
        <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>
        
        <input type="submit" value="Register">
    </form>

    <script>
        // Validate the form
        document.getElementById('registrationForm').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                e.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>