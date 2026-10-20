<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/upload.php";

$pageTitle = "Testimonials";
$message = "";
$messageType = "danger";

function find_testimonial($conn, $id)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM testimonials WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    $action = $_POST["action"] ?? "";

    if ($action === "delete") {
        $testimonial = find_testimonial($conn, (int) ($_POST["id"] ?? 0));
        if ($testimonial) {
            $stmt = mysqli_prepare($conn, "DELETE FROM testimonials WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $testimonial["id"]);
            mysqli_stmt_execute($stmt);
            delete_uploaded_image($testimonial["photo"]);
        }
        header("Location: testimonials.php?msg=deleted");
        exit;
    }

    if ($action === "toggle") {
        $stmt = mysqli_prepare($conn, "UPDATE testimonials SET is_published = 1 - is_published WHERE id = ?");
        $id = (int) ($_POST["id"] ?? 0);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: testimonials.php?msg=updated");
        exit;
    }

    if ($action === "save") {
        $id = (int) ($_POST["id"] ?? 0);
        $existing = $id ? find_testimonial($conn, $id) : null;
        $clientName = trim((string) ($_POST["client_name"] ?? ""));
        $clientTitle = trim((string) ($_POST["client_title"] ?? ""));
        $text = trim((string) ($_POST["message"] ?? ""));
        $rating = min(5, max(1, (int) ($_POST["rating"] ?? 5)));
        $published = isset($_POST["is_published"]) ? 1 : 0;

        if ($id && !$existing) {
            $message = "That testimonial no longer exists.";
        } elseif ($clientName === "" || $text === "") {
            $message = "Please enter the client's name and their testimonial.";
        } else {
            $photo = save_uploaded_image($_FILES["photo"] ?? null, $uploadError, false);
            if ($uploadError) {
                $message = $uploadError;
            } else {
                if ($existing) {
                    $newPhoto = $photo ?? $existing["photo"];
                    $stmt = mysqli_prepare($conn, "UPDATE testimonials SET client_name = ?, client_title = ?, message = ?, rating = ?, photo = ?, is_published = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt, "sssisii", $clientName, $clientTitle, $text, $rating, $newPhoto, $published, $id);
                    mysqli_stmt_execute($stmt);
                    if ($photo) {
                        delete_uploaded_image($existing["photo"]);
                    }
                    header("Location: testimonials.php?msg=updated");
                } else {
                    $stmt = mysqli_prepare($conn, "INSERT INTO testimonials (client_name, client_title, message, rating, photo, is_published) VALUES (?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "sssisi", $clientName, $clientTitle, $text, $rating, $photo, $published);
                    mysqli_stmt_execute($stmt);
                    header("Location: testimonials.php?msg=added");
                }
                exit;
            }
        }
    }
}

$notices = ["added" => "Testimonial added.", "updated" => "Testimonial updated.", "deleted" => "Testimonial deleted."];
if ($message === "" && isset($notices[$_GET["msg"] ?? ""])) {
    $message = $notices[$_GET["msg"]];
    $messageType = "success";
}

// Form values: re-show what was posted after an error, otherwise the testimonial being edited.
$editing = isset($_GET["edit"]) ? find_testimonial($conn, (int) $_GET["edit"]) : null;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $form = $_POST;
    $form["is_published"] = isset($_POST["is_published"]) ? 1 : 0;
} else {
    $form = $editing ?: ["rating" => 5, "is_published" => 1];
}
$formId = (int) ($form["id"] ?? 0);
$testimonials = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM testimonials ORDER BY created_at DESC"), MYSQLI_ASSOC);

include __DIR__ . "/layout-top.php";
?>
<div class="row">
  <div class="col-12 col-lg-5">
    <div class="card">
      <div class="card-header"><h4><?php echo $formId ? "Edit Testimonial" : "Add Testimonial"; ?></h4></div>
      <div class="card-body">
        <form method="post" action="testimonials.php<?php echo $formId ? "?edit=" . $formId : ""; ?>" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="save">
          <input type="hidden" name="id" value="<?php echo $formId; ?>">
          <div class="form-group">
            <label for="client_name">Client name *</label>
            <input id="client_name" type="text" class="form-control" name="client_name" maxlength="100" value="<?php echo e($form["client_name"] ?? ""); ?>" required>
          </div>
          <div class="form-group">
            <label for="client_title">Client position / company</label>
            <input id="client_title" type="text" class="form-control" name="client_title" maxlength="150" placeholder="e.g. Project Manager, ABC Telecoms" value="<?php echo e($form["client_title"] ?? ""); ?>">
          </div>
          <div class="form-group">
            <label for="message">Testimonial *</label>
            <textarea id="message" class="form-control" name="message" style="height:120px" required><?php echo e($form["message"] ?? ""); ?></textarea>
          </div>
          <div class="form-group">
            <label for="rating">Rating</label>
            <select id="rating" name="rating" class="form-control">
              <?php for ($r = 5; $r >= 1; $r--) { ?>
                <option value="<?php echo $r; ?>"<?php echo (int) ($form["rating"] ?? 5) === $r ? " selected" : ""; ?>><?php echo $r; ?> star<?php echo $r > 1 ? "s" : ""; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="photo">Client photo (optional<?php echo $formId ? ", leave empty to keep the current one" : ""; ?>)</label>
            <?php if (!empty($editing["photo"])) { ?>
              <div class="mb-2"><img class="admin-thumb" src="../<?php echo e(upload_url($editing["photo"])); ?>" alt=""></div>
            <?php } ?>
            <input id="photo" type="file" class="form-control" name="photo" accept="image/jpeg,image/png,image/gif,image/webp">
          </div>
          <div class="form-group">
            <label><input type="checkbox" name="is_published" value="1"<?php echo !empty($form["is_published"]) ? " checked" : ""; ?>> Show on the website</label>
          </div>
          <button type="submit" class="btn btn-primary"><?php echo $formId ? "Save Changes" : "Add Testimonial"; ?></button>
          <?php if ($formId) { ?><a href="testimonials.php" class="btn btn-light">Cancel</a><?php } ?>
        </form>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-header"><h4>Testimonials (<?php echo count($testimonials); ?>)</h4></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <tr><th>Client</th><th>Testimonial</th><th>Rating</th><th>Status</th><th>Actions</th></tr>
            <?php if (!$testimonials) { ?>
              <tr><td colspan="5" class="text-center">No testimonials yet. Add the first one with the form.</td></tr>
            <?php } ?>
            <?php foreach ($testimonials as $testimonial) { ?>
            <tr>
              <td>
                <strong><?php echo e($testimonial["client_name"]); ?></strong><br>
                <small><?php echo e($testimonial["client_title"]); ?></small>
              </td>
              <td><?php echo e(mb_strimwidth($testimonial["message"], 0, 90, "...")); ?></td>
              <td><?php echo (int) $testimonial["rating"]; ?>/5</td>
              <td><?php echo $testimonial["is_published"] ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-secondary">Hidden</span>'; ?></td>
              <td>
                <a href="testimonials.php?edit=<?php echo (int) $testimonial["id"]; ?>" class="btn btn-sm btn-primary">Edit</a>
                <form method="post" action="testimonials.php">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="toggle">
                  <input type="hidden" name="id" value="<?php echo (int) $testimonial["id"]; ?>">
                  <button type="submit" class="btn btn-sm btn-light"><?php echo $testimonial["is_published"] ? "Hide" : "Publish"; ?></button>
                </form>
                <form method="post" action="testimonials.php" onsubmit="return confirm('Delete this testimonial?')">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo (int) $testimonial["id"]; ?>">
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
