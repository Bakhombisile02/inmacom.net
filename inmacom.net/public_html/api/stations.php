<?php
require_once '../includes/secure_init.php';

// Require authentication for all station operations
requireAuth();

try {
    $pdo = getSecureDbConnection();
    $user_id = $_SESSION['user_id'];
    
    // Default query
    $query = "SELECT * FROM `station` ORDER BY `code` ASC";
    $params = [];
    
    if (isset($_POST['stations'])) {
        if ($_POST['stations'] === 'datamanager') {
            $query = "SELECT station.*, user_stations.user_id FROM `station` 
                      INNER JOIN user_stations ON station.code = user_stations.station_id 
                      WHERE user_stations.user_id = ? 
                      ORDER BY `station`.`code` ASC";
            $params = [$user_id];
        } else {
            $query = "SELECT * FROM `station` ORDER BY `station`.`code` ASC";
        }
    } else if (isset($_POST['station_cat'])) {
        $category = validateInput($_POST['station_cat'], 'string', ['max_length' => 50]);
        $query = "SELECT * FROM `station` WHERE category = ? ORDER BY `station`.`code` ASC";
        $params = [$category];
    } else if (isset($_POST['dams'])) {
        if ($_POST['dams'] === 'datamanager') {
            $query = "SELECT station.*, user_stations.user_id FROM `station` 
                      INNER JOIN user_stations ON station.code = user_stations.station_id 
                      WHERE category = 'Dam Levels' AND user_stations.user_id = ? 
                      ORDER BY `station`.`code` ASC";
            $params = [$user_id];
        } else {
            $query = "SELECT * FROM `station` WHERE category = 'Dam Levels' ORDER BY `station`.`code` ASC";
        }
    } else if (isset($_POST['flow'])) {
        if ($_POST['flow'] === 'datamanager') {
            $query = "SELECT station.*, user_stations.user_id FROM `station` 
                      INNER JOIN user_stations ON station.code = user_stations.station_id 
                      WHERE category = 'Flow Levels' AND user_stations.user_id = ? 
                      ORDER BY `station`.`code` ASC";
            $params = [$user_id];
        } else {
            $query = "SELECT * FROM `station` WHERE category = 'Flow Levels' ORDER BY `station`.`code` ASC";
        }
    } else if (isset($_POST['quality'])) {
        if ($_POST['quality'] === 'datamanager') {
            $query = "SELECT station.*, user_stations.user_id FROM `station` 
                      INNER JOIN user_stations ON station.code = user_stations.station_id 
                      WHERE category = 'Water Quality' AND user_stations.user_id = ? 
                      ORDER BY `station`.`code` ASC";
            $params = [$user_id];
        } else {
            $query = "SELECT * FROM `station` WHERE category = 'Water Quality' ORDER BY `station`.`code` ASC";
        }
    } else if (isset($_POST['ground'])) {
        if ($_POST['ground'] === 'datamanager') {
            $query = "SELECT station.*, user_stations.user_id FROM `station` 
                      INNER JOIN user_stations ON station.code = user_stations.station_id 
                      WHERE category = 'Groundwater' AND user_stations.user_id = ? 
                      ORDER BY `station`.`code` ASC";
            $params = [$user_id];
        } else {
            $query = "SELECT * FROM `station` WHERE category = 'Groundwater' ORDER BY `station`.`code` ASC";
        }
    } else if (isset($_POST['rainfall'])) {
        if ($_POST['rainfall'] === 'datamanager') {
            $query = "SELECT station.*, user_stations.user_id FROM `station` 
                      INNER JOIN user_stations ON station.code = user_stations.station_id 
                      WHERE category = 'Rainfall' AND user_stations.user_id = ? 
                      ORDER BY `station`.`code` ASC";
            $params = [$user_id];
        } else {
            $query = "SELECT * FROM `station` WHERE category = 'Rainfall' ORDER BY `station`.`code` ASC";
        }
    } else if (isset($_POST['query_station'])) {
        $query_station = validateInput($_POST['query_station'], 'string', ['max_length' => 50]);
        $query = "SELECT * FROM `station` WHERE category = ? ORDER BY `station`.`code` ASC";
        $params = [$query_station];
    }
    
    // Execute query with prepared statement
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
    
    jsonResponse('success', 'Successfully got data', $results);
    
} catch (Exception $e) {
    error_log("Stations API error: " . $e->getMessage());
    logSecurityEvent('api_error', ['endpoint' => 'stations', 'error' => $e->getMessage()]);
    jsonResponse('failed', 'Error retrieving station data');
}