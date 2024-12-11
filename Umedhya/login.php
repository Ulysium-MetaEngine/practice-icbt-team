<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db.php";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no errors, check credentials
    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, name, password FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $name, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a session
                            $_SESSION["user_id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["email"] = $email;

                            // Redirect to a secure area (e.g., dashboard)
                            header("location: dashboard.php");
                            exit;
                        } else {
                            $login_err = "Invalid password.";
                        }
                    }
                } else {
                    $login_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($login_err)) echo '<p style="color: red;">' . $login_err . '</p>'; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <p style="color: red;"><?php echo $email_err; ?></p>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <p style="color: red;"><?php echo $password_err; ?></p>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
