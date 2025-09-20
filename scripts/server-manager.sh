#!/bin/bash

# Server Management Script
# This script helps manage documentation servers

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

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

print_info() {
    echo -e "${CYAN}[INFO]${NC} $1"
}

# Function to check if port is in use
check_port() {
    local port=$1
    if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null 2>&1; then
        return 0  # Port is in use
    else
        return 1  # Port is available
    fi
}

# Function to get process info on port
get_port_info() {
    local port=$1
    if check_port $port; then
        local pid=$(lsof -ti:$port)
        local process=$(ps -p $pid -o comm= 2>/dev/null)
        echo "Port $port: PID $pid ($process)"
        return 0
    else
        echo "Port $port: Available"
        return 1
    fi
}

# Function to kill process on port
kill_port() {
    local port=$1
    local pid=$(lsof -ti:$port)
    if [ ! -z "$pid" ]; then
        print_warning "Killing process on port $port (PID: $pid)"
        kill -9 $pid 2>/dev/null
        sleep 2
        if check_port $port; then
            print_error "Failed to kill process on port $port"
            return 1
        else
            print_success "Process on port $port killed successfully"
            return 0
        fi
    else
        print_info "No process running on port $port"
        return 0
    fi
}

# Function to start backend server
start_backend() {
    local port=$1
    local name=$2
    
    if check_port $port; then
        print_warning "Port $port is already in use"
        get_port_info $port
        return 1
    fi
    
    print_info "Starting backend server on port $port..."
    cd backend
    nohup php artisan serve --host=0.0.0.0 --port=$port > ../logs/backend-$port.log 2>&1 &
    local pid=$!
    cd ..
    
    # Create logs directory if it doesn't exist
    mkdir -p logs
    echo "$pid" > logs/backend-$port.pid
    
    sleep 3
    
    if check_port $port; then
        print_success "$name backend server started on port $port (PID: $pid)"
        return 0
    else
        print_error "Failed to start $name backend server on port $port"
        return 1
    fi
}

# Function to start Storybook
start_storybook() {
    if check_port 6006; then
        print_warning "Port 6006 is already in use"
        get_port_info 6006
        return 1
    fi
    
    print_info "Starting Storybook on port 6006..."
    cd frontend
    nohup npm run storybook > ../logs/storybook.log 2>&1 &
    local pid=$!
    cd ..
    
    # Create logs directory if it doesn't exist
    mkdir -p logs
    echo "$pid" > logs/storybook.pid
    
    sleep 5
    
    if check_port 6006; then
        print_success "Storybook started on port 6006 (PID: $pid)"
        return 0
    else
        print_error "Failed to start Storybook on port 6006"
        return 1
    fi
}

# Function to show server status
show_status() {
    echo "🔍 Server Status:"
    echo ""
    
    # Check backend ports
    get_port_info 8000
    get_port_info 8080
    get_port_info 3000
    get_port_info 5000
    get_port_info 9000
    
    echo ""
    get_port_info 6006  # Storybook
    
    echo ""
    echo "📚 Documentation URLs:"
    if check_port 8000; then
        echo "  • API Docs (8000): http://localhost:8000/api/documentation"
    fi
    if check_port 8080; then
        echo "  • API Docs (8080): http://localhost:8080/api/documentation"
    fi
    if check_port 3000; then
        echo "  • API Docs (3000): http://localhost:3000/api/documentation"
    fi
    if check_port 5000; then
        echo "  • API Docs (5000): http://localhost:5000/api/documentation"
    fi
    if check_port 9000; then
        echo "  • API Docs (9000): http://localhost:9000/api/documentation"
    fi
    if check_port 6006; then
        echo "  • Storybook: http://localhost:6006"
    fi
}

# Function to stop all servers
stop_all() {
    print_status "Stopping all documentation servers..."
    
    local ports=(8000 8080 3000 5000 9000 6006)
    local stopped=0
    
    for port in "${ports[@]}"; do
        if kill_port $port; then
            ((stopped++))
        fi
    done
    
    print_success "Stopped $stopped servers"
}

# Function to start all servers
start_all() {
    print_status "Starting all documentation servers..."
    
    start_backend 8000 "Main"
    start_backend 8080 "Alternative"
    start_storybook
    
    echo ""
    show_status
}

# Main script logic
case "$1" in
    "status")
        show_status
        ;;
    "start")
        case "$2" in
            "backend")
                start_backend "${3:-8000}" "Backend"
                ;;
            "storybook")
                start_storybook
                ;;
            "all")
                start_all
                ;;
            *)
                print_error "Usage: $0 start [backend|storybook|all] [port]"
                exit 1
                ;;
        esac
        ;;
    "stop")
        case "$2" in
            "backend")
                kill_port "${3:-8000}"
                ;;
            "storybook")
                kill_port 6006
                ;;
            "all")
                stop_all
                ;;
            *)
                print_error "Usage: $0 stop [backend|storybook|all] [port]"
                exit 1
                ;;
        esac
        ;;
    "restart")
        case "$2" in
            "backend")
                kill_port "${3:-8000}"
                sleep 2
                start_backend "${3:-8000}" "Backend"
                ;;
            "storybook")
                kill_port 6006
                sleep 2
                start_storybook
                ;;
            "all")
                stop_all
                sleep 3
                start_all
                ;;
            *)
                print_error "Usage: $0 restart [backend|storybook|all] [port]"
                exit 1
                ;;
        esac
        ;;
    "logs")
        case "$2" in
            "backend")
                local port="${3:-8000}"
                if [ -f "logs/backend-$port.log" ]; then
                    tail -f logs/backend-$port.log
                else
                    print_error "Log file not found: logs/backend-$port.log"
                fi
                ;;
            "storybook")
                if [ -f "logs/storybook.log" ]; then
                    tail -f logs/storybook.log
                else
                    print_error "Log file not found: logs/storybook.log"
                fi
                ;;
            *)
                print_error "Usage: $0 logs [backend|storybook] [port]"
                exit 1
                ;;
        esac
        ;;
    *)
        echo "Server Management Script"
        echo ""
        echo "Usage: $0 [command] [options]"
        echo ""
        echo "Commands:"
        echo "  status                    Show server status"
        echo "  start [type] [port]       Start servers"
        echo "  stop [type] [port]        Stop servers"
        echo "  restart [type] [port]     Restart servers"
        echo "  logs [type] [port]        Show server logs"
        echo ""
        echo "Types:"
        echo "  backend                   Backend server (default port: 8000)"
        echo "  storybook                 Storybook server (port: 6006)"
        echo "  all                       All servers"
        echo ""
        echo "Examples:"
        echo "  $0 status"
        echo "  $0 start backend 8000"
        echo "  $0 start all"
        echo "  $0 stop backend 8080"
        echo "  $0 restart storybook"
        echo "  $0 logs backend 8000"
        ;;
esac
