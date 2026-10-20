<?php include "syntax.php"; ?>
<body>
<div class="site-preloader-wrap">
<div class="spinner"></div>
</div>
<?php include "header.php"; ?>
<section class="page-header padding">
<div class="container">
<div class="page-content text-center">
<h2>Clients reviews</h2>
<p>What our clients say about working with us.</p>
</div>
</div>
</section>
<section class="testimonial-section bg-grey padding">
<div class="dots"></div>
<div class="container">
<?php
$testimonials = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM testimonials WHERE is_published = 1 ORDER BY created_at DESC"), MYSQLI_ASSOC);
if (!$testimonials) { ?>
<p class="text-center">Client reviews are coming soon.</p>
<?php } else { ?>
<div id="testimonial-carousel" class="testimonial-carousel box-shadow owl-carousel">
<?php include "testimonial-items.php"; ?>
</div>
<?php } ?>
</div>
</section>
<?php include "footer.php"; ?>
<?php include "scripts.php"; ?>
</body>
</html>
