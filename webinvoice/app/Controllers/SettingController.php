<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SettingController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new \App\Models\SettingModel();
    }

    public function index()
    {
        if (!in_groups('admin')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('settings/index', [
            'settings' => $this->settingModel->getAllSettings()
        ]);
    }

    public function update()
    {
        if (!in_groups('admin')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Update company settings
        $companySettings = [
            ['setting_group' => 'company', 'setting_key' => 'name', 'setting_value' => 'PT. Succes Indonesia Abadi'],
            ['setting_group' => 'company', 'setting_key' => 'address', 'setting_value' => 'Jl. Mh Thamrin No.1 Kab.Bekasi'],
            ['setting_group' => 'company', 'setting_key' => 'email', 'setting_value' => 'abadisukses1@gmail.com'],
            ['setting_group' => 'company', 'setting_key' => 'phone', 'setting_value' => '021-1231101']
        ];

        // Delete existing company settings
        $this->settingModel->where('setting_group', 'company')->delete();
        
        // Insert new settings
        foreach ($companySettings as $setting) {
            $setting['created_at'] = date('Y-m-d H:i:s');
            $setting['updated_at'] = date('Y-m-d H:i:s');
            $this->settingModel->insert($setting);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function getLogo($filename)
    {
        $path = WRITEPATH . 'uploads/' . $filename;
        
        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
} 