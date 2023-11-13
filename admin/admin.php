<?php

    
         define("DB_SERVER", "localhost");
    define("DB_USER", "id18698161_root");
    define("DB_PASS", ")K-rg^1qkrw~Z2=Q");
    define("DB_NAME", "id18698161_construction");   
	

    $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if (isset($_POST['upload'])) {
        $desc = $_POST['desc'];
        // Get image name
        $image = $_FILES['image']['name'];

        // // Get text
        // $image_text = mysqli_real_escape_string($db, $_POST['image_text']);
    
        // image file directory
        $target = "../uploads/".basename($image);
        $date = date("Y-m-d");
        $upload = "INSERT INTO projects (image,description,date) VALUES ('$image','$desc','$date')";
        // execute query
        mysqli_query($conn, $upload);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                echo '<script>alert("Upload Successful")</script>';
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Adjacent - Admin Dashboard </title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favn.ico' />
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a></li>
            <li>
            </li>
          </ul>
        </div>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html"> <span
                class="logo-name">Ajacent</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
              <a href="index.php" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown active">
              <a href="admin.php" class="nav-link"><i data-feather="monitor"></i><span>Add Projects</span></a>
            </li>
          </ul>
        </aside>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
    <div class="container text-center" style="margin-bottom:20px;">
    <div class="row">
        <div class="col-md-6">
        <p class="text-center default-btn">Use The Below Form To Upload Your Projects</p>
<form method="post" action="admin.php" enctype="multipart/form-data">
<input type='file' class="form-control" name="image" /><p><p>Description</p>
<textarea type="text" class="form-control" name="desc" ></textarea>
<br><br>    
<input type="submit" class="btn default-btn form control" name="upload"/>
        </form>
</div>
</div>
</div>
</div>
</div>

 <!-- General JS Scripts -->
 <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="assets/bundles/apexcharts/apexcharts.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="assets/js/page/index.js"></script>
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>
        </body>

     
</html>