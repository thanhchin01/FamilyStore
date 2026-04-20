<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Lấy danh sách tin nhắn (Dùng cho cả Client và Admin)
     */
    public function getMessages(Request $request, $conversationId = null)
    {
        $user = Auth::user();
        
        if ($user->role === 'client') {
            $conversation = Conversation::firstOrCreate(
                ['user_id' => $user->id],
                ['status' => 'active', 'last_message_at' => now()]
            );
            $queryId = $conversation->id;
        } else {
            // Admin phải truyền conversationId
            $queryId = $conversationId;
        }

        if (!$queryId) {
            return response()->json(['success' => false, 'message' => 'Hội thoại không tồn tại'], 404);
        }

        $messages = Message::where('conversation_id', $queryId)
            ->with('sender:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get();

        // Đánh dấu đã đọc cho người nhận
        Message::where('conversation_id', $queryId)
            ->where('sender_id', '!=', $user->id)
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'data' => $messages]);
    }

    /**
     * Gửi tin nhắn
     */
    public function sendMessage(Request $request, $conversationId = null)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $user = Auth::user();

        if ($user->role === 'client') {
            $conversation = Conversation::firstOrCreate(['user_id' => $user->id]);
            $targetId = $conversation->id;
        } else {
            $targetId = $conversationId;
        }

        if (!$targetId) {
             return response()->json(['success' => false, 'message' => 'Thiếu ID hội thoại'], 400);
        }

        $message = Message::create([
            'conversation_id' => $targetId,
            'sender_id'       => $user->id,
            'message'         => $request->message,
            'is_read'         => false
        ]);

        Conversation::find($targetId)->update(['last_message_at' => now()]);

        return response()->json(['success' => true, 'data' => $message]);
    }

    /**
     * Admin: Danh sách các hội thoại đang chờ trả lời
     */
    public function adminConversations()
    {
        $conversations = Conversation::with(['user', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderBy('last_message_at', 'desc')->paginate(15);

        return response()->json(['success' => true, 'data' => $conversations]);
    }
}
