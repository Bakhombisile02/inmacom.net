<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Twitter -->
    <meta name="twitter:site" content="@Datamatics">
    <meta name="twitter:creator" content="@Datamatics">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="INMACOM">
    <meta name="twitter:description" content="INMACOM MIS">
    <meta name="twitter:image" content="dashforge/img/dashforge-social.php">

    <!-- Facebook -->
    <meta property="og:url" content="http://Datamatics.me/dashforge">
    <meta property="og:title" content="INMACOM">
    <meta property="og:description" content="INMACOM MIS">

    
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="INMACOM MIS">
    <meta name="author" content="Datamatics">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="./assets/img/favicon.png">

    <title>INMACOM MIS</title>

    <!-- vendor css -->
    <link href="./lib/%40fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="./lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="./assets/css/dashforge.css">
    <link rel="stylesheet" href="./assets/css/dashforge.auth.css">
  </head>
  <body>

    <?php require_once 'includes/header.php'; ?>

    <div class="content content-fixed content-auth-alt">
      <div class="container ht-100p tx-center">
        <div class="ht-100p d-flex flex-column align-items-center justify-content-center">
          <div class="wd-70p wd-sm-250 wd-lg-300 mg-b-15"><img src="./assets/img/img22.png" class="img-fluid" alt=""></div>
          <h1 class="tx-color-01 tx-24 tx-sm-32 tx-lg-36 mg-xl-b-5">505 Forbidden</h1>
          <h5 class="tx-16 tx-sm-18 tx-lg-20 tx-normal mg-b-20">Oopps. Something is broken.</h5>
          <p class="tx-color-03 mg-b-30">We've been automatically alerted of the issue and will work to fix it asap.</p>
          <div class="mg-b-40"><button class="btn btn-white bd-2 pd-x-30">Back to Home</button></div>
          <span class="tx-12 tx-color-03">Error page concept with laptop vector is created by <a href="https://www.freepik.com/free-photos-vectors/business">freepik (freepik.com)</a></span>

        </div>
      </div><!-- container -->
    </div><!-- content -->

    <?php require_once 'includes/footer.php'; ?>

    <script src="./lib/jquery/jquery.min.js"></script>
    <script src="./lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./lib/feather-icons/feather.min.js"></script>
    <script src="./lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <script src="./assets/js/dashforge.js"></script>

    <!-- append theme customizer -->
    <script src="./lib/js-cookie/js.cookie.js"></script>
    <script src="./assets/js/dashforge.settings.js"></script>
    <script>
      $(function(){
        'use script'

        window.darkMode = function(){
          $('.btn-white').addClass('btn-dark').removeClass('btn-white');
        }

        window.lightMode = function() {
          $('.btn-dark').addClass('btn-white').removeClass('btn-dark');
        }

        var hasMode = Cookies.get('df-mode');
        if(hasMode === 'dark') {
          darkMode();
        } else {
          lightMode();
        }
      })
    </script>
  </body>

</html>
