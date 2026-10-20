<?php
// Admin sidebar, shared by every logged-in admin page.
$currentPage = basename($_SERVER["SCRIPT_NAME"]);
$menu = [
    "index.php" => ["Projects", "briefcase"],
    "admin.php" => ["Add Project", "upload"],
    "team.php" => ["Team Members", "users"],
    "testimonials.php" => ["Testimonials", "message-square"],
    "contact-info.php" => ["Contact Info", "phone"],
    "subscribers.php" => ["Subscribers", "mail"],
    "change-password.php" => ["Change Password", "lock"],
];
?>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.php"> <span
                class="logo-name">Ajacent</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <?php foreach ($menu as $page => [$label, $icon]) { ?>
            <li class="dropdown<?php echo $page === $currentPage ? ' active' : ''; ?>">
              <a href="<?php echo $page; ?>" class="nav-link"><i data-feather="<?php echo $icon; ?>"></i><span><?php echo $label; ?></span></a>
            </li>
            <?php } ?>
            <li>
              <form method="post" action="logout.php">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link btn btn-link"><i data-feather="log-out"></i><span>Logout</span></button>
              </form>
            </li>
          </ul>
        </aside>
      </div>
