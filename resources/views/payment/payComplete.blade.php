<?php 
/**
 * Author : Adrean Goh
 */
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Completed</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>✅ Payment Completed</h1>
    <p>Your payment ID: {{ $payment->id }}</p>

    <div id="invoice-container" style="height:600px; border:1px solid #ccc;">
        <p>Loading invoice...</p>
    </div>

    <script>
    $(document).ready(function () {
        $.ajax({
            url: "{{ route('invoice.show', $payment->id) }}",
            method: "GET",
            xhrFields: { responseType: 'blob' }, // expect binary
            success: function (data) {
                let blob = new Blob([data], { type: 'application/pdf' });
                let url = URL.createObjectURL(blob);

                $('#invoice-container').html(
                    `<iframe src="${url}" width="100%" height="100%" style="border:none;"></iframe>`
                );
            },
            error: function (xhr) {
                console.error(xhr);
                $('#invoice-container').html("<p>⚠️ Failed to load invoice.</p>");
            }
        });
    });
    </script>
</body>
</html>
