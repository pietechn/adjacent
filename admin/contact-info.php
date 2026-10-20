<?php
require_once __DIR__ . "/auth.php";

$pageTitle = "Contact Info";
$message = "";
$messageType = "danger";

// key => [label, input type, help text]
$fields = [
    "company_address" => ["Office address", "textarea", "Shown in the footer and on the contact page."],
    "company_email"   => ["Company email", "email", "Shown on the site. Newsletter signups are also sent to this address."],
    "phone_primary"   => ["Main phone number", "text", "e.g. 08085059313"],
    "phone_secondary" => ["Second phone number", "text", "Optional."],
    "whatsapp_number" => ["WhatsApp number", "text", "In international format without + or spaces, e.g. 2348085059313. The contact form sends messages here."],
    "contact_intro"   => ["Contact page introduction", "textarea", "Short text above the address on the contact page."],
    "map_location"    => ["Map location", "text", "Address or place name for the map on the contact page. Leave empty to hide the map."],
];

$values = [];
foreach ($fields as $key => $field) {
    $values[$key] = setting($key);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    foreach ($fields as $key => $field) {
        $values[$key] = trim((string) ($_POST[$key] ?? ""));
    }

    if ($values["company_address"] === "" || $values["phone_primary"] === "") {
        $message = "The office address and main phone number are required.";
    } elseif (!filter_var($values["company_email"], FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid company email address.";
    } elseif ($values["whatsapp_number"] !== "" && !preg_match('/^[1-9][0-9]{7,14}$/', $values["whatsapp_number"])) {
        $message = "The WhatsApp number must be digits only in international format, e.g. 2348085059313.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)
                                       ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        foreach ($values as $key => $value) {
            mysqli_stmt_bind_param($stmt, "ss", $key, $value);
            mysqli_stmt_execute($stmt);
        }
        header("Location: contact-info.php?msg=saved");
        exit;
    }
}

if ($message === "" && ($_GET["msg"] ?? "") === "saved") {
    $message = "Contact information saved. The website now shows the new details.";
    $messageType = "success";
}

include __DIR__ . "/layout-top.php";
?>
<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-header"><h4>Company Contact Information</h4></div>
      <div class="card-body">
        <form method="post" action="contact-info.php">
          <?php echo csrf_field(); ?>
          <?php foreach ($fields as $key => [$label, $type, $help]) { ?>
          <div class="form-group">
            <label for="<?php echo $key; ?>"><?php echo e($label); ?></label>
            <?php if ($type === "textarea") { ?>
              <textarea id="<?php echo $key; ?>" class="form-control" name="<?php echo $key; ?>" style="height:80px"><?php echo e($values[$key]); ?></textarea>
            <?php } else { ?>
              <input id="<?php echo $key; ?>" type="<?php echo $type; ?>" class="form-control" name="<?php echo $key; ?>" value="<?php echo e($values[$key]); ?>">
            <?php } ?>
            <small class="form-text text-muted"><?php echo e($help); ?></small>
          </div>
          <?php } ?>
          <button type="submit" class="btn btn-primary">Save Contact Info</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . "/layout-bottom.php"; ?>
