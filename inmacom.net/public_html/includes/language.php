<?php
/**
 * INMACOM Bilingual Support System
 * Provides Portuguese and English language support as per SRS Section 3.1
 */

// Prevent direct access
if (!defined('INMACOM_ACCESS')) {
    define('INMACOM_ACCESS', true);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set default language if not set
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'en'; // Default to English
}

// Handle language switching
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'pt'])) {
    $_SESSION['language'] = $_GET['lang'];
}

// Language configuration
$languages = [
    'en' => [
        'name' => 'English',
        'flag' => 'flag-en.png',
        'direction' => 'ltr'
    ],
    'pt' => [
        'name' => 'Português',
        'flag' => 'flag-pt.png', 
        'direction' => 'ltr'
    ]
];

// Language strings
$lang = [];

if ($_SESSION['language'] == 'pt') {
    // Portuguese translations
    $lang = [
        // Navigation
        'home' => 'Início',
        'unverified_data' => 'Dados Não Verificados',
        'verified_data' => 'Dados Verificados',
        'documents' => 'Documentos',
        'external_links' => 'Links Externos',
        'login' => 'Entrar',
        'dashboard' => 'Painel',
        'logout' => 'Sair',
        
        // Home page
        'welcome_title' => 'Bem-vindo ao INMACOM MIS',
        'welcome_subtitle' => 'O Sistema de Informação de Gestão (MIS) é uma aplicação web online desenvolvida pelo INMACOM para partilha de dados entre os estados membros.',
        
        // Data sections
        'hydrology' => 'Hidrologia',
        'climate' => 'Clima',
        'current_status' => 'Estado Atual',
        'historical_data' => 'Dados Históricos',
        'dam_levels' => 'Níveis de Barragens',
        'flow_levels' => 'Níveis de Fluxo',
        'water_quality' => 'Qualidade da Água',
        'rainfall' => 'Precipitação',
        'groundwater' => 'Água Subterrânea',
        
        // Planning and Analysis
        'planning_analysis' => 'Planeamento e Análise',
        'data_models' => 'Modelos de Dados',
        
        // Disaster Management
        'disaster_management' => 'Gestão de Desastres',
        'early_warning' => 'Sistemas de Alerta Precoce',
        
        // Document categories - Portuguese  
        'study_reports' => 'Relatórios de Estudo',
        'measures_programs' => 'Programas de Medidas para implementação dos regulamentos e normas de qualidade da água da SADC',
        'aquatic_weeds' => 'Controlo de infestação e translocação de plantas aquáticas',
        'legislation' => 'Legislação desenvolvida e implementada sobre gestão de recursos hídricos',
        'quality_guidelines' => 'Diretrizes e metodologias utilizadas para avaliar a qualidade da água',
        'water_use_guidelines' => 'Diretrizes utilizadas para determinar a qualidade da água necessária para diferentes usos',
        'public_participation' => 'Diretrizes desenvolvidas para envolver o público na gestão de recursos hídricos',
        'conservation_strategies' => 'Estratégias desenvolvidas para conservação da água e gestão da demanda',
        'management_strategies' => 'Estratégias para gestão de recursos hídricos desenvolvidas a nível nacional e de bacia',
        'pollution_control' => 'Diretrizes, procedimentos, estratégias e melhores práticas para gerir utilizadores da água',
        'iwrm_strategies' => 'Todas as novas estratégias e políticas relacionadas à gestão integrada de recursos hídricos',
        'territorial_reports' => 'Relatórios preparados pelas Partes sobre as bacias dos rios Incomati e Maputo',
        'best_practices' => 'Políticas, diretrizes, procedimentos, estratégias e melhores práticas desenvolvidas',
        'biological_assessment' => 'Abordagens biológicas para avaliação da qualidade da água (critérios)',
        
        // User interface
        'search' => 'Pesquisar',
        'download' => 'Descarregar',
        'upload' => 'Carregar',
        'view' => 'Ver',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
        'submit' => 'Submeter',
        
        // Forms
        'email' => 'Email',
        'password' => 'Palavra-passe',
        'username' => 'Nome de utilizador',
        'first_name' => 'Primeiro nome',
        'last_name' => 'Último nome',
        'organization' => 'Organização',
        'role' => 'Função',
        
        // Disclaimer
        'disclaimer' => 'Desclaimer',
        'disclaimer_text' => 'O INMACOM mantém este sistema como um serviço público. No entanto, o INMACOM não assume qualquer responsabilidade legal pela precisão ou integridade das informações contidas neste site. As pessoas que utilizam informações deste site para fins oficiais, ou outros fins, para os quais são necessárias precisão e integridade, são notificadas de que devem primeiro verificar as informações com os registos públicos ou outras fontes primárias das quais as informações foram obtidas.',
        'disclaimer_agreement' => 'Ao utilizar este site, concorda ter lido e aceite as nossas Condições de Utilização e Política de Privacidade.',
        
        // Messages
        'success' => 'Sucesso',
        'error' => 'Erro',
        'warning' => 'Aviso',
        'info' => 'Informação',
        'loading' => 'Carregando...',
        'no_data' => 'Nenhum dado disponível',
        'access_denied' => 'Acesso negado'
    ];
} else {
    // English translations (default)
    $lang = [
        // Navigation
        'home' => 'Home',
        'unverified_data' => 'Unverified Data',
        'verified_data' => 'Verified Data',
        'documents' => 'Documents',
        'external_links' => 'External Links',
        'login' => 'Login',
        'dashboard' => 'Dashboard',
        'logout' => 'Logout',
        
        // Home page
        'welcome_title' => 'Welcome to INMACOM MIS',
        'welcome_subtitle' => 'The Management Information System (MIS) is an online web application developed by INMACOM for the purpose of data sharing between member of states.',
        
        // Data sections
        'hydrology' => 'Hydrology',
        'climate' => 'Climate',
        'current_status' => 'Current Status',
        'historical_data' => 'Historical Data',
        'dam_levels' => 'Dam Levels',
        'flow_levels' => 'Flow Levels',
        'water_quality' => 'Water Quality',
        'rainfall' => 'Rainfall',
        'groundwater' => 'Groundwater',
        
        // Planning and Analysis
        'planning_analysis' => 'Planning and Analysis',
        'data_models' => 'Data Models',
        
        // Disaster Management
        'disaster_management' => 'Disaster Management',
        'early_warning' => 'Early Warning Systems',
        
        // Document categories - English
        'study_reports' => 'Study Reports',
        'measures_programs' => 'Programs of measures for the implementation of SADC water quality regulations and norms',
        'aquatic_weeds' => 'Aquatic weeds infestation and translocation control',
        'legislation' => 'Legislation developed and implemented on water resource management',
        'quality_guidelines' => 'Guidelines and methodologies used for assessing the water quality',
        'water_use_guidelines' => 'Guidelines used in determining the water quality required for different water uses',
        'public_participation' => 'Guidelines developed for involving the public in water resource management',
        'conservation_strategies' => 'Strategies developed for water conservation and demand management',
        'management_strategies' => 'Strategies for water resources management developed on national and catchment level',
        'pollution_control' => 'Guidelines, procedures, strategies and best practices for managing water users',
        'iwrm_strategies' => 'All new strategies and policies related to integrated water resources management',
        'territorial_reports' => 'Reports prepared by the Parties on portions of the Incomati and Maputo watercourses',
        'best_practices' => 'Policies, guidelines, procedures, strategies and best practices as developed',
        'biological_assessment' => 'Biological approaches to water quality assessment (criteria)',
        
        // User interface
        'search' => 'Search',
        'download' => 'Download',
        'upload' => 'Upload',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'submit' => 'Submit',
        
        // Forms
        'email' => 'Email',
        'password' => 'Password',
        'username' => 'Username',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'organization' => 'Organization',
        'role' => 'Role',
        
        // Disclaimer
        'disclaimer' => 'Disclaimer',
        'disclaimer_text' => 'INMACOM is maintaining this system as a public service. However, INMACOM does not assume any legal responsibility for the accuracy or completeness of the information contained on this site. Persons using information from this site for official purposes, or other purposes, for which accuracy and completeness are required, are hereby notified that they should first verify the information with the public records or other primary sources from which the information was obtained.',
        'disclaimer_agreement' => 'By using this site, you agree to have read and accepted our Conditions of Use and Privacy Policy.',
        
        // Messages
        'success' => 'Success',
        'error' => 'Error',
        'warning' => 'Warning',
        'info' => 'Information',
        'loading' => 'Loading...',
        'no_data' => 'No data available',
        'access_denied' => 'Access denied'
    ];
}

