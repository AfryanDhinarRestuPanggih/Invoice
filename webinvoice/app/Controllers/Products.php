<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Produk',
            'products' => $this->productModel->findAll()
        ];

        return view('products/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Produk',
            'validation' => \Config\Services::validation(),
            'code' => $this->productModel->generateCode()
        ];

        return view('products/create', $data);
    }

    public function create()
    {
        if (!$this->validate($this->productModel->getValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }
        
        $this->productModel->insert([
            'code' => $this->request->getPost('code'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'min_stock' => $this->request->getPost('min_stock'),
            'unit' => $this->request->getPost('unit')
        ]);
        
        return redirect()->to('products')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('products')
                ->with('error', 'Produk tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Produk',
            'validation' => \Config\Services::validation(),
            'product' => $product
        ];
        
        return view('products/edit', $data);
    }

    public function update($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('products')
                ->with('error', 'Produk tidak ditemukan');
        }
        
        if (!$this->validate($this->productModel->getValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }
        
        $this->productModel->update($id, [
            'code' => $this->request->getPost('code'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'min_stock' => $this->request->getPost('min_stock'),
            'unit' => $this->request->getPost('unit')
        ]);
        
        return redirect()->to('products')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('products')
                ->with('error', 'Produk tidak ditemukan');
        }
        
        $this->productModel->delete($id);
        
        return redirect()->to('products')
            ->with('success', 'Produk berhasil dihapus');
    }

    // API untuk DataTables
    public function getProducts()
    {
        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = $this->request->getGet('search')['value'];
        
        $total = $this->productModel->countAllResults(false);
        
        $output = [
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total
        ];
        
        if (empty($search)) {
            $output['data'] = $this->productModel->findAll($length, $start);
        } else {
            $products = $this->productModel->like('code', $search)
                ->orLike('name', $search)
                ->orLike('description', $search)
                ->findAll($length, $start);
                
            $output['data'] = $products;
            $output['recordsFiltered'] = count($products);
        }
        
        return $this->response->setJSON($output);
    }
    
    // API untuk select2
    public function searchProducts()
    {
        $term = $this->request->getGet('term');
        
        $products = $this->productModel->like('code', $term)
            ->orLike('name', $term)
            ->findAll();
            
        $results = array_map(function($product) {
            return [
                'id' => $product['id'],
                'text' => $product['code'] . ' - ' . $product['name'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'unit' => $product['unit']
            ];
        }, $products);
        
        return $this->response->setJSON(['results' => $results]);
    }

    // API untuk mendapatkan detail produk (digunakan di form invoice)
    public function getProduct($id)
    {
        $product = $this->productModel->where('user_id', session()->get('user_id'))->find($id);
        
        if (!$product) {
            return $this->response->setJSON(['error' => 'Produk tidak ditemukan'])->setStatusCode(404);
        }

        return $this->response->setJSON($product);
    }
} 
 