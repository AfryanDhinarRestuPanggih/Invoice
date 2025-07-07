<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected $notificationModel;
    
    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }
    
    public function index()
    {
        $userId = session()->get('user_id');
        
        $data = [
            'title' => 'Notifikasi',
            'notifications' => $this->notificationModel
                ->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->paginate(10),
            'pager' => $this->notificationModel->pager
        ];
        
        return view('notifications/index', $data);
    }
    
    public function markAsRead($id = null)
    {
        $userId = session()->get('user_id');
        
        if ($id === null) {
            // Mark all as read
            $this->notificationModel->markAllAsRead($userId);
            $message = 'Semua notifikasi telah ditandai sebagai telah dibaca';
        } else {
            // Mark specific notification as read
            $notification = $this->notificationModel->find($id);
            
            if (!$notification || $notification['user_id'] != $userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan'
                ]);
            }
            
            $this->notificationModel->markAsRead($id, $userId);
            $message = 'Notifikasi telah ditandai sebagai telah dibaca';
        }
        
        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'unread_count' => $this->notificationModel->getUnreadCount($userId)
        ]);
    }
    
    public function getUnreadCount()
    {
        $userId = session()->get('user_id');
        return $this->response->setJSON([
            'count' => $this->notificationModel->getUnreadCount($userId)
        ]);
    }
    
    public function getLatest()
    {
        $userId = session()->get('user_id');
        $notifications = $this->notificationModel->getLatestNotifications($userId);
        
        return $this->response->setJSON([
            'notifications' => $notifications,
            'unread_count' => $this->notificationModel->getUnreadCount($userId)
        ]);
    }
} 