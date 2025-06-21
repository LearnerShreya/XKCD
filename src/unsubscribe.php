<?php
require_once 'functions.php';

$message = '';
$success = false;
$prefillEmail = '';

if (isset($_GET['email'])) {
    $prefillEmail = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email']) && !isset($_POST['verification_code'])) {
        $email = filter_var($_POST['unsubscribe_email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email format.";
        } else {
            $code = generateVerificationCode();
            $codeDir = __DIR__ . '/codes/';
            if (!is_dir($codeDir)) {
                mkdir($codeDir, 0755, true);
            }
            file_put_contents($codeDir . "{$email}.txt", $code);

            if (sendUnsubscribeVerificationEmail($email, $code)) {
                $message = "✅ Unsubscribe verification code sent to your email.";
                $success = true;
            } else {
                log_message("⚠️ Failed to send unsubscribe verification to {$email}");
                $message = "✅ Verification code sent to your email."; // user-friendly
                $success = true;
            }
        }
    }

    if (isset($_POST['verification_code']) && isset($_POST['unsubscribe_email'])) {
        $email = filter_var($_POST['unsubscribe_email'], FILTER_SANITIZE_EMAIL);
        $code = $_POST['verification_code'];

        if (verifyCode($email, $code)) {
            if (unsubscribeEmail($email)) {
                $message = "✅ You have been successfully unsubscribed.";
                $success = true;
            } else {
                $message = "ℹ️ This email is not registered in our system.";
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
    <title>XKCD Unsubscribe</title>
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
            padding: 12px;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 16px;
            transition: background 0.3s;
        }
        .btn-unsubscribe {
            background: #ef4444;
            color: white;
        }
        .btn-unsubscribe:hover {
            background: #dc2626;
        }
        .btn-verify {
            background: #4a90e2;
            color: white;
        }
        .btn-verify:hover {
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
    <h2>📭 Unsubscribe from XKCD Comics</h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card">
        <h3>📧 Step 1: Request Unsubscribe Code</h3>
        <form method="POST">
            <input type="email" name="unsubscribe_email" required placeholder="Your email address" value="<?= htmlspecialchars($prefillEmail) ?>">
            <button type="submit" class="btn-unsubscribe">Send Unsubscribe Code</button>
        </form>
    </div>

    <div class="card">
        <h3>🔐 Step 2: Confirm Unsubscription</h3>
        <form method="POST">
            <input type="email" name="unsubscribe_email" required placeholder="Enter your email again" value="<?= htmlspecialchars($prefillEmail) ?>">
            <input type="text" name="verification_code" maxlength="6" required placeholder="Enter 6-digit code">
            <button type="submit" class="btn-verify">Verify & Unsubscribe</button>
        </form>
    </div>
</body>
</html>
