<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input name="email" placeholder="Email" value="{{ old('email') }}"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <button type="submit">Login</button>
    </form>
    <a href="{{ route('register') }}">Register</a>
</body>
</html>