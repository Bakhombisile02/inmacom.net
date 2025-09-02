<?php 
// Include bilingual support
require_once __DIR__ . '/language.php';
?>
<header class="navbar navbar-header navbar-header-fixed">
    <a href="#" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
    <div class="navbar-brand">
        <div><img src="./assets/img/Logo.png" alt="INMACOM" height="50"></div>
    </div><!-- navbar-brand -->
    <div id="navbarMenu" class="navbar-menu-wrapper">
        <div class="navbar-menu-header">
            <!-- <a href="./" class="df-logo">INMA<span>COM</span></a> -->
            <a href="./" class="df-logo"><img src="../assets/img/Logo.png" alt=""></a>
            <a id="mainMenuClose" href="#"><i data-feather="x"></i></a>
        </div><!-- navbar-menu-header -->
        <ul class="nav navbar-menu">
            <li class="nav-item"><a href="./" class="nav-link">INMACOM MIS</a></li>
            <li class="nav-item with-sub">
                <a href="#" class="nav-link"><?php echo t('hydrology'); ?></a>
                <ul class="navbar-menu-sub">
                    <li class="nav-sub-item"><a href="hydrology-current.php" class="nav-sub-link"><?php echo t('current_status'); ?></a></li>
                    <li class="nav-sub-item"><a href="hydrology-historic.php" class="nav-sub-link"><?php echo t('historical_data'); ?></a></li>
                    <li class="nav-sub-item"><a href="dam-levels.php" class="nav-sub-link"><?php echo t('dam_levels'); ?></a></li>
                    <li class="nav-sub-item"><a href="flow-guages.php" class="nav-sub-link"><?php echo t('flow_levels'); ?></a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a href="#" class="nav-link"><?php echo t('climate'); ?></a>
                <ul class="navbar-menu-sub">
                    <li class="nav-sub-item"><a href="climate-current.php" class="nav-sub-link"><?php echo t('current_status'); ?></a></li>
                    <li class="nav-sub-item"><a href="rainfall.php" class="nav-sub-link"><?php echo t('rainfall'); ?></a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a href="#" class="nav-link"><?php echo t('water_quality'); ?></a>
                <ul class="navbar-menu-sub">
                    <li class="nav-sub-item"><a href="water-quality.php" class="nav-sub-link"><?php echo t('current_status'); ?></a></li>
                    <li class="nav-sub-item"><a href="groundwater.php" class="nav-sub-link"><?php echo t('groundwater'); ?></a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="planning-analysis.php" class="nav-link"><?php echo t('planning_analysis'); ?></a></li>
            <li class="nav-item with-sub">
                <a href="#" class="nav-link"><?php echo t('disaster_management'); ?></a>
                <ul class="navbar-menu-sub">
                    <li class="nav-sub-item"><a href="disaster-management.php" class="nav-sub-link"><?php echo t('early_warning'); ?></a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a href="#" class="nav-link"><?php echo t('external_links'); ?></a>
                <ul class="navbar-menu-sub">
                    <li class="nav-sub-item"><a href="http://www.hydronet.co.za" class="nav-sub-link" target="_blank">Hydronet</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="documents.php" class="nav-link"><?php echo t('documents'); ?></a></li>
            <?php if (!isset($_SESSION['login'])) : ?>
                <li class="nav-item"><a href="login.php" class="nav-link"><?php echo t('login'); ?></a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['login'])) : ?>
                <li class="nav-item"><a href="admin/index.php" class="nav-link"><?php echo t('dashboard'); ?></a></li>
            <?php endif ?>
        </ul>
        <div class="navbar-right">
            <?php echo getLanguageSwitcher(); ?>
        </div>
    </div><!-- navbar-menu-wrapper -->

</header>