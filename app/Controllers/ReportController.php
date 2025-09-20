<?php
// app/Controllers/ReportController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\TransactionCategory;
use App\Models\Debt;
use App\Models\Loan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController {
    private $db;
    private $transaction;
    private $account;
    private $transactionCategory;
    private $debt;
    private $loan;

    public function __construct($db) {
        $this->db = $db;
        $this->transaction = new Transaction($db);
        $this->account = new Account($db);
        $this->transactionCategory = new TransactionCategory($db);
        $this->debt = new Debt($db);
        $this->loan = new Loan($db);
    }

    // Show report form
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $accounts = $this->account->getByUserId($user_id);
        $categories = $this->transactionCategory->getByUserId($user_id);

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/reports/index.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Generate comprehensive report
    public function generate() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $start_date = !empty($_POST['start_date']) ? \App\Helpers\JalaliHelper::toGregorian($_POST['start_date']) : null;
        $end_date = !empty($_POST['end_date']) ? \App\Helpers\JalaliHelper::toGregorian($_POST['end_date']) : null;
        $account_ids = $_POST['account_ids'] ?? [];
        $category_ids = $_POST['category_ids'] ?? [];
        $report_type = $_POST['report_type'] ?? 'summary';
        $format = $_POST['format'] ?? 'pdf';

        // Get transactions
        $filters = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'account_id' => !empty($account_ids) ? $account_ids[0] : null, // For now, handle single account
        ];
        
        if (!empty($category_ids)) {
            $filters['category_id'] = $category_ids[0]; // For now, handle single category
        }

        $transactions = $this->transaction->getByUser($user_id, $filters);
        
        // Calculate totals
        $total_income = 0;
        $total_expense = 0;
        $category_totals = [];

        foreach ($transactions as $t) {
            if ($t['type'] === 'income') {
                $total_income += $t['amount'];
            } else {
                $total_expense += $t['amount'];
            }
            
            $cat_name = $t['category_name'] ?? 'بدون دسته‌بندی';
            if (!isset($category_totals[$cat_name])) {
                $category_totals[$cat_name] = ['income' => 0, 'expense' => 0];
            }
            if ($t['type'] === 'income') {
                $category_totals[$cat_name]['income'] += $t['amount'];
            } else {
                $category_totals[$cat_name]['expense'] += $t['amount'];
            }
        }

        // Get user info
        $stmt = $this->db->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($format === 'excel') {
            $this->generateExcelReport($user, $transactions, $total_income, $total_expense, $category_totals, $start_date, $end_date);
        } elseif ($format === 'csv') {
            $this->generateCSVReport($user, $transactions, $total_income, $total_expense, $category_totals, $start_date, $end_date);
        } else {
            $this->generatePDFReport($user, $transactions, $total_income, $total_expense, $category_totals, $start_date, $end_date);
        }
    }

    // Generate Excel report
    private function generateExcelReport($user, $transactions, $total_income, $total_expense, $category_totals, $start_date, $end_date) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator($user['name'])
            ->setTitle('گزارش مالی')
            ->setDescription('گزارش جامع مالی');

        // Add header
        $sheet->setCellValue('A1', 'گزارش مالی');
        $sheet->setCellValue('A2', 'از تاریخ: ' . ($start_date ?: 'شروع'));
        $sheet->setCellValue('A3', 'تا تاریخ: ' . ($end_date ?: 'اکنون'));
        $sheet->setCellValue('A4', 'تاریخ تولید گزارش: ' . date('Y-m-d H:i:s'));

        // Add summary
        $sheet->setCellValue('A6', 'خلاصه گزارش');
        $sheet->setCellValue('A7', 'درآمدها');
        $sheet->setCellValue('B7', $total_income);
        $sheet->setCellValue('A8', 'هزینه‌ها');
        $sheet->setCellValue('B8', $total_expense);
        $sheet->setCellValue('A9', 'مانده نهایی');
        $sheet->setCellValue('B9', $total_income - $total_expense);

        // Add category summary
        $sheet->setCellValue('A11', 'خلاصه بر اساس دسته‌بندی');
        $sheet->setCellValue('A12', 'دسته‌بندی');
        $sheet->setCellValue('B12', 'درآمدها');
        $sheet->setCellValue('C12', 'هزینه‌ها');
        $sheet->setCellValue('D12', 'مانده');

        $row = 13;
        foreach ($category_totals as $cat => $totals) {
            $sheet->setCellValue('A' . $row, $cat);
            $sheet->setCellValue('B' . $row, $totals['income']);
            $sheet->setCellValue('C' . $row, $totals['expense']);
            $sheet->setCellValue('D' . $row, $totals['income'] - $totals['expense']);
            $row++;
        }

        // Add transaction details
        $sheet->setCellValue('A' . ($row + 2), 'جزئیات تراکنش‌ها');
        $sheet->setCellValue('A' . ($row + 3), 'تاریخ');
        $sheet->setCellValue('B' . ($row + 3), 'حساب');
        $sheet->setCellValue('C' . ($row + 3), 'دسته‌بندی');
        $sheet->setCellValue('D' . ($row + 3), 'نوع');
        $sheet->setCellValue('E' . ($row + 3), 'مبلغ');
        $sheet->setCellValue('F' . ($row + 3), 'توضیحات');

        $detail_row = $row + 4;
        foreach ($transactions as $t) {
            $sheet->setCellValue('A' . $detail_row, $t['trans_date']);
            $sheet->setCellValue('B' . $detail_row, $t['account_title']);
            $sheet->setCellValue('C' . $detail_row, $t['category_name'] ?? '—');
            $sheet->setCellValue('D' . $detail_row, $t['type'] === 'income' ? 'درآمد' : 'هزینه');
            $sheet->setCellValue('E' . $detail_row, $t['amount']);
            $sheet->setCellValue('F' . $detail_row, $t['description'] ?? '—');
            $detail_row++;
        }

        // Style the header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A6:D6')->getFont()->setBold(true);
        $sheet->getStyle('A12:D12')->getFont()->setBold(true);
        $sheet->getStyle('A' . ($row + 2) . ':F' . ($row + 2))->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="financial_report_' . date('Ymd') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Generate CSV report
    private function generateCSVReport($user, $transactions, $total_income, $total_expense, $category_totals, $start_date, $end_date) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="financial_report_' . date('Ymd') . '.csv"');

        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Write summary
        fputcsv($output, ['گزارش مالی']);
        fputcsv($output, ['از تاریخ:', $start_date ?: 'شروع']);
        fputcsv($output, ['تا تاریخ:', $end_date ?: 'اکنون']);
        fputcsv($output, ['تاریخ تولید گزارش:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        fputcsv($output, ['خلاصه گزارش']);
        fputcsv($output, ['درآمدها', $total_income]);
        fputcsv($output, ['هزینه‌ها', $total_expense]);
        fputcsv($output, ['مانده نهایی', $total_income - $total_expense]);
        fputcsv($output, []);

        // Write category summary
        fputcsv($output, ['خلاصه بر اساس دسته‌بندی']);
        fputcsv($output, ['دسته‌بندی', 'درآمدها', 'هزینه‌ها', 'مانده']);
        foreach ($category_totals as $cat => $totals) {
            fputcsv($output, [$cat, $totals['income'], $totals['expense'], $totals['income'] - $totals['expense']]);
        }
        fputcsv($output, []);

        // Write transaction details
        fputcsv($output, ['جزئیات تراکنش‌ها']);
        fputcsv($output, ['تاریخ', 'حساب', 'دسته‌بندی', 'نوع', 'مبلغ', 'توضیحات']);
        foreach ($transactions as $t) {
            fputcsv($output, [
                $t['trans_date'],
                $t['account_title'],
                $t['category_name'] ?? '—',
                $t['type'] === 'income' ? 'درآمد' : 'هزینه',
                $t['amount'],
                $t['description'] ?? '—'
            ]);
        }

        fclose($output);
        exit;
    }

    // Generate PDF report using TCPDF
    private function generatePDFReport($user, $transactions, $total_income, $total_expense, $category_totals, $start_date, $end_date) {
        require_once PROJECT_ROOT . '/vendor/tecnickcom/tcpdf/tcpdf.php';

        // Create new PDF document
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($user['name']);
        $pdf->SetTitle('گزارش مالی');
        $pdf->SetSubject('گزارش جامع مالی');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'گزارش مالی', 'fintra سامانه حسابداری شخصی');

        // Set header and footer fonts
        $pdf->setHeaderFont(Array('dejavusans', '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array('dejavusans', '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Set font
        $pdf->SetFont('dejavusans', '', 12);

        // Add a page
        $pdf->AddPage('P', 'A4');

        // Set RTL direction
        $pdf->setRTL(true);

        // Write HTML content
        $html = '
        <h1 style="text-align:center;">گزارش مالی</h1>
        <div style="background-color:#e8f4fd; padding:15px; margin:20px 0; border-radius:5px;">
            <p><strong>از تاریخ:</strong> ' . ($start_date ?: 'شروع') . '</p>
            <p><strong>تا تاریخ:</strong> ' . ($end_date ?: 'اکنون') . '</p>
            <p><strong>تاریخ تولید گزارش:</strong> ' . date('Y-m-d H:i:s') . '</p>
        </div>

        <h2>خلاصه گزارش</h2>
        <table border="1" cellpadding="5" style="width:100%; border-collapse:collapse;">
            <tr>
                <th style="background-color:#f2f2f2;">عنوان</th>
                <th style="background-color:#f2f2f2;">مبلغ (تومان)</th>
            </tr>
            <tr>
                <td>درآمدها</td>
                <td style="color:green; font-weight:bold;">' . number_format($total_income) . '</td>
            </tr>
            <tr>
                <td>هزینه‌ها</td>
                <td style="color:red; font-weight:bold;">' . number_format($total_expense) . '</td>
            </tr>
            <tr>
                <td><strong>مانده نهایی</strong></td>
                <td style="color:' . ($total_income - $total_expense >= 0 ? 'green' : 'red') . '; font-weight:bold;">
                    <strong>' . number_format($total_income - $total_expense) . '</strong>
                </td>
            </tr>
        </table>

        <h2>خلاصه بر اساس دسته‌بندی</h2>
        <table border="1" cellpadding="5" style="width:100%; border-collapse:collapse;">
            <tr>
                <th style="background-color:#f2f2f2;">دسته‌بندی</th>
                <th style="background-color:#f2f2f2;">درآمدها</th>
                <th style="background-color:#f2f2f2;">هزینه‌ها</th>
                <th style="background-color:#f2f2f2;">مانده</th>
            </tr>';

        foreach ($category_totals as $cat => $totals) {
            $balance = $totals['income'] - $totals['expense'];
            $html .= '
            <tr>
                <td>' . htmlspecialchars($cat) . '</td>
                <td style="color:green; font-weight:bold;">' . number_format($totals['income']) . '</td>
                <td style="color:red; font-weight:bold;">' . number_format($totals['expense']) . '</td>
                <td style="color:' . ($balance >= 0 ? 'green' : 'red') . '; font-weight:bold;">' . number_format($balance) . '</td>
            </tr>';
        }

        $html .= '
        </table>

        <h2>جزئیات تراکنش‌ها</h2>
        <table border="1" cellpadding="5" style="width:100%; border-collapse:collapse;">
            <tr>
                <th style="background-color:#f2f2f2;">تاریخ</th>
                <th style="background-color:#f2f2f2;">حساب</th>
                <th style="background-color:#f2f2f2;">دسته‌بندی</th>
                <th style="background-color:#f2f2f2;">نوع</th>
                <th style="background-color:#f2f2f2;">مبلغ</th>
                <th style="background-color:#f2f2f2;">توضیحات</th>
            </tr>';

        foreach ($transactions as $t) {
            $html .= '
            <tr>
                <td>' . $t['trans_date'] . '</td>
                <td>' . htmlspecialchars($t['account_title']) . '</td>
                <td>' . htmlspecialchars($t['category_name'] ?? '—') . '</td>
                <td>' . ($t['type'] === 'income' ? 'درآمد' : 'هزینه') . '</td>
                <td style="color:' . ($t['type'] === 'income' ? 'green' : 'red') . '; font-weight:bold;">' . number_format($t['amount']) . '</td>
                <td>' . htmlspecialchars($t['description'] ?? '—') . '</td>
            </tr>';
        }

        $html .= '
        </table>';

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('financial_report_' . date('Ymd') . '.pdf', 'D');
        exit;
    }

    // Export transactions to Excel
    public function exportTransactions() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        $account_id = $_GET['account_id'] ?? null;
        $category_id = $_GET['category_id'] ?? null;

        $filters = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'account_id' => $account_id,
            'category_id' => $category_id
        ];

        $transactions = $this->transaction->getByUser($user_id, $filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'تاریخ');
        $sheet->setCellValue('B1', 'حساب');
        $sheet->setCellValue('C1', 'دسته‌بندی');
        $sheet->setCellValue('D1', 'نوع');
        $sheet->setCellValue('E1', 'مبلغ');
        $sheet->setCellValue('F1', 'توضیحات');

        $row = 2;
        foreach ($transactions as $t) {
            $sheet->setCellValue('A' . $row, $t['trans_date']);
            $sheet->setCellValue('B' . $row, $t['account_title']);
            $sheet->setCellValue('C' . $row, $t['category_name'] ?? '—');
            $sheet->setCellValue('D' . $row, $t['type'] === 'income' ? 'درآمد' : 'هزینه');
            $sheet->setCellValue('E' . $row, $t['amount']);
            $sheet->setCellValue('F' . $row, $t['description'] ?? '—');
            $row++;
        }

        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="transactions_' . date('Ymd') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Export debts to Excel
    public function exportDebts() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $debts = $this->debt->getByUserId($user_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'نام شخص');
        $sheet->setCellValue('B1', 'نوع');
        $sheet->setCellValue('C1', 'مبلغ');
        $sheet->setCellValue('D1', 'تاریخ سررسید');
        $sheet->setCellValue('E1', 'وضعیت');
        $sheet->setCellValue('F1', 'توضیحات');

        $row = 2;
        foreach ($debts as $d) {
            $sheet->setCellValue('A' . $row, $d['person_name']);
            $sheet->setCellValue('B' . $row, $d['type'] === 'debt' ? 'بدهی' : 'طلب');
            $sheet->setCellValue('C' . $row, $d['amount']);
            $sheet->setCellValue('D' . $row, $d['due_date'] ?? '—');
            $sheet->setCellValue('E' . $row, $d['status'] === 'unpaid' ? 'پرداخت نشده' : ($d['status'] === 'partial' ? 'پرداخت جزئی' : 'پرداخت شده'));
            $sheet->setCellValue('F' . $row, $d['description'] ?? '—');
            $row++;
        }

        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="debts_' . date('Ymd') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Export loans to Excel
    public function exportLoans() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $loans = $this->loan->getByUserId($user_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'وام‌دهنده');
        $sheet->setCellValue('B1', 'مبلغ اصلی');
        $sheet->setCellValue('C1', 'نرخ سود');
        $sheet->setCellValue('D1', 'تعداد اقساط');
        $sheet->setCellValue('E1', 'مبلغ هر قسط');
        $sheet->setCellValue('F1', 'تاریخ شروع');
        $sheet->setCellValue('G1', 'وضعیت');
        $sheet->setCellValue('H1', 'توضیحات');

        $row = 2;
        foreach ($loans as $l) {
            $sheet->setCellValue('A' . $row, $l['lender_name']);
            $sheet->setCellValue('B' . $row, $l['principal']);
            $sheet->setCellValue('C' . $row, $l['interest'] . '%');
            $sheet->setCellValue('D' . $row, $l['term_months']);
            $sheet->setCellValue('E' . $row, $l['installment_amount']);
            $sheet->setCellValue('F' . $row, $l['start_date']);
            $sheet->setCellValue('G' . $row, $l['status'] === 'active' ? 'فعال' : ($l['status'] === 'completed' ? 'تسویه شده' : 'لغو شده'));
            $sheet->setCellValue('H' . $row, $l['description'] ?? '—');
            $row++;
        }

        // Style header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="loans_' . date('Ymd') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}