@extends('layouts.print')

@section('content')
@php
    $consumption = (float) $billing->consumption;
    $amount = (float) $billing->amount;
    
    $minConsumption = 10.0;
    $minCharge = 200.00;
    
    if ($consumption <= $minConsumption) {
        $first10Charge = $amount;
        $excessCharge = 0.00;
    } else {
        $first10Charge = $minCharge;
        $excessCharge = max(0.00, $amount - $minCharge);
    }
    
    $totalAmountDue = $amount + (float) $billing->arrears;
    
    $orgTitle = 'MARIBULAN WSL-III';
    $orgAddress1 = 'CIENTO DIEZ, MARIBULAN';
    $orgAddress2 = 'ALABEL, SARANGANI PROVINCE';
    $orgZip = '9501';
@endphp

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: #1e293b;
        margin: 0;
        padding: 0;
    }

    /* Screen-specific viewport centering and styling */
    @media screen {
        body {
            background-color: #cbd5e1 !important; /* Soft gray viewport background matching the OR image */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 0 !important;
            margin: 0 !important;
        }
        .page-container {
            margin: 0 auto !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        }
    }

    /* Print styling overrides */
    @media print {
        @page {
            size: 140mm 216mm;
            margin: 0;
        }
        body {
            background: #fff !important;
            color: #000 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .page-container {
            box-shadow: none !important;
            border: 1.5px solid #222 !important;
            border-radius: 6px !important;
            margin: 0 !important;
            padding: 6mm 7mm 8mm !important; /* Compact padding matching the OR exactly */
            width: 140mm !important;
            height: auto !important; /* Stops perfectly below the footer text */
            min-height: auto !important;
            max-width: 140mm !important;
            box-sizing: border-box !important;
        }
        .no-print-btn {
            display: none !important;
        }
    }

    .page-container {
        width: 140mm; /* Statement size width */
        height: auto; /* Stops perfectly below the footer text */
        min-height: auto;
        margin: 20px auto;
        background: #ffffff;
        padding: 6mm 7mm 8mm; /* Compact padding matching the OR exactly */
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 1.5px solid #222;
        border-radius: 6px;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
    }

    /* Header Grid */
    .header-grid {
        display: grid;
        grid-template-columns: 1.3fr 0.7fr;
        gap: 10px;
        margin-bottom: 12px;
        border-bottom: 1.5px solid #334155;
        padding-bottom: 8px;
    }

    .org-title {
        font-family: 'Outfit', sans-serif;
        font-size: 13pt;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.1;
        margin-bottom: 3px;
    }

    .org-address {
        font-size: 7.5pt;
        color: #475569;
        line-height: 1.3;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .contact-info {
        text-align: right;
        font-size: 7.5pt;
        color: #475569;
        line-height: 1.4;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-end;
    }

    .contact-item {
        display: flex;
        gap: 4px;
    }

    .contact-label {
        font-weight: 600;
        color: #64748b;
    }

    /* Bill To Block */
    .bill-to-row {
        margin-bottom: 12px;
        font-size: 9pt;
        color: #334155;
    }
    
    .bill-to-label {
        font-weight: 500;
        color: #64748b;
        margin-right: 6px;
    }

    .bill-to-value {
        font-weight: 700;
        color: #0f172a;
        font-size: 10pt;
        letter-spacing: 0.01em;
    }

    /* Statement Title Banner */
    .statement-banner {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .statement-title {
        font-family: 'Outfit', sans-serif;
        font-size: 18pt;
        font-weight: 800;
        font-style: italic;
        color: #1e3a8a; /* Deep elegant blue */
        line-height: 1;
        letter-spacing: -0.03em;
    }

    /* Info Details Block */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 14px;
        font-size: 8pt;
    }

    .info-column {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 2px;
    }

    .info-label {
        color: #64748b;
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        color: #0f172a;
    }

    /* Meter Reading Table */
    .reading-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 14px;
        font-size: 7.5pt;
    }

    .reading-table th, .reading-table td {
        border: 1px solid #cbd5e1;
        padding: 5px 6px;
        text-align: center;
    }

    .reading-table th {
        background-color: #f1f5f9;
        color: #334155;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 7pt;
        letter-spacing: 0.03em;
    }

    .reading-table .header-main {
        font-size: 7.5pt;
        font-weight: 800;
        background-color: #e2e8f0;
    }

    .reading-table td {
        color: #1e293b;
        font-weight: 500;
    }

    /* Ledger Breakdown Section */
    .ledger-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 14px;
    }

    .ledger-table {
        width: 70%;
        border-collapse: collapse;
        font-size: 8pt;
    }

    .ledger-table td {
        padding: 4px 0;
        vertical-align: middle;
    }

    .ledger-label {
        text-align: left;
        color: #475569;
        font-weight: 500;
    }

    .ledger-value {
        text-align: right;
        font-weight: 600;
        color: #0f172a;
        width: 90px;
    }

    /* Double underline amount due styling */
    .ledger-row-total .ledger-label {
        font-weight: 700;
        color: #0f172a;
        font-size: 8.5pt;
    }

    .ledger-row-total .ledger-value {
        font-size: 9pt;
        font-weight: 800;
        color: #0f172a;
        border-top: 1.5px solid #0f172a;
        border-bottom: 4px double #0f172a;
        padding-top: 4px;
        padding-bottom: 2px;
    }

    /* Date Columns Footer */
    .dates-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        border-top: 1.5px solid #e2e8f0;
        padding-top: 12px;
        margin-bottom: 16px;
    }

    .date-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px;
        text-align: center;
    }

    .date-card-title {
        font-size: 7pt;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.05em;
        margin-bottom: 3px;
    }

    .date-card-value {
        font-size: 10.5pt;
        font-weight: 800;
        color: #0f172a;
    }

    /* Instruction Footer */
    .statement-footer {
        text-align: center;
        font-size: 8.5pt;
        font-weight: 600;
        color: #475569;
        letter-spacing: 0.01em;
        border-top: 1px dashed #cbd5e1;
        padding-top: 12px;
        margin-top: 16px;
    }

    /* Floating navigation actions on screen */
    .actions-panel {
        width: 140mm;
        margin: 0 auto 15px !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-sizing: border-box;
        gap: 12px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        font-size: 9.5pt;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        box-sizing: border-box;
        white-space: nowrap; /* Prevent wrapping completely */
        flex: 1; /* Make both buttons equal width! */
        min-width: 0;
    }

    .btn-back {
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .btn-back:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .btn-print {
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #1d4ed8;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2), 0 2px 4px -1px rgba(37, 99, 235, 0.1);
    }

    .btn-print:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 10px -1px rgba(37, 99, 235, 0.25);
    }
