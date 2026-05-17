<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt {{ $payment->reference_number ?: sprintf('%06d', $payment->id) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #0d0d0d;
            background: #fff;
        }

        /* ── PAGE WRAPPER ── */
        .page {
            width: 105mm;
            margin: 0;
            padding: 6mm 7mm 8mm;
            border: 1.5px solid #222;
            border-radius: 6px;
        }

        /* ── HEADER ── */
        .receipt-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid #222;
            margin-bottom: 0;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header-center {
            flex: 1;
            text-align: center;
            padding: 0 4px;
        }

        .header-center .doc-title {
            font-size: 14pt;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .header-center .republic-line {
            font-size: 7.5pt;
            color: #333;
            margin-top: 2px;
        }

        .header-center .barangay-name {
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .header-center .address-line {
            font-size: 6.5pt;
            color: #555;
        }

        /* ── META ROW (AF No. / ORIGINAL) ── */
        .meta-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border: 1px solid #222;
            border-top: none;
            margin-top: 0;
        }

        .meta-cell {
            padding: 3px 6px;
            font-size: 7pt;
            line-height: 1.4;
        }

        .meta-cell:first-child {
            border-right: 1px solid #222;
        }

        .meta-cell .copy-type {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        /* ── DATE / OR NUMBER ROW ── */
        .date-or-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border: 1px solid #222;
            border-top: none;
        }

        .date-or-cell {
            padding: 3px 6px;
            font-size: 8pt;
        }

        .date-or-cell:first-child {
            border-right: 1px solid #222;
        }

        .date-or-cell .cell-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #555;
        }

        .date-or-cell .cell-value {
            font-size: 9pt;
            font-weight: 700;
        }

        .date-or-cell .or-value {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9.5pt;
            font-weight: 700;
        }

        /* ── AGENCY / FUND ROW ── */
        .agency-fund-row {
            display: grid;
            grid-template-columns: 3fr 2fr;
            border: 1px solid #222;
            border-top: none;
        }

        .af-cell {
            padding: 3px 6px;
            font-size: 8pt;
        }

        .af-cell:first-child {
            border-right: 1px solid #222;
        }

        .af-cell .cell-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #555;
        }

        .af-cell .cell-value {
            font-size: 8.5pt;
            font-weight: 600;
            min-height: 13px;
        }

        /* ── PAYOR ROW ── */
        .payor-row {
            border: 1px solid #222;
            border-top: none;
            padding: 3px 6px;
        }

        .payor-row .cell-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #555;
        }

        .payor-row .cell-value {
            font-size: 9pt;
            font-weight: 700;
            min-height: 14px;
        }

        /* ── COLLECTION TABLE WRAPPER ── */
        .collection-table-wrap {
            border: 1px solid #222;
            border-top: none;
        }

        /* ── COLLECTION TABLE ── */
        .collection-table {
            width: 100%;
            border-collapse: collapse;
        }

        .collection-table th {
            border-bottom: 1px solid #222;
            border-right: 1px solid #222;
            padding: 3px 5px;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            text-align: center;
            background: #f5f5f5;
            vertical-align: middle;
        }

        .collection-table th:last-child { border-right: none; }

        .collection-table td {
            border-bottom: 1px solid #bbb;
            border-right: 1px solid #222;
            padding: 3px 5px;
            font-size: 8.5pt;
            vertical-align: top;
            height: 16px;
        }

        .collection-table td:last-child { border-right: none; }

        .collection-table .empty-row td {
            height: 14px;
            padding: 2px 5px;
        }

        .collection-table .total-row td {
            border-bottom: none;
            border-top: 1px solid #222;
            font-weight: 700;
            font-size: 9pt;
            padding: 3px 5px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ── AMOUNT IN WORDS ── */
        .words-section {
            border: 1px solid #222;
            border-top: none;
        }

        .words-section .words-label {
            border-bottom: 1px solid #bbb;
            padding: 2px 6px;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #555;
            font-weight: 700;
            background: #f5f5f5;
        }

        .words-section .words-value {
            padding: 3px 6px 4px;
            font-size: 8.5pt;
            font-style: italic;
            min-height: 28px;
        }

        /* ── PAYMENT METHOD SECTION ── */
        .payment-section {
            border: 1px solid #222;
            border-top: none;
            display: grid;
            grid-template-columns: 2fr 3fr;
        }

        .payment-checkboxes {
            padding: 5px 6px;
            border-right: 1px solid #222;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
        }

        .payment-checkboxes .check-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 8pt;
        }

        .check-box {
            width: 10px;
            height: 10px;
            border: 1px solid #222;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 7pt;
            flex-shrink: 0;
            line-height: 1;
        }

        .check-box.checked::after {
            content: '\2713';
            font-size: 8pt;
            line-height: 1;
        }

        .payment-drawee {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: stretch;
        }

        .drawee-cell {
            padding: 0;
            border-right: 1px solid #222;
            font-size: 7pt;
            display: flex;
            flex-direction: column;
        }

        .drawee-cell:last-child { border-right: none; }

        .drawee-cell .cell-label {
            text-align: center;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #555;
            font-weight: 700;
            border-bottom: 1px solid #222;
            padding: 3px 4px 2px;
            background: #f5f5f5;
        }

        .drawee-cell .cell-value {
            font-size: 8pt;
            padding: 3px 4px;
            flex: 1;
            min-height: 26px;
        }

        /* ── RECEIVED STATEMENT ── */
        .received-section {
            border: 1px solid #222;
            border-top: none;
            border-radius: 0 0 4px 4px;
            padding: 5px 6px 10px;
        }

        .received-statement {
            font-size: 8pt;
            color: #333;
            margin-bottom: 18px;
        }

        .officer-block {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .officer-sig {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
        }

        .officer-name {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            padding: 0 8px;
        }

        .officer-underline {
            display: block;
            border-top: 1px solid #222;
            width: 100%;
        }

        .officer-title {
            font-size: 7pt;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #444;
            text-align: center;
            margin-top: 1px;
        }

        /* ── FOOTER NOTE ── */
        .footer-note {
            margin-top: 5px;
            font-size: 6.5pt;
            color: #777;
            font-style: italic;
            text-align: center;
        }

        /* ── PRINT ACTIONS (screen only) ── */
        .print-actions {
            position: fixed;
            top: 14px;
            right: 18px;
            display: flex;
            gap: 8px;
            z-index: 100;
        }

        .btn-print {
            background: #1e3a8a;
            color: #fff;
            border: none;
            padding: 7px 16px;
            font-size: 12px;
            font-family: Arial, sans-serif;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-print:hover { background: #1d3079; }

        .btn-close {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 7px 13px;
            font-size: 12px;
            font-family: Arial, sans-serif;
            border-radius: 5px;
            cursor: pointer;
        }

        /* ── PRINT STYLES ── */
        @media print {
            .print-actions { display: none; }
            html, body { background: #fff; margin: 0; padding: 0; }
            .page { margin: 0 auto; box-shadow: none; border: 1.5px solid #222; }
            @page {
                size: 105mm 210mm;
                margin: 0;
            }
        }

        @media screen {
            body {
                background: #d1d5db;
                padding: 56px 16px 40px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            .page {
                box-shadow: 0 4px 28px rgba(0,0,0,0.20);
                background: #fff;
            }
        }
    </style>
</head>
<body>

<div class="print-actions">
    <a href="{{ route('billings.show', $payment->billing_id) }}" class="btn-close" style="text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
        &larr; Back to Billing
    </a>
    <button class="btn-print" onclick="window.print()">Print Receipt</button>
    <button class="btn-close" onclick="window.close()">Close Window</button>
</div>

@php
    if (!function_exists('convertNumberToWords')) {
        function convertNumberToWords($amount) {
            $amount = (float)$amount;
            $whole = floor($amount);
            $fraction = round(($amount - $whole) * 100);
            
            $numberToWord = function($num) use (&$numberToWord) {
                $ones = [
                    0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 
                    5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                    10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 
                    14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 
                    18 => 'Eighteen', 19 => 'Nineteen'
                ];
                $tens = [
                    0 => '', 2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty', 
                    6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
                ];
                
                if ($num < 20) {
                    return $ones[$num];
                }
                if ($num < 100) {
                    return $tens[floor($num / 10)] . ($num % 10 ? ' ' . $ones[$num % 10] : '');
                }
                if ($num < 1000) {
                    return $ones[floor($num / 100)] . ' Hundred' . ($num % 100 ? ' and ' . $numberToWord($num % 100) : '');
                }
                if ($num < 1000000) {
                    return $numberToWord(floor($num / 1000)) . ' Thousand' . ($num % 1000 ? ' ' . $numberToWord($num % 1000) : '');
                }
                return '';
            };

            $result = '';
            if ($whole == 0) {
                $result = 'Zero';
            } else {
                $result = $numberToWord($whole);
            }

            $result .= ' Pesos';

            if ($fraction > 0) {
                $result .= ' and ' . $numberToWord($fraction) . ' Centavos';
            } else {
                $result .= ' Only';
            }

            return $result;
        }
    }

    $issuerName = $payment->receivedBy
        ? strtoupper($payment->receivedBy->name)
        : 'BARANGAY CLERK';

    $isCash  = strtolower($payment->payment_method) === 'cash';
    $isCheck = strtolower($payment->payment_method) === 'check';
    $isMO    = in_array(strtolower($payment->payment_method), ['money order', 'online_transfer', 'other']);
@endphp

<div class="page">

    {{-- ══ HEADER ══ --}}
    <div class="receipt-header">
        {{-- PH Coat of Arms (left) --}}
        <div class="logo-box">
            <img src="{{ asset('images/seal.png') }}" alt="Republic of the Philippines">
        </div>

        {{-- Center text --}}
        <div class="header-center">
            <div class="doc-title">Official Receipt</div>
            <div class="republic-line">Republic of the Philippines</div>
            <div class="barangay-name">Barangay Maribulan</div>
            <div class="address-line">Alabel, Sarangani Province</div>
        </div>

        {{-- Barangay Seal (right) --}}
        <div class="logo-box">
            <img src="{{ asset('images/maribulan-seal.png') }}" alt="Barangay Maribulan Official Seal">
        </div>
    </div>

    {{-- ══ AF No. / ORIGINAL ══ --}}
    <div class="meta-row">
        <div class="meta-cell">
            Accountable Form No. 51<br>Revised January, 1992
        </div>
        <div class="meta-cell">
            <div class="copy-type">ORIGINAL</div>
        </div>
    </div>

    {{-- ══ DATE / OR NUMBER ══ --}}
    <div class="date-or-row">
        <div class="date-or-cell">
            <div class="cell-label">Date</div>
            <div class="cell-value">{{ $payment->payment_date->format('M d, Y') }}</div>
        </div>
        <div class="date-or-cell">
            <div class="cell-label">No.</div>
            <div class="or-value">{{ $payment->reference_number ?: sprintf('%06d', $payment->id) }}</div>
        </div>
    </div>

    {{-- ══ AGENCY / FUND ══ --}}
    <div class="agency-fund-row">
        <div class="af-cell">
            <div class="cell-label">Agency</div>
            <div class="cell-value">Barangay Maribulan, Alabel</div>
        </div>
        <div class="af-cell">
            <div class="cell-label">Fund</div>
            <div class="cell-value">{{ $payment->billing->fund ?? 'General Fund' }}</div>
        </div>
    </div>

    {{-- ══ PAYOR ══ --}}
    <div class="payor-row">
        <div class="cell-label">Payor</div>
        <div class="cell-value">{{ $payment->billing->consumer->first_name }} {{ $payment->billing->consumer->last_name }}</div>
    </div>

    {{-- ══ NATURE OF COLLECTION TABLE ══ --}}
    <div class="collection-table-wrap">
    <table class="collection-table">
        <thead>
            <tr>
                <th style="width:52%; text-align:left;">Nature of Collection</th>
                <th style="width:26%;">Account<br>Code</th>
                <th style="width:22%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Water Bill Payment ({{ \Carbon\Carbon::parse($payment->billing->billing_month)->format('F Y') }})</td>
                <td class="text-center"></td>
                <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
            </tr>
            {{-- empty filler rows to match form structure --}}
            @for($i = 0; $i < 5; $i++)
            <tr class="empty-row">
                <td>&nbsp;</td>
                <td></td>
                <td></td>
            </tr>
            @endfor
            {{-- TOTAL row --}}
            <tr class="total-row">
                <td colspan="2" style="text-align:right; border-right: 1px solid #222;">TOTAL &nbsp;&nbsp; &#8369;</td>
                <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
    </div>

    {{-- ══ AMOUNT IN WORDS ══ --}}
    <div class="words-section">
        <div class="words-label">Amount in Words</div>
        <div class="words-value">{{ convertNumberToWords($payment->amount) }}</div>
    </div>

    {{-- ══ PAYMENT METHOD + DRAWEE BANK ══ --}}
    <div class="payment-section">
        <div class="payment-checkboxes">
            <div class="check-item">
                <span class="check-box {{ $isCash ? 'checked' : '' }}"></span>
                Cash
            </div>
            <div class="check-item">
                <span class="check-box {{ $isCheck ? 'checked' : '' }}"></span>
                Check
            </div>
            <div class="check-item">
                <span class="check-box {{ $isMO ? 'checked' : '' }}"></span>
                Money Order
            </div>
        </div>
        <div class="payment-drawee">
            <div class="drawee-cell">
                <div class="cell-label">Drawee<br>Bank</div>
                <div class="cell-value"></div>
            </div>
            <div class="drawee-cell">
                <div class="cell-label">Number</div>
                <div class="cell-value">
                    {{ $payment->payment_method === 'check' || $payment->payment_method === 'online_transfer' ? $payment->reference_number : '' }}
                </div>
            </div>
            <div class="drawee-cell">
                <div class="cell-label">Date</div>
                <div class="cell-value">{{ $payment->payment_date->format('m/d/Y') }}</div>
            </div>
        </div>
    </div>

    {{-- ══ RECEIVED + COLLECTING OFFICER ══ --}}
    <div class="received-section">
        <div class="received-statement">Received the amount stated above</div>
        <div class="officer-block">
            <div class="officer-sig">
                <span class="officer-name">{{ $issuerName }}</span>
                <span class="officer-underline"></span>
                <span class="officer-title">Collecting Officer</span>
            </div>
        </div>
    </div>

    <div class="footer-note">
        Note: Write the number and date of this receipt at the back of check or money order received.
    </div>

</div>

<script>
    // Auto-print the receipt when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        window.print();
    });
</script>

</body>
</html>
