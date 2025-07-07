<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $invoiceModel;
    protected $productModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        $userId = $this->session->get('user_id');
        
        // Total invoice per status
        $totalInvoices = [
            'draft' => $this->invoiceModel->where('user_id', $userId)->where('status', 'draft')->countAllResults(),
            'sent' => $this->invoiceModel->where('user_id', $userId)->where('status', 'sent')->countAllResults(),
            'paid' => $this->invoiceModel->where('user_id', $userId)->where('status', 'paid')->countAllResults(),
            'cancelled' => $this->invoiceModel->where('user_id', $userId)->where('status', 'cancelled')->countAllResults()
        ];
        
        // Total pendapatan
        $totalRevenue = $this->invoiceModel
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->selectSum('total_amount')
            ->first()['total_amount'] ?? 0;
        
        // Total produk
        $totalProducts = $this->productModel
            ->where('user_id', $userId)
            ->countAllResults();
        
        // Invoice terbaru
        $latestInvoices = $this->invoiceModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();
        
        // Invoice yang akan jatuh tempo
        $dueSoonInvoices = $this->invoiceModel
            ->where('user_id', $userId)
            ->where('status', 'sent')
            ->where('due_date >=', date('Y-m-d'))
            ->where('due_date <=', date('Y-m-d', strtotime('+7 days')))
            ->orderBy('due_date', 'ASC')
            ->find();
        
        // Data grafik pendapatan bulanan
        $monthlyRevenue = $this->invoiceModel
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->where('YEAR(created_at)', date('Y'))
            ->select('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('MONTH(created_at)')
            ->orderBy('MONTH(created_at)', 'ASC')
            ->find();
        
        $chartData = array_fill(0, 12, 0);
        foreach ($monthlyRevenue as $revenue) {
            $chartData[$revenue['month'] - 1] = (float) $revenue['total'];
        }
        
        $data = [
            'title' => 'Dashboard',
            'totalInvoices' => $totalInvoices,
            'totalRevenue' => $totalRevenue,
            'totalProducts' => $totalProducts,
            'latestInvoices' => $latestInvoices,
            'dueSoonInvoices' => $dueSoonInvoices,
            'chartData' => $chartData
        ];
        
        return view('dashboard', $data);
    }
} 