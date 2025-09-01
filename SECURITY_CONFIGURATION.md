# INMACOM Security Configuration Guide

This document provides detailed security configuration instructions for the INMACOM Water Management Information System.

## 🔐 Security Implementation Overview

The INMACOM system now includes comprehensive security enhancements implemented in December 2024:

### Security Components

1. **Centralized Configuration** (`/config/database.php`)
   - Environment-based database settings
   - Secure connection management
   - Production vs development configurations

2. **Security Utilities** (`/config/security.php`)
   - Password hashing functions
   - Input validation and sanitization
   - CSRF token management
   - Session security
   - Rate limiting
   - Access control functions

3. **Security Bootstrap** (`/includes/secure_init.php`)
   - Automatic security header configuration
   - Session initialization
   - Environment detection
   - Security policy enforcement

## 🚀 Security Migration Steps

### Step 1: Update Existing Installations

If you have an existing INMACOM installation, follow these steps:

```bash
# 1. Backup your database
mysqldump -u username -p inmacom_db > backup_$(date +%Y%m%d).sql

# 2. Update application files
git pull origin main
# or download and replace files

# 3. Run password migration (IMPORTANT!)
cd /var/www/html
php scripts/migrate_passwords.php

# 4. Test security functions
php scripts/test_security.php

# 5. Update file permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 750 config/
```

### Step 2: Update Database Connection Files

Replace all old database connections with the new secure system:

**Old way (vulnerable):**
```php
$con = mysqli_connect("localhost", "username", "password", "database");
```

**New way (secure):**
```php
require_once '../includes/secure_init.php';
$pdo = getSecureDbConnection();
```

### Step 3: Update API Endpoints

All API endpoints should be updated to use the new security system:

```php
<?php
require_once '../includes/secure_init.php';

// Require authentication
requireAuth();

// For admin-only operations
requireRole(['admin']);

// For admin and data managers
requireRole(['admin', 'data_manager']);

try {
    $pdo = getSecureDbConnection();
    
    // Use prepared statements
    $stmt = $pdo->prepare("SELECT * FROM table WHERE id = ?");
    $stmt->execute([$id]);
    $results = $stmt->fetchAll();
    
    // Use secure JSON response
    jsonResponse('success', 'Data retrieved', $results);
    
} catch (Exception $e) {
    error_log("API error: " . $e->getMessage());
    logSecurityEvent('api_error', ['endpoint' => 'name']);
    jsonResponse('failed', 'An error occurred');
}
```

## 🛡️ Security Features Explained

### 1. Password Security

