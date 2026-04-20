<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Client: Gửi tin nhắn
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để gửi tin nhắn.'], 401);
        }

        $user = Auth::user();
        
        // Tìm hoặc tạo hội thoại cho user này
        $conversation = Conversation::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 'active', 'last_message_at' => now()]
        );

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json(['success' => true, 'data' => $message]);
    }

    /**
     * Client: Lấy danh sách tin nhắn
     */
    public function getMessages()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $conversation = Conversation::where('user_id', Auth::id())->first();
        
        if (!$conversation) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['success' => true, 'data' => $messages]);
    }

    /**
     * Admin: Danh sách hội thoại
     */
    public function adminIndex()
    {
        $conversations = Conversation::with(['user', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderBy('last_message_at', 'desc')->get();

        return view('admin.chat.index', compact('conversations'));
    }

    /**
     * Admin: Lấy tin nhắn của 1 hội thoại
     */
    public function adminGetMessages($id)
    {
        $conversation = Conversation::findOrFail($id);
        $messages = Message::where('conversation_id', $id)
            ->with('sender:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get();

        // Đánh dấu đã đọc
        Message::where('conversation_id', $id)
            ->where('sender_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'data' => $messages]);
    }

    /**
     * Admin: Gửi tin nhắn trả lời
     */
    public function adminReply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $conversation = Conversation::findOrFail($id);
        
        $message = Message::create([
            'conversation_id' => $id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json(['success' => true, 'data' => $message]);
    }
}
