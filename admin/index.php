<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/upload.php";

$pageTitle = "Projects";
$message = "";
$messageType = "success";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    csrf_check();
    $id = (int) $_POST["delete_id"];

    $stmt = mysqli_prepare($conn, "SELECT image FROM projects WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $project = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($project) {
        $stmt = mysqli_prepare($conn, "DELETE FROM projects WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        // Remove the image file unless another project still uses it.
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM projects WHERE image = ?");
        mysqli_stmt_bind_param($stmt, "s", $project["image"]);
        mysqli_stmt_execute($stmt);
        if (mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0] == 0) {
            delete_uploaded_image($project["image"]);
        }
    }
    header("Location: index.php?msg=deleted");
    exit;
}

$notices = ["deleted" => "Project deleted.", "added" => "Project uploaded successfully."];
if (isset($notices[$_GET["msg"] ?? ""])) {
    $message = $notices[$_GET["msg"]];
}

$projects = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM projects ORDER BY id DESC"), MYSQLI_ASSOC);

include __DIR__ . "/layout-top.php";
?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Projects (<?php echo count($projects); ?>)</h4>
        <div class="card-header-action">
          <a href="admin.php" class="btn btn-primary">Add Project</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <tr><th>ID</th><th>Image</th><th>Description</th><th>Date</th><th>Action</th></tr>
            <?php if (!$projects) { ?>
              <tr><td colspan="5" class="text-center">No projects yet.</td></tr>
            <?php } ?>
            <?php foreach ($projects as $project) { ?>
            <tr>
              <td><?php echo (int) $project["id"]; ?></td>
              <td><a href="../<?php echo e(upload_url($project["image"])); ?>" target="_blank"><img class="admin-thumb" src="../<?php echo e(upload_url($project["image"])); ?>" alt=""></a></td>
              <td><?php echo e($project["description"]); ?></td>
              <td><?php echo e($project["date"]); ?></td>
              <td>
                <form method="post" action="index.php" onsubmit="return confirm('Are you sure you want to delete this project?')">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="delete_id" value="<?php echo (int) $project["id"]; ?>">
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . "/layout-bottom.php"; ?>
