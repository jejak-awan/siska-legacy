#!/bin/bash

# Comprehensive API Testing Script for Kesiswaan System
# Tests all new controllers and endpoints with real seeded data

echo "🚀 COMPREHENSIVE API TESTING - KESISWAAN SYSTEM"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# API Base URL
API_BASE="http://localhost:8000/api"
TOKEN=""

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
    local method="$1"
    local endpoint="$2"
    local test_name="$3"
    local expected_status="$4"
    local data="$5"
    
    echo -e "${BLUE}Testing: $test_name${NC}"
    
    if [ "$method" = "GET" ]; then
        response=$(curl -s -w "\n%{http_code}" -H "Authorization: Bearer $TOKEN" "$API_BASE$endpoint")
    elif [ "$method" = "POST" ]; then
        response=$(curl -s -w "\n%{http_code}" -X POST -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d "$data" "$API_BASE$endpoint")
    elif [ "$method" = "PUT" ]; then
        response=$(curl -s -w "\n%{http_code}" -X PUT -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d "$data" "$API_BASE$endpoint")
    elif [ "$method" = "DELETE" ]; then
        response=$(curl -s -w "\n%{http_code}" -X DELETE -H "Authorization: Bearer $TOKEN" "$API_BASE$endpoint")
    fi
    
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

# Function to test authentication
test_auth() {
    echo -e "${YELLOW}🔐 TESTING AUTHENTICATION${NC}"
    echo "=========================="
    
    # Test login
    test_endpoint "POST" "/login" "Admin Login" "200" '{"username":"admin","password":"password123"}'
    
    # Test invalid login
    test_endpoint "POST" "/login" "Invalid Login" "401" '{"username":"admin","password":"wrongpassword"}'
    
    # Test protected endpoint without token
    response=$(curl -s -w "\n%{http_code}" "$API_BASE/auth/me")
    http_code=$(echo "$response" | tail -n1)
    if [ "$http_code" = "401" ]; then
        print_result "Protected Endpoint Without Token" "PASS" "Unauthorized"
    else
        print_result "Protected Endpoint Without Token" "FAIL" "HTTP $http_code"
    fi
    
    # Test protected endpoint with token
    test_endpoint "GET" "/auth/me" "Get User Profile" "200"
    
    echo ""
}

# Function to test BK Controller
test_bk_controller() {
    echo -e "${YELLOW}🧠 TESTING BK CONTROLLER${NC}"
    echo "======================="
    
    # Test konseling endpoints
    test_endpoint "GET" "/konseling" "Get Konseling List" "200"
    test_endpoint "GET" "/konseling/statistics" "Get Konseling Statistics" "200"
    
    # Test home visit endpoints
    test_endpoint "GET" "/home-visit" "Get Home Visit List" "200"
    test_endpoint "GET" "/home-visit/statistics" "Get Home Visit Statistics" "200"
    
    # Test combined BK statistics
    test_endpoint "GET" "/bk-statistics" "Get Combined BK Statistics" "200"
    
    # Test create konseling
    konseling_data='{
        "siswa_id": 1,
        "konselor_id": 4,
        "tanggal_konseling": "2024-10-20",
        "jam_mulai": "10:00",
        "jam_selesai": "11:00",
        "jenis_konseling": "individual",
        "status": "terjadwal",
        "masalah": "Test masalah konseling",
        "tujuan_konseling": "Test tujuan konseling",
        "tempat_konseling": "Ruang BK",
        "is_urgent": false,
        "is_confidential": true
    }'
    test_endpoint "POST" "/konseling" "Create Konseling Session" "201" "$konseling_data"
    
    # Test create home visit
    home_visit_data='{
        "siswa_id": 1,
        "konselor_id": 4,
        "tanggal_kunjungan": "2024-10-25",
        "jam_mulai": "14:00",
        "jam_selesai": "15:30",
        "status": "terjadwal",
        "tujuan_kunjungan": "Test home visit",
        "alamat_kunjungan": "Jl. Test No. 123",
        "is_urgent": false,
        "is_confidential": true
    }'
    test_endpoint "POST" "/home-visit" "Create Home Visit" "201" "$home_visit_data"
    
    echo ""
}

# Function to test OSIS Controller
test_osis_controller() {
    echo -e "${YELLOW}🏛️ TESTING OSIS CONTROLLER${NC}"
    echo "========================="
    
    # Test OSIS activities endpoints
    test_endpoint "GET" "/osis-kegiatan" "Get OSIS Activities List" "200"
    test_endpoint "GET" "/osis-statistics" "Get OSIS Statistics" "200"
    test_endpoint "GET" "/osis-kegiatan/upcoming" "Get Upcoming Activities" "200"
    test_endpoint "GET" "/osis-kegiatan/ongoing" "Get Ongoing Activities" "200"
    
    # Test create OSIS activity
    osis_data='{
        "nama_kegiatan": "Test OSIS Activity",
        "deskripsi": "Test description for OSIS activity",
        "tanggal_mulai": "2024-11-01",
        "tanggal_selesai": "2024-11-01",
        "jam_mulai": "08:00",
        "jam_selesai": "16:00",
        "jenis_kegiatan": "sosial",
        "status": "perencanaan",
        "tempat_kegiatan": "Lapangan Sekolah",
        "tujuan_kegiatan": "Test tujuan kegiatan",
        "sasaran_kegiatan": "Test sasaran kegiatan",
        "anggaran": 1000000,
        "sumber_dana": "Dana OSIS",
        "penanggung_jawab": "Admin Sekolah",
        "peserta": {"siswa": 50},
        "panitia": {"ketua": "Test Ketua"},
        "keterangan": "Test keterangan",
        "is_aktif": true,
        "is_urgent": false
    }'
    test_endpoint "POST" "/osis-kegiatan" "Create OSIS Activity" "201" "$osis_data"
    
    echo ""
}

