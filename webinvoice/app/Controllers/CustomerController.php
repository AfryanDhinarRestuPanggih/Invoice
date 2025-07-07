<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CustomerController extends BaseController
{
    protected $customerModel;
    protected $validation;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $query = $this->customerModel;

        if ($search) {
            $query->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('company_name', $search)
                ->groupEnd();
        }

        if ($status) {
            $query->where('status', $status);
        }

        $data = [
            'customers' => $query->paginate(10),
            'pager' => $query->pager,
            'search' => $search,
            'status' => $status
        ];

        return view('customers/index', $data);
    }

    public function new()
    {
        return view('customers/create', [
            'validation' => $this->validation
        ]);
    }

    public function create()
    {
        // Hanya admin yang bisa membuat customer
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk membuat customer');
        }

        if (!$this->validate($this->customerModel->validationRules, $this->customerModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'company_name' => $this->request->getPost('company_name'),
            'tax_number' => $this->request->getPost('tax_number'),
            'notes' => $this->request->getPost('notes'),
            'status' => $this->request->getPost('status') ?? 'active'
        ];

        $this->customerModel->insert($data);

        return redirect()->to('/customers')->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Hanya admin yang bisa edit customer
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengedit customer');
        }

        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw new PageNotFoundException('Data pelanggan tidak ditemukan');
        }

        return view('customers/edit', [
            'customer' => $customer,
            'validation' => $this->validation
        ]);
    }

    public function update($id)
    {
        // Hanya admin yang bisa update customer
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate customer');
        }

        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw new PageNotFoundException('Data pelanggan tidak ditemukan');
        }

        // Jika email tidak diubah, gunakan validasi tanpa cek unique
        $rules = $this->customerModel->validationRules;
        if ($this->request->getPost('email') === $customer['email']) {
            $rules['email'] = 'required|valid_email';
        }

        if (!$this->validate($rules, $this->customerModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'company_name' => $this->request->getPost('company_name'),
            'tax_number' => $this->request->getPost('tax_number'),
            'notes' => $this->request->getPost('notes'),
            'status' => $this->request->getPost('status')
        ];

        $this->customerModel->update($id, $data);

        return redirect()->to('/customers')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function delete($id)
    {
        // Hanya admin yang bisa delete customer
        if (session()->get('role') !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk menghapus customer');
        }

        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw new PageNotFoundException('Data pelanggan tidak ditemukan');
        }

        $this->customerModel->delete($id);

        return redirect()->to('/customers')->with('success', 'Data pelanggan berhasil dihapus');
    }

    public function show($id)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw new PageNotFoundException('Data pelanggan tidak ditemukan');
        }

        return view('customers/show', [
            'customer' => $customer
        ]);
    }

    public function search()
    {
        $keyword = $this->request->getGet('term');
        
        if (empty($keyword)) {
            return $this->response->setJSON([]);
        }

        $customers = $this->customerModel->select('id, name, email, phone, address, company_name')
            ->like('name', $keyword)
            ->orLike('email', $keyword)
            ->orLike('phone', $keyword)
            ->orLike('company_name', $keyword)
            ->where('status', 'active')
            ->findAll(10);

        return $this->response->setJSON($customers);
    }
} 