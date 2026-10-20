<?php
// Image upload helpers shared by the admin pages (projects, team, testimonials).

const MAX_UPLOAD_BYTES = 5 * 1024 * 1024;
const ALLOWED_IMAGE_TYPES = [
    "image/jpeg" => "jpg",
    "image/png"  => "png",
    "image/gif"  => "gif",
    "image/webp" => "webp",
];

// Validate and store an uploaded image under a random name in /uploads.
// Returns the stored file name, or null when nothing was saved; $error is
// set when the upload was invalid. With $required = false a missing file
// is not an error.
function save_uploaded_image($file, &$error, $required = true)
{
    $error = "";
    if (!$file || $file["error"] === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            $error = "Please choose an image to upload (max 5 MB).";
        }
        return null;
    }
    if ($file["error"] !== UPLOAD_ERR_OK || $file["size"] > MAX_UPLOAD_BYTES) {
        $error = "The image could not be uploaded. Images must be 5 MB or smaller.";
        return null;
    }

    // Trust the file contents, not the browser-supplied name or type.
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file["tmp_name"]);
    if (!isset(ALLOWED_IMAGE_TYPES[$mime]) || getimagesize($file["tmp_name"]) === false) {
        $error = "Only JPG, PNG, GIF or WEBP images are allowed.";
        return null;
    }

    // Random server-side name so uploads can't overwrite files or carry a script extension.
    $name = bin2hex(random_bytes(16)) . "." . ALLOWED_IMAGE_TYPES[$mime];
    if (!move_uploaded_file($file["tmp_name"], UPLOAD_DIR . "/" . $name)) {
        $error = "Could not save the image. Check that the uploads folder is writable.";
        return null;
    }
    return $name;
}

// Remove an uploaded image file, if it exists.
function delete_uploaded_image($name)
{
    $path = UPLOAD_DIR . "/" . basename((string) $name);
    if ($name && is_file($path)) {
        unlink($path);
    }
}

// Return a cleaned http(s) URL, "" when empty, or false when invalid.
// Rejects javascript: and other schemes so links are safe to output.
function clean_url($url)
{
    $url = trim((string) $url);
    if ($url === "") {
        return "";
    }
    if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $url)) {
        return false;
    }
    return $url;
}
