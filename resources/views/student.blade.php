<!DOCTYPE html>
<html>
    <head>
        <title>Student</title>
    </head>
    <body>
        <h1>Welcome to the Student Page</h1>
    </body>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</html>