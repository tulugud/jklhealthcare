<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer via Composer

$mail = new PHPMailer(true);

session_start();
$error = '';

function loadEnv($file) {
    if (!file_exists($file)) {
        return;
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignore comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Split by '=' sign
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            putenv(trim($parts[0]) . '=' . trim($parts[1]));
        }
    }
}

// Load the .env file
loadEnv(__DIR__ . '/.env');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // Database connection
    $conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Input validation
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $input = trim($_POST['email']);
    $column = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
    // Fetch email and validate login
    $stmt = $conn->prepare("SELECT email, password FROM users WHERE $column = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            $email = $user['email'];
    
            // Generate OTP
            $otp = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+2 minutes"));
    
            // Save OTP to database
            $updateOtp = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
            $updateOtp->bind_param("sss", $otp, $expiry, $email);
            $updateOtp->execute();
            $message = "<html>
<head>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f7f8;
      color: #333;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #007BFF;
      text-align: center;
    }
    p {
      line-height: 1.6;
    }
    .otp {
      font-size: 2.5em;
      color: #007BFF;
      text-align: center;
      margin: 20px 0;
      background: #e9f1fe;
      padding: 10px;
      border-radius: 6px;
    }
    .footer {
      margin-top: 20px;
      text-align: center;
      color: #666;
    }
  </style>
</head>
<body>
  <div class='container'>
    <h1>Your OTP Code</h1>
    <div class='otp'>$otp</div>
    <p>Please enter this code on the website to proceed with your action.</p>
    <p><strong>Note:</strong> The code is valid for 2 minutes.</p>
    <div class='footer'>
      <p>If you didn't request this OTP, please ignore this email.</p>
    </div>
  </div>
</body>
</html>";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
$headers .= "From: no-reply@example.com" . "\r\n";

// try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'manojkumarhook@gmail.com'; // Your email
    $mail->Password = 'cvtxmwvjcjhmqavj';   // Your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
    $mail->Port = 587; // Port for TLS

    // Email Content
    $mail->isHTML(true); 
    $mail->setFrom('manojkumarhook@gmail.com', 'Manoj');
    $mail->addAddress($email); // Recipient's email
    $mail->Subject = 'Your OTP Code';
    $mail->Body = $message;

    $mail->send();
//     echo "Email sent successfully!";
// } catch (Exception $e) {
//     echo "Email could not be sent. Error: {$mail->ErrorInfo}";
// }
    
//             // Send OTP via email
//             mail($email, "Your OTP Code",$message,$headers );
    
            // Redirect to OTP verification page
            $_SESSION['otp_email'] = $email;
            header("Location: verify_otp.php");
            exit;
        } else {
            $error = "Invalid Username or password.";
        }
    } else {
        $error = "Invalid Username or password.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .login-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #4caf50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 94%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            background: #f9f9f9;
            color: #333;
            outline: none;
            transition: border 0.3s ease;
        }

        .form-group input::placeholder {
            color: #aaa;
        }

        .form-group input:focus {
            border-color: #4caf50;
        }

        .error {
            color: #e57373;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        .btn-submit {
            width: 100%;
            background: #4caf50;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background: #43a047;
        }

        .link {
            margin-top: 15px;
            text-align: center;
        }

        .link a {
            color: #2196f3;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .link a:hover {
            color: #0d47a1;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Log In</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <input type="text" name="email" placeholder="Enter Username or Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn-submit">Log In</button>
        </form>
        <div class="link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
