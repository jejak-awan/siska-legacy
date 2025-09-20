#!/bin/bash

# Script untuk start frontend development server dengan port konsisten
# Memastikan tidak ada konflik port dan menggunakan port 3000 sesuai Nginx config

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
    print_info "Stopping existing frontend processes..."
    
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

# Main execution
main() {
    print_info "Starting Frontend Development Server..."
    print_info "Ensuring port 3000 consistency for Nginx configuration"
    
    # Change to frontend directory
    cd /opt/kesiswaan/frontend
    
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
    
    print_success "All ports are free. Starting development server..."
    
    # Start development server
    print_info "Starting npm run dev on port 3000..."
    npm run dev
}

# Run main function
main "$@"
