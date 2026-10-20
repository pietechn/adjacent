
<section class="widget-section padding">
<div class="container">
<div class="row">
<div class="col-lg-3 col-sm-6 sm-padding">
<div class="widget-content">
<a href="index.php"><img src="img/logo.png" width="100" height="50" alt="Adjacent Electrical &amp; Mechanical LTD"></a>
<!-- <p>Building your own home is about desire, fantasy. But it’s achievable anyone can do it.</p> -->
</div>
</div>
<div class="col-lg-2 col-sm-6 sm-padding">
<div class="widget-content">
<h4>Company</h4>
<ul class="widget-links">
<li><a href="about-us.php">About Us</a></li>
<li><a href="service.php">Our Services</a></li>
<li><a href="team.php">Our Team</a></li>
<li><a href="testimonial.php">Clients Reviews</a></li>
<li><a href="contact.php">Contact Us</a></li>
</ul>
</div>
</div>
<div class="col-lg-3 col-sm-6 sm-padding">
<div class="widget-content">
<h4>Headquarters</h4>
<p><?php echo e(setting("company_address")); ?></p>
<?php if (setting("company_email")) { ?><span><a href="mailto:<?php echo e(setting("company_email")); ?>"><?php echo e(setting("company_email")); ?></a></span><?php } ?>
<?php foreach (["phone_primary", "phone_secondary"] as $phoneKey) { if (setting($phoneKey)) { ?><span><a href="tel:<?php echo e(preg_replace("/[^0-9+]/", "", setting($phoneKey))); ?>"><?php echo e(setting($phoneKey)); ?></a></span><?php } } ?>
</div>
</div>
<div class="col-lg-4 col-sm-6 sm-padding">
<div class="widget-content">
<h4>Newsletter Subscription</h4>
<!-- <p>Subscribe and get 10% off from our <br>architecture company.</p> -->
<div class="subscribe-box clearfix">
<div class="subscribe-form-wrap">
<form action="subscribe.php" method="post" class="subscribe-form" id="subscribe-form">
<input type="email" name="email" id="subs-email" class="form-input" placeholder="Enter Your Email Address..." required>
<input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;">
<button type="submit" class="submit-btn">Subscribe</button>
<div id="subscribe-result">
<p class="subscription-success" style="color:#2ecc71;margin-top:10px;"></p>
<p class="subscription-error" style="color:#e74c3c;margin-top:10px;"></p>
</div>
</form>
<script>
document.getElementById("subscribe-form").addEventListener("submit", function (event) {
    event.preventDefault();
    var form = this;
    var button = form.querySelector(".submit-btn");
    var success = form.querySelector(".subscription-success");
    var error = form.querySelector(".subscription-error");
    success.textContent = "";
    error.textContent = "";
    button.disabled = true;
    button.textContent = "Submitting...";

    fetch(form.action, { method: "POST", body: new FormData(form) })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.ok) {
                success.textContent = data.message;
                form.reset();
            } else {
                error.textContent = data.message;
            }
        })
        .catch(function () {
            error.textContent = "Something went wrong. Please try again.";
        })
        .then(function () {
            button.disabled = false;
            button.textContent = "Subscribe";
        });
});
</script>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<footer class="footer-section align-center">
<div class="container">
<p><a href="https://pietechglobal.com" target="_blank" rel="noopener">Designed By PIETECH</a></p>
</div>
</footer>