<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg p-4 rounded-3">
                    <h2 class="text-center mb-4">Register</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control" name="name" placeholder="Name" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}">Already have an account? Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
