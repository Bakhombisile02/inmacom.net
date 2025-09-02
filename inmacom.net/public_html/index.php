<?php 
session_start(); 
// Include bilingual support
require_once 'includes/language.php';
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>">

<head>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Twitter -->
  <meta name="twitter:site" content="@themepixels">
  <meta name="twitter:creator" content="@themepixels">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="DashForge">
  <meta name="twitter:description" content="Responsive Bootstrap 4 Dashboard Template">
  <meta name="twitter:image" content="../../dashforge/img/dashforge-social.html">

  <!-- Facebook -->
  <meta property="og:url" content="https://datamatics.co.za">
  <meta property="og:title" content="DashForge">
  <meta property="og:description" content="INMACOM">

  <meta property="og:image" content="./dashforge/img/dashforge-social.html">
  <meta property="og:image:secure_url" content="./dashforge/img/dashforge-social.html">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="600">

  <!-- Meta -->
  <meta name="description" content="INMACOM">
  <meta name="author" content="Datamatics">

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

  <title>INMACOM MIS</title>

  <!-- vendor css -->
  <link href="lib/%40fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

  <!-- DashForge CSS -->
  <link rel="stylesheet" href="assets/css/dashforge.css">
  <link rel="stylesheet" href="assets/css/dashforge.landing.css">

  <!-- Language Switcher CSS -->
  <style>
    .language-switcher {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    .lang-link {
      padding: 5px 10px;
      text-decoration: none;
      border: 1px solid #ccc;
      border-radius: 4px;
      color: #333;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    .lang-link:hover {
      background-color: #f0f0f0;
      text-decoration: none;
      color: #333;
    }
    .lang-link.active {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }
  </style>

</head>

<body class="page-profile">
  <?php include 'includes/header.php'; ?>

  <div class="home-slider">
    <div class="home-lead">
      <p class="home-text"><?php echo t('welcome_title'); ?></p>

      <h6 class="home-headline"><?php echo t('welcome_subtitle'); ?></h6>

      <div class="d-flex tx-20 mg-t-40">
        <div class="img-group">
          <img src="./assets/img/Flag_of_South_Africa.svg.png" class="img wd-60 ht-60 rounded-circle" alt="">
          <img src="./assets/img/Flag_of_Eswatini.svg.png" class="img wd-60 ht-60 rounded-circle" alt="">
          <img src="./assets/img/Flag_of_Mozambique.svg.png" class="img wd-60 ht-60 rounded-circle" alt="">
        </div>
      </div>
      <div class="d-flex tx-20 mg-t-40">
        <p class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03"></p>
        <p class="tx-12">
          <span class="text-danger tx-uppercase tx-12 tx-medium"><?php echo t('disclaimer'); ?>:</span> <?php echo t('disclaimer_text'); ?>
          <br><span class="text-danger"><?php echo t('disclaimer_agreement'); ?></span>
        </p>
      </div>

    </div>
    <div class="home-slider-img">
      <div><img src="./assets/img/home-1.png" alt=""></div>
      <div><img src="./assets/img/home-2.png" alt=""></div>
      <div><img src="./assets/img/home-2-dark.png" alt=""></div>
    </div>
    <div class="home-slider-bg-one"></div>
  </div><!-- home-slider -->

  <script src="./lib/jquery/jquery.min.js"></script>
  <script src="./lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./lib/feather-icons/feather.min.js"></script>
  <script src="./lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="assets/js/dashforge.js"></script>
</body>

</html>