<!DOCTYPE html>
<html>
<head>
    <title>WhatsApp Test</title>
</head>
<body>
    <h1>Send WhatsApp Message</h1>

    <form action="{{ route('whatsapp.send') }}" method="POST">
        @csrf
        <label for="to">Recipient (with country code, e.g. +60123456789):</label>
        <input type="text" name="to" id="to" required><br><br>

        <label for="body">Message:</label>
        <textarea name="body" id="body" required></textarea><br><br>

        <button type="submit">Send</button>
    </form>
</body>
</html>
