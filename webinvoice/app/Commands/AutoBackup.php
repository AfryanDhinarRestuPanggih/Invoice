<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use ZipArchive;

class AutoBackup extends BaseCommand
{
    protected $group = 'Database';
    protected $name = 'db:backup';
    protected $description = 'Create automatic database backup';
    
    protected $backupPath;
    protected $db;
    protected $maxBackups = 7; // Simpan backup 7 hari terakhir
    
    public function run(array $params)
    {
        $this->backupPath = WRITEPATH . 'backups/';
        $this->db = db_connect();
        
        // Buat direktori backup jika belum ada
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
        
        try {
            // Generate nama file
            $filename = 'auto_backup_' . date('Y-m-d_His');
            
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
                
                CLI::write('Backup created successfully: ' . $filename . '.zip', 'green');
                
                // Hapus backup lama
                $this->cleanOldBackups();
                
                return;
            }
            
            CLI::error('Failed to create ZIP file');
            
        } catch (\Exception $e) {
            CLI::error('Backup failed: ' . $e->getMessage());
        }
    }
    
    protected function cleanOldBackups()
    {
        // Ambil semua file backup otomatis
        $files = glob($this->backupPath . 'auto_backup_*.zip');
        
        // Urutkan berdasarkan tanggal (terbaru dulu)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        // Hapus file yang lebih dari maxBackups
        if (count($files) > $this->maxBackups) {
            for ($i = $this->maxBackups; $i < count($files); $i++) {
                unlink($files[$i]);
                CLI::write('Deleted old backup: ' . basename($files[$i]), 'yellow');
            }
        }
    }
} 