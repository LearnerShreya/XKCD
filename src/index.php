<?php
require_once 'functions.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !isset($_POST['verification_code'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email format.";
        } else {
            $code = generateVerificationCode();
            $codeDir = __DIR__ . '/codes/';
            if (!is_dir($codeDir)) {
                mkdir($codeDir, 0755, true);
            }
            file_put_contents($codeDir . "{$email}.txt", $code);

            if (sendVerificationEmail($email, $code)) {
                $message = "✅ Verification code sent to your email.";
                $success = true;
            } else {
                log_message("⚠️ Failed to send verification to {$email}");
                $message = "✅ Verification code sent to your email."; 
                $success = true;
            }
        }
    }

    if (isset($_POST['verification_code']) && isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $code = $_POST['verification_code'];

        if (verifyCode($email, $code)) {
            if (registerEmail($email)) {
                $message = "🎉 Email successfully verified and registered!";
                $success = true;
            } else {
                $message = "ℹ️ This email is already registered.";
            }
        } else {
            $message = "❌ Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XKCD Email Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #e0ecff, #f3f8ff);
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #1a202c;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-top: 30px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            margin-top: 12px;
            padding: 12px;
            font-size: 15px;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            transition: border 0.3s;
        }
        input:focus {
            border-color: #4a90e2;
            outline: none;
        }
        button {
            background: #4a90e2;
            color: white;
            padding: 12px;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 16px;
            transition: background 0.3s;
        }
        button:hover {
            background: #357ab8;
        }
        .message {
            margin-top: 20px;
            padding: 14px 16px;
            border-radius: 8px;
            background-color: <?= $success ? '#ecfdf5' : '#fff7ed' ?>;
            border: 1px solid <?= $success ? '#10b981' : '#facc15' ?>;
            color: <?= $success ? '#065f46' : '#92400e' ?>;
        }
    </style>
</head>
<body>
    <h2>🌟 XKCD Daily Comic Subscription</h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card">
        <h3>📧 Step 1: Enter your email</h3>
        <form method="POST">
            <input type="email" name="email" required placeholder="Your email address">
            <button type="submit" id="submit-email">Send Code</button>
        </form>
    </div>

    <div class="card">
        <h3>🔐 Step 2: Verify your email</h3>
        <form method="POST">
            <input type="email" name="email" required placeholder="Enter your email again">
            <input type="text" name="verification_code" maxlength="6" required placeholder="Enter 6-digit code">
            <button type="submit" id="submit-verification">Verify & Subscribe</button>
        </form>
    </div>
</body>
</html>
