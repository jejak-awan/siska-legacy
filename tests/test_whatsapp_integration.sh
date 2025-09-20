#!/bin/bash

# WhatsApp Integration Testing Script for Kesiswaan System
# Tests WhatsApp Business API integration

echo "📱 WHATSAPP INTEGRATION TESTING - KESISWAAN SYSTEM"
echo "================================================="
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
    
    response=$(curl -s -X POST -H "Content-Type: application/json" -H "Accept: application/json" -d '{"username":"admin","password":"password123"}' "$API_BASE/login")
    
    if echo "$response" | grep -q "token"; then
        TOKEN=$(echo "$response" | jq -r '.data.token // .token // empty')
        if [ -z "$TOKEN" ] || [ "$TOKEN" = "null" ]; then
            echo -e "${RED}❌ Authentication failed - No token found${NC}"
            echo "Response: $response"
            exit 1
        fi
        echo -e "${GREEN}✅ Authentication successful${NC}"
        echo "Token: ${TOKEN:0:20}..."
        echo ""
    else
        echo -e "${RED}❌ Authentication failed${NC}"
        echo "Response: $response"
        exit 1
    fi
}

# Function to test WhatsApp connection
test_whatsapp_connection() {
    echo -e "${YELLOW}🔗 TESTING WHATSAPP CONNECTION${NC}"
    echo "================================="
    
    # Test connection
    test_endpoint "/whatsapp/test-connection" "Test WhatsApp Connection" "200"
    
    echo ""
}

# Function to test WhatsApp message sending
test_whatsapp_messaging() {
    echo -e "${YELLOW}💬 TESTING WHATSAPP MESSAGING${NC}"
    echo "=================================="
    
    # Test send text message
    test_endpoint "/whatsapp/send-text" "Send Text Message" "200" "POST" '{
        "to": "6281234567890",
        "message": "Test message from Kesiswaan System",
        "user_id": 1
    }'
    
    # Test send template message
    test_endpoint "/whatsapp/send-template" "Send Template Message" "200" "POST" '{
        "to": "6281234567890",
        "template_name": "notification",
        "parameters": ["Test User", "Test notification message"],
        "user_id": 1
    }'
    
    # Test send media message
    test_endpoint "/whatsapp/send-media" "Send Media Message" "200" "POST" '{
        "to": "6281234567890",
        "media_url": "https://via.placeholder.com/300x200.jpg",
        "media_type": "image",
        "caption": "Test image from Kesiswaan System",
        "user_id": 1
    }'
    
    echo ""
}

# Function to test WhatsApp bulk messaging
test_whatsapp_bulk_messaging() {
    echo -e "${YELLOW}📢 TESTING WHATSAPP BULK MESSAGING${NC}"
    echo "====================================="
    
    # Test send bulk messages
    test_endpoint "/whatsapp/send-bulk" "Send Bulk Messages" "200" "POST" '{
        "recipients": [
            {"phone": "6281234567890", "user_id": 1},
            {"phone": "6281234567891", "user_id": 2}
        ],
        "message": "Bulk test message from Kesiswaan System",
        "type": "text"
    }'
    
    # Test send bulk template messages
    test_endpoint "/whatsapp/send-bulk" "Send Bulk Template Messages" "200" "POST" '{
        "recipients": [
            {"phone": "6281234567890", "user_id": 1, "template": "notification", "parameters": ["User 1", "Test notification"]},
            {"phone": "6281234567891", "user_id": 2, "template": "notification", "parameters": ["User 2", "Test notification"]}
        ],
        "message": "Bulk template message",
        "type": "template",
        "template_name": "notification"
    }'
    
    echo ""
}

# Function to test WhatsApp statistics and logs
test_whatsapp_analytics() {
    echo -e "${YELLOW}📊 TESTING WHATSAPP ANALYTICS${NC}"
    echo "================================="
    
    # Test get message statistics
    test_endpoint "/whatsapp/statistics" "Get Message Statistics" "200"
    
    # Test get message logs
    test_endpoint "/whatsapp/logs" "Get Message Logs" "200"
    
    # Test get recent messages
    test_endpoint "/whatsapp/recent" "Get Recent Messages" "200"
    
    # Test get message logs with filters
    test_endpoint "/whatsapp/logs?status=sent&limit=10" "Get Filtered Message Logs" "200"
    
    echo ""
}

