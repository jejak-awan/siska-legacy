#!/bin/bash

# Documentation Generation Script
# This script generates and updates all documentation

echo "🚀 Starting Documentation Generation..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
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

# Check if we're in the right directory
if [ ! -f "README.md" ]; then
    print_error "Please run this script from the project root directory"
    exit 1
fi

print_status "Generating API Documentation (Swagger/OpenAPI)..."

# Generate Swagger documentation
if [ -d "backend" ]; then
    cd backend
    
    # Check if Laravel is installed
    if [ -f "artisan" ]; then
        print_status "Generating Swagger documentation..."
        php artisan l5-swagger:generate
        
        if [ $? -eq 0 ]; then
            print_success "Swagger documentation generated successfully"
        else
            print_error "Failed to generate Swagger documentation"
        fi
    else
        print_warning "Laravel backend not found, skipping Swagger generation"
    fi
    
    cd ..
else
    print_warning "Backend directory not found, skipping Swagger generation"
fi

print_status "Building Frontend Component Documentation (Storybook)..."

# Build Storybook documentation
if [ -d "frontend" ]; then
    cd frontend
    
    # Check if Storybook is installed
    if [ -d ".storybook" ]; then
        print_status "Building Storybook documentation..."
        npm run build-storybook
        
        if [ $? -eq 0 ]; then
            print_success "Storybook documentation built successfully"
        else
            print_error "Failed to build Storybook documentation"
        fi
    else
        print_warning "Storybook not found, skipping Storybook build"
    fi
    
    cd ..
else
    print_warning "Frontend directory not found, skipping Storybook build"
fi

print_status "Updating README documentation links..."

# Update README with current documentation status
if [ -f "README.md" ]; then
    # Get current date
    CURRENT_DATE=$(date '+%Y-%m-%d %H:%M:%S')
    
    # Update the last updated timestamp in README
    sed -i "s/Last Updated.*/Last Updated: $CURRENT_DATE/" README.md
    
    print_success "README updated with current timestamp"
fi

print_status "Documentation generation completed!"

echo ""
echo "📚 Available Documentation:"
echo "  • API Documentation: http://localhost:8000/api/documentation"
echo "  • Component Documentation: http://localhost:6006"
echo "  • README: ./README.md"
echo ""
echo "🔧 To start documentation servers:"
echo "  • Backend: cd backend && php artisan serve"
echo "  • Frontend: cd frontend && npm run storybook"
echo ""
