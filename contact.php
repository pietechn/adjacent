<?php include "syntax.php"; ?>
<body>
<div class="site-preloader-wrap">
<div class="spinner"></div>
</div>
<?php include "header.php"; ?>
<section class="page-header padding">
<div class="container">
<div class="page-content text-center">
<h2>Contact us</h2>
<p>We would love to hear about your project.</p>
</div>
</div>
</section>
<section class="contact-section bg-grey padding">
<div class="dots"></div>
<div class="container">
<div class="contact-wrap d-flex align-items-center row">
<div class="col-md-6 padding-15">
<div class="contact-info">
<h2>Get in touch with us &amp; <br>send us a message today!</h2>
<p><?php echo e(setting("contact_intro")); ?></p>
<h3><?php echo e(setting("company_address")); ?></h3>
<h4>
<?php if (setting("company_email")) { ?><span>Email:</span> <a href="mailto:<?php echo e(setting("company_email")); ?>"><?php echo e(setting("company_email")); ?></a><br><?php } ?>
<?php if (setting("phone_primary")) { ?><span>Phone:</span> <a href="tel:<?php echo e(preg_replace("/[^0-9+]/", "", setting("phone_primary"))); ?>"><?php echo e(setting("phone_primary")); ?></a><br><?php } ?>
<?php if (setting("phone_secondary")) { ?><span>Phone:</span> <a href="tel:<?php echo e(preg_replace("/[^0-9+]/", "", setting("phone_secondary"))); ?>"><?php echo e(setting("phone_secondary")); ?></a><?php } ?>
</h4>
</div>
</div>
<div class="col-md-6 padding-15">
<div class="contact-form">
<p>Messages are sent to us on WhatsApp.</p>
<form class="form-horizontal" id="contact-form">
<div class="form-group colum-row row">
<div class="col-sm-6">
<input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
</div>
<div class="col-sm-6">
<input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
</div>
</div>
<div class="form-group row">
<div class="col-md-12">
<textarea id="message" name="message" cols="30" rows="5" class="form-control message" placeholder="Message" required></textarea>
</div>
</div>
<div class="form-group row">
<div class="col-md-12">
<button id="submit" class="default-btn" type="submit">Send Message</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</section>
<?php if (setting("map_location")) { ?>
<div class="contact-map">
<iframe title="Our location" width="100%" height="350" style="border:0;display:block;" loading="lazy"
src="https://maps.google.com/maps?q=<?php echo e(rawurlencode(setting("map_location"))); ?>&amp;z=13&amp;output=embed"></iframe>
</div>
<?php } ?>
<?php include "footer.php"; ?>
<?php include "scripts.php"; ?>
<script>
document.getElementById("contact-form").addEventListener("submit", function (event) {
    event.preventDefault();
    var text = "Name: " + document.getElementById("name").value + "\n"
        + "Email: " + document.getElementById("email").value + "\n"
        + "Message: " + document.getElementById("message").value;
    var whatsappNumber = <?php echo json_encode(preg_replace("/[^0-9]/", "", setting("whatsapp_number", "2348085059313"))); ?>;
    window.open("https://wa.me/" + whatsappNumber + "?text=" + encodeURIComponent(text), "_blank");
});
</script>
</body>
</html>
