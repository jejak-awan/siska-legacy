<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use App\Models\WhatsAppLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send text message
     */
    public function sendTextMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|string',
            'message' => 'required|string|max:4096',
            'user_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->whatsappService->sendTextMessage(
                $request->to,
                $request->message,
                $request->user_id
            );

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Message sent successfully' : 'Failed to send message',
                'data' => $result
            ], $result['success'] ? 200 : 500);

        } catch (\Exception $e) {
            Log::error('WhatsApp send text message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send template message
     */
    public function sendTemplateMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|string',
            'template_name' => 'required|string',
            'parameters' => 'nullable|array',
            'user_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->whatsappService->sendTemplateMessage(
                $request->to,
                $request->template_name,
                $request->parameters ?? [],
                $request->user_id
            );

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Template message sent successfully' : 'Failed to send template message',
                'data' => $result
            ], $result['success'] ? 200 : 500);

        } catch (\Exception $e) {
            Log::error('WhatsApp send template message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send template message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send media message
     */
    public function sendMediaMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|string',
            'media_url' => 'required|url',
            'media_type' => 'required|in:image,video,document,audio',
            'caption' => 'nullable|string|max:1024',
            'user_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->whatsappService->sendMediaMessage(
                $request->to,
                $request->media_url,
                $request->media_type,
                $request->caption,
                $request->user_id
            );

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Media message sent successfully' : 'Failed to send media message',
                'data' => $result
            ], $result['success'] ? 200 : 500);

        } catch (\Exception $e) {
            Log::error('WhatsApp send media message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send media message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send bulk messages
     */
    public function sendBulkMessages(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'recipients' => 'required|array|min:1|max:100',
            'recipients.*.phone' => 'required|string',
            'recipients.*.user_id' => 'nullable|exists:users,id',
            'message' => 'required|string|max:4096',
            'type' => 'required|in:text,template',
            'template_name' => 'required_if:type,template|string',
            'parameters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $recipients = $request->recipients;
            
            // Add template info if needed
            if ($request->type === 'template') {
                foreach ($recipients as &$recipient) {
                    $recipient['template'] = $request->template_name;
                    $recipient['parameters'] = $request->parameters ?? [];
                }
            }

            $result = $this->whatsappService->sendBulkMessages(
                $recipients,
                $request->message,
                $request->type
            );

            return response()->json([
                'success' => true,
                'message' => 'Bulk messages processed',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp send bulk messages error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send bulk messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get message status
     */
    public function getMessageStatus(string $messageId): JsonResponse
    {
        try {
            $result = $this->whatsappService->getMessageStatus($messageId);

            return response()->json([
                'success' => $result['success'],
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp get message status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get message status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get message logs
     */
    public function getMessageLogs(Request $request): JsonResponse
    {
        try {
            $query = WhatsAppLog::with('user');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by phone number
            if ($request->has('phone_number')) {
                $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
            }

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            // Filter by message type
            if ($request->has('message_type')) {
                $query->where('message_type', $request->message_type);
            }

            $perPage = $request->get('per_page', 15);
            $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $logs
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp get message logs error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get message logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get message statistics
     */
    public function getMessageStatistics(): JsonResponse
    {
        try {
            $stats = $this->whatsappService->getMessageStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp get message statistics error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get message statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent messages
     */
    public function getRecentMessages(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 50);
            $messages = $this->whatsappService->getRecentMessages($limit);

            return response()->json([
                'success' => true,
                'data' => $messages
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp get recent messages error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get recent messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook verification
     */
    public function webhookVerify(Request $request): JsonResponse
    {
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        $challengeResponse = $this->whatsappService->verifyWebhook($mode, $token, $challenge);

        if ($challengeResponse) {
            return response($challengeResponse, 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Webhook verification failed'
        ], 403);
    }

    /**
     * Webhook handler
     */
    public function webhookHandler(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            
            // Log webhook data
            Log::info('WhatsApp webhook received', $data);
            
            // Process webhook
            $this->whatsappService->processWebhook($data);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp webhook processing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process webhook',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up old logs
     */
    public function cleanupOldLogs(Request $request): JsonResponse
    {
        try {
            $daysOld = $request->get('days_old', 30);
            $deletedCount = $this->whatsappService->cleanupOldLogs($daysOld);

            return response()->json([
                'success' => true,
                'message' => "Cleaned up {$deletedCount} old log entries",
                'data' => [
                    'deleted_count' => $deletedCount,
                    'days_old' => $daysOld
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp cleanup old logs error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cleanup old logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(): JsonResponse
    {
        try {
            // Test with a simple API call
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.whatsapp.access_token'),
            ])->get(config('services.whatsapp.api_url') . '/' . config('services.whatsapp.phone_number_id'));

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'WhatsApp connection successful',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp connection failed',
                    'error' => $response->body()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp test connection error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to test WhatsApp connection',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
