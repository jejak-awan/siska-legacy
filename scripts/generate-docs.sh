#!/bin/bash

# Documentation Generation Script
# This script generates and updates all documentation
# Usage: ./scripts/generate-docs.sh [options]
# Options:
#   --port=PORT     Set backend port (default: 8000)
#   --alt-port=PORT Set alternative backend port (default: 8080)
#   --start         Start documentation servers after generation
#   --help          Show this help message

echo "🚀 Starting Documentation Generation..."

# Default configuration
BACKEND_PORT=8000
ALT_PORT=8080
START_SERVERS=false
SHOW_HELP=false

# Parse command line arguments
for arg in "$@"; do
    case $arg in
        --port=*)
            BACKEND_PORT="${arg#*=}"
            shift
            ;;
        --alt-port=*)
            ALT_PORT="${arg#*=}"
            shift
            ;;
        --start)
            START_SERVERS=true
            shift
            ;;
        --help)
            SHOW_HELP=true
            shift
            ;;
        *)
            print_error "Unknown option: $arg"
            print_error "Use --help for usage information"
            exit 1
            ;;
    esac
done

# Show help if requested
if [ "$SHOW_HELP" = true ]; then
    echo "Documentation Generation Script"
    echo ""
    echo "Usage: ./scripts/generate-docs.sh [options]"
    echo ""
    echo "Options:"
    echo "  --port=PORT     Set backend port (default: 8000)"
    echo "  --alt-port=PORT Set alternative backend port (default: 8080)"
    echo "  --start         Start documentation servers after generation"
    echo "  --help          Show this help message"
    echo ""
    echo "Examples:"
    echo "  ./scripts/generate-docs.sh"
    echo "  ./scripts/generate-docs.sh --port=3000"
    echo "  ./scripts/generate-docs.sh --start"
    echo "  ./scripts/generate-docs.sh --port=5000 --alt-port=9000 --start"
    exit 0
fi

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
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

print_info() {
    echo -e "${CYAN}[INFO]${NC} $1"
}

print_config() {
    echo -e "${PURPLE}[CONFIG]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "README.md" ]; then
    print_error "Please run this script from the project root directory"
    exit 1
fi

# Display configuration
print_config "Backend Port: $BACKEND_PORT"
print_config "Alternative Port: $ALT_PORT"
print_config "Start Servers: $START_SERVERS"
echo ""

# Function to check if port is available
check_port() {
    local port=$1
    if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null 2>&1; then
        return 1  # Port is in use
    else
        return 0  # Port is available
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
    fi
}

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

# Start servers if requested
if [ "$START_SERVERS" = true ]; then
    print_status "Starting documentation servers..."
    
    # Kill existing processes on target ports
    kill_port $BACKEND_PORT
    kill_port $ALT_PORT
    kill_port 6006  # Storybook port
    
    # Start backend server on main port
    print_info "Starting backend server on port $BACKEND_PORT..."
    cd backend
    nohup php artisan serve --host=0.0.0.0 --port=$BACKEND_PORT > ../logs/backend-$BACKEND_PORT.log 2>&1 &
    BACKEND_PID=$!
    cd ..
    
    # Start backend server on alternative port
    print_info "Starting backend server on port $ALT_PORT..."
    cd backend
    nohup php artisan serve --host=0.0.0.0 --port=$ALT_PORT > ../logs/backend-$ALT_PORT.log 2>&1 &
    ALT_PID=$!
    cd ..
    
    # Start Storybook
    print_info "Starting Storybook on port 6006..."
    cd frontend
    nohup npm run storybook > ../logs/storybook.log 2>&1 &
    STORYBOOK_PID=$!
    cd ..
    
    # Create logs directory if it doesn't exist
    mkdir -p logs
    
    # Wait a moment for servers to start
    sleep 5
    
    # Check if servers are running
    if check_port $BACKEND_PORT; then
        print_error "Backend server failed to start on port $BACKEND_PORT"
    else
        print_success "Backend server running on port $BACKEND_PORT (PID: $BACKEND_PID)"
    fi
    
    if check_port $ALT_PORT; then
        print_error "Alternative backend server failed to start on port $ALT_PORT"
    else
        print_success "Alternative backend server running on port $ALT_PORT (PID: $ALT_PID)"
    fi
    
    if check_port 6006; then
        print_error "Storybook failed to start on port 6006"
    else
        print_success "Storybook running on port 6006 (PID: $STORYBOOK_PID)"
    fi
    
    # Save PIDs for later reference
    echo "$BACKEND_PID" > logs/backend-$BACKEND_PORT.pid
    echo "$ALT_PID" > logs/backend-$ALT_PORT.pid
    echo "$STORYBOOK_PID" > logs/storybook.pid
    
    print_info "Server logs available in logs/ directory"
fi

print_status "Documentation generation completed!"

echo ""
echo "📚 Available Documentation:"
echo "  • API Documentation (Main): http://localhost:$BACKEND_PORT/api/documentation"
echo "  • API Documentation (Alt): http://localhost:$ALT_PORT/api/documentation"
echo "  • Component Documentation: http://localhost:6006"
echo "  • README: ./README.md"
echo ""
echo "🔧 To start documentation servers manually:"
echo "  • Backend (Main): cd backend && php artisan serve --port=$BACKEND_PORT"
echo "  • Backend (Alt): cd backend && php artisan serve --port=$ALT_PORT"
echo "  • Frontend: cd frontend && npm run storybook"
echo ""
echo "🛠️  Script Usage:"
echo "  • Generate docs: ./scripts/generate-docs.sh"
echo "  • Generate + Start: ./scripts/generate-docs.sh --start"
echo "  • Custom ports: ./scripts/generate-docs.sh --port=3000 --alt-port=5000"
echo "  • Help: ./scripts/generate-docs.sh --help"
echo ""
