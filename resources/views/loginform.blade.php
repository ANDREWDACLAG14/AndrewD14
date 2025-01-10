<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
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
        .login-container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
        }
        .login-container h3 {
            color: #e50914;
            margin-bottom: 20px;
            text-align: center;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .login-container button {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .login-container button:hover {
            background-color: #f6121d;
        }
        .login-container a {
            color: #e50914;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3>Login</h3>
        <form method="POST" action="/login">
            @csrf
            <label for="login-username">Username:</label>
            <input type="text" id="login-username" name="username" placeholder="Enter your username" required>

            <label for="login-password">Password:</label>
            <input type="password" id="login-password" name="password" placeholder="Enter your password" required>

            <button type="submit">Login</button>
            <p class="mt-3 text-center">Don't have an account? <a href="/registerform">Register here</a></p>
        </form>
    </div>

    @if(session('incorrect_msg'))
    <script>
        alert("{{ session('incorrect_msg') }}");
    </script>
    @endif
</body>
</html>