/**
 * Get translated text
 * @param string $key
 * @param string $default
 * @return string
 */
function t($key, $default = null) {
    global $lang;
    return isset($lang[$key]) ? $lang[$key] : ($default ?? $key);
}

/**
 * Get current language
 * @return string
 */
function getCurrentLanguage() {
    return $_SESSION['language'] ?? 'en';
}

/**
 * Get all available languages
 * @return array
 */
function getLanguages() {
    global $languages;
    return $languages;
}

/**
 * Get language switcher HTML
 * @return string
 */
function getLanguageSwitcher() {
    $current = getCurrentLanguage();
    $languages = getLanguages();
    $currentUrl = $_SERVER['REQUEST_URI'];
    $currentUrl = preg_replace('/[?&]lang=[^&]*/', '', $currentUrl);
    $separator = strpos($currentUrl, '?') !== false ? '&' : '?';
    
    $html = '<div class="language-switcher">';
    foreach ($languages as $code => $info) {
        $active = $current === $code ? 'active' : '';
        $url = $currentUrl . $separator . 'lang=' . $code;
        $html .= '<a href="' . $url . '" class="lang-link ' . $active . '" title="' . $info['name'] . '">';
        $html .= strtoupper($code);
        $html .= '</a>';
    }
    $html .= '</div>';
    
    return $html;
}