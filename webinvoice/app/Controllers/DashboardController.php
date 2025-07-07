<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $productModel;
    protected $invoiceModel;
    protected $invoiceItemModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
    }

    public function index()
    {
        $data = [];
        
        // Total Invoice
        $data['total_invoices'] = $this->invoiceModel->countAll();
        
        // Total Pendapatan (dari invoice yang sudah dibayar)
        $totalRevenue = $this->invoiceModel
            ->where('status', 'paid')
            ->selectSum('total_amount')
            ->first();
        $data['total_revenue'] = $totalRevenue['total_amount'] ?? 0;
        
        // Invoice Pending (status draft atau sent)
        $data['pending_invoices'] = $this->invoiceModel
            ->whereIn('status', ['draft', 'sent'])
            ->countAllResults();
        
        // Pendapatan Bulanan (dari invoice yang sudah dibayar)
        $data['monthly_revenue'] = $this->getMonthlyInvoiceRevenue();
        
        // Status Invoice
        $data['invoice_status'] = [
            'draft' => $this->invoiceModel->where('status', 'draft')->countAllResults(),
            'sent' => $this->invoiceModel->where('status', 'sent')->countAllResults(),
            'paid' => $this->invoiceModel->where('status', 'paid')->countAllResults(),
            'cancelled' => $this->invoiceModel->where('status', 'cancelled')->countAllResults()
        ];
        
        return view('dashboard/index', $data);
    }

    private function getMonthlyInvoiceRevenue()
    {
        $db = \Config\Database::connect();
        
        // Query untuk mendapatkan total pendapatan dari invoice yang sudah dibayar per bulan
        $query = $db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total_amount) as revenue
            FROM invoices
            WHERE status = 'paid'
            AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ");

        // Format data untuk chart
        $monthlyData = [];
        $months = [];
        $revenues = [];

        // Inisialisasi array dengan 12 bulan terakhir
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i month"));
            $months[] = date('M', strtotime("-$i month")); // Nama bulan singkat
            $monthlyData[$month] = 0;
        }

        // Isi data dari database
        foreach ($query->getResult() as $row) {
            $monthlyData[$row->month] = (float) $row->revenue;
        }

        // Ambil hanya nilai revenue dalam urutan yang benar
        foreach ($monthlyData as $revenue) {
            $revenues[] = $revenue;
        }

        return [
            'months' => $months,
            'revenues' => $revenues
        ];
    }
} 