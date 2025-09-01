# INMACOM Water Management Information System - Database Structure

## Overview
The INMACOM MIS database contains 11 core tables that manage water monitoring data, user accounts, station information, and document management for the water management system.

## Database Configuration

### Local Development Setup
- **Database Name**: `inmacom_db`
- **Username**: `inmacom_db`
- **Password**: `AccessInmacom`
- **Host**: `localhost`

### Connection Files Updated
All database connection files have been updated to use consistent local development credentials:
- `/public_html/includes/db.php` - Main database connection
- `/public_html/includes/pdo_db.php` - PDO connection
- `/public_html/admin/includes/db.php` - Admin module connection
- `/public_html/data-manager/includes/db.php` - Data manager connection
- All 39 API files in `/public_html/api/` directory

## Core Tables

### 1. `users` - User Account Management
```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','data_manager') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);
```

### 2. `station` - Monitoring Station Information
```sql
CREATE TABLE `station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `category` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
);
```

**Station Categories:**
- Dam Levels
- Flow Levels  
- Water Quality
- Rainfall
- Groundwater

### 3. `user_stations` - User-Station Associations
```sql
CREATE TABLE `user_stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `station_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `station_id` (`station_id`)
);
```

### 4. `dam_levels` - Dam Water Level Data
```sql
CREATE TABLE `dam_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` varchar(50) NOT NULL,
  `fsc` decimal(10,2) NOT NULL DEFAULT 0.00,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `station_id` (`station_id`),
  KEY `date` (`date`)
);
```

### 5. `flow_levels` - River Flow Data
```sql
CREATE TABLE `flow_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` varchar(50) NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(25) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `station_id` (`station_id`),
  KEY `date` (`date`)
);
```

### 6. `water_quality` - Water Quality Measurements
```sql
CREATE TABLE `water_quality` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` varchar(50) NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(25) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `station_id` (`station_id`),
  KEY `date` (`date`)
);
```

### 7. `rainfall` - Rainfall Data
```sql
CREATE TABLE `rainfall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` varchar(50) NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(25) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `station_id` (`station_id`),
  KEY `date` (`date`)
);
```

### 8. `groundwater` - Groundwater Level Data
```sql
CREATE TABLE `groundwater` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` varchar(50) NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(25) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `station_id` (`station_id`),
  KEY `date` (`date`)
);
```

### 9. `treshholds` - Threshold/Compliance Values
```sql
CREATE TABLE `treshholds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `min_value` decimal(10,2) DEFAULT NULL,
  `max_value` decimal(10,2) DEFAULT NULL,
  `unit` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `station_id` (`station_id`)
);
```

### 10. `documents` - Document Management
```sql
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `folder_id` (`folder_id`),
  KEY `uploaded_by` (`uploaded_by`)
);
```

### 11. `folder` - Document Folder Organization
```sql
CREATE TABLE `folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
);
```

## Sample Data Included

### Test Users
- **Admin User**: admin@inmacom.test (role: admin)
- **Data Manager**: datamanager@inmacom.test (role: data_manager)  
- **Regular User**: user@inmacom.test (role: user)

### Test Stations
- **Dam Levels**: Vaal Dam (DAM001), Hartbeespoort Dam (DAM002)
- **Flow Levels**: Vaal River at Orkney (FLOW001), Orange River at Aliwal North (FLOW002)
- **Water Quality**: Vaal River Quality Station (QUAL001), Orange River Quality Station (QUAL002)
- **Rainfall**: Johannesburg (RAIN001), Cape Town (RAIN002)
- **Groundwater**: Witwatersrand (GW001), Karoo (GW002)

### Sample Data Points
Each station category includes sample measurements with realistic values and timestamps for testing all system functionality.

## Application Status

✅ **Database**: Successfully created and populated
✅ **Web Server**: PHP development server running on port 8080
✅ **Database Connections**: All 42 database connection files updated
✅ **API Endpoints**: Tested and functional
✅ **User Interface**: Loading successfully with all navigation elements

## Next Steps

1. **User Authentication**: Test login functionality with sample users
2. **Module Testing**: Verify all data modules (dams, flow, quality, etc.)
3. **Admin Panel**: Test administrative functions
4. **Data Manager**: Test data import/export capabilities
5. **Document Management**: Test file upload and organization features

## Security Notes

- All database credentials have been updated for local development
- Production credentials are backed up in `/tmp/db_backup/`
- Password hashing is implemented using PHP's password_hash() function
- User roles control access to different system areas