#!/bin/bash

# Dashboard Integration Testing Script for Kesiswaan System
# Tests dashboard API endpoints and frontend integration

echo "🚀 DASHBOARD INTEGRATION TESTING - KESISWAAN SYSTEM"
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

# Function to test API endpoint
test_endpoint() {
    local endpoint="$1"
    local test_name="$2"
    local expected_status="$3"
    
    echo -e "${BLUE}Testing: $test_name${NC}"
    
    response=$(curl -s -w "\n%{http_code}" -H "Authorization: Bearer $TOKEN" "$API_BASE$endpoint")
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n -1)
    
    if [ "$http_code" = "$expected_status" ]; then
        print_result "$test_name" "PASS" "$body"
    else
        print_result "$test_name" "FAIL" "HTTP $http_code: $body"
    fi
}

# Function to authenticate and get token
authenticate() {
    echo -e "${YELLOW}🔐 Authenticating...${NC}"
    
    response=$(curl -s -X POST -H "Content-Type: application/json" -d '{"username":"admin","password":"password123"}' "$API_BASE/login")
    
    if echo "$response" | grep -q "token"; then
        TOKEN=$(echo "$response" | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
        echo -e "${GREEN}✅ Authentication successful${NC}"
        echo "Token: ${TOKEN:0:20}..."
        echo ""
    else
        echo -e "${RED}❌ Authentication failed${NC}"
        echo "Response: $response"
        exit 1
    fi
}

# Function to test dashboard statistics
test_dashboard_statistics() {
    echo -e "${YELLOW}📊 TESTING DASHBOARD STATISTICS${NC}"
    echo "================================="
    
    # Test all statistics endpoints
    test_endpoint "/users-statistics" "Users Statistics" "200"
    test_endpoint "/guru-statistics" "Guru Statistics" "200"
    test_endpoint "/siswa/statistics/overview" "Siswa Statistics" "200"
    test_endpoint "/presensi-statistics" "Presensi Statistics" "200"
    test_endpoint "/kredit-poin-statistics" "Kredit Poin Statistics" "200"
    test_endpoint "/bk-statistics" "BK Statistics" "200"
    test_endpoint "/osis-statistics" "OSIS Statistics" "200"
    test_endpoint "/ekstrakurikuler-statistics" "Ekstrakurikuler Statistics" "200"
    test_endpoint "/notifications/stats" "Notifications Statistics" "200"
    
    echo ""
}

# Function to test dashboard data structure
test_dashboard_data_structure() {
    echo -e "${YELLOW}🔍 TESTING DASHBOARD DATA STRUCTURE${NC}"
    echo "====================================="
    
    # Test BK statistics structure
    echo -e "${BLUE}Testing: BK Statistics Data Structure${NC}"
    bk_response=$(curl -s -H "Authorization: Bearer $TOKEN" "$API_BASE/bk-statistics")
    if echo "$bk_response" | grep -q "total_konseling\|total_home_visit"; then
        print_result "BK Statistics Data Structure" "PASS" "Contains required fields"
    else
        print_result "BK Statistics Data Structure" "FAIL" "Missing required fields"
    fi
    
    # Test OSIS statistics structure
    echo -e "${BLUE}Testing: OSIS Statistics Data Structure${NC}"
    osis_response=$(curl -s -H "Authorization: Bearer $TOKEN" "$API_BASE/osis-statistics")
    if echo "$osis_response" | grep -q "total_kegiatan\|kegiatan_aktif"; then
        print_result "OSIS Statistics Data Structure" "PASS" "Contains required fields"
    else
        print_result "OSIS Statistics Data Structure" "FAIL" "Missing required fields"
    fi
    
    # Test Ekstrakurikuler statistics structure
    echo -e "${BLUE}Testing: Ekstrakurikuler Statistics Data Structure${NC}"
    ekskul_response=$(curl -s -H "Authorization: Bearer $TOKEN" "$API_BASE/ekstrakurikuler-statistics")
    if echo "$ekskul_response" | grep -q "total_ekstrakurikuler\|ekstrakurikuler_aktif"; then
        print_result "Ekstrakurikuler Statistics Data Structure" "PASS" "Contains required fields"
    else
        print_result "Ekstrakurikuler Statistics Data Structure" "FAIL" "Missing required fields"
    fi
    
    echo ""
}

# Function to test frontend dashboard access
test_frontend_dashboard() {
    echo -e "${YELLOW}🌐 TESTING FRONTEND DASHBOARD ACCESS${NC}"
    echo "====================================="
    
    # Test if frontend server is running
    if curl -s "http://localhost:3000" > /dev/null; then
        print_result "Frontend Server Status" "PASS" "Frontend server is running"
        
        # Test dashboard page access
        dashboard_response=$(curl -s -w "\n%{http_code}" "http://localhost:3000/dashboard")
        http_code=$(echo "$dashboard_response" | tail -n1)
        if [ "$http_code" = "200" ]; then
            print_result "Dashboard Page Access" "PASS" "Dashboard page accessible"
        else
            print_result "Dashboard Page Access" "FAIL" "HTTP $http_code"
        fi
    else
        print_result "Frontend Server Status" "FAIL" "Frontend server not running"
    fi
    
    echo ""
}

# Function to test dashboard performance
test_dashboard_performance() {
    echo -e "${YELLOW}⚡ TESTING DASHBOARD PERFORMANCE${NC}"
    echo "=================================="
    
    # Test response times for statistics endpoints
    echo -e "${BLUE}Testing: Dashboard Statistics Response Time${NC}"
    start_time=$(date +%s%N)
    curl -s -H "Authorization: Bearer $TOKEN" "$API_BASE/bk-statistics" > /dev/null
    end_time=$(date +%s%N)
    response_time=$(( (end_time - start_time) / 1000000 ))
    
    if [ $response_time -lt 1000 ]; then
        print_result "Dashboard Statistics Response Time" "PASS" "${response_time}ms"
    else
        print_result "Dashboard Statistics Response Time" "FAIL" "${response_time}ms (too slow)"
    fi
    
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
        echo -e "${GREEN}🎉 ALL DASHBOARD TESTS PASSED! 🎉${NC}"
        echo -e "${GREEN}Dashboard integration is working perfectly!${NC}"
    else
        echo -e "${YELLOW}⚠️  Some dashboard tests failed. Please check the errors above.${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}Test completed at: $(date)${NC}"
}

# Main execution
main() {
    echo -e "${BLUE}Starting dashboard integration testing...${NC}"
    echo ""
    
    # API Base URL
    API_BASE="http://localhost:8000/api"
    TOKEN=""
    
    # Check if backend server is running
    if ! curl -s "$API_BASE/test" > /dev/null; then
        echo -e "${RED}❌ Backend server is not running!${NC}"
        echo "Please start the backend server with: cd /opt/kesiswaan/backend && php artisan serve"
        exit 1
    fi
    
    # Authenticate
    authenticate
    
    # Run all tests
    test_dashboard_statistics
    test_dashboard_data_structure
    test_frontend_dashboard
    test_dashboard_performance
    
    # Print final results
    print_final_results
}

# Run main function
main
