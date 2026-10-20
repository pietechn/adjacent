<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/upload.php";

$pageTitle = "Add Project";
$message = "";
$messageType = "danger";
$desc = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["upload"])) {
    csrf_check();
    $desc = trim((string) ($_POST["desc"] ?? ""));

    if ($desc === "") {
        $message = "Please enter a description.";
    } else {
        $image = save_uploaded_image($_FILES["image"] ?? null, $uploadError);
        if ($uploadError) {
            $message = $uploadError;
        } else {
            $date = date("Y-m-d");
            $stmt = mysqli_prepare($conn, "INSERT INTO projects (image, description, date) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $image, $desc, $date);
            mysqli_stmt_execute($stmt);
            header("Location: index.php?msg=added");
            exit;
        }
    }
}

include __DIR__ . "/layout-top.php";
?>
<div class="row">
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-header"><h4>Upload a Project</h4></div>
      <div class="card-body">
        <form method="post" action="admin.php" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <div class="form-group">
            <label for="image">Project image *</label>
            <input id="image" type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
            <small class="form-text text-muted">JPG, PNG, GIF or WEBP, max 5 MB.</small>
          </div>
          <div class="form-group">
            <label for="desc">Description *</label>
            <textarea id="desc" class="form-control" name="desc" style="height:100px" required><?php echo e($desc); ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary" name="upload" value="1">Upload Project</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . "/layout-bottom.php"; ?>
