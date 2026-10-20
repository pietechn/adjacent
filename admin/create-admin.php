<?php
// Command-line tool to create an admin account or reset its password:
//   php admin/create-admin.php <username> <password>

if (PHP_SAPI !== "cli") {
    http_response_code(404);
    exit;
}

require_once __DIR__ . "/../config.php";

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php admin/create-admin.php <username> <password>\n");
    exit(1);
}

[, $username, $password] = $argv;
if (strlen($password) < 10) {
    fwrite(STDERR, "Password must be at least 10 characters.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO admins (username, password_hash) VALUES (?, ?)
     ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)"
);
mysqli_stmt_bind_param($stmt, "ss", $username, $hash);
mysqli_stmt_execute($stmt);

echo "Admin '$username' saved.\n";
