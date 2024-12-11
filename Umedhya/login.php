<?php
// Start session
session_start();

// Database connection
$server = "localhost";
$database = "share_ride_web";
$username = "root";
$password = "";

$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email || strlen($password) < 8) {
        $error = "Invalid email or password.";
    } else {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT id, password, failed_attempts, last_failed_attempt FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Check for account lockout
            if ($user['failed_attempts'] >= 5 && strtotime($user['last_failed_attempt']) > strtotime('-15 minutes')) {
                $error = "Account is locked. Please try again later.";
            } elseif (password_verify($password, $user['password'])) {
                // Reset failed attempts
                $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0 WHERE id = ?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();

                // Create session
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                // Increment failed attempts
                $stmt = $conn->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE id = ?");
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
<form method="POST" action="login.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required minlength="8">
    
    <button type="submit">Login</button>
</form>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
