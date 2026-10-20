<?php
// Opening markup for logged-in admin pages. Set $pageTitle before including.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Adjacent Admin - <?php echo e($pageTitle); ?></title>
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="shortcut icon" type="image/x-icon" href="../img/logo.png">
  <style>
    .admin-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
    .sidebar-menu form .nav-link { width: 100%; text-align: left; }
    .table td { vertical-align: middle; }
    .table form { display: inline; }
  </style>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn"><i data-feather="maximize"></i></a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li><a href="../index.php" target="_blank" class="nav-link">View website</a></li>
        </ul>
      </nav>
      <?php include __DIR__ . "/sidebar.php"; ?>
      <div class="main-content">
        <section class="section">
          <?php if (!empty($message)) { ?>
            <div class="alert alert-<?php echo e($messageType ?? "danger"); ?>"><?php echo e($message); ?></div>
          <?php } ?>
