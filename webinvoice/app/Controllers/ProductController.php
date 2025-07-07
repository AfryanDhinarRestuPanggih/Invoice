<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductController extends BaseController
{
    protected $productModel;
    protected $validation;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $keyword = $this->request->getGet('search');
        $data = [
            'products' => $keyword ? 
                $this->productModel->search($keyword) : 
                $this->productModel->findAll(),
            'keyword' => $keyword
        ];

        return view('products/index', $data);
    }

    public function new()
    {
        $data = [
            'product' => [
                'id' => null,
                'code' => $this->productModel->generateCode(),
                'name' => '',
                'description' => '',
                'price' => 0,
                'unit' => 'pcs',
                'stock' => 0,
                'is_active' => 1
            ],
            'validation' => \Config\Services::validation()
        ];

        return view('products/form', $data);
    }

    public function create()
    {
        // Hanya admin yang bisa membuat produk
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk membuat produk');
        }

        $data = $this->request->getPost();
        
        // Format harga dari Rupiah ke numeric
        if (isset($data['price'])) {
            $data['price'] = $this->productModel->formatPriceToNumeric($data['price']);
        }

        // Set nilai default untuk stock dan is_active jika tidak diisi
        $data['stock'] = $data['stock'] ?? 0;
        $data['is_active'] = isset($data['is_active']) ? '1' : '0';
        
        // Validasi data menggunakan rules dari model
        if (!$this->productModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->productModel->errors());
        }

        // Jika berhasil, redirect ke halaman products dengan pesan sukses
        return redirect()->to('/products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Hanya admin yang bisa edit produk
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengedit produk');
        }

        $product = $this->productModel->find($id);

        if (!$product) {
            throw new PageNotFoundException('Produk tidak ditemukan.');
        }

        return view('products/form', ['product' => $product]);
    }

    public function update($id)
    {
        // Hanya admin yang bisa update produk
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate produk');
        }

        // Get product first
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Produk tidak ditemukan.');
        }

        // Get form data
        $data = $this->request->getPost();
        
        // Set nilai default untuk stock jika tidak diisi
        $data['stock'] = $data['stock'] ?? 0;
        
        // Handle checkbox
        $data['is_active'] = isset($data['is_active']) ? '1' : '0';

        // Validasi data
        if (!$this->validate($this->productModel->getValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            // Update product
            $data['id'] = $id;
            
            // Log data before save
            log_message('info', 'Updating product with data: ' . json_encode($data));
            
            if ($this->productModel->save($data)) {
                return redirect()->to('/products')
                    ->with('success', 'Produk berhasil diperbarui.');
            }
            
            throw new \Exception('Gagal menyimpan data produk.');
        } catch (\Exception $e) {
            log_message('error', '[Product Update] Exception: ' . $e->getMessage());
            log_message('error', '[Product Update] Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        // Hanya admin yang bisa delete produk
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk menghapus produk');
        }

        if ($this->productModel->delete($id)) {
            return redirect()->to('/products')
                ->with('success', 'Produk berhasil dihapus.');
        }

        return redirect()->back()
            ->with('error', 'Gagal menghapus produk.');
    }

    public function toggle($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ]);
        }

        $data = [
            'id' => $id,
            'is_active' => $product['is_active'] ? 0 : 1
        ];

        if ($this->productModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status produk berhasil diperbarui.',
                'is_active' => $data['is_active']
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal memperbarui status produk.'
        ]);
    }

    public function search()
    {
        $keyword = $this->request->getGet('term');
        $products = $this->productModel->search($keyword);

        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'id' => $product['id'],
                'label' => $product['name'] . ' (' . $product['code'] . ')',
                'value' => $product['name'],
                'price' => $product['price'],
                'unit' => $product['unit']
            ];
        }

        return $this->response->setJSON($result);
    }
} 