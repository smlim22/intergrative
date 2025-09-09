<!DOCTYPE html>
<html>
    <head>
        <title>Student</title>
    </head>
    <body>
        <h1>Welcome to the Student Page</h1>
            <div style="margin-top:2em;">
                <h2>Leave Feedback & Rating for Facilities</h2>
                <p>Select a facility to leave feedback and rating:</p>
                <form method="GET" action="/facilities/1/feedback/create" style="display:inline;">
                    <button type="submit">Facility 1</button>
                </form>
                <!-- Add more facilities as needed -->
            </div>
    </body>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</html>