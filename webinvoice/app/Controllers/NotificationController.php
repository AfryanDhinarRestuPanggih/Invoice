<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new \App\Models\NotificationModel();
    }

    /**
     * Get notifications for current user
     */
    public function index()
    {
        $notifications = $this->notificationModel->getNotifications(user_id());
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'notifications' => $notifications,
                    'unread_count' => $this->notificationModel->getUnreadCount(user_id())
                ]
            ]);
        }

        return view('notifications/index', [
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        if ($id) {
            $this->notificationModel->markAsRead($id);
        } else {
            $this->notificationModel->markAllAsRead(user_id());
        }

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $this->notificationModel->getUnreadCount(user_id())
        ]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $this->notificationModel->getUnreadCount(user_id())
        ]);
    }
} 