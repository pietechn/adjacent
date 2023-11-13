<?php include "syntax.php";?>
<body>
<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
<div class="site-preloader-wrap">
<div class="spinner"></div>
</div>
<?php include "header.php";?>

<!-- <div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="350" id="gmap_canvas" src="https://maps.google.com/maps?q=Dynamic%20Layers&amp;t=&amp;z=11&amp;ie=UTF8&amp;iwloc=&amp;output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://www.emojilib.com/"></a></div><style>.mapouter{position:relative;text-align:right;height:350px;width:100%;}.gmap_canvas {overflow:hidden;background:none!important;height:350px;width:100%;}</style></div> -->
<section class="contact-section bg-grey padding">
<div class="dots"></div>
<div class="container">
<div class="contact-wrap d-flex align-items-center row">
<div class="col-md-6 padding-15">
<div class="contact-info">
<h2>Get in touch with us & <br>send us message today!</h2>
<</div>
</div>
<div class="col-md-6 padding-15">
<div class="contact-form">
<form class="form-horizontal">
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
<button id="submit" class="default-btn" type="submit" onclick="gotowhatsapp()">Send Message</button>
</div>
</div>
<div id="form-messages" class="alert" role="alert"></div>
</form>
</div>
</div>
</div>
</div>
</section>
<?php include "footer.php";?>
<a data-scroll href="#header" id="scroll-to-top"><i class="arrow_carrot-up"></i></a>

<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="js/vendor/jquery-1.12.4.min.js"></script>

<script src="js/vendor/bootstrap.min.js"></script>

<script src="js/vendor/tether.min.js"></script>

<script src="js/vendor/headroom.min.js"></script>

<script src="js/vendor/owl.carousel.min.js"></script>

<script src="js/vendor/smooth-scroll.min.js"></script>

<script src="js/vendor/venobox.min.js"></script>

<script src="js/vendor/jquery.ajaxchimp.min.js"></script>

<script src="js/vendor/slick.min.js"></script>

<script src="js/vendor/waypoints.min.js"></script>

<script src="js/vendor/odometer.min.js"></script>

<script src="js/vendor/wow.min.js"></script>

<script src="js/main.js"></script>
</body>

<script>
function gotowhatsapp() {
    
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var message = document.getElementById("message").value;

    var url = "https://wa.me/2348085059313?text=" 
    + "Name: " + name + "%0a"
    + "Email: " + email  + "%0a"
    + "Message: " + message; 

    window.open(url, '_blank').focus();
}
</script>
<!-- contact.html  29 Nov 2019 03:34:47 GMT -->
</html>