<?php
// Shared database connection and helpers.
// Credentials live in config.local.php (not committed) - copy
// config.sample.php to config.local.php and fill in the values.

// Never show PHP error details to visitors; log them instead. Errors are
// still displayed when browsing from this machine, for local development.
$isLocal = PHP_SAPI === "cli" || in_array($_SERVER["REMOTE_ADDR"] ?? "", ["127.0.0.1", "::1"], true);
ini_set("display_errors", $isLocal ? "1" : "0");
ini_set("log_errors", "1");
error_reporting(E_ALL);

$localConfig = __DIR__ . "/config.local.php";
if (!is_file($localConfig)) {
    http_response_code(500);
    exit("Missing config.local.php - copy config.sample.php and set the database credentials.");
}
require_once $localConfig;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    mysqli_set_charset($conn, "utf8mb4");
} catch (mysqli_sql_exception $ex) {
    error_log("Database connection failed: " . $ex->getMessage());
    http_response_code(500);
    exit("Database connection failed.");
}

define("UPLOAD_DIR", __DIR__ . "/uploads");

// Escape a value for HTML output.
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

// Read a company setting (contact details etc.) from site_settings.
// Edited in admin/contact-info.php. Returns $default when empty or missing.
function setting($key, $default = "")
{
    global $conn;
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        $result = mysqli_query($conn, "SELECT setting_key, setting_value FROM site_settings");
        while ($row = mysqli_fetch_assoc($result)) {
            $settings[$row["setting_key"]] = $row["setting_value"];
        }
    }
    return isset($settings[$key]) && $settings[$key] !== "" ? $settings[$key] : $default;
}

// Public URL of an uploaded image, or the placeholder when there is none.
function upload_url($fileName, $placeholder = "img/avatar-placeholder.svg")
{
    return $fileName ? "uploads/" . rawurlencode($fileName) : $placeholder;
}

// Inbox that receives newsletter signup notifications.
define("COMPANY_EMAIL", setting("company_email", "ajacentconsultant@gmail.com"));
