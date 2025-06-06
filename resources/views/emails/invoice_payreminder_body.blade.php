<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Reminder Invoice Email</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; margin: 0; padding: 20px; background-color: #f9f9f9; }
        h2 { color: #dc3545; }
        .reminder-card { max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="reminder-card">
        <h2>BoTrak Payment Reminder Invoice #{{ $invoice->invoice_number }}</h2>

        <p><strong>Hello {{ ucwords($invoice->receiver->name) }}</strong>,<br>
            <strong>{{ ucwords($invoice->receiver->contact_person) }}</strong><br>
            CIN: {{ $invoice->receiver->CIN }}<br>
            GST: {{ $invoice->receiver->GST }}<br>
            {{ $invoice->receiver->organization_email }}<br>
            {{ $invoice->receiver->phone }}
        </p>

        <p>This is a gentle reminder that payment for the following invoice is still pending:</p>

        <ul>
            <li><strong>Plan:</strong> {{ ucwords($invoice->companypriceplans->priceplan->name) }}</li>
            <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($invoice->plan_start_date)->format('d/m/Y') }}</li>
            <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($invoice->plan_end_date)->format('d/m/Y') }}</li>
            <li><strong>Amount:</strong> ₹ {{ number_format($invoice->amount, 2) }}</li>
            <li><strong>Discount:</strong> ₹ {{ number_format($invoice->discount, 2) }}</li>
            <li><strong>SGST:</strong> ₹ {{ number_format($invoice->sgst, 2) }}</li>
            <li><strong>CGST:</strong> ₹ {{ number_format($invoice->cgst, 2) }}</li>
            <li><strong>Total:</strong> ₹ {{ number_format($invoice->total_amount, 2) }}</li>
            <li><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->sent_date)->format('d/m/Y') }}</li>
            <li style="color: red;"><strong>Payment Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->payment_due_date)->format('d/m/Y') }}</li>
            <li style="color: red;"><strong>Payment Pending Since:</strong> {{ \Carbon\Carbon::parse($invoice->payment_due_date)->diffForHumans() }}</li>
        </ul>

        <p><strong>Please arrange payment at your earliest convenience to avoid service interruption or late fees.</strong></p>

        <p>The invoice PDF is attached for your reference.</p>

        <p>If payment has already been made, kindly disregard this message. Otherwise, we request you to clear the dues as soon as possible.</p>

        <p>For any queries, feel free to reply to this email or reach out to <a href="mailto:{{ $invoice->sender->email }}">{{ $invoice->sender->email }}</a>.</p>

        <p style="margin-top: 30px;">Regards,<br><strong>{{ ucwords($invoice->sender->name) }}</strong></p>

        <p style="margin-top: 50px;"><strong>Note:</strong> This is an automated reminder based on your invoice due date. Kindly ensure timely payments to avoid disruptions.</p>
    </div>
</body>
</html>