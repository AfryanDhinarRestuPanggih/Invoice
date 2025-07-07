<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->findAll()
        ];

        return view('admin/users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah User Baru'
        ];

        return view('admin/users/create', $data);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,user]',
            'status'   => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
            'status'   => $this->request->getPost('status')
        ];

        $this->userModel->insert($data);

        return redirect()->to('admin/users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan');
        }

        $data = [
            'title' => 'Edit User',
            'user'  => $user
        ];

        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username,id,' . $id . ']',
            'email'    => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role'     => 'required|in_list[admin,user]',
            'status'   => 'required|in_list[active,inactive]'
        ];

        // Password optional saat update
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'status'   => $this->request->getPost('status')
        ];

        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->userModel->update($id, $data);

        return redirect()->to('admin/users')->with('success', 'User berhasil diupdate');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan');
        }

        // Mencegah admin menghapus dirinya sendiri
        if ($user['id'] === session()->get('user_id')) {
            return redirect()->to('admin/users')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $this->userModel->delete($id);

        return redirect()->to('admin/users')->with('success', 'User berhasil dihapus');
    }
} 