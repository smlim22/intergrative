<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 100;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg p-4 rounded-3">
                    <h2 class="text-center mb-4">Login</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3 password-container">
                            <input class="form-control" type="password" name="password" id="passwordInput" placeholder="Password">
                            <i class="bi bi-eye-slash password-toggle" id="passwordToggle"></i>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <hr>
                    <div class="text-center mb-3">
                        <a href="{{ route('forgot-password') }}">Forgot Your Password?</a>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('register') }}">Don't have an account? Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('passwordInput');
        const passwordToggle = document.getElementById('passwordToggle');

        passwordToggle.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>