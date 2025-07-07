<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'due_date',
        'paid_at',
        'cancelled_at',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'invoice_number' => 'required|max_length[50]|is_unique[invoices.invoice_number,id,{id}]',
        'customer_name' => 'required|min_length[3]|max_length[100]',
        'customer_email' => 'required|valid_email',
        'customer_phone' => 'required|min_length[10]|max_length[20]',
        'customer_address' => 'required',
        'date' => 'permit_empty|valid_date',
        'due_date' => 'required|valid_date',
        'total_amount' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'notes' => 'permit_empty',
        'status' => 'required|in_list[draft,sent,paid,cancelled]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'ID pengguna harus diisi',
            'integer' => 'ID pengguna tidak valid'
        ],
        'invoice_number' => [
            'required' => 'Nomor invoice harus diisi',
            'max_length' => 'Nomor invoice maksimal 50 karakter',
            'is_unique' => 'Nomor invoice sudah digunakan'
        ],
        'customer_name' => [
            'required' => 'Nama pelanggan harus diisi',
            'min_length' => 'Nama pelanggan minimal 3 karakter',
            'max_length' => 'Nama pelanggan maksimal 100 karakter'
        ],
        'customer_email' => [
            'required' => 'Email pelanggan harus diisi',
            'valid_email' => 'Format email tidak valid'
        ],
        'customer_phone' => [
            'required' => 'Nomor telepon harus diisi',
            'min_length' => 'Nomor telepon minimal 10 karakter',
            'max_length' => 'Nomor telepon maksimal 20 karakter'
        ],
        'customer_address' => [
            'required' => 'Alamat harus diisi'
        ],
        'due_date' => [
            'required' => 'Tanggal jatuh tempo harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'total_amount' => [
            'numeric' => 'Total harus berupa angka',
            'greater_than_equal_to' => 'Total tidak boleh negatif'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status tidak valid'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Status invoice yang valid
    public $validStatuses = [
        'draft',     // Invoice baru dibuat
        'sent',      // Invoice sudah dikirim ke pelanggan
        'paid',      // Invoice sudah dibayar
        'overdue',   // Invoice melewati jatuh tempo
        'cancelled'  // Invoice dibatalkan
    ];

    // Relasi dengan invoice items
    public function items()
    {
        return $this->hasMany('App\Models\InvoiceItemModel', 'invoice_id', 'id');
    }

    // Generate nomor invoice
    public function generateInvoiceNumber()
    {
        // Format: INV/YYYY/MM/XXXX
        $year = date('Y');
        $month = date('m');
        
        // Cari nomor terakhir untuk bulan ini
        $lastInvoice = $this->where('YEAR(created_at)', $year)
                           ->where('MONTH(created_at)', $month)
                           ->orderBy('id', 'DESC')
                           ->first();
        
        if ($lastInvoice) {
            // Ambil nomor urut terakhir
            $lastNumber = (int) substr($lastInvoice['invoice_number'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada invoice di bulan ini
            $newNumber = '0001';
        }
        
        return "INV/{$year}/{$month}/{$newNumber}";
    }

    // Mendapatkan invoice beserta items
    public function getInvoiceWithItems($id)
    {
        $invoice = $this->find($id);
        if (!$invoice) {
            return null;
        }

        $itemModel = new InvoiceItemModel();
        $invoice['items'] = $itemModel->where('invoice_id', $id)->findAll();

        return $invoice;
    }

    // Format status invoice
    public function getStatusLabel($status)
    {
        $labels = [
            'draft' => [
                'text' => 'Draft',
                'class' => 'secondary'
            ],
            'sent' => [
                'text' => 'Terkirim',
                'class' => 'primary'
            ],
            'paid' => [
                'text' => 'Lunas',
                'class' => 'success'
            ],
            'cancelled' => [
                'text' => 'Dibatalkan',
                'class' => 'danger'
            ]
        ];
        
        return $labels[$status] ?? [
            'text' => ucfirst($status),
            'class' => 'secondary'
        ];
    }

    // Format currency
    public function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public function getUserInvoices($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    // Fungsi untuk mengecek dan mengupdate status invoice yang sudah melewati jatuh tempo
    public function checkOverdueInvoices()
    {
        $today = date('Y-m-d');
        
        // Update langsung menggunakan query builder untuk menghindari infinite loop
        $this->builder()
            ->where('status', 'sent')
            ->where('due_date <', $today)
            ->set('status', 'overdue')
            ->update();
    }

    // Override fungsi find untuk selalu mengecek status overdue
    public function find($id = null)
    {
        $this->checkOverdueInvoices();
        return parent::find($id);
    }

    // Override fungsi findAll untuk selalu mengecek status overdue
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $this->checkOverdueInvoices();
        return parent::findAll($limit, $offset);
    }
} 