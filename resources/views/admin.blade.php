<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
    <h1>Welcome, {{ Auth::user()->name }}</h1>
    <p>Your role: {{ Auth::user()->role }}</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <p>Your role: {{ Auth::user()->role->name }}</p>
</body>
</html>
