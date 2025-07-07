<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\RESTful\ResourceController;

class Customers extends ResourceController
{
    protected $customerModel;
    protected $helpers = ['form'];
    
    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Daftar Pelanggan',
            'customers' => $this->customerModel->findAll()
        ];
        
        return view('customers/index', $data);
    }
    
    public function new()
    {
        $data = [
            'title' => 'Tambah Pelanggan',
            'validation' => \Config\Services::validation()
        ];
        
        return view('customers/create', $data);
    }
    
    public function create()
    {
        if (!$this->validate($this->customerModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->customerModel->insert($this->request->getPost());
        
        return redirect()->to('customers')->with('message', 'Data pelanggan berhasil ditambahkan');
    }
    
    public function edit($id = null)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pelanggan tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Pelanggan',
            'validation' => \Config\Services::validation(),
            'customer' => $customer
        ];
        
        return view('customers/edit', $data);
    }
    
    public function update($id = null)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pelanggan tidak ditemukan');
        }
        
        if (!$this->validate($this->customerModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->customerModel->update($id, $this->request->getPost());
        
        return redirect()->to('customers')->with('message', 'Data pelanggan berhasil diperbarui');
    }
    
    public function delete($id = null)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pelanggan tidak ditemukan');
        }
        
        $this->customerModel->delete($id);
        
        return redirect()->to('customers')->with('message', 'Data pelanggan berhasil dihapus');
    }
    
    public function getCustomers()
    {
        $customers = $this->customerModel->findAll();
        
        $data = [];
        foreach ($customers as $customer) {
            $row = [
                'id' => $customer['id'],
                'code' => $customer['code'],
                'name' => $customer['name'],
                'contact_person' => $customer['contact_person'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'city' => $customer['city'],
                'status' => $customer['status'],
                'total_invoices' => $this->customerModel->getTotalInvoices($customer['id']),
                'total_transactions' => number_format($this->customerModel->getTotalTransactions($customer['id']), 0, ',', '.'),
                'actions' => '
                    <a href="' . site_url('customers/' . $customer['id'] . '/edit') . '" class="btn btn-sm btn-warning">Edit</a>
                    <form action="' . site_url('customers/' . $customer['id']) . '" method="post" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus data ini?\')">
                        ' . csrf_field() . '
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                '
            ];
            
            $data[] = $row;
        }
        
        return $this->response->setJSON(['data' => $data]);
    }
    
    public function search()
    {
        $term = $this->request->getGet('term');
        
        $customers = $this->customerModel->like('name', $term)
            ->orLike('code', $term)
            ->where('status', 'active')
            ->findAll();
            
        $data = [];
        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer['id'],
                'text' => $customer['code'] . ' - ' . $customer['name']
            ];
        }
        
        return $this->response->setJSON($data);
    }
    
    public function show($id = null)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pelanggan tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Pelanggan',
            'customer' => $customer
        ];
        
        return view('customers/show', $data);
    }
} 