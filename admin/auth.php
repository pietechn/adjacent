<?php
// Included at the top of every admin page. Starts the session, and unless
// the page defines AUTH_PUBLIC, sends visitors who are not logged in to login.php.

require_once __DIR__ . "/../config.php";

session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
    "httponly" => true,
    "samesite" => "Strict",
]);
session_start();

// Log out after 30 minutes of inactivity.
if (!empty($_SESSION["admin_id"]) && time() - ($_SESSION["last_seen"] ?? 0) > 1800) {
    $_SESSION = [];
    session_regenerate_id(true);
}
$_SESSION["last_seen"] = time();

if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

if (!defined("AUTH_PUBLIC") && empty($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Hidden form field carrying the CSRF token.
function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . e($_SESSION["csrf"]) . '">';
}

// Stop the request unless the posted CSRF token matches the session.
function csrf_check()
{
    if (!hash_equals($_SESSION["csrf"], (string) ($_POST["csrf"] ?? ""))) {
        http_response_code(400);
        exit("Invalid or expired form. Go back, reload the page and try again.");
    }
}
