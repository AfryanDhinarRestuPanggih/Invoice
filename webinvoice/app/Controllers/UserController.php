<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class UserController extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->userModel->builder();

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        if ($status) {
            $builder->where('status', $status);
        }

        $data = [
            'users' => $builder->paginate(10),
            'pager' => $builder->pager,
            'search' => $search,
            'status' => $status
        ];

        return view('users/index', $data);
    }

    public function new()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        return view('users/create', [
            'validation' => $this->validation
        ]);
    }

    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        if (!$this->validate($this->userModel->validationRules, $this->userModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status', 'active')
        ];

        $this->userModel->insert($data);

        return redirect()->to('/users')->with('success', 'Data user berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new PageNotFoundException('Data user tidak ditemukan');
        }

        return view('users/edit', [
            'user' => $user,
            'validation' => $this->validation
        ]);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new PageNotFoundException('Data user tidak ditemukan');
        }

        // Jika password kosong, hapus dari validasi
        if (!$this->request->getPost('password')) {
            unset($this->userModel->validationRules['password']);
        }

        if (!$this->validate($this->userModel->validationRules, $this->userModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];

        // Hanya update password jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/users')->with('success', 'Data user berhasil diperbarui');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new PageNotFoundException('Data user tidak ditemukan');
        }

        // Tidak bisa menghapus diri sendiri
        if ($user['id'] === session()->get('id')) {
            return redirect()->to('/users')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $this->userModel->delete($id);

        return redirect()->to('/users')->with('success', 'Data user berhasil dihapus');
    }

    public function show($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new PageNotFoundException('Data user tidak ditemukan');
        }

        return view('users/show', [
            'user' => $user
        ]);
    }
} 