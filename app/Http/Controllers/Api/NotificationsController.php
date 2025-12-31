<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NotificationController extends Controller
{
    /**
     * 📥 Liste des notifications d'un utilisateur
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * 🔔 Notifications non lues (badge)
     */
    public function unreadCount(Request $request)
    {
        $userId = $request->user()->id;

        $count = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'success' => true,
            'unread' => $count
        ]);
    }

    /**
     * 👁 Marquer une notification comme lue
     */
    public function markAsRead($id, Request $request)
    {
        $userId = $request->user()->id;

        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'is_read' => 1,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue'
        ]);
    }

    /**
     * 📩 Créer une notification (admin / système)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        DB::table('notifications')->insert([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'info',
            'data' => $request->data ? json_encode($request->data) : null,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification créée'
        ]);
    }
}
