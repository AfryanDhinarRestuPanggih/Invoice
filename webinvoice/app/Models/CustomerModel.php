<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Pager\Pager;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'phone',
        'address',
        'company_name',
        'tax_number',
        'notes',
        'status'
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
        'email'    => 'required|valid_email|is_unique[customers.email,id,{id}]',
        'phone'    => 'permit_empty|min_length[10]|max_length[20]',
        'status'   => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama pelanggan harus diisi',
            'min_length' => 'Nama pelanggan minimal 3 karakter',
            'max_length' => 'Nama pelanggan maksimal 100 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah digunakan'
        ],
        'phone' => [
            'min_length' => 'Nomor telepon minimal 10 karakter',
            'max_length' => 'Nomor telepon maksimal 20 karakter'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status tidak valid'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setDefaultStatus'];
    protected $beforeUpdate = ['setDefaultStatus'];

    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'active';
        }
        return $data;
    }

    public function getActiveCustomers()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function searchCustomers($keyword)
    {
        return $this->like('name', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('phone', $keyword)
                    ->orLike('company_name', $keyword)
                    ->findAll();
    }
} 