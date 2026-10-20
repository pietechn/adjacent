<?php
// Newsletter signup from the site footer. Saves the email and notifies the
// company inbox. Responds with JSON for the footer's fetch() call.

require_once __DIR__ . "/config.php";
header("Content-Type: application/json; charset=UTF-8");

function respond($ok, $message, $status = 200)
{
    http_response_code($status);
    echo json_encode(["ok" => $ok, "message" => $message]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    respond(false, "Method not allowed.", 405);
}

// Hidden "website" field: real visitors leave it empty, spam bots fill it in.
if (!empty($_POST["website"])) {
    respond(true, "Thank you for subscribing!");
}

$email = trim((string) ($_POST["email"] ?? ""));
if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, "Please enter a valid email address.", 422);
}

$stmt = mysqli_prepare($conn, "INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
if (mysqli_stmt_affected_rows($stmt) === 0) {
    respond(true, "You are already subscribed. Thank you!");
}
$id = mysqli_insert_id($conn);

// Notify the company. FILTER_VALIDATE_EMAIL rejects line breaks, so the
// address is safe to use in the Reply-To header.
$host = preg_replace('/[^a-z0-9.-]/i', "", explode(":", $_SERVER["HTTP_HOST"] ?? "localhost")[0]);
$subject = "New newsletter subscriber: " . $email;
$body = "A visitor subscribed to the newsletter on the Adjacent website.\r\n\r\n"
    . "Email: " . $email . "\r\n"
    . "Date: " . date("Y-m-d H:i:s") . "\r\n\r\n"
    . "All subscribers: Admin dashboard > Subscribers\r\n";
$headers = "From: Adjacent Website <noreply@" . $host . ">\r\n"
    . "Reply-To: " . $email . "\r\n"
    . "Content-Type: text/plain; charset=UTF-8";

if (@mail(COMPANY_EMAIL, $subject, $body, $headers)) {
    $stmt = mysqli_prepare($conn, "UPDATE newsletter_subscribers SET notified = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
} else {
    error_log("Newsletter: notification email to " . COMPANY_EMAIL . " failed for " . $email);
}

respond(true, "Thank you for subscribing!");
