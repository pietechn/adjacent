<?php
// Testimonial carousel items. Expects $testimonials (rows from the testimonials table).
foreach ($testimonials as $testimonial) { ?>
<div class="testi-item d-flex align-items-center">
<img src="<?php echo e(upload_url($testimonial["photo"])); ?>" alt="<?php echo e($testimonial["client_name"]); ?>">
<div class="testi-content">
<p>"<?php echo nl2br(e($testimonial["message"])); ?>"</p>
<h3><?php echo e($testimonial["client_name"]); ?></h3>
<ul class="rattings">
<?php for ($star = 1; $star <= 5; $star++) { ?>
<li><i class="fa<?php echo $star <= (int) $testimonial["rating"] ? 's' : 'r'; ?> fa-star"></i></li>
<?php } ?>
</ul>
<?php if ($testimonial["client_title"] !== "") { ?><span><?php echo e($testimonial["client_title"]); ?></span><?php } ?>
</div>
<i class="fa fa-quote-right"></i>
</div>
<?php }
