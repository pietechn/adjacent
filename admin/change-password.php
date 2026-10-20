<?php
require_once __DIR__ . "/auth.php";

$pageTitle = "Change Password";
$message = "";
$messageType = "danger";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    $current = (string) ($_POST["current_password"] ?? "");
    $new = (string) ($_POST["new_password"] ?? "");
    $confirm = (string) ($_POST["confirm_password"] ?? "");

    $stmt = mysqli_prepare($conn, "SELECT password_hash FROM admins WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["admin_id"]);
    mysqli_stmt_execute($stmt);
    $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$admin || !password_verify($current, $admin["password_hash"])) {
        sleep(1);
        $message = "Your current password is incorrect.";
    } elseif (strlen($new) < 10) {
        $message = "The new password must be at least 10 characters.";
    } elseif ($new !== $confirm) {
        $message = "The new passwords do not match.";
    } elseif ($new === $current) {
        $message = "The new password must be different from the current one.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE admins SET password_hash = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $hash, $_SESSION["admin_id"]);
        mysqli_stmt_execute($stmt);

        session_regenerate_id(true);
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
        $message = "Password changed successfully.";
        $messageType = "success";
    }
}
?>
<?php include __DIR__ . "/layout-top.php"; ?>
          <div class="row">
            <div class="col-12 col-md-8 col-lg-6">
              <div class="card">
                <div class="card-header"><h4>Change Password</h4></div>
                <div class="card-body">
                  <form method="post" action="change-password.php">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                      <label for="current_password">Current password</label>
                      <input id="current_password" type="password" class="form-control" name="current_password" autocomplete="current-password" required>
                    </div>
                    <div class="form-group">
                      <label for="new_password">New password <small>(at least 10 characters)</small></label>
                      <input id="new_password" type="password" class="form-control" name="new_password" minlength="10" autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                      <label for="confirm_password">Confirm new password</label>
                      <input id="confirm_password" type="password" class="form-control" name="confirm_password" minlength="10" autocomplete="new-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
<?php include __DIR__ . "/layout-bottom.php"; ?>
