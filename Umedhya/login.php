<?php
// Start session
session_start();

// Include database connection
require_once './db.php';
$conn = new mysqli($server, $username, $password, $database);

// Set default timezone (if not already set)
if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

// Initialize error variable
$error = "";

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if (!$email || strlen($password) < 8) {
        $error = "Invalid email or password.";
    } else {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT id, password, failed_attempts, last_failed_attempt FROM users WHERE email = ?");
        if (!$stmt) {
            die("Database error: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        var_dump(password_hash($password, PASSWORD_BCRYPT));
        var_dump($user['password']);

        if ($user) {
            // Check for account lockout
            $lastAttempt = $user['last_failed_attempt'] ? (new DateTime($user['last_failed_attempt']))->getTimestamp() : 0;
            $lockoutDuration = (new DateTime())->sub(new DateInterval('PT15M'))->getTimestamp();
            
            if ($user['failed_attempts'] >= 5 && $lastAttempt > $lockoutDuration) {
                $error = "Account is locked. Please try again later.";
            } elseif (password_verify($password, $user['password'])) {
                // Reset failed attempts on successful login
                $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0 WHERE id = ?");
                if (!$stmt) {
                    die("Database error: " . htmlspecialchars($conn->error));
                }
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();

                // Create session
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                // Increment failed attempts
                $stmt = $conn->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE id = ?");
                if (!$stmt) {
                    die("Database error: " . htmlspecialchars($conn->error));
                }
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}

// Logout functionality
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required minlength="8">
        
        <button type="submit">Login</button>
    </form>
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
</body>
</html>
