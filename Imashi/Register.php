<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Register.css">
    <title>Registration</title>
    <style>
        /* Inline styles for example; move to Register.css */
        .error {
            border: 2px solid red;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: -10px;
            margin-bottom: 10px;
        }
        .success {
            border: 2px solid green;
        }
    </style>
</head>
<body>
    <form id="registrationForm" action="function.dbh.php" method="post" novalidate>
        <h2>Register</h2>
        
        <!-- Full Name -->
        <label for="fullName">Full Name:</label><br>
        <input type="text" id="fullName" name="fullName" required>
        <div id="fullNameError" class="error-message"></div><br>
        
        <!-- Email -->
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required>
        <div id="emailError" class="error-message"></div><br>
        
        <!-- Password -->
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required>
        <div id="passwordError" class="error-message"></div><br>
        
        <!-- Confirm Password -->
        <label for="confirmPassword">Confirm Password:</label><br>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <div id="confirmPasswordError" class="error-message"></div><br>
        
        <input type="submit" value="Register">
    </form>

    <script>
        // Function to validate input fields
        const validateInput = (input, errorMessage, condition) => {
            const errorDiv = document.getElementById(errorMessage);
            if (condition) {
                input.classList.add('error');
                input.classList.remove('success');
                errorDiv.textContent = condition;
                return false;
            } else {
                input.classList.remove('error');
                input.classList.add('success');
                errorDiv.textContent = '';
                return true;
            }
        };

        // Add event listeners for real-time validation
        document.getElementById('fullName').addEventListener('input', function () {
            validateInput(this, 'fullNameError', !this.value.trim() ? 'Full Name is required' : '');
        });

        document.getElementById('email').addEventListener('input', function () {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            validateInput(this, 'emailError', !emailRegex.test(this.value) ? 'Enter a valid email address' : '');
        });

        document.getElementById('password').addEventListener('input', function () {
            const passwordValue = this.value;
            const minLength = passwordValue.length < 8 ? 'Password must be at least 8 characters long.' : '';
            const hasLetter = !/[a-zA-Z]/.test(passwordValue) ? 'Password must contain at least one letter.' : '';
            const hasNumber = !/[0-9]/.test(passwordValue) ? 'Password must contain at least one number.' : '';

            const errorMessage = minLength || hasLetter || hasNumber;
            validateInput(this, 'passwordError', errorMessage);
        });

        document.getElementById('confirmPassword').addEventListener('input', function () {
            const password = document.getElementById('password').value;
            validateInput(this, 'confirmPasswordError', this.value !== password ? 'Passwords do not match' : '');
        });

        // Form submission validation
        document.getElementById('registrationForm').addEventListener('submit', function (e) {
            const fullName = document.getElementById('fullName');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');

            const isFullNameValid = validateInput(fullName, 'fullNameError', !fullName.value.trim() ? 'Full Name is required' : '');
            const isEmailValid = validateInput(email, 'emailError', !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value) ? 'Enter a valid email address' : '');
            const isPasswordValid = validateInput(password, 'passwordError', password.value.length < 8 || 
                !/[a-zA-Z]/.test(password.value) || !/[0-9]/.test(password.value) ? 
                'Password must be at least 8 characters, include at least one letter, and one number.' : '');
            const isConfirmPasswordValid = validateInput(confirmPassword, 'confirmPasswordError', confirmPassword.value !== password.value ? 'Passwords do not match' : '');

            if (!(isFullNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid)) {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });
    </script>
</body>
</html>