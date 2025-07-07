<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class InvoiceController extends BaseController
{
    protected $invoiceModel;
    protected $invoiceItemModel;
    protected $productModel;
    protected $customerModel;
    protected $validation;
    protected $db;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
        $this->productModel = new ProductModel();
        $this->customerModel = new CustomerModel();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        // Base query
        $builder = $this->invoiceModel->select('invoices.*, users.name as created_by')
            ->join('users', 'users.id = invoices.user_id')
            ->where('invoices.deleted_at IS NULL');

        // Filter by user if not admin
        if (session('role') !== 'admin') {
            $builder->where('invoices.user_id', session('id'));
        }

        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('invoices.invoice_number', $search)
                ->orLike('invoices.customer_name', $search)
                ->orLike('invoices.customer_email', $search)
                ->groupEnd();
        }

        // Apply status filter
        if ($status) {
            $builder->where('invoices.status', $status);
        }

        // Apply date range filter
        if ($start_date) {
            $builder->where('DATE(invoices.created_at) >=', $start_date);
        }
        if ($end_date) {
            $builder->where('DATE(invoices.created_at) <=', $end_date);
        }

        // Get statistics
        $statsBuilder = clone $builder;
        $stats = [
            'total_invoices' => $statsBuilder->countAllResults(false),
            'total_income' => $this->invoiceModel
                ->where('invoices.deleted_at IS NULL')
                ->where('invoices.status', 'paid')
                ->where('invoices.user_id', session('id'))
                ->selectSum('total_amount')
                ->get()
                ->getRow()
                ->total_amount ?? 0,
            'total_unpaid' => $this->invoiceModel
                ->where('invoices.deleted_at IS NULL')
                ->where('invoices.status', 'sent')
                ->where('invoices.user_id', session('id'))
                ->selectSum('total_amount')
                ->get()
                ->getRow()
                ->total_amount ?? 0,
            'average_amount' => $this->invoiceModel
                ->where('invoices.deleted_at IS NULL')
                ->where('invoices.status !=', 'cancelled')
                ->where('invoices.user_id', session('id'))
                ->selectAvg('total_amount')
                ->get()
                ->getRow()
                ->total_amount ?? 0
        ];

        $builder->orderBy('invoices.created_at', 'DESC');

        // Get paginated results
        $data = [
            'invoices' => $builder->paginate(10),
            'pager' => $builder->pager,
            'search' => $search,
            'status' => $status,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'stats' => $stats
        ];

        return view('invoices/index', $data);
    }

    public function create()
    {
        // Hanya admin yang bisa membuat invoice baru
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk membuat invoice');
        }

        // Siapkan data untuk view
        $data = [
            'products' => $this->productModel->findAll(),
            'customers' => $this->customerModel->findAll(),
            'invoice_number' => $this->invoiceModel->generateInvoiceNumber(),
            'validation' => $this->validation,
            'title' => 'Buat Invoice Baru'
        ];

        // Set default values untuk old input
        if (!session('errors')) {
            $data['old_input'] = [
                'customer_name' => '',
                'customer_email' => '',
                'customer_phone' => '',
                'customer_address' => '',
                'notes' => ''
            ];
        }

        return view('invoices/create', $data);
    }

    public function store()
    {
        // Hanya admin yang bisa membuat invoice baru
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk membuat invoice');
        }

        // Validasi input
        $rules = [
            'invoice_number' => 'required|is_unique[invoices.invoice_number]',
            'customer_name' => 'required|min_length[3]',
            'customer_email' => 'required|valid_email',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'due_date' => 'required|valid_date',
            'products.*' => 'required|numeric|greater_than[0]',
            'quantities.*' => 'required|numeric|greater_than[0]'
        ];

        $messages = [
            'invoice_number' => [
                'required' => 'Nomor invoice harus diisi',
                'is_unique' => 'Nomor invoice sudah digunakan'
            ],
            'customer_name' => [
                'required' => 'Nama pelanggan harus diisi',
                'min_length' => 'Nama pelanggan minimal 3 karakter'
            ],
            'customer_email' => [
                'required' => 'Email pelanggan harus diisi',
                'valid_email' => 'Format email tidak valid'
            ],
            'customer_phone' => [
                'required' => 'Nomor telepon harus diisi'
            ],
            'customer_address' => [
                'required' => 'Alamat harus diisi'
            ],
            'due_date' => [
                'required' => 'Tanggal jatuh tempo harus diisi',
                'valid_date' => 'Format tanggal tidak valid'
            ],
            'products.*' => [
                'required' => 'Produk harus dipilih',
                'numeric' => 'ID produk tidak valid',
                'greater_than' => 'Produk tidak valid'
            ],
            'quantities.*' => [
                'required' => 'Jumlah produk harus diisi',
                'numeric' => 'Jumlah harus berupa angka',
                'greater_than' => 'Jumlah harus lebih dari 0'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Validasi tambahan: pastikan ada minimal 1 produk
        $products = $this->request->getPost('products');
        if (empty($products)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimal harus ada 1 produk');
        }

        // Mulai transaksi database
        $this->db->transStart();

        try {
            // Data invoice
            $invoiceData = [
                'invoice_number' => $this->request->getPost('invoice_number'),
                'customer_name' => $this->request->getPost('customer_name'),
                'customer_email' => $this->request->getPost('customer_email'),
                'customer_phone' => $this->request->getPost('customer_phone'),
                'customer_address' => $this->request->getPost('customer_address'),
                'due_date' => $this->request->getPost('due_date'),
                'notes' => $this->request->getPost('notes'),
                'status' => 'draft',
                'user_id' => session()->get('id'),
                'subtotal' => 0,
                'tax' => 0,
                'total_amount' => 0
            ];

            // Simpan invoice
            $this->invoiceModel->insert($invoiceData);
            $invoiceId = $this->invoiceModel->getInsertID();

            // Simpan items dan hitung total
            $products = $this->request->getPost('products');
            $quantities = $this->request->getPost('quantities');
            $subtotal = 0;

            foreach ($products as $index => $productId) {
                $product = $this->productModel->find($productId);
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan');
                }

                $quantity = (int)$quantities[$index];
                $price = $product['price'];
                $amount = $price * $quantity;
                $subtotal += $amount;

                $itemData = [
                    'invoice_id' => $invoiceId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'amount' => $amount
                ];

                $this->invoiceItemModel->insert($itemData);
            }

            // Hitung pajak (10%)
            $tax = $subtotal * 0.1;
            $total_amount = $subtotal + $tax;

            // Update total invoice
            $this->invoiceModel->update($invoiceId, [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total_amount
            ]);

            // Commit transaksi
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan invoice');
            }

            return redirect()->to('/invoices')->with('success', 'Invoice berhasil dibuat');

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', '[Invoice] Error creating invoice: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat invoice. Silakan coba lagi.');
        }
    }

    public function show($id = null)
    {
        if (!$id) {
            throw new PageNotFoundException('Invoice tidak ditemukan');
        }

        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            throw new PageNotFoundException('Invoice tidak ditemukan');
        }

        // Cek akses
        if (session('role') !== 'admin' && $invoice['user_id'] !== session('id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke invoice ini');
        }

        $items = $this->invoiceItemModel
            ->select('invoice_items.*, (invoice_items.price * invoice_items.quantity) as subtotal, products.name as product_name, products.code as product_code')
            ->join('products', 'products.id = invoice_items.product_id')
            ->where('invoice_id', $id)
            ->findAll();

        $data = [
            'invoice' => $invoice,
            'items' => $items
        ];

        return view('invoices/show', $data);
    }

    public function edit($id = null)
    {
        // Hanya admin yang bisa edit invoice
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengedit invoice');
        }

        if (!$id) {
            throw new PageNotFoundException('Invoice tidak ditemukan');
        }

        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            throw new PageNotFoundException('Invoice tidak ditemukan');
        }

        // Hanya invoice draft yang bisa diedit
        if ($invoice['status'] !== 'draft') {
            return redirect()->back()->with('error', 'Hanya invoice draft yang dapat diedit');
        }

        $items = $this->invoiceItemModel
            ->select('invoice_items.*, products.name as product_name, (invoice_items.price * invoice_items.quantity) as subtotal')
            ->join('products', 'products.id = invoice_items.product_id')
            ->where('invoice_id', $id)
            ->findAll();

        $data = [
            'invoice' => $invoice,
            'items' => $items,
            'products' => $this->productModel->findAll(),
            'validation' => $this->validation
        ];

        return view('invoices/edit', $data);
    }

    public function update($id = null)
    {
        // Hanya admin yang bisa update invoice
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate invoice');
        }

        if (!$id) {
            return redirect()->to('/invoices')->with('error', 'ID Invoice tidak ditemukan.');
        }

        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan.');
        }

        // Validasi input
        $rules = [
            'customer_name' => 'required|min_length[3]|max_length[100]',
            'customer_email' => 'required|valid_email',
            'customer_phone' => 'required|min_length[10]|max_length[15]',
            'customer_address' => 'required|min_length[10]',
            'due_date' => 'required|valid_date',
            'products.*' => 'required|is_natural_no_zero',
            'quantities.*' => 'required|is_natural_no_zero'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $products = $this->request->getPost('products');
            $quantities = $this->request->getPost('quantities');
            $total_amount = 0;

            // Validasi produk dan hitung total
            foreach ($products as $index => $productId) {
                $product = $this->productModel->find($productId);
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                $quantity = (int)$quantities[$index];
                if ($quantity <= 0) {
                    throw new \Exception('Jumlah harus lebih dari 0.');
                }

                // Hitung total amount
                $total_amount += $product['price'] * $quantity;
            }

            // Update data invoice
            $invoiceData = [
                'customer_name' => $this->request->getPost('customer_name'),
                'customer_email' => $this->request->getPost('customer_email'),
                'customer_phone' => $this->request->getPost('customer_phone'),
                'customer_address' => $this->request->getPost('customer_address'),
                'due_date' => $this->request->getPost('due_date'),
                'notes' => $this->request->getPost('notes'),
                'total_amount' => $total_amount
            ];

            if (!$this->invoiceModel->update($id, $invoiceData)) {
                throw new \Exception('Gagal mengupdate invoice.');
            }

            // Hapus item invoice lama
            $this->invoiceItemModel->where('invoice_id', $id)->delete();

            // Simpan item invoice baru
            foreach ($products as $index => $productId) {
                $product = $this->productModel->find($productId);
                $quantity = (int)$quantities[$index];
                $price = (float)$product['price'];
                $amount = $price * $quantity;

                $itemData = [
                    'invoice_id' => $id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'amount' => $amount
                ];

                if (!$this->invoiceItemModel->insert($itemData)) {
                    throw new \Exception('Gagal menyimpan item invoice.');
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan perubahan invoice.');
            }

            return redirect()->to('/invoices/' . $id)
                ->with('success', 'Invoice berhasil diperbarui.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        // Hanya admin yang bisa delete invoice
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk menghapus invoice');
        }

        if (!$id) {
            return redirect()->to('/invoices')->with('error', 'ID Invoice tidak ditemukan.');
        }

        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            throw new PageNotFoundException('Invoice tidak ditemukan');
        }

        // Hanya invoice draft yang bisa dihapus
        if ($invoice['status'] !== 'draft') {
            return redirect()->back()->with('error', 'Hanya invoice draft yang dapat dihapus');
        }

        // Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Hapus invoice items
            $this->invoiceItemModel->where('invoice_id', $id)->delete();
            
            // Hapus invoice
            $this->invoiceModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menghapus invoice');
            }

            return redirect()->to('/invoices')->with('success', 'Invoice berhasil dihapus');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            throw new PageNotFoundException('Invoice tidak ditemukan');
        }

        // Cek akses
        if (session('role') !== 'admin' && $invoice['user_id'] !== session('id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke invoice ini');
        }

        $status = $this->request->getPost('status');
        $allowedStatus = ['draft', 'sent', 'paid', 'cancelled'];

        if (!in_array($status, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // Update status
        $this->invoiceModel->update($id, ['status' => $status]);

        return redirect()->back()->with('success', 'Status invoice berhasil diupdate');
    }

    public function print($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            throw new PageNotFoundException('Invoice tidak ditemukan');
        }

        // Cek akses
        if (session('role') !== 'admin' && $invoice['user_id'] !== session('id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke invoice ini');
        }

        $items = $this->invoiceItemModel
            ->select('invoice_items.*, products.name as product_name, products.code as product_code')
            ->join('products', 'products.id = invoice_items.product_id')
            ->where('invoice_id', $id)
            ->findAll();

        $data = [
            'invoice' => $invoice,
            'items' => $items
        ];

        return view('invoices/print', $data);
    }

    /**
     * Mengirim invoice ke pelanggan
     */
    public function send($id = null)
    {
        if (!$id) {
            return redirect()->to('/invoices')->with('error', 'ID Invoice tidak ditemukan.');
        }

        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan.');
        }

        // Validasi akses
        if ($invoice['user_id'] !== session('id') && session('role') !== 'admin') {
            return redirect()->to('/invoices')->with('error', 'Anda tidak memiliki akses untuk mengirim invoice ini.');
        }

        // Validasi status
        if ($invoice['status'] !== 'draft') {
            return redirect()->to('/invoices/' . $id)->with('error', 'Hanya invoice dengan status draft yang dapat dikirim.');
        }

        // Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get invoice items
            $items = $this->invoiceItemModel->where('invoice_id', $id)->findAll();
            if (empty($items)) {
                throw new \Exception('Invoice tidak memiliki item.');
            }

            // Update status invoice
            $this->invoiceModel->update($id, [
                'status' => 'sent',
                'sent_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal memperbarui status invoice.');
            }
            
            return redirect()->to('/invoices/' . $id)
                ->with('success', 'Status invoice berhasil diubah menjadi terkirim');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menandai invoice sebagai sudah dibayar
     */
    public function pay($id = null)
    {
        if (!$id) {
            return redirect()->to('/invoices')->with('error', 'ID Invoice tidak ditemukan.');
        }

        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan.');
        }

        // Validasi akses
        if ($invoice['user_id'] !== session('id') && session('role') !== 'admin') {
            return redirect()->to('/invoices')->with('error', 'Anda tidak memiliki akses untuk memproses pembayaran invoice ini.');
        }

        // Validasi status
        if (!in_array($invoice['status'], ['sent', 'overdue'])) {
            return redirect()->to('/invoices/' . $id)
                ->with('error', 'Hanya invoice yang sudah dikirim atau melewati jatuh tempo yang dapat ditandai sebagai dibayar.');
        }

        // Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update status invoice
            $this->invoiceModel->update($id, [
                'status' => 'paid',
                'paid_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Update stok produk
            $items = $this->invoiceItemModel->where('invoice_id', $id)->findAll();
            foreach ($items as $item) {
                $product = $this->productModel->find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                // Kurangi stok
                $newStock = $product['stock'] - $item['quantity'];
                if ($newStock < 0) {
                    throw new \Exception('Stok produk ' . $product['name'] . ' tidak mencukupi.');
                }

                $this->productModel->update($item['product_id'], [
                    'stock' => $newStock,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal memproses pembayaran invoice.');
            }
            
            return redirect()->to('/invoices/' . $id)
                ->with('success', 'Pembayaran invoice berhasil diproses.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membatalkan invoice
     */
    public function cancel($id = null)
    {
        // Hanya admin yang bisa cancel invoice
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk membatalkan invoice');
        }

        if (!$id) {
            return redirect()->to('/invoices')->with('error', 'ID Invoice tidak ditemukan.');
        }

        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to('/invoices')->with('error', 'Invoice tidak ditemukan.');
        }

        // Update status invoice menjadi cancelled
        $this->invoiceModel->update($id, [
            'status' => 'cancelled',
            'cancelled_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Invoice berhasil dibatalkan.');
    }
} 