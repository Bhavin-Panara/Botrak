<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Email</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; margin: 0; padding: 20px; background-color: #f9f9f9; }
        h2 { color: #007bff; }
        .invoice-card { max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="invoice-card">
        <h2>BoTrak Invoice #{{ $invoice->invoice_number }}</h2>

        <p><strong>Hello {{ $invoice->receiver->name ?? 'Customer' }}</strong>,<br>
            CIN: {{ $invoice->receiver->CIN }}<br>
            GST: {{ $invoice->receiver->GST }}<br>
            {{ $invoice->receiver->organization_email }}<br>
            {{ $invoice->receiver->phone }}
        </p>
        <p>We've generated an invoice for your recent subscription plan:</p>

        <ul>
            <li><strong>Plan:</strong> {{ $invoice->companypriceplans->priceplan->name }}</li>
            <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($invoice->plan_start_date)->format('d/m/Y') }}</li>
            <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($invoice->plan_end_date)->format('d/m/Y') }}</li>
            <li><strong>Amount:</strong> ₹ {{ number_format($invoice->amount, 2) }}</li>
            <li><strong>Discount:</strong> ₹ {{ number_format($invoice->discount, 2) }}</li>
            <li><strong>SGST:</strong> ₹ {{ number_format($invoice->sgst, 2) }}</li>
            <li><strong>CGST:</strong> ₹ {{ number_format($invoice->cgst, 2) }}</li>
            <li><strong>Total:</strong> ₹ {{ number_format($invoice->total_amount, 2) }}</li>
        </ul>

        <p>You can find your invoice attached to this email as a PDF document.</p>
        <p>If you have any questions, please reply to this email or contact us at <a href="mailto:{{ $invoice->sender->email }}">{{ $invoice->sender->email }}</a>.</p>
        <p>Thank you for your business!</p>

        <p style="margin-top: 30px;">Regards,<br><strong>{{ $invoice->sender->name ?? 'BoTrak Team' }}</strong></p>
        
        <p style="margin-top: 50px;"><strong>Terms & Conditions:</strong> Payment is due within 7 days from the invoice date. Late payments may be subject to additional fees. Please contact <b>{{ $invoice->sender->email }}</b> for any questions.</p>
    </div>
</body>
</html>