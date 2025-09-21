#!/bin/bash

# Build Script with Conditional Swagger Generation
# This script builds the application with or without Swagger based on environment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Helper functions
print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Configuration
ENVIRONMENT=${1:-development}
GENERATE_SWAGGER=${2:-false}

print_info "Building application for environment: $ENVIRONMENT"
print_info "Generate Swagger: $GENERATE_SWAGGER"

cd /opt/kesiswaan

# Step 1: Backend Build
print_info "Building backend..."

cd backend

# Install/Update dependencies
print_info "Installing backend dependencies..."
composer install --no-dev --optimize-autoloader

# Clear caches (with array cache driver to avoid Redis issues)
print_info "Clearing Laravel caches..."
CACHE_DRIVER=array php artisan cache:clear
CACHE_DRIVER=array php artisan config:clear
CACHE_DRIVER=array php artisan route:clear
CACHE_DRIVER=array php artisan view:clear

# Generate Swagger only if requested
if [ "$GENERATE_SWAGGER" = "true" ]; then
    print_info "Generating Swagger documentation..."
    php artisan l5-swagger:generate
    
    if [ $? -eq 0 ]; then
        print_success "Swagger documentation generated successfully"
    else
        print_warning "Swagger generation failed, continuing without it"
    fi
else
    print_info "Skipping Swagger generation for faster build"
fi

# Optimize for production
if [ "$ENVIRONMENT" = "production" ]; then
    print_info "Optimizing for production..."
    CACHE_DRIVER=array php artisan config:cache
    CACHE_DRIVER=array php artisan route:cache
    CACHE_DRIVER=array php artisan view:cache
fi

cd ..

# Step 2: Frontend Build
print_info "Building frontend..."

cd frontend

# Install/Update dependencies
print_info "Installing frontend dependencies..."
npm ci

# Build frontend
print_info "Building frontend assets..."
npm run build

if [ $? -eq 0 ]; then
    print_success "Frontend build completed successfully"
else
    print_error "Frontend build failed"
    exit 1
fi

cd ..

# Step 3: Final optimizations
print_info "Applying final optimizations..."

# Set proper permissions
chmod -R 755 backend/storage
chmod -R 755 backend/bootstrap/cache
chmod -R 755 frontend/dist

# Create symlink for storage
cd backend
if [ ! -L "public/storage" ]; then
    print_info "Creating storage symlink..."
    php artisan storage:link
fi
cd ..

print_success "Build completed successfully!"
print_info "Environment: $ENVIRONMENT"
print_info "Swagger Generated: $GENERATE_SWAGGER"

# Show build summary
echo ""
echo "📊 Build Summary:"
echo "  • Backend: Optimized for $ENVIRONMENT"
echo "  • Frontend: Built and minified"
echo "  • Swagger: $([ "$GENERATE_SWAGGER" = "true" ] && echo "Generated" || echo "Skipped")"
echo "  • Storage: Linked"
echo "  • Permissions: Set"

echo ""
echo "🚀 Ready for deployment!"
