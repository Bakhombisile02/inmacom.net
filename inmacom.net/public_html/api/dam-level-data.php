<?php
require_once '../includes/secure_init.php';

// Require authentication for all operations
requireAuth();

try {
    $pdo = getSecureDbConnection();
    $user_id = $_SESSION['user_id'];
    
    if (isset($_POST['getdata'])) {
        if ($_POST['getdata'] === 'datamanager') {
            $stmt = $pdo->prepare("
                SELECT user_stations.user_id, station.code, station.name, station.category, dam_levels.* 
                FROM station 
                INNER JOIN user_stations ON station.code = user_stations.station_id 
                INNER JOIN dam_levels ON dam_levels.station_id = user_stations.station_id 
                WHERE user_id = ?
                ORDER BY dam_levels.date DESC
            ");
            $stmt->execute([$user_id]);
        } else {
            $stmt = $pdo->prepare("
                SELECT dam_levels.*, station.name 
                FROM dam_levels 
                INNER JOIN station ON dam_levels.station_id = station.code
                ORDER BY dam_levels.date DESC
            ");
            $stmt->execute();
        }
        
        $results = $stmt->fetchAll();
        jsonResponse('successful', 'Successfully got data', $results);
        
    } else if (isset($_POST['delete'])) {
        // Check if user has permission to delete
        requireRole(['admin', 'data_manager']);
        
        $id = validateInput($_POST['id'], 'int', ['min' => 1]);
        
        $stmt = $pdo->prepare("DELETE FROM dam_levels WHERE id = ?");
        if ($stmt->execute([$id])) {
            logSecurityEvent('dam_level_deleted', ['id' => $id, 'user_id' => $user_id]);
            jsonResponse('successful', 'Successfully deleted!');
        } else {
            jsonResponse('error', 'Failed to delete data');
        }
        
    } else if (isset($_REQUEST['station'])) {
        // Add new dam level data
        requireRole(['admin', 'data_manager']);
        
        $station = validateInput($_REQUEST['station'], 'station_code');
        $fsc = validateInput($_REQUEST['fsc'], 'float');
        $storage = validateInput($_REQUEST['storage'], 'float');
        $date_input = validateInput($_REQUEST['date_time'], 'date');
        
        // Verify station exists and user has access (for data managers)
        if ($_SESSION['role'] === 'data_manager') {
            $check_stmt = $pdo->prepare("
                SELECT 1 FROM user_stations 
                WHERE user_id = ? AND station_id = ?
            ");
            $check_stmt->execute([$user_id, $station]);
            if (!$check_stmt->fetch()) {
                jsonResponse('failed', 'Access denied to this station');
            }
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO dam_levels (station_id, fsc, value, date) 
            VALUES (?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$station, $fsc, $storage, $date_input])) {
            logSecurityEvent('dam_level_added', [
                'station' => $station,
                'value' => $storage,
                'user_id' => $user_id
            ]);
            jsonResponse('success', 'Successfully Saved');
        } else {
            jsonResponse('failed', 'Failed to save data');
        }
        
    } else if (isset($_POST['getrecord'])) {
        $id = validateInput($_POST['id'], 'int', ['min' => 1]);
        
        $stmt = $pdo->prepare("
            SELECT dam_levels.*, station.name 
            FROM dam_levels 
            INNER JOIN station ON dam_levels.station_id = station.code 
            WHERE dam_levels.id = ?
        ");
        $stmt->execute([$id]);
        $results = $stmt->fetchAll();
        
        jsonResponse('successful', 'Successfully got data', $results);
        
    } else if (isset($_REQUEST['updaterecord'])) {
        // Update existing record
        requireRole(['admin', 'data_manager']);
        
        $id = validateInput($_REQUEST['id'], 'int', ['min' => 1]);
        $station = validateInput($_REQUEST['station_id'], 'station_code');
        $fsc = validateInput($_REQUEST['fsc'], 'float');
        $storage = validateInput($_REQUEST['storage'], 'float');
        
        // Verify station access for data managers
        if ($_SESSION['role'] === 'data_manager') {
            $check_stmt = $pdo->prepare("
                SELECT 1 FROM user_stations 
                WHERE user_id = ? AND station_id = ?
            ");
            $check_stmt->execute([$user_id, $station]);
            if (!$check_stmt->fetch()) {
                jsonResponse('failed', 'Access denied to this station');
            }
        }
        
        $stmt = $pdo->prepare("
            UPDATE dam_levels 
            SET station_id = ?, fsc = ?, value = ? 
            WHERE id = ?
        ");
        
        if ($stmt->execute([$station, $fsc, $storage, $id])) {
            logSecurityEvent('dam_level_updated', [
                'id' => $id,
                'station' => $station,
                'value' => $storage,
                'user_id' => $user_id
            ]);
            jsonResponse('success', 'Successfully Saved');
        } else {
            jsonResponse('failed', 'Failed to update data');
        }
    } else {
        jsonResponse('failed', 'Invalid request');
    }
    
} catch (Exception $e) {
    error_log("Dam level API error: " . $e->getMessage());
    logSecurityEvent('api_error', ['endpoint' => 'dam_levels', 'error' => $e->getMessage()]);
    jsonResponse('failed', 'An error occurred while processing your request');
}
