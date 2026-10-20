<?php
$currentPage = basename($_SERVER["SCRIPT_NAME"]);
$navLinks = [
    "index.php" => "Home",
    "about-us.php" => "About",
    "service.php" => "Services",
    "projects.php" => "Our Projects",
    "team.php" => "Our Team",
    "testimonial.php" => "Testimonials",
    "contact.php" => "Contact Us",
];
?>
<header class="header" id="header">
<div class="primary-header">
<div class="container">
<div class="primary-header-inner">
<div class="header-logo">
<a href="index.php"><img src="img/logo.png" width="100" height="50" alt="Adjacent Electrical &amp; Mechanical LTD"></a>
</div>
<div class="header-menu-wrap">
<ul class="dl-menu">
<?php foreach ($navLinks as $page => $label) { ?>
<li<?php echo $page === $currentPage ? ' class="active"' : ''; ?>><a href="<?php echo $page; ?>"><?php echo $label; ?></a></li>
<?php } ?>
</ul>
</div>
<div class="header-right">
<div class="mobile-menu-icon">
<div class="burger-menu">
<div class="line-menu line-half first-line"></div>
<div class="line-menu"></div>
<div class="line-menu line-half last-line"></div>
</div>
</div>
</div>
</div>
</div>
</div>
</header>
