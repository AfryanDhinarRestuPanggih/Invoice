<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemModel extends Model
{
    protected $table            = 'invoice_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'amount'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'invoice_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity'   => 'required|integer|greater_than[0]',
        'price'      => 'required|numeric|greater_than[0]',
        'amount'     => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'invoice_id' => [
            'required' => 'ID invoice harus diisi',
            'integer' => 'ID invoice tidak valid'
        ],
        'product_id' => [
            'required' => 'ID produk harus diisi',
            'integer' => 'ID produk tidak valid'
        ],
        'quantity' => [
            'required' => 'Jumlah harus diisi',
            'integer' => 'Jumlah harus berupa angka bulat',
            'greater_than' => 'Jumlah harus lebih dari 0'
        ],
        'price' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih dari 0'
        ],
        'amount' => [
            'required' => 'Total harus diisi',
            'numeric' => 'Total harus berupa angka',
            'greater_than' => 'Total harus lebih dari 0'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function __construct()
    {
        parent::__construct();
        $this->validation = \Config\Services::validation();
    }

    // Relasi dengan invoice
    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoiceModel', 'invoice_id', 'id');
    }

    // Relasi dengan product
    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id', 'id');
    }

    public function getItemsWithProductDetails($invoiceId)
    {
        return $this->select('invoice_items.*, products.name as product_name, products.description')
                    ->join('products', 'products.id = invoice_items.product_id')
                    ->where('invoice_id', $invoiceId)
                    ->findAll();
    }

    // Validasi bahwa amount = price * quantity
    protected function validateAmount(array $data): bool
    {
        if (!isset($data['price']) || !isset($data['quantity']) || !isset($data['amount'])) {
            return false;
        }

        $calculated = (float)$data['price'] * (int)$data['quantity'];
        $difference = abs($calculated - (float)$data['amount']);
        return $difference < 0.01; // Allow for tiny floating-point differences
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (!$this->validateAmount($data)) {
            $this->validation->setError('amount', 'Total tidak sesuai dengan harga dan jumlah');
            return false;
        }

        return parent::insert($data, $returnID);
    }

    public function update($id = null, $data = null): bool
    {
        if (!$this->validateAmount($data)) {
            $this->validation->setError('amount', 'Total tidak sesuai dengan harga dan jumlah');
            return false;
        }

        return parent::update($id, $data);
    }

    // Format currency
    public function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Get invoice items with product information
     */
    public function getItemsWithProducts($invoiceId)
    {
        return $this->select('invoice_items.*, products.name as product_name, products.code as product_code')
            ->join('products', 'products.id = invoice_items.product_id')
            ->where('invoice_id', $invoiceId)
            ->findAll();
    }
} 