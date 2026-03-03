<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: "Times New Roman", serif;
        }

        .receipt-box {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 50px;
            border: 1px solid #dee2e6;
        }

        .receipt-header {
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: 600;
        }

        .info-table td {
            padding: 8px 0;
        }

        .amount-box {
            border-top: 2px solid #000;
            margin-top: 30px;
            padding-top: 20px;
        }

        .amount-value {
            font-size: 28px;
            font-weight: bold;
        }

        .footer-note {
            border-top: 1px solid #ccc;
            margin-top: 40px;
            padding-top: 15px;
            font-size: 13px;
            color: #666;
        }

        @media print {
            body {
                background: none;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="receipt-box shadow-sm">

        <!-- Header -->
        <div class="receipt-header d-flex justify-content-between">
            <div>
                <div class="company-name">{{ \App\Helpers\Helper::getCompanyName() }}</div>
                <small class="text-muted">Official Payment Receipt</small>
            </div>
            <div class="text-end">
                <div class="receipt-title">Transaction Receipt</div>
                <small>
                    Date: {{ $transaction->created_at->format('d M Y, h:i A') }}
                </small>
            </div>
        </div>

        <!-- User Information -->
        <div class="row mb-4">
            <div class="col-6">
                <h6 class="fw-bold">Billed To:</h6>
                <p class="mb-1">{{ $transaction->user->name }}</p>
                <p class="mb-1">{{ $transaction->user->email }}</p>
                <p class="mb-0">Username: {{ $transaction->user->username }}</p>
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold">Transaction Details:</h6>
                <p class="mb-1"><strong>ID:</strong> {{ $transaction->transaction_id }}</p>
                <p class="mb-1">
                    <strong>Type:</strong>
                    {{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}
                </p>
                <p class="mb-1">
                    <strong>Flow:</strong>
                    {{ strtoupper($transaction->money_flow) }}
                </p>
                <p class="mb-0">
                    <strong>Status:</strong>
                    {{ ucfirst($transaction->status) }}
                </p>
            </div>
        </div>

        <!-- Divider -->
        <hr>

        <!-- Amount Section -->
        <div class="amount-box text-end">
            <p class="mb-1">Total Amount</p>
            <div class="amount-value">
                @if ($transaction->money_flow == 'in')
                    + ${{ number_format($transaction->amount, 2) }}
                @else
                    - ${{ number_format($transaction->amount, 2) }}
                @endif
            </div>
        </div>

        <!-- Description -->
        @if ($transaction->description)
            <div class="mt-4">
                <h6 class="fw-bold">Description</h6>
                <p class="mb-0 text-muted">
                    {{ $transaction->description }}
                </p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer-note text-center">
            This document serves as an official receipt for the above transaction.
            <br>
            Generated electronically and valid without signature or stamp.
        </div>

    </div>

    <!-- Print Button -->
    <div class="text-center mt-4 print-btn">
        <button onclick="window.print()" class="btn btn-dark px-4">
            Print Receipt
        </button>
    </div>

</div>

</body>
</html>
