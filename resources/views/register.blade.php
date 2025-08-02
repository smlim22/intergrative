<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h1>Register</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input name="name" placeholder="Name" value="{{ old('name') }}"><br>
        <input name="email" placeholder="Email" value="{{ old('email') }}"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <input type="password" name="password_confirmation" placeholder="Confirm Password"><br>
        <button type="submit">Register</button>
    </form>
    <a href="{{ route('login') }}">Login</a>
</body>
</html>