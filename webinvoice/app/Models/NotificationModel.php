<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'user_id',
        'title',
        'message',
        'type',
        'link',
        'read_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Create a new notification
     */
    public function createNotification($userId, $title, $message, $type = 'info', $link = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id, $userId)
    {
        return $this->where('id', $id)
                    ->where('user_id', $userId)
                    ->set(['read_at' => date('Y-m-d H:i:s')])
                    ->update();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('read_at IS NULL')
                    ->set(['read_at' => date('Y-m-d H:i:s')])
                    ->update();
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('read_at IS NULL')
                    ->countAllResults();
    }

    /**
     * Get notifications for a user
     */
    public function getNotifications($userId, $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }
} 