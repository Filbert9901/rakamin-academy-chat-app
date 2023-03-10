<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    function sendMessage(SendMessageRequest $request)
    {
        if ($request->receiver_id == auth()->user()->id) return response()->json(["result" => "You cannot send a message to yourself"], 400);
        auth()->user()->chatFriends()->attach($request->receiver_id, ['message' => $request->message]);
        return ["result" => "Message sent successfully", "message" => $request->message];
    }

    function getConversation(User $user)
    {
        if ($user->id == auth()->user()->id) return response()->json(["result" => "You cannot view a conversation with yourself"], 400);
        $data = DB::table('sender_receiver')
            ->whereIn('sender_id', [auth()->user()->id, $user->id])
            ->whereIn('receiver_id', [auth()->user()->id, $user->id])
            ->orderBy('created_at')
            ->get();
        if ($data->isEmpty()) return response()->json(["result" => "You have not started a conversation with {$user->name}"], 200);
        // update message status to read
        DB::table('sender_receiver')
            ->where('receiver_id', auth()->user()->id)
            ->where('sender_id', $user->id)
            ->update(['status' => "Read"]);
        return ["data" => $data];
    }

    function getAllConversations()
    {
        $data = DB::table('sender_receiver')
            ->select(DB::raw('count(*) as unread_count, users.name, message, sender_id'))
            ->join('users', 'sender_receiver.sender_id', '=', 'users.id')
            ->where('receiver_id', auth()->user()->id)
            ->where('status', 'Unread')
            ->groupBy('sender_id')
            ->get();

        // set latest message
        $data = $data->map(function ($item) {
            $latest_message = DB::table('sender_receiver')
                ->select('message')
                ->where('receiver_id', auth()->user()->id)
                ->where('sender_id', $item->sender_id)
                ->where('status', 'Unread')
                ->latest('sender_receiver.created_at')->first();
            $item->message = $latest_message->message;
            return $item;
        });
        if ($data->isEmpty()) return response()->json(["result" => "You have not started any conversation yet"], 200);
        return ["data" => $data];
    }
}
