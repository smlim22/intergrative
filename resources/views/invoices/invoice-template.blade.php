<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice_no }}</title>
    <style>
        body { 
            font-family: sans-serif, 'Times New Roman', Times, serif; 
            ont-size: 14px; 
        }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Facility Reservation Invoice</h2>
    </div>

    <p><strong>Invoice No:</strong> {{ $invoice_no }}</p>
    <p><strong>Customer:</strong> {{ $customer }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>

    <table>
        <thead>
            <tr>
                <th>Facility</th>
                <th>Reservation Time</th>
                <th>Amount (MYR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $facility }}</td>
                <td>{{ $reservation }}</td>
                <td>{{ number_format($amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p class="total">Total: RM {{ number_format($amount, 2) }}</p>
</body>
</html>
