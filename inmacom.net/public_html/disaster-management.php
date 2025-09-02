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

  <!-- Meta -->
  <meta name="description" content="INMACOM Disaster Management - Early Warning Systems">
  <meta name="author" content="INMACOM">

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

  <title>INMACOM MIS - <?php echo t('disaster_management'); ?></title>

  <!-- vendor css -->
  <link href="lib/%40fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

  <!-- DashForge CSS -->
  <link rel="stylesheet" href="assets/css/dashforge.css">
  <link rel="stylesheet" href="assets/css/dashforge.profile.css">

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

  <div class="content content-components">
    <div class="container">
      <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mg-b-10">
              <li class="breadcrumb-item"><a href="./"><?php echo t('home'); ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo t('disaster_management'); ?></li>
            </ol>
          </nav>
          <h4 class="mg-b-0 tx-spacing--1"><?php echo t('disaster_management'); ?></h4>
        </div>
      </div>

      <div class="row row-sm">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title"><?php echo t('early_warning'); ?></h5>
            </div>
            <div class="card-body">
              <p><?php echo getCurrentLanguage() == 'pt' ? 
                'Os sistemas de alerta precoce são essenciais para a gestão de riscos de inundações e secas na bacia dos rios Incomati e Maputo. Estes sistemas fornecem informações oportunas aos tomadores de decisão e às comunidades para permitir ações preventivas e de mitigação.' :
                'Early warning systems are essential for flood and drought risk management in the Incomati and Maputo river basins. These systems provide timely information to decision makers and communities to enable preventive and mitigation actions.'; ?>
              </p>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="card bg-primary text-white mg-b-20">
                    <div class="card-body">
                      <h6 class="card-title text-white"><?php echo getCurrentLanguage() == 'pt' ? 'Monitoramento de Enchentes' : 'Flood Monitoring'; ?></h6>
                      <p class="card-text"><?php echo getCurrentLanguage() == 'pt' ? 
                        'Sistema de monitoramento em tempo real dos níveis de água dos rios e barragens para alerta precoce de enchentes.' :
                        'Real-time monitoring system for river and dam water levels for early flood warnings.'; ?>
                      </p>
                      <a href="hydrology-current.php" class="btn btn-light btn-sm"><?php echo t('view'); ?></a>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="card bg-warning text-white mg-b-20">
                    <div class="card-body">
                      <h6 class="card-title text-white"><?php echo getCurrentLanguage() == 'pt' ? 'Alerta de Seca' : 'Drought Alert'; ?></h6>
                      <p class="card-text"><?php echo getCurrentLanguage() == 'pt' ? 
                        'Monitoramento de precipitação e indicadores de seca para alertas de escassez de água.' :
                        'Rainfall monitoring and drought indicators for water scarcity warnings.'; ?>
                      </p>
                      <a href="climate-current.php" class="btn btn-light btn-sm"><?php echo t('view'); ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card mg-t-20">
            <div class="card-header">
              <h5 class="card-title"><?php echo getCurrentLanguage() == 'pt' ? 'Links de Sistemas de Alerta' : 'Warning System Links'; ?></h5>
            </div>
            <div class="card-body">
              <div class="list-group">
                <a href="http://www.hydronet.co.za" target="_blank" class="list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">Hydronet South Africa</h6>
                    <small><i class="fas fa-external-link-alt"></i></small>
                  </div>
                  <p class="mb-1"><?php echo getCurrentLanguage() == 'pt' ? 
                    'Sistema nacional de monitoramento hidrológico da África do Sul' :
                    'South African national hydrological monitoring system'; ?>
                  </p>
                </a>
                
                <div class="list-group-item">
                  <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1"><?php echo getCurrentLanguage() == 'pt' ? 'Sistema de Alerta Regional' : 'Regional Warning System'; ?></h6>
                    <small class="text-muted"><?php echo getCurrentLanguage() == 'pt' ? 'Em Desenvolvimento' : 'In Development'; ?></small>
                  </div>
                  <p class="mb-1"><?php echo getCurrentLanguage() == 'pt' ? 
                    'Sistema integrado de alerta precoce para a região da bacia do Incomati e Maputo' :
                    'Integrated early warning system for the Incomati and Maputo basin region'; ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 mg-t-20 mg-lg-t-0">
          <div class="card">
            <div class="card-header">
              <h6 class="card-title"><?php echo getCurrentLanguage() == 'pt' ? 'Recursos Relacionados' : 'Related Resources'; ?></h6>
            </div>
            <div class="card-body">
              <div class="list-group list-group-flush">
                <a href="hydrology-current.php" class="list-group-item list-group-item-action">
                  <i class="fas fa-tint text-primary mg-r-10"></i>
                  <?php echo t('hydrology'); ?>
                </a>
                <a href="climate-current.php" class="list-group-item list-group-item-action">
                  <i class="fas fa-cloud-rain text-info mg-r-10"></i>
                  <?php echo t('climate'); ?>
                </a>
                <a href="dam-levels.php" class="list-group-item list-group-item-action">
                  <i class="fas fa-water text-success mg-r-10"></i>
                  <?php echo t('dam_levels'); ?>
                </a>
                <a href="documents.php" class="list-group-item list-group-item-action">
                  <i class="fas fa-file-alt text-warning mg-r-10"></i>
                  <?php echo t('documents'); ?>
                </a>
              </div>
            </div>
          </div>

          <div class="card mg-t-20">
            <div class="card-header">
              <h6 class="card-title"><?php echo getCurrentLanguage() == 'pt' ? 'Informações de Contato' : 'Contact Information'; ?></h6>
            </div>
            <div class="card-body">
              <p class="mg-b-10"><strong><?php echo getCurrentLanguage() == 'pt' ? 'Emergência:' : 'Emergency:'; ?></strong></p>
              <p class="tx-13 mg-b-20"><?php echo getCurrentLanguage() == 'pt' ? 
                'Em caso de emergência relacionada a inundações ou secas, entre em contato com as autoridades locais.' :
                'In case of flood or drought-related emergencies, contact local authorities.'; ?>
              </p>
              
              <p class="mg-b-10"><strong><?php echo getCurrentLanguage() == 'pt' ? 'Suporte Técnico:' : 'Technical Support:'; ?></strong></p>
              <p class="tx-13">
                Email: support@inmacom.org<br>
                <?php echo getCurrentLanguage() == 'pt' ? 'Tel:' : 'Phone:'; ?> +27 (0)12 xxx-xxxx
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/feather-icons/feather.min.js"></script>
  <script src="lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="assets/js/dashforge.js"></script>
</body>

</html>