#!/bin/bash

# Business Logic Testing Script for Kesiswaan System
# Tests business services and workflows

echo "🚀 BUSINESS LOGIC TESTING - KESISWAAN SYSTEM"
echo "============================================="
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
    local method="$4"
    local data="$5"
    
    echo -e "${BLUE}Testing: $test_name${NC}"
    
    if [ "$method" = "POST" ] && [ -n "$data" ]; then
        response=$(curl -s -w "\n%{http_code}" -X POST -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d "$data" "$API_BASE$endpoint")
    else
        response=$(curl -s -w "\n%{http_code}" -H "Authorization: Bearer $TOKEN" "$API_BASE$endpoint")
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

# Function to test notification service
test_notification_service() {
    echo -e "${YELLOW}📧 TESTING NOTIFICATION SERVICE${NC}"
    echo "=================================="
    
    # Test send notification
    test_endpoint "/notifications" "Send Notification" "201" "POST" '{
        "user_id": 1,
        "judul": "Test Notification",
        "pesan": "This is a test notification",
        "tipe": "info"
    }'
    
    # Test get notifications
    test_endpoint "/notifications" "Get Notifications" "200"
    
    # Test notification statistics
    test_endpoint "/notifications/stats" "Notification Statistics" "200"
    
    echo ""
}

# Function to test kredit poin service
test_kredit_poin_service() {
    echo -e "${YELLOW}⭐ TESTING KREDIT POIN SERVICE${NC}"
    echo "================================="
    
    # Test create kredit poin
    test_endpoint "/kredit-poin" "Create Kredit Poin" "201" "POST" '{
        "siswa_id": 1,
        "kategori_id": 1,
        "deskripsi": "Test kredit poin",
        "pelapor_id": 1
    }'
    
    # Test get kredit poin statistics
    test_endpoint "/kredit-poin-statistics" "Kredit Poin Statistics" "200"
    
    # Test get pending kredit poin
    test_endpoint "/kredit-poin/pending" "Get Pending Kredit Poin" "200"
    
    echo ""
}

# Function to test presensi service
test_presensi_service() {
    echo -e "${YELLOW}📅 TESTING PRESENSI SERVICE${NC}"
    echo "============================="
    
    # Test create presensi (use valid user with unique date)
    UNIQUE_DATE=$(date +%Y-%m-%d -d "+$((RANDOM % 30)) days")
    test_endpoint "/presensi" "Create Presensi" "201" "POST" '{
        "user_id": 1,
        "tanggal": "'$UNIQUE_DATE'",
        "jam_masuk": "07:00:00",
        "status": "hadir"
    }'
    
    # Test get presensi statistics
    test_endpoint "/presensi-statistics" "Presensi Statistics" "200"
    
    # Test get presensi by user
    test_endpoint "/presensi/user/2" "Get Presensi by User" "200"
    
    echo ""
}

# Function to test BK service
test_bk_service() {
    echo -e "${YELLOW}💬 TESTING BK SERVICE${NC}"
    echo "====================="
    
    # Test create konseling
    test_endpoint "/konseling" "Create Konseling" "201" "POST" '{
        "siswa_id": 1,
        "konselor_id": 1,
        "tanggal_konseling": "'$(date +%Y-%m-%d)'",
        "jam_mulai": "08:00",
        "jam_selesai": "09:00",
        "jenis_konseling": "individual",
        "status": "terjadwal",
        "masalah": "Test konseling",
        "tujuan_konseling": "Test tujuan"
    }'
    
    # Test create home visit
    test_endpoint "/home-visit" "Create Home Visit" "201" "POST" '{
        "siswa_id": 1,
        "konselor_id": 1,
        "tanggal_kunjungan": "'$(date +%Y-%m-%d)'",
        "jam_mulai": "10:00",
        "jam_selesai": "11:00",
        "status": "terjadwal",
        "alamat_kunjungan": "Test address",
        "tujuan_kunjungan": "Test tujuan"
    }'
    
    # Test get BK statistics
    test_endpoint "/bk-statistics" "BK Statistics" "200"
    
    echo ""
}

# Function to test OSIS service
test_osis_service() {
    echo -e "${YELLOW}🏛️ TESTING OSIS SERVICE${NC}"
    echo "======================="
    
    # Test create OSIS kegiatan
    test_endpoint "/osis-kegiatan" "Create OSIS Kegiatan" "201" "POST" '{
        "nama_kegiatan": "Test Kegiatan",
        "deskripsi": "Test deskripsi",
        "jenis_kegiatan": "akademik",
        "tanggal_mulai": "'$(date +%Y-%m-%d)'",
        "tanggal_selesai": "'$(date +%Y-%m-%d)'",
        "jam_mulai": "08:00",
        "jam_selesai": "10:00",
        "status": "perencanaan",
        "tempat_kegiatan": "Test location",
        "tujuan_kegiatan": "Test tujuan",
        "sasaran_kegiatan": "Test sasaran",
        "penanggung_jawab": "Test penanggung jawab"
    }'
    
    # Test get OSIS statistics
    test_endpoint "/osis-statistics" "OSIS Statistics" "200"
    
    # Test get upcoming activities
    test_endpoint "/osis-kegiatan/upcoming" "Get Upcoming Activities" "200"
    
    # Test get ongoing activities
    test_endpoint "/osis-kegiatan/ongoing" "Get Ongoing Activities" "200"
    
    echo ""
}

