<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fee Receipt</title>
    <style>
        @page { size: A4 portrait; margin: 16mm; }
        body { margin: 0; padding: 0; font-family: 'Arial', sans-serif; color: #1d1d1d; background: #fff; }
        .page { width: 100%; min-height: 297mm; padding: 20mm; box-sizing: border-box; }
        .receipt { max-width: 860px; margin: 0 auto; padding: 24px; border: 1px solid #d7d7d7; border-radius: 12px; background: #fff; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; border-bottom: 2px solid #222; padding-bottom: 16px; margin-bottom: 20px; }
        .brand { display: flex; align-items: center; gap: 14px; }
        .brand-logo { width: 86px; height: 86px; border: 1px solid #e1e1e1; border-radius: 14px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f8f8; }
        .brand-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .brand-text { line-height: 1.2; }
        .brand-name { font-size: 19px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; }
        .brand-subtitle { font-size: 12px; color: #555; margin-top: 4px; }
        .receipt-info { text-align: right; }
        .receipt-title { font-size: 18px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .receipt-meta { font-size: 12px; color: #444; line-height: 1.6; }
        .details { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; margin-top: 24px; border-bottom: 1px solid #e0e0e0; padding-bottom: 20px; }
        .details-block { padding-right: 8px; }
        .details-block strong { display: block; font-size: 12px; color: #777; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.08em; }
        .details-block span { display: block; font-size: 13px; color: #222; margin-bottom: 4px; }
        .table-wrap { margin-top: 24px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f7f7f7; }
        th, td { padding: 12px 10px; border: 1px solid #e0e0e0; }
        th { font-size: 12px; text-transform: uppercase; color: #555; text-align: left; }
        td { font-size: 13px; color: #222; vertical-align: top; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; display: flex; justify-content: flex-end; }
        .summary table { width: 340px; border: none; }
        .summary td { border: none; padding: 8px 10px; font-size: 13px; }
        .summary .label { color: #555; }
        .summary .value { font-weight: 700; text-align: right; }
        .notes { margin-top: 24px; font-size: 12px; color: #555; line-height: 1.6; }
        .signatures { display: flex; justify-content: space-between; gap: 18px; margin-top: 34px; }
        .signature-box { width: 48%; text-align: center; }
        .signature-line { border-top: 1px dashed #999; margin-top: 44px; padding-top: 8px; font-size: 12px; color: #555; }
        @media print { body { margin: 0; } .page { padding: 10mm; } .receipt { border: none; } }
    </style>
</head>
<body>
    <div class="page">
        @php
            $receiptData = $receipt ?? null;
            $studentData = $student ?? optional($receiptData)->student ?? null;
            $lineItems = $items ?? $receiptItems ?? optional($receiptData)->items ?? optional($invoice ?? null)->items ?? [];
            $receiptNumber = optional($receiptData)->invoice_number ?? optional($invoice ?? null)->number ?? optional($receiptData)->receipt_no ?? '';
            $receiptDate = optional($receiptData)->date ?? optional($invoice ?? null)->date ?? date('Y-m-d');
            $dueDate = optional($receiptData)->due_date ?? optional($invoice ?? null)->due_date ?? '';
            $paidAmount = optional($receiptData)->paid_amount ?? optional($payment ?? null)->amount ?? '';
            $totalAmount = optional($receiptData)->total_amount ?? optional($invoice ?? null)->total ?? '';
            $balanceAmount = optional($receiptData)->balance_amount ?? optional($invoice ?? null)->balance ?? '';
            $schoolName = $schoolName ?? config('app.name', 'School Name');
            $schoolAddress = $schoolAddress ?? 'Address Line 1, City, Country';
            $schoolPhone = $schoolPhone ?? 'Phone: +123 456 7890';
            $schoolEmail = $schoolEmail ?? 'Email: info@school.com';
        @endphp
        <div class="receipt">
            <div class="header">
                <div class="brand">
                    <div class="brand-logo">
                        @if(!empty($schoolLogo))
                            <img src="{{ $schoolLogo }}" alt="Logo">
                        @else
                            <span style="font-size: 22px; color: #333; font-weight: 700;">LOGO</span>
                        @endif
                    </div>
                    <div class="brand-text">
                        <div class="brand-name">{{ $schoolName }}</div>
                        <div class="brand-subtitle">{{ $schoolAddress }}</div>
                        <div class="brand-subtitle">{{ $schoolPhone }} | {{ $schoolEmail }}</div>
                    </div>
                </div>
                <div class="receipt-info">
                    <div class="receipt-title">Fee Receipt</div>
                    <div class="receipt-meta">Receipt No: <strong>{{ $receiptNumber }}</strong></div>
                    <div class="receipt-meta">Date: <strong>{{ $receiptDate }}</strong></div>
                    @if($dueDate)
                        <div class="receipt-meta">Due Date: <strong>{{ $dueDate }}</strong></div>
                    @endif
                </div>
            </div>

            <div class="details">
                <div class="details-block">
                    <strong>Student Information</strong>
                    <span>{{ optional($studentData)->name ?? optional($studentData)->student_name ?? '' }}</span>
                    <span>ID: {{ optional($studentData)->student_id ?? optional($studentData)->id ?? '' }}</span>
                    <span>Grade: {{ optional(optional($studentData)->grade)->Name ?? optional(optional($studentData)->classroom)->Name ?? '' }}</span>
                    <span>Section: {{ optional(optional($studentData)->section)->Name ?? '' }}</span>
                </div>
                <div class="details-block">
                    <strong>Payer Details</strong>
                    <span>{{ optional($receiptData)->payer_name ?? optional(optional($studentData)->myparent)->Name_Father ?? '' }}</span>
                    <span>Mobile: {{ optional($receiptData)->payer_phone ?? optional(optional($studentData)->myparent)->Phone_Father ?? '' }}</span>
                    <span>Email: {{ optional($receiptData)->payer_email ?? optional(optional($studentData)->myparent)->email ?? '' }}</span>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th>Description</th>
                            <th style="width: 18%; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($lineItems) && count($lineItems))
                            @foreach($lineItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->description ?? $item['description'] ?? $item['title'] ?? 'Fee item' }}</td>
                                    <td class="text-right">{{ number_format($item->amount ?? $item['amount'] ?? $item->price ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-right">No line items available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="summary">
                <table>
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="value">{{ number_format(optional($receiptData)->subtotal ?? $totalAmount ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Paid</td>
                        <td class="value">{{ number_format($paidAmount ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Balance</td>
                        <td class="value">{{ number_format($balanceAmount ?? max(($totalAmount ?? 0) - ($paidAmount ?? 0), 0), 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total</td>
                        <td class="value">{{ number_format($totalAmount ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>

            @if(optional($receiptData)->note || optional($receiptData)->remarks || optional($receiptData)->description)
                <div class="notes">
                    <strong>Note:</strong>
                    <div>{{ optional($receiptData)->note ?? optional($receiptData)->remarks ?? optional($receiptData)->description }}</div>
                </div>
            @endif

            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-line">Received By</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">Authorized Signature</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