# Function to test WhatsApp webhook
test_whatsapp_webhook() {
    echo -e "${YELLOW}🔗 TESTING WHATSAPP WEBHOOK${NC}"
    echo "==============================="
    
    # Test webhook verification
    test_endpoint "/whatsapp/webhook?hub.mode=subscribe&hub.verify_token=test_token&hub.challenge=test_challenge" "Webhook Verification" "200"
    
    # Test webhook handler (this will fail without proper webhook data, but we test the endpoint)
    test_endpoint "/whatsapp/webhook" "Webhook Handler" "200" "POST" '{
        "object": "whatsapp_business_account",
        "entry": []
    }'
    
    echo ""
}

# Function to test WhatsApp integration with notifications
test_whatsapp_notification_integration() {
    echo -e "${YELLOW}🔔 TESTING WHATSAPP NOTIFICATION INTEGRATION${NC}"
    echo "==============================================="
    
    # Test send notification with WhatsApp
    test_endpoint "/notifications" "Send Notification with WhatsApp" "201" "POST" '{
        "user_id": 1,
        "judul": "Test WhatsApp Notification",
        "pesan": "This is a test notification that should trigger WhatsApp message",
        "tipe": "info",
        "send_whatsapp": true
    }'
    
    echo ""
}

# Function to test WhatsApp message status
test_whatsapp_message_status() {
    echo -e "${YELLOW}📋 TESTING WHATSAPP MESSAGE STATUS${NC}"
    echo "====================================="
    
    # First send a message to get message ID
    echo -e "${BLUE}Testing: Send Message for Status Check${NC}"
    response=$(curl -s -X POST -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d '{
        "to": "6281234567890",
        "message": "Test message for status check",
        "user_id": 1
    }' "$API_BASE/whatsapp/send-text")
    
    if echo "$response" | grep -q "message_id"; then
        message_id=$(echo "$response" | grep -o '"message_id":"[^"]*"' | cut -d'"' -f4)
        echo "Message ID: $message_id"
        
        # Test get message status
        test_endpoint "/whatsapp/message-status/$message_id" "Get Message Status" "200"
    else
        print_result "Send Message for Status Check" "FAIL" "Failed to get message ID"
    fi
    
    echo ""
}

# Function to test WhatsApp cleanup
test_whatsapp_cleanup() {
    echo -e "${YELLOW}🧹 TESTING WHATSAPP CLEANUP${NC}"
    echo "============================="
    
    # Test cleanup old logs
    test_endpoint "/whatsapp/cleanup-logs" "Cleanup Old Logs" "200" "POST" '{
        "days_old": 30
    }'
    
    echo ""
}

# Function to test WhatsApp error handling
test_whatsapp_error_handling() {
    echo -e "${YELLOW}⚠️ TESTING WHATSAPP ERROR HANDLING${NC}"
    echo "====================================="
    
    # Test invalid phone number
    test_endpoint "/whatsapp/send-text" "Send to Invalid Phone Number" "422" "POST" '{
        "to": "invalid_phone",
        "message": "Test message",
        "user_id": 1
    }'
    
    # Test empty message
    test_endpoint "/whatsapp/send-text" "Send Empty Message" "422" "POST" '{
        "to": "6281234567890",
        "message": "",
        "user_id": 1
    }'
    
    # Test invalid template
    test_endpoint "/whatsapp/send-template" "Send Invalid Template" "422" "POST" '{
        "to": "6281234567890",
        "template_name": "invalid_template",
        "parameters": [],
        "user_id": 1
    }'
    
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
        echo -e "${GREEN}🎉 ALL WHATSAPP INTEGRATION TESTS PASSED! 🎉${NC}"
        echo -e "${GREEN}WhatsApp integration is working perfectly!${NC}"
    else
        echo -e "${YELLOW}⚠️  Some WhatsApp integration tests failed. Please check the errors above.${NC}"
        echo -e "${YELLOW}Note: Some tests may fail if WhatsApp API credentials are not configured.${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}Test completed at: $(date)${NC}"
}

# Main execution
main() {
    echo -e "${BLUE}Starting WhatsApp integration testing...${NC}"
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
    test_whatsapp_connection
    test_whatsapp_messaging
    test_whatsapp_bulk_messaging
    test_whatsapp_analytics
    test_whatsapp_webhook
    test_whatsapp_notification_integration
    test_whatsapp_message_status
    test_whatsapp_cleanup
    test_whatsapp_error_handling
    
    # Print final results
    print_final_results
}

# Run main function
main
