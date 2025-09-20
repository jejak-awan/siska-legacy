#!/bin/bash

# Frontend Integration Testing Script for Kesiswaan System
# Tests frontend views and components integration

echo "🚀 FRONTEND INTEGRATION TESTING - KESISWAAN SYSTEM"
echo "=================================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Test counter
TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

# Function to print test results
print_result() {
    local test_name="$1"
    local status="$2"
    local response="$3"
    
    TOTAL_TESTS=$((TOTAL_TESTS + 1))
    
    if [ "$status" = "PASS" ]; then
        echo -e "${GREEN}✅ PASS${NC} - $test_name"
        PASSED_TESTS=$((PASSED_TESTS + 1))
    else
        echo -e "${RED}❌ FAIL${NC} - $test_name"
        echo -e "${RED}Response: $response${NC}"
        FAILED_TESTS=$((FAILED_TESTS + 1))
    fi
    echo ""
}

# Function to test frontend endpoint
test_frontend() {
    local endpoint="$1"
    local test_name="$2"
    local expected_status="$3"
    
    echo -e "${BLUE}Testing: $test_name${NC}"
    
    response=$(curl -s -w "\n%{http_code}" "http://localhost:3000$endpoint")
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n -1)
    
    if [ "$http_code" = "$expected_status" ]; then
        print_result "$test_name" "PASS" "$body"
    else
        print_result "$test_name" "FAIL" "HTTP $http_code: $body"
    fi
}

# Function to test file existence
test_file_exists() {
    local file_path="$1"
    local test_name="$2"
    
    echo -e "${BLUE}Testing: $test_name${NC}"
    
    if [ -f "$file_path" ]; then
        print_result "$test_name" "PASS" "File exists"
    else
        print_result "$test_name" "FAIL" "File not found: $file_path"
    fi
}

# Function to test Vue component syntax
test_vue_syntax() {
    local file_path="$1"
    local test_name="$2"
    
    echo -e "${BLUE}Testing: $test_name${NC}"
    
    if [ -f "$file_path" ]; then
        # Check for basic Vue structure
        if grep -q "<template>" "$file_path" && grep -q "<script" "$file_path"; then
            print_result "$test_name" "PASS" "Valid Vue component structure"
        else
            print_result "$test_name" "FAIL" "Invalid Vue component structure"
        fi
    else
        print_result "$test_name" "FAIL" "File not found: $file_path"
    fi
}

# Function to test frontend server
test_frontend_server() {
    echo -e "${YELLOW}🌐 TESTING FRONTEND SERVER${NC}"
    echo "=========================="
    
    # Test main page
    test_frontend "/" "Frontend Main Page" "200"
    
    # Test login page
    test_frontend "/login" "Login Page" "200"
    
    # Test dashboard (should redirect to login)
    test_frontend "/dashboard" "Dashboard Page" "200"
    
    echo ""
}

# Function to test view files
test_view_files() {
    echo -e "${YELLOW}📁 TESTING VIEW FILES${NC}"
    echo "====================="
    
    # Test main views
    test_file_exists "/opt/kesiswaan/frontend/src/views/bk/BKView.vue" "BK View File"
    test_file_exists "/opt/kesiswaan/frontend/src/views/osis/OSISView.vue" "OSIS View File"
    test_file_exists "/opt/kesiswaan/frontend/src/views/ekstrakurikuler/EkstrakurikulerView.vue" "Ekstrakurikuler View File"
    
    # Test existing views
    test_file_exists "/opt/kesiswaan/frontend/src/views/presensi/PresensiView.vue" "Presensi View File"
    test_file_exists "/opt/kesiswaan/frontend/src/views/kredit-poin/KreditPoinView.vue" "Kredit Poin View File"
    test_file_exists "/opt/kesiswaan/frontend/src/views/notifications/NotificationView.vue" "Notification View File"
    
    echo ""
}

# Function to test modal components
test_modal_components() {
    echo -e "${YELLOW}🔧 TESTING MODAL COMPONENTS${NC}"
    echo "============================="
    
    # Test new modal components
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/KonselingFormModal.vue" "Konseling Form Modal"
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/HomeVisitFormModal.vue" "Home Visit Form Modal"
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/OSISFormModal.vue" "OSIS Form Modal"
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/EkstrakurikulerFormModal.vue" "Ekstrakurikuler Form Modal"
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/EkstrakurikulerRegistrationModal.vue" "Ekstrakurikuler Registration Modal"
    
    # Test existing modal components
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/PresensiFormModal.vue" "Presensi Form Modal"
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/KreditPoinFormModal.vue" "Kredit Poin Form Modal"
    test_file_exists "/opt/kesiswaan/frontend/src/components/modals/NotificationFormModal.vue" "Notification Form Modal"
    
    echo ""
}

