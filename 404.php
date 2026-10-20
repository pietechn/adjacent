<?php http_response_code(404); ?>
<?php include "syntax.php"; ?>
<body>
<div class="site-preloader-wrap">
<div class="spinner"></div>
</div>
<?php include "header.php"; ?>
<section class="error-section padding">
<div class="container">
<div class="error-content text-center">
<img src="img/404.png" alt="Page not found">
<div class="error-info">
<h2>404</h2>
<p>Sorry, the page you are looking for could not be found. <br>It may have been moved or no longer exists. <br><a href="index.php">Go back to the homepage</a></p>
</div>
</div>
</div>
</section>
<?php include "footer.php"; ?>
<?php include "scripts.php"; ?>
</body>
</html>
