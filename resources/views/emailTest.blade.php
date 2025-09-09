<!DOCTYPE html>
<html>
<head>
    <title>Email Test</title>
</head>
<body>
    <h1>Send Invoice via Gmail</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <p style="color: red;">{{ implode(', ', $errors->all()) }}</p>
    @endif

    <form action="{{ route('email.send') }}" method="POST">
        @csrf
        <label for="to">Recipient Email:</label>
        <input type="email" name="to" id="to" required>
        <br><br>
        <button type="submit">Send Invoice</button>
    </form>
</body>
</html>
