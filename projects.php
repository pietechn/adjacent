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
    require_once __DIR__ . "/config.php";
            $sql = mysqli_query($conn,"SELECT * FROM projects order by id desc");
            while($rows = mysqli_fetch_array($sql)){?>
            <div class="col-lg-4 col-sm-6 padding-15">
                <div class="project-item">
                <img src="<?php echo e('./uploads/'.rawurlencode($rows['image']));?>" alt="projects">
                <div class="overlay"></div>
                <a href="<?php echo e('./uploads/'.rawurlencode($rows['image']));?>" class="view-icon img-popup" data-gall="project"> <i class="fas fa-expand"></i></a>
                <div class="projects-content">
                <h3><a href="#" class="tittle"><?php echo e($rows['description']);?></a></h3>
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
<?php include "scripts.php"; ?>
</body>

<!-- projects.html  29 Nov 2019 03:33:52 GMT -->
</html>