#!/bin/bash

# INMACOM Water Management System - Installation Script
# This script helps set up the INMACOM system with security enhancements

set -e

echo "========================================"
echo "INMACOM Water Management System Setup"
echo "Security-Enhanced Version 2.0"
echo "========================================"
echo

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root for security reasons."
   exit 1
fi

# Check if we're in the correct directory
if [[ ! -f "inmacom.net/public_html/config/database.php" ]]; then
    print_error "Please run this script from the INMACOM project root directory."
    print_error "Expected file: inmacom.net/public_html/config/database.php"
    exit 1
fi

print_info "Starting INMACOM installation and security setup..."
echo

# Step 1: Check system requirements
print_info "Step 1: Checking system requirements..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;" 2>/dev/null || echo "not found")
if [[ "$PHP_VERSION" == "not found" ]]; then
    print_error "PHP is not installed. Please install PHP 7.4 or higher."
    exit 1
fi

REQUIRED_VERSION="7.4"
if [[ $(echo -e "$PHP_VERSION\n$REQUIRED_VERSION" | sort -V | head -n1) != "$REQUIRED_VERSION" ]]; then
    print_error "PHP version $PHP_VERSION is too old. Please upgrade to PHP 7.4 or higher."
    exit 1
fi

print_info "PHP version: $PHP_VERSION ✓"

# Check required PHP extensions
REQUIRED_EXTENSIONS=("pdo_mysql" "mbstring" "json" "session")
for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -q "$ext"; then
        print_info "PHP extension $ext: ✓"
    else
        print_error "Required PHP extension '$ext' is not installed."
        exit 1
    fi
done

# Step 2: Database configuration
print_info "Step 2: Database configuration..."
echo "Please provide database connection details:"

read -p "Database host [localhost]: " DB_HOST
DB_HOST=${DB_HOST:-localhost}

read -p "Database name [inmacom_db]: " DB_NAME
DB_NAME=${DB_NAME:-inmacom_db}

read -p "Database username [inmacom_db]: " DB_USER
DB_USER=${DB_USER:-inmacom_db}

# Prompt for password securely
echo -n "Database password: "
read -s DB_PASS
echo

# Test database connection
print_info "Testing database connection..."
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME;" 2>/dev/null
if [[ $? -eq 0 ]]; then
    print_info "Database connection: ✓"
else
    print_error "Database connection failed. Please check your credentials."
    exit 1
fi

# Step 3: Set up web directory
print_info "Step 3: Setting up web directory..."

# Determine web root
if [[ -d "/var/www/html" ]]; then
    WEB_ROOT="/var/www/html"
elif [[ -d "/usr/share/nginx/html" ]]; then
    WEB_ROOT="/usr/share/nginx/html"
else
    read -p "Enter web root directory [/var/www/html]: " WEB_ROOT
    WEB_ROOT=${WEB_ROOT:-/var/www/html}
fi

print_info "Using web root: $WEB_ROOT"

