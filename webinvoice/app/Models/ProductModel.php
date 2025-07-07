<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code', 
        'name', 
        'description', 
        'price', 
        'unit',
        'stock',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Skip validation in model
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // Validation Rules
    protected $validationRules = [
        'code' => 'required|min_length[2]|is_unique[products.code,id,{id}]',
        'name' => 'required|min_length[3]',
        'price' => 'required|numeric|greater_than_equal_to[0]',
        'unit' => 'required',
        'stock' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'code' => [
            'required' => 'Kode produk harus diisi',
            'min_length' => 'Kode produk minimal 2 karakter',
            'is_unique' => 'Kode produk sudah digunakan'
        ],
        'name' => [
            'required' => 'Nama produk harus diisi',
            'min_length' => 'Nama produk minimal 3 karakter'
        ],
        'price' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than_equal_to' => 'Harga tidak boleh negatif'
        ],
        'unit' => [
            'required' => 'Satuan harus diisi'
        ],
        'stock' => [
            'numeric' => 'Stok harus berupa angka',
            'greater_than_equal_to' => 'Stok tidak boleh negatif'
        ]
    ];

    /**
     * Get active products
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Generate unique product code
     */
    public function generateCode()
    {
        $prefix = 'P';
        $lastProduct = $this->select('code')
            ->like('code', $prefix, 'after')
            ->orderBy('code', 'DESC')
            ->first();
            
        if ($lastProduct) {
            $number = (int) substr($lastProduct['code'], strlen($prefix));
            $number++;
        } else {
            $number = 1;
        }
        
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if code exists (excluding current product)
     */
    public function isCodeUnique($code, $excludeId = null)
    {
        $query = $this->where('code', $code);
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }
        return $query->countAllResults() === 0;
    }

    /**
     * Search products
     */
    public function search($keyword)
    {
        return $this->like('name', $keyword)
            ->orLike('code', $keyword)
            ->findAll();
    }

    // Update stok produk
    public function updateStock($id, $quantity, $type = 'add')
    {
        $product = $this->find($id);
        
        if (!$product) {
            return false;
        }
        
        $newStock = $type === 'add' 
            ? $product['stock'] + $quantity
            : $product['stock'] - $quantity;
            
        if ($newStock < 0) {
            return false;
        }
        
        return $this->update($id, ['stock' => $newStock]);
    }

    public function checkStock($id, $quantity)
    {
        $product = $this->find($id);
        
        if (!$product) {
            return false;
        }

        return $product['stock'] >= $quantity;
    }

    public function formatPrice($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    // Format harga dari format Rupiah ke numeric
    public function formatPriceToNumeric($price)
    {
        if (is_numeric($price)) {
            return (float) $price;
        }
        // Hapus 'Rp', titik ribuan, dan ubah koma desimal menjadi titik
        $price = str_replace(['Rp', '.', ','], ['', '', '.'], $price);
        return (float) trim($price);
    }

    // Format harga dari numeric ke format Rupiah
    public function formatPriceToRupiah($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    // Sebelum menyimpan data
    protected function beforeInsert(array $data): array
    {
        if (isset($data['data']['price'])) {
            $data['data']['price'] = $this->formatPriceToNumeric($data['data']['price']);
        }
        return $data;
    }

    // Sebelum mengupdate data
    protected function beforeUpdate(array $data): array
    {
        if (isset($data['data']['price'])) {
            $data['data']['price'] = $this->formatPriceToNumeric($data['data']['price']);
        }
        return $data;
    }
} 