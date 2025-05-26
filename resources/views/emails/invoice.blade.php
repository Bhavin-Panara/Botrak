<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        h2, h3 { margin: 0px 0px 10px 0px; }
        p { margin: 0px 0px 5px 0px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .border-none { border: none; }
        .margin-0 { margin: 0; }
        .padding-0 { padding: 0; }
        a { margin-top: 10px; background-color: blue; color: white; padding: 10px; font-size: 16px; font-weight: bold; border: none; border-radius: 5px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>
    <table style="margin-top: 0px;">
        <tbody>
            <tr>
                <td class="border-none padding-0">
                    <h1 style="color: blue;">BoTrak</h1>
                </td>
                <td class="border-none padding-0">
                    <h2 class="text-right">Invoice</h2>
                    <p class="text-right">Invoice: #{{ $invoice->invoice_number }}</p>
                    <p class="text-right margin-0">Date: {{ \Carbon\Carbon::parse($invoice->sent_date)->format('d/m/Y H:i:s') }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    
    <table>
        <tbody>
            <tr>
                <td class="border-none padding-0">
                    <h3 style="font-size: 18px;">From:</h3>
                    <p><b>{{ $invoice->sender->name }}</b></p>
                    <p>{{ $invoice->sender->email }}</p>
                    <p class="margin-0">{{ $invoice->sender->organizations->phone ?? '-' }}</p>
                </td>
                <td class="border-none padding-0">
                    <h3 class="text-right" style="font-size: 18px;">To:</h3>
                    <p class="text-right"><b>{{ $invoice->receiver->name }}</b></p>
                    <p class="text-right">{{ $invoice->receiver->organization_email }}</p>
                    <p class="text-right margin-0">{{ $invoice->receiver->phone }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>Plan Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-wrap-mode: nowrap;">{{ $invoice->companypriceplans->priceplan->name }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->plan_start_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->plan_end_date)->format('d/m/Y') }}</td>
                <td class="text-right">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">{{ number_format($invoice->discount, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">{{ number_format($invoice->total_amount, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr>
                <td colspan="6" style="padding: 15px;"></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>Discount</b></td>
                <td>{{ number_format($invoice->discount, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>Total Amount</b></td>
                <td><b>{{ number_format($invoice->total_amount, 2) ?? '0.00' }} ₹</b></td>
            </tr>
        </tbody>
    </table>

    <h3 style="font-size: 18px;">Terms & Conditions</h3>
    <p>Payment is due within 7 days from the invoice date. Late payments may be subject to additional fees. Please contact <b>{{ $invoice->sender->email }}</b> for any questions.</p>

    <div class="text-right">
        <a href="#" target="_blank">Pay Now</a>
    </div>
</body>
</html>