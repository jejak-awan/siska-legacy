#!/bin/bash

# Script untuk build frontend dengan memastikan tidak ada konflik port
# Memastikan port 3000 konsisten sesuai Nginx config

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

# Function to kill processes on specific ports
kill_port_processes() {
    local ports=("3000" "3002" "5173")
    
    for port in "${ports[@]}"; do
        if lsof -i:$port >/dev/null 2>&1; then
            print_warning "Killing processes on port $port..."
            lsof -ti:$port | xargs -r kill -9 2>/dev/null || true
            sleep 1
        fi
    done
}

# Function to kill npm/vite processes
kill_frontend_processes() {
    print_info "Stopping existing frontend processes before build..."
    
    # Kill npm run dev processes
    pkill -f "npm run dev" 2>/dev/null || true
    
    # Kill vite processes
    pkill -f "vite" 2>/dev/null || true
    
    # Kill node processes from frontend directory
    pkill -f "node.*frontend" 2>/dev/null || true
    
    sleep 2
}

# Function to verify port is free
verify_port_free() {
    local port=$1
    if lsof -i:$port >/dev/null 2>&1; then
        print_error "Port $port is still in use!"
        lsof -i:$port
        return 1
    fi
    return 0
}

# Function to clean build directory
clean_build() {
    print_info "Cleaning previous build..."
    cd /opt/kesiswaan/frontend
    
    if [ -d "dist" ]; then
        rm -rf dist
        print_success "Previous build directory cleaned"
    fi
}

# Function to run build
run_build() {
    print_info "Running frontend build..."
    cd /opt/kesiswaan/frontend
    
    # Install dependencies if needed
    if [ ! -d "node_modules" ]; then
        print_info "Installing dependencies..."
        npm install
    fi
    
    # Run build
    print_info "Building frontend with Vite..."
    npm run build
    
    print_success "Frontend build completed successfully!"
}

# Function to verify build
verify_build() {
    print_info "Verifying build output..."
    cd /opt/kesiswaan/frontend
    
    if [ -d "dist" ] && [ -f "dist/index.html" ]; then
        print_success "Build verification passed"
        print_info "Build files:"
        ls -la dist/
    else
        print_error "Build verification failed - dist directory or index.html not found"
        exit 1
    fi
}

# Main execution
main() {
    print_info "Starting Frontend Build Process..."
    print_info "Ensuring no port conflicts before build"
    
    # Kill existing processes
    kill_frontend_processes
    kill_port_processes
    
    # Verify ports are free
    if ! verify_port_free 3000; then
        print_error "Port 3000 is still in use. Please check manually."
        exit 1
    fi
    
    if ! verify_port_free 3002; then
        print_error "Port 3002 is still in use. Please check manually."
        exit 1
    fi
    
    print_success "All ports are free. Proceeding with build..."
    
    # Clean and build
    clean_build
    run_build
    verify_build
    
    print_success "Frontend build process completed successfully!"
    print_info "Build files are ready in /opt/kesiswaan/frontend/dist/"
    print_info "Port 3000 is free and ready for development server"
}

# Run main function
main "$@"
