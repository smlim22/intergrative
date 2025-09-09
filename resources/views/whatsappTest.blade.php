<!DOCTYPE html>
<html>
<head>
    <title>WhatsApp Test</title>
</head>
<body>
    <h1>Send WhatsApp Text</h1>

    <form action="{{ route('whatsapp.send') }}" method="POST">
        @csrf
        <label for="to">Recipient (e.g. +60123456789):</label>
        <input type="text" name="to" required><br><br>

        <label for="body">Message:</label>
        <textarea name="body" required></textarea><br><br>

        <button type="submit">Send Text</button>
    </form>

    <hr>

    <h1>Send Invoice PDF</h1>
    <form action="{{ route('whatsapp.invoice') }}" method="POST">
        @csrf
        <label for="to">Recipient (e.g. +60123456789):</label>
        <input type="text" name="to" required><br><br>

        <button type="submit">Send Invoice PDF</button>
    </form>
</body>
</html>
