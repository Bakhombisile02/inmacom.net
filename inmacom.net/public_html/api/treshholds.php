<?php
require_once '../includes/secure_init.php';

// Require authentication for all operations
requireAuth();

try {
    $pdo = getSecureDbConnection();
    $user_id = $_SESSION['user_id'];
    
    if (isset($_POST['getdata'])) {
        // Get threshold data
        $stmt = $pdo->prepare("SELECT * FROM quality_compliance ORDER BY station_id, parameter");
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        jsonResponse('successful', 'Successfully got data', $results);
        
    } else if (isset($_POST['getrecord'])) {
        // Get specific record
        $id = validateInput($_POST['id'], 'int', ['min' => 1]);
        
        $stmt = $pdo->prepare("SELECT * FROM quality_compliance WHERE id = ?");
        $stmt->execute([$id]);
        $results = $stmt->fetchAll();
        
        jsonResponse('successful', 'Successfully got data', $results);
        
    } else if (isset($_POST['delete'])) {
        // Delete threshold - require admin role
        requireRole(['admin']);
        
        $id = validateInput($_POST['id'], 'int', ['min' => 1]);
        
        $stmt = $pdo->prepare("DELETE FROM quality_compliance WHERE id = ?");
        if ($stmt->execute([$id])) {
            logSecurityEvent('threshold_deleted', ['id' => $id, 'user_id' => $user_id]);
            jsonResponse('successful', 'Successfully deleted!');
        } else {
            jsonResponse('error', 'Failed to delete data');
        }
        
    } else if (isset($_POST['edit'])) {
        // Update threshold - require admin role
        requireRole(['admin']);
        
        $id = validateInput($_POST['id'], 'int', ['min' => 1]);
        $station_id = validateInput($_POST['station_id'], 'station_code');
        $min = validateInput($_POST['min'], 'float');
        $max = validateInput($_POST['max'], 'float');
        $parameter = validateInput($_POST['parameter'], 'string', ['max_length' => 100]);
        $unit = validateInput($_POST['unit'], 'string', ['max_length' => 25]);
        
        // Validate that min <= max
        if ($min > $max) {
            jsonResponse('failed', 'Minimum value cannot be greater than maximum value');
        }
        
        $stmt = $pdo->prepare("
            UPDATE quality_compliance 
            SET station_id = ?, min = ?, max = ?, unit = ?, parameter = ? 
            WHERE id = ?
        ");
        
        if ($stmt->execute([$station_id, $min, $max, $unit, $parameter, $id])) {
            logSecurityEvent('threshold_updated', [
                'id' => $id,
                'station_id' => $station_id,
                'parameter' => $parameter,
                'user_id' => $user_id
            ]);
            jsonResponse('success', 'Successfully Updated');
        } else {
            jsonResponse('failed', 'Failed to update threshold');
        }
        
    } else if (isset($_REQUEST['station'])) {
        // Add new threshold - require admin role
        requireRole(['admin']);
        
        $station = validateInput($_REQUEST['station'], 'station_code');
        $min = validateInput($_REQUEST['min'], 'float');
        $max = validateInput($_REQUEST['max'], 'float');
        $unit = validateInput($_REQUEST['unit'], 'string', ['max_length' => 25]);
        $parameter = validateInput($_REQUEST['parameter'], 'string', ['max_length' => 100]);
        
        // Validate that min <= max
        if ($min > $max) {
            jsonResponse('failed', 'Minimum value cannot be greater than maximum value');
        }
        
        // Check if threshold already exists for this station/parameter combination
        $check_stmt = $pdo->prepare("
            SELECT id FROM quality_compliance 
            WHERE station_id = ? AND parameter = ?
        ");
        $check_stmt->execute([$station, $parameter]);
        
        if ($check_stmt->fetch()) {
            jsonResponse('failed', 'Threshold already exists for this station and parameter. Use edit instead.');
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO quality_compliance (station_id, min, max, unit, parameter) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$station, $min, $max, $unit, $parameter])) {
            logSecurityEvent('threshold_added', [
                'station_id' => $station,
                'parameter' => $parameter,
                'min' => $min,
                'max' => $max,
                'user_id' => $user_id
            ]);
            jsonResponse('success', 'Successfully Saved');
        } else {
            jsonResponse('failed', 'Failed to add threshold');
        }
        
    } else {
        jsonResponse('failed', 'Invalid request');
    }
    
} catch (Exception $e) {
    error_log("Thresholds API error: " . $e->getMessage());
    logSecurityEvent('api_error', ['endpoint' => 'thresholds', 'error' => $e->getMessage()]);
    jsonResponse('failed', 'An error occurred while processing your request');
}