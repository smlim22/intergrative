<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
                    <h2 class="text-center mb-4">Register</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control" name="name" placeholder="Name" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3 password-container">
                            <input class="form-control" type="password" name="password" id="passwordInput" placeholder="Password">
                            <i class="bi bi-eye-slash password-toggle" id="passwordToggle"></i>
                        </div>
                        <div class="mb-3 password-container">
                            <input class="form-control" type="password" name="password_confirmation" id="confirmPasswordInput" placeholder="Confirm Password">
                            <i class="bi bi-eye-slash password-toggle" id="confirmPasswordToggle"></i>
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

    <script>
        // Get the password and toggle elements for the main password field
        const passwordInput = document.getElementById('passwordInput');
        const passwordToggle = document.getElementById('passwordToggle');

        // Get the password and toggle elements for the confirmation password field
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');

        // Function to toggle password visibility
        function togglePassword(input, toggle) {
            // Check the current type of the input
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            
            // Set the new type
            input.setAttribute('type', type);

            // Toggle the eye icon classes to switch the icon
            toggle.classList.toggle('bi-eye');
            toggle.classList.toggle('bi-eye-slash');
        }

        // Add event listeners to both toggle icons
        passwordToggle.addEventListener('click', () => {
            togglePassword(passwordInput, passwordToggle);
        });

        confirmPasswordToggle.addEventListener('click', () => {
            togglePassword(confirmPasswordInput, confirmPasswordToggle);
        });
    </script>
</body>
</html>