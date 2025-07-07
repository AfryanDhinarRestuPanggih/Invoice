<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\ProductModel;

class Invoices extends BaseController
{
    protected $invoiceModel;
    protected $invoiceItemModel;
    protected $productModel;
    protected $session;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
        $this->productModel = new ProductModel();
        $this->session = session();
    }

    public function index()
    {
        $userId = $this->session->get('user_id');
        $data = [
            'title' => 'Daftar Invoice',
            'invoices' => $this->invoiceModel->where('user_id', $userId)->findAll()
        ];
        
        return view('invoices/index', $data);
    }

    public function create()
    {
        $userId = $this->session->get('user_id');
        $data = [
            'title' => 'Buat Invoice Baru',
            'products' => $this->productModel->where('user_id', $userId)->findAll(),
            'validation' => \Config\Services::validation()
        ];
        
        return view('invoices/create', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'customer_name' => 'required|min_length[3]',
            'customer_email' => 'permit_empty|valid_email',
            'customer_phone' => 'permit_empty|min_length[10]|max_length[15]',
            'customer_address' => 'required',
            'due_date' => 'required|valid_date',
            'notes' => 'permit_empty',
            'products' => 'required',
            'quantities' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Generate invoice number
        $invoiceNumber = $this->invoiceModel->generateInvoiceNumber();
        
        // Hitung total
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');
        $subtotal = 0;
        $tax = 0;
        $items = [];
        
        // Validasi dan persiapkan items
        foreach ($products as $index => $productId) {
            $product = $this->productModel->find($productId);
            if (!$product) {
                return redirect()->back()->withInput()->with('error', 'Produk tidak valid');
            }
            
            $quantity = (int) $quantities[$index];
            if ($quantity <= 0) {
                return redirect()->back()->withInput()->with('error', 'Jumlah harus lebih dari 0');
            }
            
            $price = $product['price'];
            $itemSubtotal = $quantity * $price;
            $subtotal += $itemSubtotal;
            
            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $itemSubtotal
            ];
        }
        
        // Hitung pajak (10%)
        $tax = $subtotal * 0.1;
        $totalAmount = $subtotal + $tax;
        
        // Simpan invoice
        $invoiceData = [
            'user_id' => $this->session->get('user_id'),
            'invoice_number' => $invoiceNumber,
            'customer_name' => $this->request->getPost('customer_name'),
            'customer_email' => $this->request->getPost('customer_email'),
            'customer_phone' => $this->request->getPost('customer_phone'),
            'customer_address' => $this->request->getPost('customer_address'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'notes' => $this->request->getPost('notes'),
            'due_date' => $this->request->getPost('due_date'),
            'status' => 'draft'
        ];
        
        $this->db->transStart();
        
        try {
            // Simpan invoice
            $this->invoiceModel->insert($invoiceData);
            $invoiceId = $this->invoiceModel->getInsertID();
            
            // Simpan items
            foreach ($items as $item) {
                $item['invoice_id'] = $invoiceId;
                $this->invoiceItemModel->insert($item);
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan invoice');
            }
            
            return redirect()->to('/invoices')->with('success', 'Invoice berhasil dibuat');
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $userId = $this->session->get('user_id');
        $invoice = $this->invoiceModel->getInvoiceWithItems($id);
        
        if (!$invoice || $invoice['user_id'] != $userId) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Invoice',
            'invoice' => $invoice
        ];
        
        return view('invoices/show', $data);
    }

    public function edit($id)
    {
        $userId = $this->session->get('user_id');
        $invoice = $this->invoiceModel->getInvoiceWithItems($id);
        
        if (!$invoice || $invoice['user_id'] != $userId) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan');
        }
        
        if ($invoice['status'] !== 'draft') {
            return redirect()->to('/invoices')->with('error', 'Hanya invoice draft yang dapat diedit');
        }
        
        $data = [
            'title' => 'Edit Invoice',
            'invoice' => $invoice,
            'products' => $this->productModel->where('user_id', $userId)->findAll(),
            'validation' => \Config\Services::validation()
        ];
        
        return view('invoices/edit', $data);
    }

    public function update($id)
    {
        $userId = $this->session->get('user_id');
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice || $invoice['user_id'] != $userId) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan');
        }
        
        if ($invoice['status'] !== 'draft') {
            return redirect()->to('/invoices')->with('error', 'Hanya invoice draft yang dapat diedit');
        }
        
        // Validasi input
        $rules = [
            'customer_name' => 'required|min_length[3]',
            'customer_email' => 'permit_empty|valid_email',
            'customer_phone' => 'permit_empty|min_length[10]|max_length[15]',
            'customer_address' => 'required',
            'due_date' => 'required|valid_date',
            'notes' => 'permit_empty',
            'products' => 'required',
            'quantities' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Hitung total
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');
        $subtotal = 0;
        $tax = 0;
        $items = [];
        
        // Validasi dan persiapkan items
        foreach ($products as $index => $productId) {
            $product = $this->productModel->find($productId);
            if (!$product) {
                return redirect()->back()->withInput()->with('error', 'Produk tidak valid');
            }
            
            $quantity = (int) $quantities[$index];
            if ($quantity <= 0) {
                return redirect()->back()->withInput()->with('error', 'Jumlah harus lebih dari 0');
            }
            
            $price = $product['price'];
            $itemSubtotal = $quantity * $price;
            $subtotal += $itemSubtotal;
            
            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $itemSubtotal
            ];
        }
        
        // Hitung pajak (10%)
        $tax = $subtotal * 0.1;
        $totalAmount = $subtotal + $tax;
        
        // Update invoice
        $invoiceData = [
            'customer_name' => $this->request->getPost('customer_name'),
            'customer_email' => $this->request->getPost('customer_email'),
            'customer_phone' => $this->request->getPost('customer_phone'),
            'customer_address' => $this->request->getPost('customer_address'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'notes' => $this->request->getPost('notes'),
            'due_date' => $this->request->getPost('due_date')
        ];
        
        $this->db->transStart();
        
        try {
            // Update invoice
            $this->invoiceModel->update($id, $invoiceData);
            
            // Hapus items lama
            $this->invoiceItemModel->where('invoice_id', $id)->delete();
            
            // Simpan items baru
            foreach ($items as $item) {
                $item['invoice_id'] = $id;
                $this->invoiceItemModel->insert($item);
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate invoice');
            }
            
            return redirect()->to('/invoices')->with('success', 'Invoice berhasil diupdate');
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $userId = $this->session->get('user_id');
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice || $invoice['user_id'] != $userId) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan');
        }
        
        if ($invoice['status'] !== 'draft') {
            return redirect()->to('/invoices')->with('error', 'Hanya invoice draft yang dapat dihapus');
        }
        
        $this->db->transStart();
        
        try {
            // Hapus items
            $this->invoiceItemModel->where('invoice_id', $id)->delete();
            
            // Hapus invoice
            $this->invoiceModel->delete($id);
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal menghapus invoice');
            }
            
            return redirect()->to('/invoices')->with('success', 'Invoice berhasil dihapus');
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus($id)
    {
        $userId = $this->session->get('user_id');
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice || $invoice['user_id'] != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invoice tidak ditemukan']);
        }
        
        $status = $this->request->getPost('status');
        $allowedStatuses = ['draft', 'sent', 'paid', 'cancelled'];
        
        if (!in_array($status, $allowedStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status tidak valid']);
        }
        
        try {
            $this->invoiceModel->update($id, ['status' => $status]);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status invoice berhasil diupdate',
                'status' => $status,
                'label' => $this->invoiceModel->getStatusLabel($status)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupdate status']);
        }
    }

    public function print($id)
    {
        $userId = $this->session->get('user_id');
        $invoice = $this->invoiceModel->getInvoiceWithItems($id);
        
        if (!$invoice || $invoice['user_id'] != $userId) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan');
        }
        
        $data = [
            'invoice' => $invoice
        ];
        
        return view('invoices/print', $data);
    }
} 