</style>
@endpush

{{-- Actions panel for cashiers (hidden on print) --}}
<div class="actions-panel no-print-btn">
    <a href="{{ route('billings.show', $billing) }}" class="btn btn-back">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Details
    </a>
    <button onclick="window.print()" class="btn btn-print">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2zm0-9a9 9 0 0118 0v4a3 3 0 01-3 3H6a3 3 0 01-3-3V7a9 9 0 019-9z" />
        </svg>
        Print Statement
    </button>
</div>

{{-- Standard Statement-sized Container (140mm x 216mm) --}}
<div class="page-container">
    {{-- Header block --}}
    <div class="header-grid">
        <div>
            <div class="org-title">{{ $orgTitle }}</div>
            <div class="org-address">
                {{ $orgAddress1 }}<br>
                {{ $orgAddress2 }}<br>
                {{ $orgZip }}
            </div>
        </div>
        <div class="contact-info">
            <div class="contact-item">
                <span class="contact-label">Phone:</span>
                <span>_______________</span>
            </div>
            <div class="contact-item" style="margin-top: 2px;">
                <span class="contact-label">Fax:</span>
                <span>_______________</span>
            </div>
            <div class="contact-item" style="margin-top: 2px;">
                <span class="contact-label">E-mail:</span>
                <span>_______________</span>
            </div>
        </div>
    </div>

    {{-- Bill To Consumer row --}}
    <div class="bill-to-row">
        <span class="bill-to-label">Bill To:</span>
        <span class="bill-to-value">
            {{ strtoupper($billing->consumer->last_name) }}&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($billing->consumer->first_name) }}
        </span>
    </div>

    {{-- Statement Title --}}
    <div class="statement-banner">
        <div class="statement-title">Statement</div>
    </div>

    {{-- Metadata details grid --}}
    <div class="info-grid">
        <div class="info-column">
            <div class="info-row">
                <span class="info-label">Statement #:</span>
                <span class="info-value">{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ $billing->created_at->format('m/d/Y') }}</span>
            </div>
            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">Customer ID:</span>
                <span class="info-value">{{ $billing->consumer->account_number }}</span>
            </div>
        </div>
        <div class="info-column">
            <div class="info-row">
                <span class="info-label">Street Addres:</span>
                <span class="info-value">
                    {{ strtoupper($billing->consumer->address_line_1 ?: 'MARIBULAN') }}
                </span>
            </div>
            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">Due Date:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($billing->due_date)->format('m/d/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Water Meter Readings Grid --}}
    <table class="reading-table">
        <thead>
            <tr>
                <th colspan="2" class="header-main">WATER METER READING</th>
                <th colspan="1" class="header-main">CUBIC MT.</th>
                <th colspan="1" class="header-main">Minimum</th>
                <th colspan="1" class="header-main">EXCESS</th>
            </tr>
            <tr>
                <th>Present</th>
                <th>Previous</th>
                <th>used</th>
                <th>consumption</th>
                <th>consumption</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($billing->current_reading, 0) }}</td>
                <td>{{ number_format($billing->previous_reading, 0) }}</td>
                <td>{{ number_format($consumption, 0) }}</td>
                <td>10</td>
                <td>{{ $consumption > 10 ? number_format($consumption - 10, 0) : '-' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Billing Financial breakdown --}}
    <div class="ledger-container">
        <table class="ledger-table">
            <tbody>
                <tr>
                    <td class="ledger-label">Current (first 10 cum)</td>
                    <td class="ledger-value">{{ number_format($first10Charge, 2) }}</td>
                </tr>
                <tr>
                    <td class="ledger-label">(excess)</td>
                    <td class="ledger-value">{{ $excessCharge > 0 ? number_format($excessCharge, 2) : '-' }}</td>
                </tr>
                <tr>
                    <td class="ledger-label">Current Due&nbsp;&nbsp;{{ strtoupper(\Carbon\Carbon::parse($billing->billing_month)->format('F')) }}</td>
                    <td class="ledger-value" style="border-top: 1px solid #cbd5e1;">{{ number_format($amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="ledger-label">Balance</td>
                    <td class="ledger-value" style="border-bottom: 1px solid #cbd5e1;">{{ $billing->arrears > 0 ? number_format($billing->arrears, 2) : '-' }}</td>
                </tr>
                <tr class="ledger-row-total">
                    <td class="ledger-label">Amount Due:</td>
                    <td class="ledger-value">{{ number_format($totalAmountDue, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Due Date cards footer --}}
    <div class="dates-container">
        <div class="date-card">
            <div class="date-card-title">Due Date</div>
            <div class="date-card-value">{{ \Carbon\Carbon::parse($billing->due_date)->format('m/d/Y') }}</div>
        </div>
        <div class="date-card">
            <div class="date-card-title">Disconnection Date</div>
            <div class="date-card-value">{{ \Carbon\Carbon::parse($billing->due_date)->addDays(15)->format('m/d/Y') }}</div>
        </div>
    </div>

    {{-- Important notice --}}
    <div class="statement-footer">
        Please pay your WATER BILL account to our Barangay Clerk
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function() {
            window.print();
        }, 800);
    });
</script>
@endpush
@endsection
