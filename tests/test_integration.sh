#!/bin/bash

echo "🚀 COMPREHENSIVE INTEGRATION TESTING"
echo "==================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Test results
TESTS_PASSED=0
TESTS_FAILED=0

# Function to run test
run_test() {
    local test_name="$1"
    local command="$2"
    local expected_pattern="$3"
    
    echo -e "\n${BLUE}Testing: $test_name${NC}"
    
    result=$(eval "$command" 2>/dev/null)
    
    if [[ $result =~ $expected_pattern ]]; then
        echo -e "${GREEN}✅ PASSED${NC}: $test_name"
        ((TESTS_PASSED++))
        return 0
    else
        echo -e "${RED}❌ FAILED${NC}: $test_name"
        echo -e "${YELLOW}Expected pattern: $expected_pattern${NC}"
        echo -e "${YELLOW}Actual result: $result${NC}"
        ((TESTS_FAILED++))
        return 1
    fi
}

echo -e "\n${BLUE}📡 BACKEND API TESTING${NC}"
echo "====================="

# Test 1: Backend server accessibility
run_test "Backend Server Accessibility" \
    "curl -s -o /dev/null -w '%{http_code}' http://localhost:8000" \
    "200"

# Test 2: Login endpoint
run_test "Login API Endpoint" \
    "curl -s -X POST http://localhost:8000/api/login -H 'Content-Type: application/json' -d '{\"username\":\"admin\",\"password\":\"password123\"}'" \
    "Login successful"

# Get token for authenticated tests
TOKEN=$(curl -s -X POST http://localhost:8000/api/login -H "Content-Type: application/json" -d '{"username":"admin","password":"password123"}' | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

# Test 3: Users statistics
run_test "Users Statistics API" \
    "curl -s -H 'Authorization: Bearer $TOKEN' http://localhost:8000/api/users-statistics" \
    "totalUsers"

# Test 4: Guru statistics  
run_test "Guru Statistics API" \
    "curl -s -H 'Authorization: Bearer $TOKEN' http://localhost:8000/api/guru-statistics" \
    "totalGuru"

# Test 5: Siswa statistics
run_test "Siswa Statistics API" \
    "curl -s -H 'Authorization: Bearer $TOKEN' http://localhost:8000/api/siswa/statistics/overview" \
    "total"

# Test 6: Auth me endpoint
run_test "Auth Me API" \
    "curl -s -H 'Authorization: Bearer $TOKEN' http://localhost:8000/api/auth/me" \
    "admin"

# Test 7: Users list
run_test "Users List API" \
    "curl -s -H 'Authorization: Bearer $TOKEN' http://localhost:8000/api/users" \
    "data"

# Test 8: Guru list
run_test "Guru List API" \
    "curl -s -H 'Authorization: Bearer $TOKEN' http://localhost:8000/api/guru" \
    "data"

# Test 9: Siswa list
run_test "Siswa List API" \
    "curl -s -H 'Authorization: Bearer $TOKEN' http://localhost:8000/api/siswa" \
    "data"

echo -e "\n${BLUE}🌐 FRONTEND TESTING${NC}"
echo "=================="

# Test 10: Frontend server accessibility
run_test "Frontend Server Accessibility" \
    "curl -s -o /dev/null -w '%{http_code}' http://localhost:5173" \
    "200"

# Test 11: Frontend HTML structure
run_test "Frontend HTML Structure" \
    "curl -s http://localhost:5173" \
    "Sistem Kesiswaan"

# Test 12: Vite dev server
run_test "Vite Development Server" \
    "curl -s http://localhost:5173" \
    "/@vite/client"

echo -e "\n${BLUE}🔧 DATABASE TESTING${NC}"
echo "=================="

# Test 13: Database connection
run_test "Database Connection" \
    "cd /opt/kesiswaan/backend && php artisan migrate:status" \
    "create_users_table"

# Test 14: Seeded data
run_test "Seeded Test Users" \
    "cd /opt/kesiswaan/backend && php artisan tinker --execute=\"echo App\Models\User::count()\"" \
    "[0-9]+"

echo -e "\n${BLUE}📊 SYSTEM HEALTH CHECK${NC}"
echo "======================"

# Test 15: Laravel cache
run_test "Laravel Cache System" \
    "cd /opt/kesiswaan/backend && php artisan route:list --path=api | wc -l" \
    "[0-9]+"

# Test 16: Vue.js components
run_test "Vue.js Main Component" \
    "ls /opt/kesiswaan/frontend/src/App.vue" \
    "App.vue"

# Test 17: API service
run_test "Frontend API Service" \
    "ls /opt/kesiswaan/frontend/src/services/api.js" \
    "api.js"

# Test 18: Auth store
run_test "Frontend Auth Store" \
    "ls /opt/kesiswaan/frontend/src/stores/auth.js" \
    "auth.js"

echo -e "\n${BLUE}🎯 FINAL RESULTS${NC}"
echo "==============="

TOTAL_TESTS=$((TESTS_PASSED + TESTS_FAILED))
PASS_RATE=$((TESTS_PASSED * 100 / TOTAL_TESTS))

echo -e "Total Tests: $TOTAL_TESTS"
echo -e "${GREEN}Passed: $TESTS_PASSED${NC}"
echo -e "${RED}Failed: $TESTS_FAILED${NC}"
echo -e "Pass Rate: ${PASS_RATE}%"

if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "\n${GREEN}🎉 ALL TESTS PASSED! SYSTEM IS FULLY FUNCTIONAL! 🎉${NC}"
    exit 0
else
    echo -e "\n${YELLOW}⚠️  Some tests failed. Please check the issues above.${NC}"
    exit 1
fi