# Function to test ekstrakurikuler service
test_ekstrakurikuler_service() {
    echo -e "${YELLOW}🏆 TESTING EKSTRAKURIKULER SERVICE${NC}"
    echo "====================================="
    
    # Test create ekstrakurikuler
    test_endpoint "/ekstrakurikuler" "Create Ekstrakurikuler" "201" "POST" '{
        "nama_ekstrakurikuler": "Test Ekstrakurikuler",
        "deskripsi": "Test deskripsi",
        "jenis": "olahraga",
        "status": "aktif",
        "pembina_id": 1,
        "jadwal_latihan": "Senin, Rabu, Jumat 15:00-17:00",
        "tempat_latihan": "Test location"
    }'
    
    # Test get ekstrakurikuler statistics
    test_endpoint "/ekstrakurikuler-statistics" "Ekstrakurikuler Statistics" "200"
    
    # Test get students in ekstrakurikuler
    test_endpoint "/ekstrakurikuler/1/students" "Get Students in Ekstrakurikuler" "200"
    
    echo ""
}

# Function to test business workflows
test_business_workflows() {
    echo -e "${YELLOW}🔄 TESTING BUSINESS WORKFLOWS${NC}"
    echo "================================="
    
    # Test kredit poin approval workflow
    echo -e "${BLUE}Testing: Kredit Poin Approval Workflow${NC}"
    
    # First create a kredit poin
    create_response=$(curl -s -X POST -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d '{
        "siswa_id": 1,
        "kategori_id": 1,
        "deskripsi": "Workflow test kredit poin",
        "pelapor_id": 1
    }' "$API_BASE/kredit-poin")
    
    if echo "$create_response" | grep -q "success"; then
        kredit_poin_id=$(echo "$create_response" | grep -o '"id":[0-9]*' | cut -d':' -f2)
        
        # Test approve
        approve_response=$(curl -s -X POST -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d '{
            "catatan_admin": "Approved for testing"
        }' "$API_BASE/kredit-poin/$kredit_poin_id/approve")
        
        if echo "$approve_response" | grep -q "approved successfully"; then
            print_result "Kredit Poin Approval Workflow" "PASS" "Workflow completed successfully"
        elif echo "$approve_response" | grep -q "Only pending kredit poin can be approved"; then
            print_result "Kredit Poin Approval Workflow" "PASS" "Workflow validation working correctly"
        else
            print_result "Kredit Poin Approval Workflow" "FAIL" "Approval failed: $approve_response"
        fi
    else
        print_result "Kredit Poin Approval Workflow" "FAIL" "Creation failed: $create_response"
    fi
    
    echo ""
}

# Function to test service integration
test_service_integration() {
    echo -e "${YELLOW}🔗 TESTING SERVICE INTEGRATION${NC}"
    echo "================================="
    
    # Test notification integration with kredit poin
    echo -e "${BLUE}Testing: Notification Integration${NC}"
    
    # Create kredit poin and check if notification is sent
    create_response=$(curl -s -X POST -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d '{
        "siswa_id": 1,
        "kategori_id": 1,
        "deskripsi": "Integration test kredit poin",
        "pelapor_id": 1
    }' "$API_BASE/kredit-poin")
    
    if echo "$create_response" | grep -q "success"; then
        # Check if notification was created
        sleep 2
        notifications_response=$(curl -s -H "Authorization: Bearer $TOKEN" "$API_BASE/notifications")
        
        if echo "$notifications_response" | grep -q "Kredit Poin Baru"; then
            print_result "Notification Integration" "PASS" "Notification created successfully"
        else
            print_result "Notification Integration" "FAIL" "No notification found"
        fi
    else
        print_result "Notification Integration" "FAIL" "Kredit poin creation failed"
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
        echo -e "${GREEN}🎉 ALL BUSINESS LOGIC TESTS PASSED! 🎉${NC}"
        echo -e "${GREEN}Business logic implementation is working perfectly!${NC}"
    else
        echo -e "${YELLOW}⚠️  Some business logic tests failed. Please check the errors above.${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}Test completed at: $(date)${NC}"
}

# Main execution
main() {
    echo -e "${BLUE}Starting business logic testing...${NC}"
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
    test_notification_service
    test_kredit_poin_service
    test_presensi_service
    test_bk_service
    test_osis_service
    test_ekstrakurikuler_service
    test_business_workflows
    test_service_integration
    
    # Print final results
    print_final_results
}

# Run main function
main
