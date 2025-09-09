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
                    
                    <form id="forgotPasswordForm">
                        <input type="email" id="email" name="email"
                            class="mt-1 p-2 w-full border rounded-lg"
                            placeholder="Enter your email"
                            required>

                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded mt-4">
                            Send Reset Link
                        </button>
                    </form>

                    <div id="responseMessage" class="mt-4 hidden"></div>

                    <hr>

                    <div class="text-center mb-3">
                        <a href="{{ route('login') }}">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value;

        const response = await fetch("/api/forgot-password", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
            },
            body: JSON.stringify({ email })
        });

        const data = await response.json();
        const messageBox = document.getElementById("responseMessage");

        messageBox.classList.remove("hidden");
        messageBox.textContent = data.message;

        if (response.ok) {
            messageBox.className = "mt-4 p-2 rounded bg-green-100 text-green-700";
        } else {
            messageBox.className = "mt-4 p-2 rounded bg-red-100 text-red-700";
        }
    });
</script>