# Function to test Ekstrakurikuler Controller
test_ekstrakurikuler_controller() {
    echo -e "${YELLOW}⚽ TESTING EKSTRAKURIKULER CONTROLLER${NC}"
    echo "====================================="
    
    # Test ekstrakurikuler endpoints
    test_endpoint "GET" "/ekstrakurikuler" "Get Ekstrakurikuler List" "200"
    test_endpoint "GET" "/ekstrakurikuler-statistics" "Get Ekstrakurikuler Statistics" "200"
    
    # Test create ekstrakurikuler
    ekskul_data='{
        "nama_ekstrakurikuler": "Test Ekstrakurikuler",
        "deskripsi": "Test description for ekstrakurikuler",
        "jenis": "olahraga",
        "status": "aktif",
        "pembina_id": 2,
        "ketua_id": 3,
        "jadwal_latihan": "Senin dan Kamis, 15:00-17:00",
        "tempat_latihan": "Lapangan Sekolah",
        "persyaratan_anggota": "Siswa kelas X-XII",
        "kuota_anggota": 20,
        "biaya_pendaftaran": 50000,
        "fasilitas": "Lapangan, bola, kostum",
        "prestasi": "Test prestasi",
        "is_aktif": true
    }'
    test_endpoint "POST" "/ekstrakurikuler" "Create Ekstrakurikuler" "201" "$ekskul_data"
    
    # Test student registration
    registration_data='{
        "ekstrakurikuler_id": 1,
        "siswa_id": 1,
        "catatan": "Test registration"
    }'
    test_endpoint "POST" "/ekstrakurikuler/register-student" "Register Student to Ekstrakurikuler" "201" "$registration_data"
    
    # Test get students in ekstrakurikuler
    test_endpoint "GET" "/ekstrakurikuler/1/students" "Get Students in Ekstrakurikuler" "200"
    
    echo ""
}

# Function to test existing controllers
test_existing_controllers() {
    echo -e "${YELLOW}📊 TESTING EXISTING CONTROLLERS${NC}"
    echo "================================="
    
    # Test dashboard
    test_endpoint "GET" "/dashboard/stats" "Get Dashboard Stats" "200"
    test_endpoint "GET" "/dashboard/admin" "Get Admin Dashboard" "200"
    
    # Test users
    test_endpoint "GET" "/users" "Get Users List" "200"
    test_endpoint "GET" "/users-statistics" "Get Users Statistics" "200"
    
    # Test guru
    test_endpoint "GET" "/guru" "Get Guru List" "200"
    test_endpoint "GET" "/guru-statistics" "Get Guru Statistics" "200"
    
    # Test siswa
    test_endpoint "GET" "/siswa" "Get Siswa List" "200"
    test_endpoint "GET" "/siswa/statistics/overview" "Get Siswa Statistics" "200"
    
    # Test presensi
    test_endpoint "GET" "/presensi" "Get Presensi List" "200"
    test_endpoint "GET" "/presensi-statistics" "Get Presensi Statistics" "200"
    
    # Test kredit poin
    test_endpoint "GET" "/kredit-poin" "Get Kredit Poin List" "200"
    test_endpoint "GET" "/kredit-poin-statistics" "Get Kredit Poin Statistics" "200"
    
    # Test notifications
    test_endpoint "GET" "/notifications" "Get Notifications List" "200"
    test_endpoint "GET" "/notifications/stats" "Get Notifications Statistics" "200"
    
    echo ""
}

# Function to test role-based access
test_role_access() {
    echo -e "${YELLOW}🔒 TESTING ROLE-BASED ACCESS${NC}"
    echo "============================="
    
    # Test with different user roles
    echo -e "${BLUE}Testing with Guru user...${NC}"
    guru_response=$(curl -s -X POST -H "Content-Type: application/json" -d '{"username":"guru001","password":"password123"}' "$API_BASE/login")
    if echo "$guru_response" | grep -q "token"; then
        guru_token=$(echo "$guru_response" | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
        
        # Test guru access to admin-only endpoints
        response=$(curl -s -w "\n%{http_code}" -H "Authorization: Bearer $guru_token" "$API_BASE/users")
        http_code=$(echo "$response" | tail -n1)
        if [ "$http_code" = "403" ]; then
            print_result "Guru Access to Admin Endpoint" "PASS" "Forbidden"
        else
            print_result "Guru Access to Admin Endpoint" "FAIL" "HTTP $http_code"
        fi
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
        echo -e "${GREEN}🎉 ALL TESTS PASSED! 🎉${NC}"
        echo -e "${GREEN}API is working perfectly!${NC}"
    else
        echo -e "${YELLOW}⚠️  Some tests failed. Please check the errors above.${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}Test completed at: $(date)${NC}"
}

# Main execution
main() {
    echo -e "${BLUE}Starting comprehensive API testing...${NC}"
    echo ""
    
    # Check if backend server is running
    if ! curl -s "$API_BASE/test" > /dev/null; then
        echo -e "${RED}❌ Backend server is not running!${NC}"
        echo "Please start the backend server with: cd /opt/kesiswaan/backend && php artisan serve"
        exit 1
    fi
    
    # Authenticate
    authenticate
    
    # Run all tests
    test_auth
    test_bk_controller
    test_osis_controller
    test_ekstrakurikuler_controller
    test_existing_controllers
    test_role_access
    
    # Print final results
    print_final_results
}

# Run main function
main
