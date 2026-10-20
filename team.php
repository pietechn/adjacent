<?php include "syntax.php"; ?>
<body>
<div class="site-preloader-wrap">
<div class="spinner"></div>
</div>
<?php include "header.php"; ?>
<section class="page-header padding">
<div class="container">
<div class="page-content text-center">
<h2>Our team</h2>
<p>The skilled people behind every project we deliver.</p>
</div>
</div>
</section>
<section class="team-section bg-grey padding">
<div class="dots"></div>
<div class="container">
<?php
$members = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM team_members ORDER BY sort_order, id"), MYSQLI_ASSOC);
$socialIcons = ["facebook" => "ti-facebook", "twitter" => "ti-twitter", "instagram" => "ti-instagram", "linkedin" => "ti-linkedin"];
if (!$members) { ?>
<p class="text-center">Our team profiles are coming soon.</p>
<?php } else { ?>
<div class="row team-wrap box-shadow">
<?php foreach ($members as $member) { ?>
<div class="col-lg-3 col-sm-6 padding-15">
<div class="team-item">
<div class="overlay"></div>
<img src="<?php echo e(upload_url($member["photo"])); ?>" alt="<?php echo e($member["name"]); ?>">
<div class="team-content">
<h3><?php echo e($member["name"]); ?></h3>
<span><?php echo e($member["position"]); ?></span>
</div>
<ul class="team-social">
<?php foreach ($socialIcons as $network => $icon) { if ($member[$network] !== "") { ?>
<li><a href="<?php echo e($member[$network]); ?>" target="_blank" rel="noopener"><i class="<?php echo $icon; ?>"></i></a></li>
<?php } } ?>
</ul>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
</section>
<?php include "footer.php"; ?>
<?php include "scripts.php"; ?>
</body>
</html>
