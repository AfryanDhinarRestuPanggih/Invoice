<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class Reports extends BaseController
{
    protected $invoiceModel;
    
    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
    }
    
    public function sales()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $status = $this->request->getGet('status');
        
        $query = $this->invoiceModel
            ->where('user_id', session()->get('user_id'))
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59');
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $invoices = $query->findAll();
        
        // Hitung total
        $totalAmount = 0;
        $totalPaid = 0;
        $totalUnpaid = 0;
        
        foreach ($invoices as $invoice) {
            if ($invoice['status'] == 'paid') {
                $totalPaid += $invoice['total_amount'];
            } elseif ($invoice['status'] == 'sent') {
                $totalUnpaid += $invoice['total_amount'];
            }
            $totalAmount += $invoice['total_amount'];
        }
        
        $data = [
            'title' => 'Laporan Penjualan',
            'invoices' => $invoices,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'totalUnpaid' => $totalUnpaid
        ];
        
        return view('reports/sales', $data);
    }
    
    public function exportExcel()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $status = $this->request->getGet('status');
        
        $query = $this->invoiceModel
            ->where('user_id', session()->get('user_id'))
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59');
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $invoices = $query->findAll();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header
        $sheet->setCellValue('A1', 'No. Invoice');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Pelanggan');
        $sheet->setCellValue('D1', 'Status');
        $sheet->setCellValue('E1', 'Subtotal');
        $sheet->setCellValue('F1', 'Pajak');
        $sheet->setCellValue('G1', 'Total');
        
        // Set data
        $row = 2;
        foreach ($invoices as $invoice) {
            $sheet->setCellValue('A' . $row, $invoice['invoice_number']);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($invoice['created_at'])));
            $sheet->setCellValue('C' . $row, $invoice['customer_name']);
            $sheet->setCellValue('D' . $row, ucfirst($invoice['status']));
            $sheet->setCellValue('E' . $row, $invoice['subtotal']);
            $sheet->setCellValue('F' . $row, $invoice['tax_amount']);
            $sheet->setCellValue('G' . $row, $invoice['total_amount']);
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set number format
        $sheet->getStyle('E2:G' . ($row-1))->getNumberFormat()->setFormatCode('#,##0');
        
        // Create file
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_penjualan_' . date('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }
    
    public function exportPdf()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $status = $this->request->getGet('status');
        
        $query = $this->invoiceModel
            ->where('user_id', session()->get('user_id'))
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59');
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $invoices = $query->findAll();
        
        // Hitung total
        $totalAmount = 0;
        $totalPaid = 0;
        $totalUnpaid = 0;
        
        foreach ($invoices as $invoice) {
            if ($invoice['status'] == 'paid') {
                $totalPaid += $invoice['total_amount'];
            } elseif ($invoice['status'] == 'sent') {
                $totalUnpaid += $invoice['total_amount'];
            }
            $totalAmount += $invoice['total_amount'];
        }
        
        // Create PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(session()->get('name'));
        $pdf->SetTitle('Laporan Penjualan');
        
        // Remove header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Penjualan', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate)), 0, 1, 'C');
        $pdf->Ln(5);
        
        // Table header
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(40, 7, 'No. Invoice', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Tanggal', 1, 0, 'C', true);
        $pdf->Cell(60, 7, 'Pelanggan', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Status', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Subtotal', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Pajak', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Total', 1, 1, 'C', true);
        
        // Table data
        $pdf->SetFont('helvetica', '', 10);
        foreach ($invoices as $invoice) {
            $pdf->Cell(40, 7, $invoice['invoice_number'], 1, 0, 'L');
            $pdf->Cell(30, 7, date('d/m/Y', strtotime($invoice['created_at'])), 1, 0, 'C');
            $pdf->Cell(60, 7, $invoice['customer_name'], 1, 0, 'L');
            $pdf->Cell(30, 7, ucfirst($invoice['status']), 1, 0, 'C');
            $pdf->Cell(35, 7, number_format($invoice['subtotal'], 0, ',', '.'), 1, 0, 'R');
            $pdf->Cell(35, 7, number_format($invoice['tax_amount'], 0, ',', '.'), 1, 0, 'R');
            $pdf->Cell(35, 7, number_format($invoice['total_amount'], 0, ',', '.'), 1, 1, 'R');
        }
        
        // Summary
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(195, 7, 'Ringkasan:', 0, 0);
        $pdf->Cell(70, 7, 'Total: Rp ' . number_format($totalAmount, 0, ',', '.'), 0, 1, 'R');
        $pdf->Cell(195, 7, '', 0, 0);
        $pdf->Cell(70, 7, 'Lunas: Rp ' . number_format($totalPaid, 0, ',', '.'), 0, 1, 'R');
        $pdf->Cell(195, 7, '', 0, 0);
        $pdf->Cell(70, 7, 'Belum Lunas: Rp ' . number_format($totalUnpaid, 0, ',', '.'), 0, 1, 'R');
        
        // Output PDF
        $pdf->Output('laporan_penjualan_' . date('Y-m-d') . '.pdf', 'D');
        exit();
    }
} 