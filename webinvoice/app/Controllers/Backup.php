<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use ZipArchive;

class Backup extends BaseController
{
    protected $db;
    protected $backupPath;
    
    public function __construct()
    {
        $this->db = db_connect();
        $this->backupPath = WRITEPATH . 'backups/';
        
        // Buat direktori backup jika belum ada
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
    }
    
    public function index()
    {
        // Ambil daftar file backup
        $backups = [];
        if (is_dir($this->backupPath)) {
            $files = scandir($this->backupPath);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'zip') {
                    $backups[] = [
                        'name' => $file,
                        'size' => $this->formatSize(filesize($this->backupPath . $file)),
                        'date' => date('d/m/Y H:i', filemtime($this->backupPath . $file))
                    ];
                }
            }
        }
        
        // Urutkan berdasarkan tanggal terbaru
        usort($backups, function($a, $b) {
            return filemtime($this->backupPath . $b['name']) - filemtime($this->backupPath . $a['name']);
        });
        
        $data = [
            'title' => 'Backup Database',
            'backups' => $backups
        ];
        
        return view('backup/index', $data);
    }
    
    public function create()
    {
        try {
            // Generate nama file
            $filename = 'backup_' . date('Y-m-d_His');
            
            // Backup struktur dan data
            $backup = '';
            
            // Daftar tabel
            $tables = $this->db->listTables();
            
            foreach ($tables as $table) {
                // Struktur tabel
                $query = $this->db->query("SHOW CREATE TABLE $table");
                $row = $query->getRowArray();
                $backup .= "\n\n" . $row['Create Table'] . ";\n\n";
                
                // Data tabel
                $query = $this->db->query("SELECT * FROM $table");
                $rows = $query->getResultArray();
                
                foreach ($rows as $row) {
                    $values = array_map(function($value) {
                        if ($value === null) {
                            return 'NULL';
                        }
                        return $this->db->escape($value);
                    }, $row);
                    
                    $backup .= "INSERT INTO $table VALUES (" . implode(", ", $values) . ");\n";
                }
            }
            
            // Simpan ke file SQL
            $sqlFile = $this->backupPath . $filename . '.sql';
            write_file($sqlFile, $backup);
            
            // Buat ZIP
            $zip = new ZipArchive();
            $zipFile = $this->backupPath . $filename . '.zip';
            
            if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($sqlFile, $filename . '.sql');
                $zip->close();
                
                // Hapus file SQL
                unlink($sqlFile);
                
                return redirect()->to('backup')->with('success', 'Backup database berhasil dibuat');
            }
            
            return redirect()->to('backup')->with('error', 'Gagal membuat file ZIP');
            
        } catch (\Exception $e) {
            return redirect()->to('backup')->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }
    
    public function download($filename)
    {
        $file = $this->backupPath . $filename;
        
        if (file_exists($file)) {
            return $this->response->download($file, null);
        }
        
        return redirect()->to('backup')->with('error', 'File tidak ditemukan');
    }
    
    public function restore()
    {
        if (!$this->request->getFile('backup')) {
            return redirect()->to('backup')->with('error', 'File backup tidak ditemukan');
        }
        
        $file = $this->request->getFile('backup');
        
        if ($file->getExtension() !== 'zip') {
            return redirect()->to('backup')->with('error', 'File harus berformat ZIP');
        }
        
        try {
            // Extract ZIP
            $zip = new ZipArchive();
            if ($zip->open($file->getTempName()) === TRUE) {
                $sqlContent = '';
                
                // Baca file SQL dari ZIP
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    if (pathinfo($zip->getNameIndex($i), PATHINFO_EXTENSION) == 'sql') {
                        $sqlContent = $zip->getFromIndex($i);
                        break;
                    }
                }
                
                $zip->close();
                
                if (empty($sqlContent)) {
                    return redirect()->to('backup')->with('error', 'File SQL tidak ditemukan dalam ZIP');
                }
                
                // Eksekusi SQL
                $queries = explode(';', $sqlContent);
                
                foreach ($queries as $query) {
                    $query = trim($query);
                    if (!empty($query)) {
                        $this->db->query($query);
                    }
                }
                
                return redirect()->to('backup')->with('success', 'Database berhasil direstore');
            }
            
            return redirect()->to('backup')->with('error', 'Gagal membuka file ZIP');
            
        } catch (\Exception $e) {
            return redirect()->to('backup')->with('error', 'Gagal restore database: ' . $e->getMessage());
        }
    }
    
    public function delete($filename)
    {
        $file = $this->backupPath . $filename;
        
        if (file_exists($file)) {
            unlink($file);
            return redirect()->to('backup')->with('success', 'File backup berhasil dihapus');
        }
        
        return redirect()->to('backup')->with('error', 'File tidak ditemukan');
    }
    
    protected function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }
} 