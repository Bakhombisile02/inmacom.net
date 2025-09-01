# INMACOM Water Management Information System

[![Security](https://img.shields.io/badge/Security-Enhanced-green.svg)](https://shields.io/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4+-blue.svg)](https://php.net)
[![Database](https://img.shields.io/badge/Database-MySQL-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

A comprehensive water management information system for monitoring water resources, dam levels, flow rates, water quality, rainfall, and groundwater data across multiple monitoring stations.

## 🌊 Overview

The INMACOM Water Management Information System (WMIS) is a web-based platform designed to collect, store, analyze, and visualize water resource data from various monitoring stations. The system supports multiple data types and provides role-based access control for different user categories.

### Key Features

- **Multi-Station Monitoring**: Support for dam levels, flow rates, water quality, rainfall, and groundwater monitoring
- **Role-Based Access Control**: Admin, Data Manager, and User roles with appropriate permissions
- **Real-Time Data Management**: Import, export, and manage water resource data
- **Compliance Monitoring**: Automated threshold monitoring and alerting
- **Document Management**: Organized file storage and retrieval system
- **Data Visualization**: Charts and graphs for data analysis
- **Security Enhanced**: Modern security features including encrypted passwords, SQL injection protection, and session security

## 🔒 Security Features

### Recently Enhanced Security (v2.0)

- **Secure Password Hashing**: Uses PHP's `password_hash()` with bcrypt
- **SQL Injection Prevention**: All database queries use prepared statements
- **Session Security**: HttpOnly, Secure cookies with session timeout
- **CSRF Protection**: Token-based cross-site request forgery protection
- **Input Validation**: Comprehensive input sanitization and validation
- **Rate Limiting**: Protection against brute force attacks
- **XSS Prevention**: Output encoding and Content Security Policy
- **Error Handling**: Secure error messages without information disclosure
- **Security Logging**: Comprehensive audit trail for security events
- **Environment-Based Configuration**: Separate development and production settings

### Access Control

- **Authentication Required**: All API endpoints require valid authentication
- **Role-Based Permissions**: Data access restricted based on user roles
- **Station-Level Access**: Data managers can only access assigned stations
- **Session Management**: Automatic session timeout and regeneration

## 📋 System Requirements

### Minimum Requirements

- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: Version 7.4 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Storage**: 1GB minimum (varies with data volume)
- **Memory**: 512MB RAM minimum
- **Network**: Internet connection for external integrations

### Recommended Requirements

- **Web Server**: Apache 2.4+ with mod_rewrite
- **PHP**: Version 8.0+ with extensions:
  - `pdo_mysql`
  - `mbstring`
  - `json`
  - `session`
  - `filter`
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **Storage**: 5GB+ for production use
- **Memory**: 2GB+ RAM
- **SSL Certificate**: For HTTPS encryption

## 🚀 Installation Guide

### 1. Prerequisites Setup

#### Install Required Software

**On Ubuntu/Debian:**
```bash
# Update package list
sudo apt update

# Install Apache, PHP, and MySQL
sudo apt install apache2 php php-mysql php-mbstring php-json php-session mysql-server

# Install Composer (optional, for dependency management)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**On CentOS/RHEL:**
```bash
# Install Apache, PHP, and MySQL
sudo yum install httpd php php-mysql php-mbstring php-json mysql-server

# Start services
sudo systemctl start httpd mysql
sudo systemctl enable httpd mysql
```

### 2. Database Setup

#### Create Database and User

```sql
-- Connect to MySQL as root
mysql -u root -p

-- Create database
CREATE DATABASE inmacom_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (replace with secure password)
CREATE USER 'inmacom_db'@'localhost' IDENTIFIED BY 'SecurePassword123!';

-- Grant permissions
GRANT ALL PRIVILEGES ON inmacom_db.* TO 'inmacom_db'@'localhost';
FLUSH PRIVILEGES;

-- Exit MySQL
EXIT;
```

#### Import Database Schema

```bash
# Import the database structure and sample data
mysql -u inmacom_db -p inmacom_db < u550237388_inmacom_db1-3.sql
```

### 3. Application Installation

#### Clone/Download Application

```bash
# Clone from repository
git clone https://github.com/Bakhombisile02/inmacom.net.git

# Or download and extract ZIP file
# Navigate to web root directory
cd /var/www/html

# Copy application files
sudo cp -r /path/to/inmacom.net/inmacom.net/public_html/* .

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 777 uploads/
sudo chmod -R 777 data/
```

#### Configure Database Connection

Edit the database configuration in `/config/database.php`:

```php
// For production, use environment variables
'production' => [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'database' => getenv('DB_NAME') ?: 'inmacom_db',
    'username' => getenv('DB_USER') ?: 'inmacom_db',
    'password' => getenv('DB_PASS') ?: 'YourSecurePassword',
    // ... other settings
]
```

#### Set Environment Variables (Production)

Create a `.env` file or set server environment variables:

```bash
# Database configuration
export INMACOM_ENV=production
export DB_HOST=localhost
export DB_NAME=inmacom_db
export DB_USER=inmacom_db
export DB_PASS=YourSecurePassword
```

### 4. Security Configuration

#### Run Password Migration (First Time Only)

```bash
# Navigate to application directory
cd /var/www/html

# Run password migration for existing users
php scripts/migrate_passwords.php
```

#### Configure SSL/HTTPS (Recommended)

```bash
# Install SSL certificate (example with Let's Encrypt)
sudo apt install certbot python3-certbot-apache

# Obtain certificate
sudo certbot --apache -d yourdomain.com

# Verify auto-renewal
sudo certbot renew --dry-run
```

#### Web Server Configuration

**Apache Virtual Host Example:**

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html
    
    # Redirect HTTP to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/html
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Security headers
    Header always set X-Frame-Options DENY
    Header always set X-Content-Type-Options nosniff
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    
    # Hide server information
    ServerTokens Prod
    ServerSignature Off
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5. Final Configuration

#### Test Installation

```bash
# Test security functions
php scripts/test_security.php

# Check file permissions
ls -la /var/www/html/uploads/
ls -la /var/www/html/config/

# Test web server access
curl -I http://localhost/
```

#### Create Admin User

Access the system at `https://yourdomain.com/register.php` or through the database:

```sql
INSERT INTO users (first_name, last_name, email, password, role, email_verified) 
VALUES ('Admin', 'User', 'admin@yourdomain.com', 'HashedPasswordHere', 'admin', 1);
```

## 🔧 Configuration

### Environment Configuration

The system supports two environments:

#### Development Environment
```php
// Automatically detected as default
$environment = 'development';

// Features:
- Detailed error reporting
- Debug information
- Relaxed security headers
- Local database credentials
```

#### Production Environment
```bash
# Set environment variable
export INMACOM_ENV=production

# Features:
- Error reporting disabled
- Secure headers enabled
- Environment-based credentials
- Enhanced security measures
```

### Database Configuration Options

```php
// Development settings
'development' => [
    'host' => 'localhost',
    'database' => 'inmacom_db',
    'username' => 'inmacom_db',
    'password' => 'AccessInmacom',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
],

// Production settings (uses environment variables)
'production' => [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'database' => getenv('DB_NAME') ?: 'inmacom_db',
    'username' => getenv('DB_USER') ?: 'inmacom_db',
    'password' => getenv('DB_PASS') ?: 'SecurePassword',
    // ... same options as development
]
```

## 👥 User Management

### User Roles

#### Administrator (`admin`)
- Full system access
- User management capabilities
- All station data access
- System configuration
- Data import/export
- Threshold management

#### Data Manager (`data_manager`)
- Assigned station access only
- Data entry and modification
- Basic reporting features
- Cannot manage users
- Limited system settings access

#### Regular User (`user`)
- Read-only access to assigned stations
- Basic reporting capabilities
- No data modification privileges
- No administrative functions

### Default Users

The system includes test users for initial setup:

| Email | Role | Default Password |
|-------|------|------------------|
| admin@inmacom.test | admin | admin123 |
| datamanager@inmacom.test | data_manager | data123 |
| user@inmacom.test | user | user123 |

**⚠️ Important**: Change default passwords immediately in production!

## 📊 Data Management

### Station Categories

The system supports monitoring data from various station types:

#### 1. Dam Levels
- **Purpose**: Monitor water reservoir levels
- **Data Points**: Full Supply Capacity (FSC), Current Level, Date/Time
- **Units**: Cubic meters, percentage full
- **Compliance**: Automatic threshold monitoring

#### 2. Flow Levels
- **Purpose**: Monitor river and stream flow rates
- **Data Points**: Flow rate, Date/Time
- **Units**: Cubic meters per second (m³/s)
- **Applications**: Flood monitoring, water allocation

#### 3. Water Quality
- **Purpose**: Monitor water quality parameters
- **Data Points**: Various parameters (pH, turbidity, dissolved oxygen, etc.)
- **Units**: Parameter-specific units
- **Compliance**: Quality threshold monitoring

#### 4. Rainfall
- **Purpose**: Monitor precipitation data
- **Data Points**: Rainfall amount, Date/Time
- **Units**: Millimeters (mm)
- **Applications**: Weather monitoring, flood prediction

#### 5. Groundwater
- **Purpose**: Monitor groundwater levels
- **Data Points**: Water level depth, Date/Time
- **Units**: Meters below ground level
- **Applications**: Drought monitoring, aquifer management

### Data Import/Export

#### Supported Formats
- **CSV**: Comma-separated values
- **Excel**: .xlsx and .xls formats
- **JSON**: API data exchange
- **XML**: Legacy system integration

#### Import Process
1. Navigate to Data Manager section
2. Select station category
3. Choose file format
4. Upload data file
5. Review and validate data
6. Confirm import

#### Export Options
- **Date Range**: Specify start and end dates
- **Station Filter**: Select specific stations
- **Format Selection**: Choose output format
- **Data Filtering**: Apply quality filters

## 🔍 API Documentation

### Authentication

All API endpoints require authentication via session cookies. Include session cookie in requests:

```javascript
// Example AJAX request
$.ajax({
    url: '/api/stations.php',
    method: 'POST',
    data: { stations: 'all' },
    success: function(response) {
        console.log(response);
    }
});
```

### Core Endpoints

#### Authentication Endpoints

**Login** - `POST /api/login.php`
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

Response:
```json
{
    "type": "success",
    "text": "Login successful",
    "data": {
        "role": "admin"
    }
}
```

**Logout** - `POST /api/logout.php`
```json
{
    "type": "success",
    "text": "Logged out successfully"
}
```

#### Station Management

**Get Stations** - `POST /api/stations.php`
```json
{
    "stations": "all"    // or "datamanager" for assigned stations only
}
```

**Get Stations by Category** - `POST /api/stations.php`
```json
{
    "station_cat": "Dam Levels"  // Dam Levels, Flow Levels, Water Quality, Rainfall, Groundwater
}
```

#### Dam Level Data

**Get Dam Level Data** - `POST /api/dam-level-data.php`
```json
{
    "getdata": "all"    // or "datamanager" for user's stations
}
```

**Add Dam Level Data** - `POST /api/dam-level-data.php`
```json
{
    "station": "DAM001",
    "fsc": "100.5",
    "storage": "75.2",
    "date_time": "2024-01-15 10:30:00"
}
```

**Update Dam Level Data** - `POST /api/dam-level-data.php`
```json
{
    "updaterecord": "true",
    "id": "123",
    "station_id": "DAM001",
    "fsc": "100.5",
    "storage": "76.8"
}
```

**Delete Dam Level Data** - `POST /api/dam-level-data.php`
```json
{
    "delete": "true",
    "id": "123"
}
```

### Error Responses

All API endpoints return standardized error responses:

```json
{
    "type": "failed",
    "text": "Error message description"
}
```

Common error scenarios:
- `Authentication required` - User not logged in
- `Insufficient permissions` - User lacks required role
- `Invalid input data` - Validation failed
- `Database error` - Server-side error occurred

### Rate Limiting

API endpoints implement rate limiting to prevent abuse:

- **Login attempts**: 5 attempts per 5 minutes
- **Data modifications**: 100 requests per hour
- **Data retrieval**: 1000 requests per hour

Rate limit responses:
```json
{
    "type": "failed",
    "text": "Too many requests. Please try again later."
}
```

## 🏗️ Development Guide

### Development Environment Setup

1. **Clone Repository**
```bash
git clone https://github.com/Bakhombisile02/inmacom.net.git
cd inmacom.net/inmacom.net/public_html
```

2. **Install Dependencies**
```bash
# If using Composer
composer install

# For frontend dependencies (if applicable)
npm install
```

3. **Configure Development Database**
```bash
# Create local database
mysql -u root -p -e "CREATE DATABASE inmacom_dev;"

# Import schema
mysql -u root -p inmacom_dev < database.sql
```

4. **Start Development Server**
```bash
# PHP built-in server
php -S localhost:8080

# Or use Apache/Nginx
```

### Code Structure

```
public_html/
├── config/
│   ├── database.php      # Database configuration
│   └── security.php      # Security utilities
├── includes/
│   ├── secure_init.php   # Security bootstrap
│   ├── auth_session.php  # Session authentication
│   ├── header.php        # Common header
│   └── footer.php        # Common footer
├── api/
│   ├── login.php         # Authentication API
│   ├── stations.php      # Station management API
│   ├── dam-level-data.php # Dam level data API
│   └── ...               # Other API endpoints
├── admin/
│   ├── index.php         # Admin dashboard
│   └── ...               # Admin functionality
├── data-manager/
│   └── ...               # Data manager interface
├── assets/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── img/              # Images
├── scripts/
│   ├── migrate_passwords.php # Password migration
│   └── test_security.php     # Security testing
└── uploads/              # User uploaded files
```

### Security Best Practices

1. **Always Use Prepared Statements**
```php
// Good
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// Bad - Never do this
$query = "SELECT * FROM users WHERE email = '$email'";
```

2. **Validate All Input**
```php
// Use the validation functions
$email = validateInput($_POST['email'], 'email');
$station = validateInput($_POST['station'], 'station_code');
$value = validateInput($_POST['value'], 'float');
```

3. **Check Authentication and Authorization**
```php
// Require authentication
requireAuth();

// Require specific role
requireRole(['admin', 'data_manager']);

// Check permissions
if (!hasRole('admin')) {
    jsonResponse('failed', 'Access denied');
}
```

4. **Use Security Headers**
```php
// Already included in secure_init.php
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
```

### Testing

#### Run Security Tests
```bash
php scripts/test_security.php
```

#### Database Testing
```bash
# Test database connection
php -r "require 'includes/secure_init.php'; $pdo = getSecureDbConnection(); echo 'Connected successfully';"
```

#### API Testing
```bash
# Test API endpoint
curl -X POST http://localhost:8080/api/stations.php \
  -H "Content-Type: application/json" \
  -d '{"stations":"all"}' \
  --cookie-jar cookies.txt
```

### Contributing

1. **Fork the repository**
2. **Create feature branch**: `git checkout -b feature/new-feature`
3. **Follow security guidelines**
4. **Test thoroughly**
5. **Submit pull request**

## 🚀 Production Deployment

### Pre-Deployment Checklist

- [ ] SSL certificate installed and configured
- [ ] Database backups configured
- [ ] Environment variables set
- [ ] File permissions secured
- [ ] Error reporting disabled
- [ ] Security headers configured
- [ ] Default passwords changed
- [ ] Rate limiting configured

### Deployment Steps

1. **Prepare Production Server**
```bash
# Update system
sudo apt update && sudo apt upgrade

# Install required packages
sudo apt install apache2 php8.0 php8.0-mysql php8.0-mbstring mysql-server

# Configure firewall
sudo ufw enable
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
```

2. **Deploy Application**
```bash
# Upload files to server
rsync -avz --exclude '.git' local/path/ user@server:/var/www/html/

# Set permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 750 /var/www/html/config
```

3. **Configure Production Environment**
```bash
# Set environment variables
echo "export INMACOM_ENV=production" >> /etc/environment
echo "export DB_HOST=localhost" >> /etc/environment
echo "export DB_NAME=inmacom_prod" >> /etc/environment
echo "export DB_USER=inmacom_user" >> /etc/environment
echo "export DB_PASS=SecureProductionPassword" >> /etc/environment
```

4. **Setup Database**
```sql
CREATE DATABASE inmacom_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'inmacom_user'@'localhost' IDENTIFIED BY 'SecureProductionPassword';
GRANT ALL PRIVILEGES ON inmacom_prod.* TO 'inmacom_user'@'localhost';
FLUSH PRIVILEGES;
```

5. **Configure SSL and Security**
```bash
# Install SSL certificate
sudo certbot --apache -d yourdomain.com

# Configure security headers in Apache
sudo a2enmod headers
sudo systemctl restart apache2
```

### Monitoring and Maintenance

#### Log Monitoring
```bash
# Monitor Apache logs
sudo tail -f /var/log/apache2/access.log
sudo tail -f /var/log/apache2/error.log

# Monitor application logs
sudo tail -f /var/log/syslog | grep SECURITY
```

#### Database Maintenance
```bash
# Daily backup script
#!/bin/bash
mysqldump -u inmacom_user -p inmacom_prod > /backups/inmacom_$(date +%Y%m%d).sql
find /backups -name "inmacom_*.sql" -mtime +30 -delete
```

#### Performance Monitoring
- Monitor server resources (CPU, RAM, disk)
- Track database performance
- Monitor response times
- Check for security alerts

## 🔧 Troubleshooting

### Common Issues

#### Database Connection Issues

**Problem**: "Database connection failed"

**Solutions**:
1. Check database credentials in config
2. Verify MySQL service is running:
   ```bash
   sudo systemctl status mysql
   sudo systemctl start mysql
   ```
3. Test connection manually:
   ```bash
   mysql -u inmacom_db -p inmacom_db
   ```

#### Permission Errors

**Problem**: File upload or write errors

**Solutions**:
1. Check directory permissions:
   ```bash
   ls -la uploads/
   sudo chmod -R 777 uploads/
   ```
2. Verify web server user:
   ```bash
   sudo chown -R www-data:www-data /var/www/html
   ```

#### Login Issues

**Problem**: "Invalid credentials" with correct password

**Solutions**:
1. Check if passwords are migrated to hashed format
2. Run password migration script:
   ```bash
   php scripts/migrate_passwords.php
   ```
3. Verify user exists in database:
   ```sql
   SELECT email, role FROM users WHERE email = 'your@email.com';
   ```

#### Session Issues

**Problem**: "Authentication required" after login

**Solutions**:
1. Check session configuration in PHP
2. Verify session files directory exists and is writable
3. Check for secure cookie settings conflicts

### Debug Mode

Enable debug mode for troubleshooting:

```bash
# Set development environment
export INMACOM_ENV=development

# Or modify config temporarily
```

### Performance Issues

**Slow API Responses**:
1. Check database query performance
2. Add database indexes if needed
3. Monitor server resources
4. Enable PHP OpCache

**High Memory Usage**:
1. Optimize database queries
2. Limit result set sizes
3. Implement pagination
4. Check for memory leaks

### Security Issues

**Failed Login Attempts**:
- Check security logs for patterns
- Implement IP blocking if necessary
- Monitor for brute force attacks

**Suspicious Activity**:
- Review security event logs
- Check file integrity
- Verify user permissions

## 📞 Support and Contact

### Getting Help

1. **Documentation**: Check this README first
2. **Issues**: Submit GitHub issues for bugs
3. **Security**: Report security vulnerabilities privately
4. **Feature Requests**: Submit GitHub issues with enhancement label

### Reporting Security Issues

For security vulnerabilities, please contact:
- **Email**: security@inmacom.com (replace with actual contact)
- **Response Time**: Within 24 hours
- **Disclosure**: Responsible disclosure policy

### Contributing

We welcome contributions! Please:
1. Fork the repository
2. Create feature branches
3. Follow security guidelines
4. Submit pull requests
5. Include tests for new features

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Security Improvements**: Enhanced by GitHub Copilot
- **Original Development**: INMACOM Team
- **Database Design**: Water management domain experts
- **Testing**: Quality assurance team

---

## 📋 Quick Reference

### Default URLs
- **Login**: `/login.php`
- **Dashboard**: `/index.php`
- **Admin Panel**: `/admin/`
- **Data Manager**: `/data-manager/`

### Default Credentials (Change in Production!)
- **Admin**: admin@inmacom.test / admin123
- **Data Manager**: datamanager@inmacom.test / data123
- **User**: user@inmacom.test / user123

### Important Files
- **Configuration**: `/config/database.php`, `/config/security.php`
- **Security Bootstrap**: `/includes/secure_init.php`
- **Migration Script**: `/scripts/migrate_passwords.php`
- **Test Script**: `/scripts/test_security.php`

### Security Checklist
- [ ] Change default passwords
- [ ] Configure SSL/HTTPS
- [ ] Set environment variables
- [ ] Run password migration
- [ ] Test security functions
- [ ] Configure backups
- [ ] Monitor logs
- [ ] Update dependencies regularly

---

**⚠️ Important**: This system handles sensitive water resource data. Always follow security best practices and keep the system updated with the latest security patches.

For additional support or questions, please refer to the troubleshooting section or contact the development team.