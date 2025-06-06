<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Plan Cancelled</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; margin: 0; padding: 20px; background-color: #f9f9f9; }
        h2 { color: #007bff; }
        .card { max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="card">
        <h2><strong>Hello {{ ucwords($priceplans->organizations->name) }}</strong>,</h2>
        <p><strong>{{ ucwords($priceplans->organizations->contact_person) }}</strong><br>
            CIN: {{ $priceplans->organizations->CIN }}<br>
            GST: {{ $priceplans->organizations->GST }}<br>
            {{ $priceplans->organizations->organization_email }}<br>
            {{ $priceplans->organizations->phone }}
        </p>

        <p><strong>Plan Details:</strong></p>
        <ul>
            <li><strong>Plan:</strong> {{ ucwords($priceplans->priceplan->name) }}</li>
            <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($priceplans->start_date)->format('d/m/Y') }}</li>
            <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($priceplans->end_date)->format('d/m/Y') }}</li>
        </ul>

        <p>We want to inform you that your subscription plan has been <strong style="color: red;">cancelled</strong>.</p>

        <p>If this was a mistake or you have any questions, please contact our support team.</p>

        <p style="margin-top: 30px;">Thank you,<br><strong>{{ env('SUPER_ADMIN_COMPANY') }}</strong></p>
    </div>
</body>
</html>