# Copy files (if not already in web root)
if [[ "$PWD/inmacom.net/public_html" != "$WEB_ROOT" ]]; then
    print_info "Copying application files to web root..."
    
    # Ask for confirmation
    print_warning "This will copy files to $WEB_ROOT. Continue? (y/n)"
    read -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_info "Installation cancelled."
        exit 0
    fi
    
    sudo cp -r inmacom.net/public_html/* "$WEB_ROOT/"
    print_info "Files copied successfully."
fi

# Step 4: Set permissions
print_info "Step 4: Setting file permissions..."

# Set basic permissions
sudo chown -R www-data:www-data "$WEB_ROOT"
sudo chmod -R 755 "$WEB_ROOT"

# Secure sensitive directories
sudo chmod -R 750 "$WEB_ROOT/config"
sudo chmod -R 750 "$WEB_ROOT/scripts"

# Make uploads directory writable
sudo chmod -R 777 "$WEB_ROOT/uploads" 2>/dev/null || true
sudo chmod -R 777 "$WEB_ROOT/data" 2>/dev/null || true

print_info "File permissions set successfully."

# Step 5: Environment configuration
print_info "Step 5: Configuring environment..."

# Create environment configuration
ENV_FILE="$WEB_ROOT/.env"
cat > "$ENV_FILE" << EOF
# INMACOM Environment Configuration
INMACOM_ENV=production
DB_HOST=$DB_HOST
DB_NAME=$DB_NAME
DB_USER=$DB_USER
DB_PASS=$DB_PASS

# Security settings
SESSION_TIMEOUT=1800
RATE_LIMIT_LOGIN=5
RATE_LIMIT_WINDOW=300
EOF

# Secure the environment file
sudo chmod 640 "$ENV_FILE"
sudo chown www-data:www-data "$ENV_FILE"

print_info "Environment configuration created."

# Step 6: Password migration
print_info "Step 6: Running password migration..."

cd "$WEB_ROOT"

# Check if migration is needed
if php -r "
define('INMACOM_ACCESS', true);
require_once 'config/database.php';
try {
    \$pdo = getSecureDbConnection();
    \$stmt = \$pdo->prepare('SELECT COUNT(*) FROM users WHERE password NOT LIKE \"\$2y\$%\"');
    \$stmt->execute();
    \$count = \$stmt->fetchColumn();
    if (\$count > 0) {
        echo 'needed';
    } else {
        echo 'not_needed';
    }
} catch (Exception \$e) {
    echo 'error';
}
" | grep -q "needed"; then
    print_info "Password migration needed. Running migration..."
    php scripts/migrate_passwords.php
    print_info "Password migration completed."
else
    print_info "Password migration not needed (passwords already hashed)."
fi

# Step 7: Security tests
print_info "Step 7: Running security tests..."

if php scripts/test_security.php | grep -q "All core security functions are working properly"; then
    print_info "Security tests: ✓"
else
    print_warning "Security tests show some issues. Please check manually."
fi

# Step 8: Web server configuration
print_info "Step 8: Web server configuration recommendations..."

print_warning "Please ensure your web server is configured with:"
echo "  • SSL/HTTPS enabled"
echo "  • Security headers configured"
echo "  • Directory indexing disabled"
echo "  • PHP version display disabled"
echo

print_info "Example Apache virtual host configuration:"
cat << 'EOF'
<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/html
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Security headers
    Header always set X-Frame-Options DENY
    Header always set X-Content-Type-Options nosniff
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000"
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

# Step 9: Final steps
print_info "Step 9: Final configuration steps..."

print_warning "IMPORTANT: Please complete these manual steps:"
echo
echo "1. Change default user passwords:"
echo "   • admin@inmacom.test (current: admin123)"
echo "   • datamanager@inmacom.test (current: data123)"  
echo "   • user@inmacom.test (current: user123)"
echo
echo "2. Configure SSL certificate for your domain"
echo "3. Set up database backups"
echo "4. Configure monitoring and logging"
echo "5. Test the installation by accessing your website"
echo

# Installation summary
echo "========================================"
print_info "INSTALLATION COMPLETED SUCCESSFULLY!"
echo "========================================"
echo
echo "Installation Summary:"
echo "• Web root: $WEB_ROOT"
echo "• Database: $DB_NAME on $DB_HOST"
echo "• Environment: Production"
echo "• Security: Enhanced (v2.0)"
echo
echo "Access your INMACOM system at:"
echo "https://yourdomain.com/login.php"
echo
print_warning "Remember to:"
echo "• Change default passwords immediately"
echo "• Configure HTTPS/SSL"
echo "• Set up regular backups"
echo "• Monitor security logs"
echo

print_info "For detailed configuration options, see:"
echo "• README.md - Complete documentation"
echo "• SECURITY_CONFIGURATION.md - Security guide"
echo
print_info "Installation script completed successfully!"