**Hashing Algorithm**: bcrypt (via PHP's `password_hash()`)
- Cost factor: 10 (default, automatically adjusted)
- Salt: Automatically generated and included
- Verification: `password_verify()` for constant-time comparison

**Migration Process**:
- Detects existing plaintext passwords
- Converts to bcrypt hashes
- Maintains user access with existing passwords
- Test users have predefined passwords for migration

### 2. SQL Injection Prevention

**Prepared Statements**: All database queries use PDO prepared statements
```php
// Secure query
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// Never do this (vulnerable)
$query = "SELECT * FROM users WHERE email = '$email'";
```

**Input Validation**: Comprehensive validation for all input types
```php
$email = validateInput($_POST['email'], 'email');
$station = validateInput($_POST['station'], 'station_code');
$value = validateInput($_POST['value'], 'float');
$date = validateInput($_POST['date'], 'date');
```

### 3. Session Security

**Configuration**: Automatic secure session settings
- `HttpOnly` cookies (prevents XSS access)
- `Secure` cookies (HTTPS only)
- `SameSite` protection
- Session timeout (30 minutes)
- Session ID regeneration

**Authentication Checks**:
```php
requireAuth(); // Redirects if not logged in
requireRole(['admin']); // Checks specific roles
```

### 4. Cross-Site Request Forgery (CSRF) Protection

**Token Generation**:
```php
$token = generateCSRFToken(); // Creates/returns session token
```

**Token Verification**:
```php
if (!verifyCSRFToken($_POST['csrf_token'])) {
    jsonResponse('failed', 'Security token validation failed');
}
```

### 5. Rate Limiting

**Implementation**: Session-based rate limiting
```php
if (!checkRateLimit('login', 5, 300)) { // 5 attempts in 5 minutes
    jsonResponse('failed', 'Too many attempts');
}
```

**Default Limits**:
- Login attempts: 5 per 5 minutes
- API requests: Configurable per endpoint

### 6. Input Validation and Sanitization

**Validation Types**:
- `email`: Email format validation
- `int`: Integer with optional min/max
- `float`: Floating point numbers
- `string`: String with XSS protection
- `station_code`: Alphanumeric codes
- `date`: Date/datetime validation

**XSS Prevention**: All string inputs are HTML-encoded
```php
$clean = validateInput($input, 'string'); // Automatically HTML-encoded
```

## 🔧 Configuration Options

### Environment Variables

Set these for production deployments:

```bash
# Required for production
export INMACOM_ENV=production
export DB_HOST=localhost
export DB_NAME=inmacom_prod
export DB_USER=inmacom_user
export DB_PASS=SecureProductionPassword

# Optional security settings
export SESSION_TIMEOUT=1800  # 30 minutes
export RATE_LIMIT_LOGIN=5    # Login attempts
export RATE_LIMIT_WINDOW=300 # Time window (seconds)
```

### Security Headers

Automatically set by `secure_init.php`:

```php
// Basic security headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Production-only Content Security Policy
if (getenv('INMACOM_ENV') === 'production') {
    header("Content-Security-Policy: default-src 'self'; ...");
}
```

### Database Security

**Connection Security**:
- UTF-8 character set
- Exception-based error handling
- Connection pooling via PDO
- Prepared statement enforcement

**User Permissions**: Grant minimal required permissions
```sql
-- Create dedicated database user
CREATE USER 'inmacom_app'@'localhost' IDENTIFIED BY 'StrongPassword';

-- Grant only necessary permissions
GRANT SELECT, INSERT, UPDATE, DELETE ON inmacom_db.* TO 'inmacom_app'@'localhost';

-- No administrative privileges
-- No access to other databases
```

## 🚨 Security Monitoring

### Logging

All security events are logged via `logSecurityEvent()`:

**Logged Events**:
- Login attempts (success/failure)
- Authentication failures
- Permission violations
- Rate limit violations
- API errors
- Data modifications
- Administrative actions

**Log Format**:
```json
{
    "timestamp": "2024-01-15 10:30:00",
    "event": "login_failed",
    "ip": "192.168.1.100",
    "user_agent": "Mozilla/5.0...",
    "user_id": null,
    "data": {
        "email": "user@example.com",
        "reason": "invalid_password"
    }
}
```

### Monitoring Recommendations

1. **Log Analysis**: Monitor security logs for patterns
2. **Failed Logins**: Watch for brute force attempts
3. **Permission Violations**: Check for privilege escalation attempts
4. **Database Access**: Monitor unusual query patterns
5. **File Access**: Check for unauthorized file access

## ⚠️ Security Warnings

### Critical Security Items

1. **Change Default Passwords**: Update all test user passwords
2. **Enable HTTPS**: Never run production without SSL/TLS
3. **Database Credentials**: Use strong, unique passwords
4. **File Permissions**: Restrict access to configuration files
5. **Error Reporting**: Disable in production environments
6. **Regular Updates**: Keep PHP and dependencies current

### Common Vulnerabilities Prevented

✅ **SQL Injection**: Prevented by prepared statements
✅ **XSS**: Prevented by input encoding
✅ **CSRF**: Prevented by token validation
✅ **Session Fixation**: Prevented by ID regeneration
✅ **Brute Force**: Prevented by rate limiting
✅ **Password Attacks**: Prevented by bcrypt hashing
✅ **Information Disclosure**: Prevented by error handling

## 🔍 Testing Security

### Security Test Script

Run the included security tests:
```bash
cd /var/www/html
php scripts/test_security.php
```

### Manual Testing

1. **Password Hashing**: Verify passwords are hashed in database
2. **SQL Injection**: Test with malicious input
3. **Session Security**: Check session cookie settings
4. **CSRF Protection**: Test with missing tokens
5. **Rate Limiting**: Test multiple rapid requests
6. **Input Validation**: Test with invalid data types

### Penetration Testing

Consider professional security testing for production:
- **OWASP Testing**: Follow OWASP testing guidelines
- **Automated Scanning**: Use tools like OWASP ZAP
- **Code Review**: Static analysis for security issues
- **Infrastructure Testing**: Network and server security

## 📞 Security Support

### Reporting Security Issues

For security vulnerabilities:
1. **Do not** create public GitHub issues
2. **Contact**: Send details to security team privately
3. **Include**: Steps to reproduce, impact assessment
4. **Response**: Expect acknowledgment within 24 hours

### Security Updates

Stay informed about security updates:
- Monitor repository for security patches
- Subscribe to security notifications
- Review PHP security advisories
- Update dependencies regularly

---

**Note**: This security implementation represents a significant upgrade from the previous version. Take time to understand the new security model and test thoroughly before deploying to production.