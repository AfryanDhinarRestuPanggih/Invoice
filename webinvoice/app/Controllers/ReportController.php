<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends BaseController
{
    protected $invoiceModel;
    protected $invoiceItemModel;

    public function __construct()
    {
        $this->invoiceModel = new \App\Models\InvoiceModel();
        $this->invoiceItemModel = new \App\Models\InvoiceItemModel();
    }

    public function index()
    {
        // Filter periode
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        // Base query untuk invoice
        $builder = $this->invoiceModel;
        if (!in_groups('admin')) {
            $builder->where('user_id', user_id());
        }

        // Filter periode
        $builder->where('DATE(created_at) >=', $start_date)
                ->where('DATE(created_at) <=', $end_date);

        // Data untuk grafik pendapatan per bulan
        $monthlyRevenue = $builder->select("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as total_invoices,
                SUM(CASE WHEN status = 'paid' THEN total_amount ELSE 0 END) as revenue,
                SUM(CASE WHEN status = 'sent' THEN total_amount ELSE 0 END) as pending
            ")
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();

        // Data untuk grafik status invoice
        $statusCount = $builder->select("
                status,
                COUNT(*) as total
            ")
            ->groupBy('status')
            ->get()
            ->getResultArray();

        // Ringkasan
        $summary = [
            'total_invoices' => $builder->countAllResults(false),
            'total_revenue' => $builder->where('status', 'paid')->selectSum('total_amount')->get()->getRow()->total_amount ?? 0,
            'total_pending' => $builder->where('status', 'sent')->selectSum('total_amount')->get()->getRow()->total_amount ?? 0,
            'average_amount' => $builder->where('status !=', 'cancelled')->selectAvg('total_amount')->get()->getRow()->total_amount ?? 0
        ];

        // Data untuk tabel transaksi
        $transactions = $builder->select('
                invoices.*,
                users.username as created_by
            ')
            ->join('users', 'users.id = invoices.user_id')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $data = [
            'monthlyRevenue' => $monthlyRevenue,
            'statusCount' => $statusCount,
            'summary' => $summary,
            'transactions' => $transactions,
            'pager' => $builder->pager,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('reports/index', $data);
    }

    public function export()
    {
        // Filter periode
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        // Base query
        $builder = $this->invoiceModel->select('
                invoices.*,
                users.username as created_by
            ')
            ->join('users', 'users.id = invoices.user_id');

        if (!in_groups('admin')) {
            $builder->where('invoices.user_id', user_id());
        }

        // Filter periode
        $builder->where('DATE(invoices.created_at) >=', $start_date)
                ->where('DATE(invoices.created_at) <=', $end_date)
                ->orderBy('invoices.created_at', 'DESC');

        $invoices = $builder->get()->getResultArray();

        // Ringkasan
        $summary = [
            'total_invoices' => count($invoices),
            'total_revenue' => array_reduce($invoices, function($carry, $item) {
                return $carry + ($item['status'] == 'paid' ? $item['total_amount'] : 0);
            }, 0),
            'total_pending' => array_reduce($invoices, function($carry, $item) {
                return $carry + ($item['status'] == 'sent' ? $item['total_amount'] : 0);
            }, 0)
        ];

        // Generate PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        
        $html = view('reports/export_pdf', [
            'invoices' => $invoices,
            'summary' => $summary,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Stream PDF
        $dompdf->stream('laporan-invoice-' . date('Y-m-d') . '.pdf', [
            'Attachment' => true
        ]);
    }
} 