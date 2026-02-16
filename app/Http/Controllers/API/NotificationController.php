<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user_id = $request->get('user_id');
            $is_read = $request->get('is_read'); // null for all, true for read, false for unread
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 20);

            // $notifications = Notification::where('user_id', $user_id);
            // if ($is_read !== null) {
            //     $notifications->where('is_read', $is_read);
            // }
            // $notifications = $notifications->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Notifications retrieved successfully',
                'data' => [
                    // 'notifications' => $notifications->items(),
                    'pagination' => [
                        // 'total' => $notifications->total(),
                        // 'per_page' => $notifications->perPage(),
                        // 'current_page' => $notifications->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            // $notification = Notification::findOrFail($id);
            // $notification->update(['is_read' => true, 'read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'data' => [
                    // 'notification' => $notification,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user_id = $request->get('user_id');

            // $updated = Notification::where('user_id', $user_id)
            //     ->where('is_read', false)
            //     ->update(['is_read' => true, 'read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'data' => [
                    // 'updated_count' => $updated,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete notification
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id)
    {
        try {
            // $notification = Notification::findOrFail($id);
            // $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
