<?php

namespace App\Http\Controllers;

use App\Models\JivochatMessage;
use Illuminate\Http\Request;

class JivoChatController extends Controller
{
    // Webhook que JivoChat llama cuando hay nuevo mensaje
    public function webhook(Request $request)
    {
        $data = $request->all();

        JivochatMessage::create([
            'sender_name' => $data['sender_name'] ?? 'Usuario',
            'sender_phone' => $data['sender_phone'] ?? null,
            'message' => $data['message'] ?? '',
            'channel' => $data['channel'] ?? 'jivochat',
            'status' => 'received',
        ]);

        return response()->json(['success' => true]);
    }

    // Ver todos los mensajes (admin)
    public function index()
    {
        $messages = JivochatMessage::latest()->paginate(20);
        return view('admin.jivochat.index', compact('messages'));
    }
}