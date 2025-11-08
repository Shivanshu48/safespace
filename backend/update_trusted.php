<?php
include "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    echo "❌ Please log in first.";
    exit();
}

$trusted_email = trim($_POST['trusted_email'] ?? '');
if (empty($trusted_email)) {
    echo "⚠️ Enter a valid email.";
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("UPDATE users SET trusted_email=? WHERE id=?");
$stmt->bind_param("si", $trusted_email, $user_id);

if ($stmt->execute()) {
    echo "✅ Trusted contact saved successfully!";
} else {
    echo "❌ Database error: " . $stmt->error;
}
?>
