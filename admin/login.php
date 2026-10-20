<?php
define("AUTH_PUBLIC", true);
require_once __DIR__ . "/auth.php";

if (!empty($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit;
}

$error = "";
$attempts = $_SESSION["login_attempts"] ?? 0;
$lockedUntil = $_SESSION["login_locked_until"] ?? 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();

    if (time() < $lockedUntil) {
        $error = "Too many failed attempts. Try again in a few minutes.";
    } else {
        $username = trim((string) ($_POST["username"] ?? ""));
        $password = (string) ($_POST["password"] ?? "");

        $stmt = mysqli_prepare($conn, "SELECT id, password_hash FROM admins WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($admin && password_verify($password, $admin["password_hash"])) {
            session_regenerate_id(true);
            $_SESSION["admin_id"] = (int) $admin["id"];
            $_SESSION["admin_username"] = $username;
            $_SESSION["csrf"] = bin2hex(random_bytes(32));
            unset($_SESSION["login_attempts"], $_SESSION["login_locked_until"]);
            header("Location: index.php");
            exit;
        }

        $attempts++;
        $_SESSION["login_attempts"] = $attempts;
        if ($attempts >= 5) {
            $_SESSION["login_locked_until"] = time() + 300;
            $_SESSION["login_attempts"] = 0;
        }
        sleep(1);
        $error = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Adjacent - Admin Login</title>
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
            <div class="card card-primary">
              <div class="card-header"><h4>Adjacent Admin Login</h4></div>
              <div class="card-body">
                <?php if ($error) { ?>
                  <div class="alert alert-danger"><?php echo e($error); ?></div>
                <?php } ?>
                <form method="post" action="login.php">
                  <?php echo csrf_field(); ?>
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" required autofocus>
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>
</html>
