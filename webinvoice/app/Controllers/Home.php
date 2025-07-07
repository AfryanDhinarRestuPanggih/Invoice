<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'total_invoices' => 0, // Nanti akan diambil dari database
            'total_revenue' => 0,
            'pending_invoices' => 0,
            'recent_invoices' => [], // Nanti akan diambil dari database
            'monthly_revenue' => [], // Untuk grafik
            'invoice_status' => [], // Untuk grafik pie
        ];
        
        return view('dashboard', $data);
    }
}
