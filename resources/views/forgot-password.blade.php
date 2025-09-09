<!DOCTYPE html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center w-100">
            <div class="col-md-5">
                <div class="card shadow-lg p-4 rounded-3">
                    <h2 class="text-center mb-4">Reset Password</h2>
                    
                    <form id="forgotPasswordForm" method="POST" action="{{ route('forgot-password') }}">
                        @csrf
                        <input type="email" id="email" name="email"
                            class="mt-1 p-2 w-full border rounded-lg"
                            placeholder="Enter your email"
                            required>

                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded mt-4">
                            Send Reset Link
                        </button>
                    </form>

                    @if (session('status'))
                        <div class="alert alert-success mt-2">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mt-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <hr>

                    <div class="text-center mb-3">
                        <a href="{{ route('login') }}">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>