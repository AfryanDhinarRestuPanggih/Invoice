<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'last_login'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[admin,user]',
        'status'   => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah digunakan'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter'
        ],
        'role' => [
            'required' => 'Role harus diisi',
            'in_list' => 'Role tidak valid'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status tidak valid'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = [];
    protected $beforeUpdate = [];

    public function findByEmail(string $email)
    {
        return $this->where('LOWER(email)', strtolower($email))
                    ->where('deleted_at', null)
                    ->first();
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function createUser($data)
    {
        // Pastikan password di-hash
        if (isset($data['password']) && !$this->isHashed($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->insert($data);
    }

    public function updateUser($id, $data)
    {
        // Hash password jika ada perubahan password
        if (isset($data['password']) && !$this->isHashed($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->update($id, $data);
    }

    private function isHashed($password)
    {
        return strlen($password) === 60 && preg_match('/^\$2[ayb]\$.{56}$/', $password);
    }

    public function getActiveUsers()
    {
        return $this->where('status', 'active')
                    ->findAll();
    }

    public function searchUsers($keyword)
    {
        return $this->like('name', $keyword)
                    ->orLike('email', $keyword)
                    ->findAll();
    }
} 