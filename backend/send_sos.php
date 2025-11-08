<?php
include "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    echo "Please log in.";
    exit();
}

$uid = $_SESSION['user_id'];
$res = $conn->query("SELECT name, trusted_email FROM users WHERE id=$uid");
$user = $res->fetch_assoc();

if (empty($user['trusted_email'])) {
    echo "No trusted contact set.";
    exit();
}

// Example send (real implementation later with PHPMailer)
$to = $user['trusted_email'];
$subject = "🚨 SOS Alert from {$user['name']}";
$message = "{$user['name']} just triggered an SOS alert via SafeSpace. Please check on them immediately.";
$headers = "From: safespace.alerts@gmail.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ SOS email sent to {$to}";
} else {
    echo "❌ Failed to send email.";
}
?>
