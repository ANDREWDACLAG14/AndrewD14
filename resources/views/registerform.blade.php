<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset('images/milktealogo.jpg') }}') no-repeat center center fixed;
            background-size: contain; /* Change this to 'contain' for better scaling */
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: black; /* Add a fallback color */
        }
        .register-container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
        }
        .register-container h3 {
            color: #e50914;
            margin-bottom: 20px;
            text-align: center;
        }
        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .register-container button {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .register-container button:hover {
            background-color: #f6121d;
        }
        .register-container a {
            color: #e50914;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h3>Register</h3>
        <form method="POST" action="/submit">
            @csrf
            <label for="reg-username">Username:</label>
            <input type="text" id="reg-username" name="username" placeholder="Enter your username" required>

            <label for="reg-email">Email:</label>
            <input type="email" id="reg-email" name="email" placeholder="Enter your email" required>

            <label for="reg-password">Password:</label>
            <input type="password" id="reg-password" name="password" placeholder="Enter your password" required>

            <button type="submit">Register</button>
            <p class="mt-3 text-center">Already have an account? <a href="/">Login here</a></p>
        </form>
    </div>

    <!-- Show success message from session if available -->
    @if(session('success_msg'))
    <script>
        alert("{{ session('success_msg') }}");
    </script>
@endif

</body>
</html>
