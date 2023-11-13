<?php include "syntax.php"; ?>
<body>
<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
<div class="site-preloader-wrap">
<div class="spinner"></div>
</div><?php include "header.php";?>
<section class="page-header padding">
<div class="container">
<div class="page-content text-center">
<h2>Specialized projects</h2>
<p>Construction, Building, Electrical & Mechanical.</p>
</div>
</div>
</section>
<section class="projects-section padding">
<div class="container">
<div class="row">
<?php 
       define("DB_SERVER", "localhost");
    define("DB_USER", "id18698161_root");
    define("DB_PASS", ")K-rg^1qkrw~Z2=Q");
    define("DB_NAME", "id18698161_construction");     
    $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            $sql = mysqli_query($conn,"SELECT * FROM projects order by id desc");
            while($rows = mysqli_fetch_array($sql)){?>
            <div class="col-lg-4 col-sm-6 padding-15">
                <div class="project-item">
                <img src="<?php echo './uploads/'.$rows['image'];?>" alt="projects">
                <div class="overlay"></div>
                <a href="<?php echo './uploads/'.$rows['image'];?>" class="view-icon img-popup" data-gall="project"> <i class="fas fa-expand"></i></a>
                <div class="projects-content">
                <h3><a href="#" class="tittle"><?php echo $rows['description'];?></a></h3>
                </div>
                </div>
            </div>
            <?php }?>
</div>
</div>
</section>
<!-- <div class="sponsor-section bg-grey">
<div class="dots"></div>
<div class="container">
<div id="sponsor-carousel" class="sponsor-carousel owl-carousel">
<div class="sponsor-item">
<img src="img/sponsor1.png" alt="sponsor">
</div>
<div class="sponsor-item">
<img src="img/sponsor2.png" alt="sponsor">
</div>
<div class="sponsor-item">
<img src="img/sponsor3.png" alt="sponsor">
</div>
<div class="sponsor-item">
<img src="img/sponsor4.png" alt="sponsor">
</div>
<div class="sponsor-item">
<img src="img/sponsor5.png" alt="sponsor">
</div>
<div class="sponsor-item">
<img src="img/sponsor6.png" alt="sponsor">
</div>
<div class="sponsor-item">
<img src="img/sponsor7.png" alt="sponsor">
</div>
<div class="sponsor-item">
<img src="img/sponsor8.png" alt="sponsor">
</div>
</div>
</div> -->
</div><?php include "footer.php";?>
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

<!-- projects.html  29 Nov 2019 03:33:52 GMT -->
</html>