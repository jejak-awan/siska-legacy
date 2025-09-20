<?php

namespace App\Services;

use App\Models\WhatsAppLog;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WhatsAppService
{
    protected $apiUrl;
    protected $accessToken;
    protected $phoneNumberId;
    protected $webhookVerifyToken;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->webhookVerifyToken = config('services.whatsapp.webhook_verify_token');
    }

    /**
     * Send text message
     */
    public function sendTextMessage(string $to, string $message, ?int $userId = null): array
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($to);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            $responseData = $response->json();
            $messageId = $responseData['messages'][0]['id'] ?? null;

            // Log the message
            $this->logMessage([
                'phone_number' => $phoneNumber,
                'message' => $message,
                'status' => $response->successful() ? 'sent' : 'failed',
                'message_id' => $messageId,
                'response_data' => $responseData,
                'error_message' => $response->successful() ? null : $response->body(),
                'user_id' => $userId,
                'message_type' => 'text'
            ]);

            return [
                'success' => $response->successful(),
                'message_id' => $messageId,
                'response' => $responseData,
                'error' => $response->successful() ? null : $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp text message failed: ' . $e->getMessage());
            
            $this->logMessage([
                'phone_number' => $to,
                'message' => $message,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'user_id' => $userId,
                'message_type' => 'text'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send template message
     */
    public function sendTemplateMessage(string $to, string $templateName, array $parameters = [], ?int $userId = null): array
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($to);
            
            $templateData = [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => 'id'
                    ]
                ]
            ];

            // Add parameters if provided
            if (!empty($parameters)) {
                $templateData['template']['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => array_map(function($param) {
                            return ['type' => 'text', 'text' => $param];
                        }, $parameters)
                    ]
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", $templateData);

            $responseData = $response->json();
            $messageId = $responseData['messages'][0]['id'] ?? null;

            // Log the message
            $this->logMessage([
                'phone_number' => $phoneNumber,
                'message' => "Template: {$templateName}",
                'status' => $response->successful() ? 'sent' : 'failed',
                'message_id' => $messageId,
                'response_data' => $responseData,
                'error_message' => $response->successful() ? null : $response->body(),
                'user_id' => $userId,
                'template_name' => $templateName,
                'template_params' => $parameters,
                'message_type' => 'template'
            ]);

            return [
                'success' => $response->successful(),
                'message_id' => $messageId,
                'response' => $responseData,
                'error' => $response->successful() ? null : $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp template message failed: ' . $e->getMessage());
            
            $this->logMessage([
                'phone_number' => $to,
                'message' => "Template: {$templateName}",
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'user_id' => $userId,
                'template_name' => $templateName,
                'template_params' => $parameters,
                'message_type' => 'template'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send media message (image, document, etc.)
     */
    public function sendMediaMessage(string $to, string $mediaUrl, string $mediaType = 'image', ?string $caption = null, ?int $userId = null): array
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($to);
            
            $mediaData = [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => $mediaType,
                $mediaType => [
                    'link' => $mediaUrl
                ]
            ];

            if ($caption && in_array($mediaType, ['image', 'video', 'document'])) {
                $mediaData[$mediaType]['caption'] = $caption;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", $mediaData);

            $responseData = $response->json();
            $messageId = $responseData['messages'][0]['id'] ?? null;

            // Log the message
            $this->logMessage([
                'phone_number' => $phoneNumber,
                'message' => "Media: {$mediaType} - {$mediaUrl}",
                'status' => $response->successful() ? 'sent' : 'failed',
                'message_id' => $messageId,
                'response_data' => $responseData,
                'error_message' => $response->successful() ? null : $response->body(),
                'user_id' => $userId,
                'message_type' => $mediaType
            ]);

            return [
                'success' => $response->successful(),
                'message_id' => $messageId,
                'response' => $responseData,
                'error' => $response->successful() ? null : $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp media message failed: ' . $e->getMessage());
            
            $this->logMessage([
                'phone_number' => $to,
                'message' => "Media: {$mediaType} - {$mediaUrl}",
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'user_id' => $userId,
                'message_type' => $mediaType
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send interactive message (buttons, lists)
     */
    public function sendInteractiveMessage(string $to, array $interactiveData, ?int $userId = null): array
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($to);
            
            $messageData = [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'interactive',
                'interactive' => $interactiveData
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", $messageData);

            $responseData = $response->json();
            $messageId = $responseData['messages'][0]['id'] ?? null;

            // Log the message
            $this->logMessage([
                'phone_number' => $phoneNumber,
                'message' => 'Interactive message',
                'status' => $response->successful() ? 'sent' : 'failed',
                'message_id' => $messageId,
                'response_data' => $responseData,
                'error_message' => $response->successful() ? null : $response->body(),
                'user_id' => $userId,
                'message_type' => 'interactive'
            ]);

            return [
                'success' => $response->successful(),
                'message_id' => $messageId,
                'response' => $responseData,
                'error' => $response->successful() ? null : $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp interactive message failed: ' . $e->getMessage());
            
            $this->logMessage([
                'phone_number' => $to,
                'message' => 'Interactive message',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'user_id' => $userId,
                'message_type' => 'interactive'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk messages
     */
    public function sendBulkMessages(array $recipients, string $message, string $type = 'text'): array
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($recipients as $recipient) {
            $phoneNumber = is_array($recipient) ? $recipient['phone'] : $recipient;
            $userId = is_array($recipient) ? $recipient['user_id'] : null;

            if ($type === 'template') {
                $templateName = $recipient['template'] ?? 'default';
                $parameters = $recipient['parameters'] ?? [];
                $result = $this->sendTemplateMessage($phoneNumber, $templateName, $parameters, $userId);
            } else {
                $result = $this->sendTextMessage($phoneNumber, $message, $userId);
            }

            $results[] = [
                'phone_number' => $phoneNumber,
                'result' => $result
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
            }

            // Add delay between messages to avoid rate limiting
            usleep(100000); // 0.1 second delay
        }

        return [
            'total' => count($recipients),
            'success' => $successCount,
            'failed' => $failureCount,
            'results' => $results
        ];
    }

    /**
     * Get message status
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get("{$this->apiUrl}/{$messageId}");

            if ($response->successful()) {
                $data = $response->json();
                
                // Update log if exists
                $log = WhatsAppLog::where('message_id', $messageId)->first();
                if ($log) {
                    $log->update([
                        'status' => $data['status'] ?? 'unknown',
                        'response_data' => $data
                    ]);
                }

                return [
                    'success' => true,
                    'status' => $data['status'] ?? 'unknown',
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get message status'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get WhatsApp message status: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook
     */
    public function verifyWebhook(string $mode, string $token, string $challenge): ?string
    {
        if ($mode === 'subscribe' && $token === $this->webhookVerifyToken) {
            return $challenge;
        }
        return null;
    }

    /**
     * Process webhook
     */
    public function processWebhook(array $data): void
    {
        try {
            if (isset($data['entry'])) {
                foreach ($data['entry'] as $entry) {
                    if (isset($entry['changes'])) {
                        foreach ($entry['changes'] as $change) {
                            if (isset($change['value']['statuses'])) {
                                foreach ($change['value']['statuses'] as $status) {
                                    $this->updateMessageStatus($status);
                                }
                            }
                            
                            if (isset($change['value']['messages'])) {
                                foreach ($change['value']['messages'] as $message) {
                                    $this->processIncomingMessage($message);
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to process WhatsApp webhook: ' . $e->getMessage());
        }
    }

    /**
     * Update message status from webhook
     */
    private function updateMessageStatus(array $statusData): void
    {
        try {
            $messageId = $statusData['id'] ?? null;
            $status = $statusData['status'] ?? null;
            $timestamp = $statusData['timestamp'] ?? null;

            if ($messageId && $status) {
                $log = WhatsAppLog::where('message_id', $messageId)->first();
                if ($log) {
                    $log->update([
                        'status' => $status,
                        'response_data' => array_merge($log->response_data ?? [], [
                            'status_update' => $statusData,
                            'updated_at' => now()
                        ])
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to update message status: ' . $e->getMessage());
        }
    }

    /**
     * Process incoming message
     */
    private function processIncomingMessage(array $messageData): void
    {
        try {
            // Log incoming message for future processing
            Log::info('WhatsApp incoming message received', [
                'message_id' => $messageData['id'] ?? null,
                'from' => $messageData['from'] ?? null,
                'type' => $messageData['type'] ?? null,
                'timestamp' => $messageData['timestamp'] ?? null
            ]);

            // Here you can add logic to process incoming messages
            // For example, auto-replies, command processing, etc.
        } catch (\Exception $e) {
            Log::error('Failed to process incoming message: ' . $e->getMessage());
        }
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if not present
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }
        
        return $phoneNumber;
    }

    /**
     * Log message to database
     */
    private function logMessage(array $data): void
    {
        try {
            WhatsAppLog::create([
                'phone_number' => $data['phone_number'],
                'message' => $data['message'],
                'status' => $data['status'],
                'message_id' => $data['message_id'] ?? null,
                'response_data' => $data['response_data'] ?? null,
                'error_message' => $data['error_message'] ?? null,
                'user_id' => $data['user_id'] ?? null,
                'template_name' => $data['template_name'] ?? null,
                'template_params' => $data['template_params'] ?? null,
                'message_type' => $data['message_type'] ?? 'text'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log WhatsApp message: ' . $e->getMessage());
        }
    }

    /**
     * Get message statistics
     */
    public function getMessageStatistics(): array
    {
        $total = WhatsAppLog::count();
        $sent = WhatsAppLog::where('status', 'sent')->count();
        $delivered = WhatsAppLog::where('status', 'delivered')->count();
        $read = WhatsAppLog::where('status', 'read')->count();
        $failed = WhatsAppLog::where('status', 'failed')->count();

        return [
            'total_messages' => $total,
            'sent' => $sent,
            'delivered' => $delivered,
            'read' => $read,
            'failed' => $failed,
            'delivery_rate' => $total > 0 ? round(($delivered / $total) * 100, 2) : 0,
            'read_rate' => $total > 0 ? round(($read / $total) * 100, 2) : 0,
            'failure_rate' => $total > 0 ? round(($failed / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get recent messages
     */
    public function getRecentMessages(int $limit = 50): array
    {
        return WhatsAppLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Clean up old logs
     */
    public function cleanupOldLogs(int $daysOld = 30): int
    {
        return WhatsAppLog::where('created_at', '<', now()->subDays($daysOld))->delete();
    }
}
