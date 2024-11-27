<?php
session_start();
function loadEnv($file) {
    if (!file_exists($file)) {
        return;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            putenv(trim($parts[0]) . '=' . trim($parts[1]));
        }
    }
}

$error_message = '';

// Load the .env file
loadEnv(__DIR__ . '/.env');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['otp_email'])) {
    $email = $_SESSION['otp_email'];
    $otp = trim($_POST['otp']);

    $conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate OTP
    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['otp'] === $otp && strtotime($user['otp_expiry']) > time()) {
        // Clear OTP from database
        $clearOtp = $conn->prepare("UPDATE users SET otp = NULL, otp_expiry = NULL WHERE email = ?");
        $clearOtp->bind_param("s", $email);
        $clearOtp->execute();
        $_SESSION['user_email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid or expired OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4ff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .otp-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .otp-container h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }
        .otp-container .error-message {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .otp-container input[type="text"] {
            width: 94%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .otp-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .otp-container button:hover {
            background-color: #45a049;
        }
        .otp-container p {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Verify OTP</h2>
        <form method="POST" action="">
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
