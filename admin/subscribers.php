<?php
require_once __DIR__ . "/auth.php";

$pageTitle = "Newsletter Subscribers";

$result = mysqli_query($conn, "SELECT email, notified, created_at FROM newsletter_subscribers ORDER BY created_at DESC");
$subscribers = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (($_GET["export"] ?? "") === "csv") {
    header("Content-Type: text/csv; charset=UTF-8");
    header('Content-Disposition: attachment; filename="newsletter-subscribers-' . date("Y-m-d") . '.csv"');
    $out = fopen("php://output", "w");
    fputcsv($out, ["Email", "Subscribed On"]);
    foreach ($subscribers as $row) {
        fputcsv($out, [$row["email"], $row["created_at"]]);
    }
    exit;
}
?>
<?php include __DIR__ . "/layout-top.php"; ?>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Newsletter Subscribers (<?php echo count($subscribers); ?>)</h4>
                  <div class="card-header-action">
                    <a href="subscribers.php?export=csv" class="btn btn-primary">Download CSV</a>
                  </div>
                </div>
                <div class="card-body p-0">
                  <p class="px-4 pt-3 mb-2">Each new subscriber is emailed to <strong><?php echo e(COMPANY_EMAIL); ?></strong>.
                    "Email sent: No" means that notification failed, but the subscriber is still saved here.</p>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <tr>
                        <th>Email</th>
                        <th>Subscribed On</th>
                        <th>Email sent</th>
                      </tr>
                      <?php if (!$subscribers) { ?>
                      <tr><td colspan="3" class="text-center">No subscribers yet.</td></tr>
                      <?php } ?>
                      <?php foreach ($subscribers as $row) { ?>
                      <tr>
                        <td><a href="mailto:<?php echo e($row["email"]); ?>"><?php echo e($row["email"]); ?></a></td>
                        <td><?php echo e($row["created_at"]); ?></td>
                        <td><?php echo $row["notified"] ? "Yes" : "No"; ?></td>
                      </tr>
                      <?php } ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
<?php include __DIR__ . "/layout-bottom.php"; ?>
