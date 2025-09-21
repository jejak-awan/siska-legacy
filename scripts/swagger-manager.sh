#!/bin/bash

# Swagger Management Script
# This script helps manage Swagger documentation based on environment

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

# Function to enable Swagger for production
enable_swagger_production() {
    print_info "Enabling Swagger for production deployment..."
    
    cd /opt/kesiswaan/backend
    
    # Generate Swagger documentation
    print_info "Generating Swagger documentation..."
    php artisan l5-swagger:generate
    
    if [ $? -eq 0 ]; then
        print_success "Swagger documentation generated successfully"
    else
        print_error "Failed to generate Swagger documentation"
        exit 1
    fi
    
    # Clear cache
    print_info "Clearing application cache..."
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    
    print_success "Swagger enabled for production"
}

# Function to disable Swagger for development
disable_swagger_development() {
    print_info "Disabling Swagger for development..."
    
    cd /opt/kesiswaan/backend
    
    # Remove generated Swagger files
    print_info "Removing generated Swagger files..."
    rm -rf storage/api-docs/
    rm -rf public/docs/
    
    # Clear cache
    print_info "Clearing application cache..."
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    
    print_success "Swagger disabled for development"
}

# Function to switch to development mode
switch_to_dev() {
    print_info "Switching to development mode..."
    
    cd /opt/kesiswaan/backend
    
    # Use development routes
    if [ -f "routes/api-dev.php" ]; then
        print_info "Using development routes (no Swagger annotations)..."
        # You can implement route switching logic here
    fi
    
    # Set environment variables
    export SWAGGER_DEV_ENABLED=false
    export SWAGGER_SKIP_DEV_ANNOTATIONS=true
    
    print_success "Switched to development mode"
}

# Function to switch to production mode
switch_to_prod() {
    print_info "Switching to production mode..."
    
    cd /opt/kesiswaan/backend
    
    # Use production routes with Swagger
    print_info "Using production routes with Swagger annotations..."
    
    # Set environment variables
    export SWAGGER_DEV_ENABLED=true
    export SWAGGER_SKIP_DEV_ANNOTATIONS=false
    
    print_success "Switched to production mode"
}

# Function to generate minimal Swagger docs
generate_minimal_swagger() {
    print_info "Generating minimal Swagger documentation..."
    
    cd /opt/kesiswaan/backend
    
    # Create minimal swagger config
    cat > config/l5-swagger-minimal.php << 'EOF'
<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Sistem Kesiswaan API (Minimal)',
            ],
            'routes' => [
                'docs' => 'api/documentation',
                'oauth2_callback' => 'api/oauth2-callback',
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'annotations' => [
                    base_path('app/Http/Controllers/Api/DocumentationController.php'),
                ],
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'docs' => 'api/documentation',
            'oauth2_callback' => 'api/oauth2-callback',
        ],
        'controller' => \L5Swagger\Http\Controllers\SwaggerController::class,
        'view' => 'l5-swagger::index',
        'ui' => [
            'display' => [
                'dark_mode' => false,
                'doc_expansion' => 'none',
                'filter' => false,
            ],
        ],
    ],
];
EOF

    # Generate minimal documentation
    php artisan l5-swagger:generate --config=l5-swagger-minimal
    
    print_success "Minimal Swagger documentation generated"
}

# Function to show current status
show_status() {
    print_info "Swagger Status:"
    echo ""
    
    cd /opt/kesiswaan/backend
    
    if [ -d "storage/api-docs" ]; then
        print_success "Swagger documentation exists"
        echo "  • Location: storage/api-docs/"
        echo "  • Size: $(du -sh storage/api-docs/ | cut -f1)"
    else
        print_warning "No Swagger documentation found"
    fi
    
    if [ -f "routes/api-dev.php" ]; then
        print_success "Development routes available"
    else
        print_warning "Development routes not found"
    fi
    
    echo ""
    echo "Environment Variables:"
    echo "  • SWAGGER_DEV_ENABLED: ${SWAGGER_DEV_ENABLED:-not set}"
    echo "  • SWAGGER_SKIP_DEV_ANNOTATIONS: ${SWAGGER_SKIP_DEV_ANNOTATIONS:-not set}"
}

# Main script logic
case "${1:-help}" in
    "enable-prod")
        enable_swagger_production
        ;;
    "disable-dev")
        disable_swagger_development
        ;;
    "switch-dev")
        switch_to_dev
        ;;
    "switch-prod")
        switch_to_prod
        ;;
    "generate-minimal")
        generate_minimal_swagger
        ;;
    "status")
        show_status
        ;;
    "help"|*)
        echo "Swagger Management Script"
        echo ""
        echo "Usage: $0 [command]"
        echo ""
        echo "Commands:"
        echo "  enable-prod      Enable Swagger for production deployment"
        echo "  disable-dev      Disable Swagger for development"
        echo "  switch-dev       Switch to development mode (no Swagger)"
        echo "  switch-prod      Switch to production mode (with Swagger)"
        echo "  generate-minimal Generate minimal Swagger documentation"
        echo "  status           Show current Swagger status"
        echo "  help             Show this help message"
        echo ""
        echo "Examples:"
        echo "  $0 enable-prod    # Before deploying to production"
        echo "  $0 disable-dev    # For faster development"
        echo "  $0 status         # Check current status"
        ;;
esac
