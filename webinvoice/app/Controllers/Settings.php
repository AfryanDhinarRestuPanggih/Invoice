<?php

namespace App\Controllers;

use App\Models\UserModel;

class Settings extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email,id,' . $userId . ']',
                'phone' => 'permit_empty|min_length[10]|max_length[15]',
                'address' => 'permit_empty|max_length[255]'
            ];
            
            if (!empty($this->request->getPost('password'))) {
                $rules['password'] = 'required|min_length[6]';
                $rules['password_confirm'] = 'required|matches[password]';
            }
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address')
                ];
                
                if (!empty($this->request->getPost('password'))) {
                    $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                }
                
                if ($this->userModel->update($userId, $data)) {
                    // Update session data
                    session()->set([
                        'name' => $data['name'],
                        'email' => $data['email']
                    ]);
                    
                    return redirect()->to('settings/profile')->with('success', 'Profil berhasil diperbarui');
                }
                
                return redirect()->back()->with('error', 'Gagal memperbarui profil');
            }
        }
        
        $data = [
            'title' => 'Pengaturan Profil',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];
        
        return view('settings/profile', $data);
    }
    
    public function company()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'company_name' => 'required|max_length[100]',
                'company_address' => 'required|max_length[255]',
                'company_phone' => 'required|max_length[15]',
                'company_email' => 'required|valid_email',
                'tax_percentage' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
                'invoice_prefix' => 'required|alpha_numeric|max_length[10]',
                'invoice_due_days' => 'required|numeric|greater_than[0]|less_than[366]'
            ];
            
            if ($this->validate($rules)) {
                $settings = [
                    'company_name' => $this->request->getPost('company_name'),
                    'company_address' => $this->request->getPost('company_address'),
                    'company_phone' => $this->request->getPost('company_phone'),
                    'company_email' => $this->request->getPost('company_email'),
                    'tax_percentage' => $this->request->getPost('tax_percentage'),
                    'invoice_prefix' => $this->request->getPost('invoice_prefix'),
                    'invoice_due_days' => $this->request->getPost('invoice_due_days')
                ];
                
                // Simpan ke file JSON
                $file = WRITEPATH . 'settings.json';
                if (write_file($file, json_encode($settings, JSON_PRETTY_PRINT))) {
                    return redirect()->to('settings/company')->with('success', 'Pengaturan perusahaan berhasil disimpan');
                }
                
                return redirect()->back()->with('error', 'Gagal menyimpan pengaturan perusahaan');
            }
        }
        
        // Baca pengaturan dari file JSON
        $file = WRITEPATH . 'settings.json';
        $settings = [];
        
        if (file_exists($file)) {
            $settings = json_decode(file_get_contents($file), true);
        }
        
        $data = [
            'title' => 'Pengaturan Perusahaan',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];
        
        return view('settings/company', $data);
    }
} 