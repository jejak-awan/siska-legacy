# WhatsApp Business API Setup Guide

## Environment Configuration

Add these settings to your `.env` file:

```env
# WhatsApp Business API Configuration
WHATSAPP_API_URL=https://graph.facebook.com/v18.0
WHATSAPP_ACCESS_TOKEN=your_whatsapp_access_token_here
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id_here
WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_webhook_verify_token_here
WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id_here

# WhatsApp Rate Limiting
WHATSAPP_RATE_LIMITING_ENABLED=true
WHATSAPP_MAX_MESSAGES_PER_MINUTE=20
WHATSAPP_MAX_MESSAGES_PER_HOUR=1000
WHATSAPP_DELAY_BETWEEN_MESSAGES=100000

# WhatsApp Logging
WHATSAPP_LOGGING_ENABLED=true
WHATSAPP_LOG_ALL_MESSAGES=true
WHATSAPP_LOG_FAILED_MESSAGES=true
WHATSAPP_LOG_WEBHOOK_EVENTS=true
WHATSAPP_LOG_RETENTION_DAYS=30

# WhatsApp Testing
WHATSAPP_TESTING_ENABLED=false
WHATSAPP_TEST_PHONE_NUMBER=6281234567890
WHATSAPP_MOCK_RESPONSES=false
WHATSAPP_SIMULATE_DELAYS=false

# WhatsApp Webhook
WHATSAPP_WEBHOOK_ENABLED=true
WHATSAPP_PROCESS_INCOMING_MESSAGES=false
WHATSAPP_AUTO_REPLY=false
WHATSAPP_AUTO_REPLY_MESSAGE="Terima kasih telah menghubungi kami. Pesan Anda akan segera diproses."

# WhatsApp Security
WHATSAPP_VERIFY_SIGNATURE=true
WHATSAPP_ALLOWED_IPS=
WHATSAPP_ENCRYPT_MESSAGES=false
WHATSAPP_REQUIRE_AUTHENTICATION=true

# WhatsApp Media
WHATSAPP_CDN_URL=https://your-cdn-url.com
```

## Setup Steps

### 1. Create WhatsApp Business Account
1. Go to [Facebook Business](https://business.facebook.com/)
2. Create a business account
3. Add WhatsApp Business API

### 2. Get API Credentials
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app
3. Add WhatsApp Business API product
4. Get your access token and phone number ID

### 3. Configure Webhook
1. Set webhook URL: `https://yourdomain.com/api/whatsapp/webhook`
2. Set verify token (use the same value as WHATSAPP_WEBHOOK_VERIFY_TOKEN)
3. Subscribe to webhook fields: messages, message_deliveries, message_reads

### 4. Create Message Templates
Create the following templates in your WhatsApp Business Manager:

1. **notification** - General notifications
2. **presensi_reminder** - Attendance reminders
3. **kredit_poin_approval** - Credit point approval
4. **kredit_poin_rejection** - Credit point rejection
5. **konseling_reminder** - Counseling reminders
6. **home_visit_reminder** - Home visit reminders
7. **osis_activity** - OSIS activity notifications
8. **ekstrakurikuler_registration** - Extracurricular registration
9. **system_alert** - System alerts

### 5. Test Integration
Use the test endpoint to verify your setup:
```bash
curl -X GET "https://yourdomain.com/api/whatsapp/test-connection" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## API Endpoints

### Send Text Message
```bash
POST /api/whatsapp/send-text
{
  "to": "6281234567890",
  "message": "Hello, this is a test message",
  "user_id": 1
}
```

### Send Template Message
```bash
POST /api/whatsapp/send-template
{
  "to": "6281234567890",
  "template_name": "notification",
  "parameters": ["John Doe", "You have a new notification"],
  "user_id": 1
}
```

### Send Media Message
```bash
POST /api/whatsapp/send-media
{
  "to": "6281234567890",
  "media_url": "https://example.com/image.jpg",
  "media_type": "image",
  "caption": "Check this out!",
  "user_id": 1
}
```

### Send Bulk Messages
```bash
POST /api/whatsapp/send-bulk
{
  "recipients": [
    {"phone": "6281234567890", "user_id": 1},
    {"phone": "6281234567891", "user_id": 2}
  ],
  "message": "Bulk message content",
  "type": "text"
}
```

### Get Message Statistics
```bash
GET /api/whatsapp/statistics
```

### Get Message Logs
```bash
GET /api/whatsapp/logs?status=sent&limit=50
```

## Message Templates

### Notification Template
```
Halo {{1}}, Anda memiliki notifikasi baru: {{2}}
```

### Presensi Reminder Template
```
Halo {{1}}, jangan lupa untuk melakukan presensi hari ini. Waktu presensi: {{2}}
```

### Kredit Poin Approval Template
```
Halo {{1}}, kredit poin Anda sebesar {{2}} poin telah disetujui.
```

### Kredit Poin Rejection Template
```
Halo {{1}}, kredit poin Anda sebesar {{2}} poin telah ditolak. Alasan: {{3}}
```

### Konseling Reminder Template
```
Halo {{1}}, Anda memiliki jadwal konseling pada {{2}} pukul {{3}}.
```

### Home Visit Reminder Template
```
Halo {{1}}, akan ada kunjungan rumah pada {{2}} pukul {{3}}.
```

### OSIS Activity Template
```
Halo {{1}}, akan ada kegiatan OSIS "{{2}}" pada {{3}} di {{4}}.
```

### Ekstrakurikuler Registration Template
```
Halo {{1}}, pendaftaran Anda ke ekstrakurikuler "{{2}}" berhasil.
```

### System Alert Template
```
ALERT: {{1}} - {{2}}
```

## Rate Limiting

The system includes built-in rate limiting to prevent API quota exhaustion:
- Maximum 20 messages per minute
- Maximum 1000 messages per hour
- 0.1 second delay between messages

## Error Handling

All WhatsApp operations include comprehensive error handling:
- Automatic retry for failed messages
- Detailed error logging
- Status tracking for all messages
- Webhook processing for delivery confirmations

## Security

- Webhook signature verification
- IP whitelisting support
- Message encryption support
- Authentication required for all endpoints

## Monitoring

- Real-time message statistics
- Delivery rate tracking
- Read receipt monitoring
- Failed message analysis
- Performance metrics