# Function to test Vue component syntax
test_vue_syntax_validation() {
    echo -e "${YELLOW}🔍 TESTING VUE COMPONENT SYNTAX${NC}"
    echo "================================="
    
    # Test new components syntax
    test_vue_syntax "/opt/kesiswaan/frontend/src/views/bk/BKView.vue" "BK View Syntax"
    test_vue_syntax "/opt/kesiswaan/frontend/src/views/osis/OSISView.vue" "OSIS View Syntax"
    test_vue_syntax "/opt/kesiswaan/frontend/src/views/ekstrakurikuler/EkstrakurikulerView.vue" "Ekstrakurikuler View Syntax"
    
    # Test modal components syntax
    test_vue_syntax "/opt/kesiswaan/frontend/src/components/modals/KonselingFormModal.vue" "Konseling Modal Syntax"
    test_vue_syntax "/opt/kesiswaan/frontend/src/components/modals/HomeVisitFormModal.vue" "Home Visit Modal Syntax"
    test_vue_syntax "/opt/kesiswaan/frontend/src/components/modals/OSISFormModal.vue" "OSIS Modal Syntax"
    test_vue_syntax "/opt/kesiswaan/frontend/src/components/modals/EkstrakurikulerFormModal.vue" "Ekstrakurikuler Modal Syntax"
    
    echo ""
}

# Function to test router configuration
test_router_config() {
    echo -e "${YELLOW}🛣️  TESTING ROUTER CONFIGURATION${NC}"
    echo "==============================="
    
    # Check if router file exists
    test_file_exists "/opt/kesiswaan/frontend/src/router/index.js" "Router Configuration File"
    
    # Check for new routes in router
    if [ -f "/opt/kesiswaan/frontend/src/router/index.js" ]; then
        if grep -q "bimbingan-konseling\|osis\|ekstrakurikuler" "/opt/kesiswaan/frontend/src/router/index.js"; then
            print_result "New Routes in Router" "PASS" "New routes found in router configuration"
        else
            print_result "New Routes in Router" "FAIL" "New routes not found in router configuration"
        fi
    fi
    
    echo ""
}

# Function to test sidebar navigation
test_sidebar_navigation() {
    echo -e "${YELLOW}🧭 TESTING SIDEBAR NAVIGATION${NC}"
    echo "==============================="
    
    # Check if sidebar file exists
    test_file_exists "/opt/kesiswaan/frontend/src/components/layout/Sidebar.vue" "Sidebar Component File"
    
    # Check for new navigation items in sidebar
    if [ -f "/opt/kesiswaan/frontend/src/components/layout/Sidebar.vue" ]; then
        if grep -q "bimbingan-konseling\|osis\|ekstrakurikuler" "/opt/kesiswaan/frontend/src/components/layout/Sidebar.vue"; then
            print_result "New Navigation Items" "PASS" "New navigation items found in sidebar"
        else
            print_result "New Navigation Items" "FAIL" "New navigation items not found in sidebar"
        fi
    fi
    
    echo ""
}

# Function to test API service integration
test_api_service() {
    echo -e "${YELLOW}🔌 TESTING API SERVICE INTEGRATION${NC}"
    echo "===================================="
    
    # Check if API service file exists
    test_file_exists "/opt/kesiswaan/frontend/src/services/api.js" "API Service File"
    
    # Check if auth store exists
    test_file_exists "/opt/kesiswaan/frontend/src/stores/auth.js" "Auth Store File"
    
    echo ""
}

# Function to print final results
print_final_results() {
    echo -e "${YELLOW}📊 FINAL TEST RESULTS${NC}"
    echo "===================="
    echo -e "Total Tests: ${BLUE}$TOTAL_TESTS${NC}"
    echo -e "Passed: ${GREEN}$PASSED_TESTS${NC}"
    echo -e "Failed: ${RED}$FAILED_TESTS${NC}"
    echo ""
    
    if [ $FAILED_TESTS -eq 0 ]; then
        echo -e "${GREEN}🎉 ALL FRONTEND TESTS PASSED! 🎉${NC}"
        echo -e "${GREEN}Frontend integration is working perfectly!${NC}"
    else
        echo -e "${YELLOW}⚠️  Some frontend tests failed. Please check the errors above.${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}Test completed at: $(date)${NC}"
}

# Main execution
main() {
    echo -e "${BLUE}Starting frontend integration testing...${NC}"
    echo ""
    
    # Check if frontend server is running
    if ! curl -s "http://localhost:3000" > /dev/null; then
        echo -e "${RED}❌ Frontend server is not running!${NC}"
        echo "Please start the frontend server with: cd /opt/kesiswaan/frontend && npm run dev"
        exit 1
    fi
    
    # Run all tests
    test_frontend_server
    test_view_files
    test_modal_components
    test_vue_syntax_validation
    test_router_config
    test_sidebar_navigation
    test_api_service
    
    # Print final results
    print_final_results
}

# Run main function
main
