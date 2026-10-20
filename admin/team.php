<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/upload.php";

$pageTitle = "Team Members";
$message = "";
$messageType = "danger";
$socialFields = ["facebook" => "Facebook", "twitter" => "Twitter / X", "instagram" => "Instagram", "linkedin" => "LinkedIn"];

function find_member($conn, $id)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM team_members WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    $action = $_POST["action"] ?? "";

    if ($action === "delete") {
        $member = find_member($conn, (int) ($_POST["id"] ?? 0));
        if ($member) {
            $stmt = mysqli_prepare($conn, "DELETE FROM team_members WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $member["id"]);
            mysqli_stmt_execute($stmt);
            delete_uploaded_image($member["photo"]);
        }
        header("Location: team.php?msg=deleted");
        exit;
    }

    if ($action === "save") {
        $id = (int) ($_POST["id"] ?? 0);
        $existing = $id ? find_member($conn, $id) : null;
        $name = trim((string) ($_POST["name"] ?? ""));
        $position = trim((string) ($_POST["position"] ?? ""));
        $sortOrder = (int) ($_POST["sort_order"] ?? 0);
        $links = [];
        foreach ($socialFields as $field => $label) {
            $links[$field] = clean_url($_POST[$field] ?? "");
            if ($links[$field] === false) {
                $message = "The $label link must be a full web address starting with http:// or https://.";
            }
        }

        if ($id && !$existing) {
            $message = "That team member no longer exists.";
        } elseif ($name === "" || $position === "") {
            $message = "Please enter the name and position.";
        }

        if ($message === "") {
            $photo = save_uploaded_image($_FILES["photo"] ?? null, $uploadError, false);
            if ($uploadError) {
                $message = $uploadError;
            } else {
                if ($existing) {
                    $newPhoto = $photo ?? $existing["photo"];
                    $stmt = mysqli_prepare($conn, "UPDATE team_members SET name = ?, position = ?, photo = ?, facebook = ?, twitter = ?, instagram = ?, linkedin = ?, sort_order = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt, "sssssssii", $name, $position, $newPhoto, $links["facebook"], $links["twitter"], $links["instagram"], $links["linkedin"], $sortOrder, $id);
                    mysqli_stmt_execute($stmt);
                    if ($photo) {
                        delete_uploaded_image($existing["photo"]);
                    }
                    header("Location: team.php?msg=updated");
                } else {
                    $stmt = mysqli_prepare($conn, "INSERT INTO team_members (name, position, photo, facebook, twitter, instagram, linkedin, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "sssssssi", $name, $position, $photo, $links["facebook"], $links["twitter"], $links["instagram"], $links["linkedin"], $sortOrder);
                    mysqli_stmt_execute($stmt);
                    header("Location: team.php?msg=added");
                }
                exit;
            }
        }
    }
}

$notices = ["added" => "Team member added.", "updated" => "Team member updated.", "deleted" => "Team member deleted."];
if ($message === "" && isset($notices[$_GET["msg"] ?? ""])) {
    $message = $notices[$_GET["msg"]];
    $messageType = "success";
}

// Form values: re-show what was posted after an error, otherwise the member being edited.
$editing = isset($_GET["edit"]) ? find_member($conn, (int) $_GET["edit"]) : null;
$form = $_SERVER["REQUEST_METHOD"] === "POST" ? $_POST : ($editing ?: []);
$formId = (int) ($form["id"] ?? 0);
$members = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM team_members ORDER BY sort_order, id"), MYSQLI_ASSOC);

include __DIR__ . "/layout-top.php";
?>
<div class="row">
  <div class="col-12 col-lg-5">
    <div class="card">
      <div class="card-header"><h4><?php echo $formId ? "Edit Team Member" : "Add Team Member"; ?></h4></div>
      <div class="card-body">
        <form method="post" action="team.php<?php echo $formId ? "?edit=" . $formId : ""; ?>" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="save">
          <input type="hidden" name="id" value="<?php echo $formId; ?>">
          <div class="form-group">
            <label for="name">Full name *</label>
            <input id="name" type="text" class="form-control" name="name" maxlength="100" value="<?php echo e($form["name"] ?? ""); ?>" required>
          </div>
          <div class="form-group">
            <label for="position">Position *</label>
            <input id="position" type="text" class="form-control" name="position" maxlength="100" placeholder="e.g. Site Engineer" value="<?php echo e($form["position"] ?? ""); ?>" required>
          </div>
          <div class="form-group">
            <label for="photo">Photo <?php echo $formId ? "(leave empty to keep the current one)" : ""; ?></label>
            <?php if (!empty($editing["photo"])) { ?>
              <div class="mb-2"><img class="admin-thumb" src="../<?php echo e(upload_url($editing["photo"])); ?>" alt=""></div>
            <?php } ?>
            <input id="photo" type="file" class="form-control" name="photo" accept="image/jpeg,image/png,image/gif,image/webp">
            <small class="form-text text-muted">JPG, PNG, GIF or WEBP, max 5 MB. A portrait photo looks best.</small>
          </div>
          <?php foreach ($socialFields as $field => $label) { ?>
          <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?> link</label>
            <input id="<?php echo $field; ?>" type="url" class="form-control" name="<?php echo $field; ?>" placeholder="https://" value="<?php echo e($form[$field] ?? ""); ?>">
          </div>
          <?php } ?>
          <div class="form-group">
            <label for="sort_order">Display order</label>
            <input id="sort_order" type="number" class="form-control" name="sort_order" value="<?php echo (int) ($form["sort_order"] ?? 0); ?>">
            <small class="form-text text-muted">Lower numbers are shown first.</small>
          </div>
          <button type="submit" class="btn btn-primary"><?php echo $formId ? "Save Changes" : "Add Member"; ?></button>
          <?php if ($formId) { ?><a href="team.php" class="btn btn-light">Cancel</a><?php } ?>
        </form>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-header"><h4>Team Members (<?php echo count($members); ?>)</h4></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <tr><th>Photo</th><th>Name</th><th>Position</th><th>Order</th><th>Actions</th></tr>
            <?php if (!$members) { ?>
              <tr><td colspan="5" class="text-center">No team members yet. Add the first one with the form.</td></tr>
            <?php } ?>
            <?php foreach ($members as $member) { ?>
            <tr>
              <td><img class="admin-thumb" src="../<?php echo e(upload_url($member["photo"])); ?>" alt=""></td>
              <td><?php echo e($member["name"]); ?></td>
              <td><?php echo e($member["position"]); ?></td>
              <td><?php echo (int) $member["sort_order"]; ?></td>
              <td>
                <a href="team.php?edit=<?php echo (int) $member["id"]; ?>" class="btn btn-sm btn-primary">Edit</a>
                <form method="post" action="team.php" onsubmit="return confirm('Delete this team member?')">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo (int) $member["id"]; ?>">
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
