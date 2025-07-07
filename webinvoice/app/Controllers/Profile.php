<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        return view('profile/index', ['user' => $user]);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email,id,'.$userId.']',
        ];

        // Jika password diisi, tambahkan validasi password
        if ($this->request->getPost('password')) {
            $rules['password'] = 'required|min_length[6]';
            $rules['password_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($userId, $data);

        // Update session data
        session()->set([
            'user_name' => $data['name'],
            'user_email' => $data['email']
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function changePassword()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'new_password_confirm' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verifikasi password lama
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai');
        }

        // Update password
        $this->userModel->update($userId, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
} 