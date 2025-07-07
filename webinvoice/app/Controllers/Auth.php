<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    public function attemptLogin()
    {
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        
        // Cari user dengan case-insensitive email
        $user = $this->userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return redirect()->back()
                    ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.')
                    ->withInput();
            }

            $sessionData = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            $this->userModel->updateLastLogin($user['id']);
            
            return redirect()->to('/dashboard')
                ->with('success', 'Selamat datang kembali, ' . $user['name']);
        }
        
        return redirect()->back()
            ->with('error', 'Email atau password salah')
            ->withInput();
    }

    public function attemptRegister()
    {
        $rules = [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah terdaftar'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Password konfirmasi tidak cocok'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Bersihkan input
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $name = trim($this->request->getPost('name'));
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user',
            'status' => 'active'
        ];

        try {
            $userId = $this->userModel->insert($data);
            
            if ($userId) {
                // Redirect ke halaman login dengan pesan sukses
                return redirect()->to('/login')
                    ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
            }
            
            return redirect()->back()->withInput()
                ->with('error', 'Gagal membuat akun. Silakan coba lagi.');
                
        } catch (\Exception $e) {
            log_message('error', 'Registration error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout');
    }

    public function resetPassword()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email harus diisi',
                        'valid_email' => 'Format email tidak valid'
                    ]
                ],
                'new_password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password baru harus diisi',
                        'min_length' => 'Password minimal 6 karakter'
                    ]
                ],
                'confirm_password' => [
                    'rules' => 'required|matches[new_password]',
                    'errors' => [
                        'required' => 'Konfirmasi password harus diisi',
                        'matches' => 'Konfirmasi password tidak cocok'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                return redirect()->back()->withInput()
                    ->with('error', implode('<br>', $errors));
            }

            $email = $this->request->getPost('email');
            $newPassword = $this->request->getPost('new_password');
            
            $user = $this->userModel->where('email', $email)->first();
            
            if ($user) {
                try {
                    $this->userModel->update($user['id'], [
                        'password' => password_hash($newPassword, PASSWORD_DEFAULT)
                    ]);
                    
                    return redirect()->to('/login')
                        ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
                } catch (\Exception $e) {
                    log_message('error', 'Error resetting password: ' . $e->getMessage());
                    return redirect()->back()->withInput()
                        ->with('error', 'Terjadi kesalahan saat mereset password. Silakan coba lagi.');
                }
            }
            
            return redirect()->back()->withInput()
                ->with('error', 'Email tidak ditemukan dalam sistem.');
        }

        // Tampilkan view dengan pesan error jika ada
        $data = [
            'error' => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success')
        ];
        
        return view('auth/reset_password', $data);
    }

    // Tambahkan method ini untuk debugging
    public function debugUser()
    {
        // Hanya bisa diakses di development
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/login');
        }

        $email = $this->request->getGet('email');
        if (!$email) {
            return 'Email parameter is required';
        }

        $user = $this->userModel->where('email', $email)->first();
        if (!$user) {
            return 'User not found';
        }

        // Tampilkan informasi user (jangan tampilkan password)
        unset($user['password']);
        echo '<pre>';
        print_r($user);
        echo '</pre>';
        return;
    }
} 