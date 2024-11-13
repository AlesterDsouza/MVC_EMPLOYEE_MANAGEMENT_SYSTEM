<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 1em;
            color: #333;
        }
        .input-group {
            margin-bottom: 1em;
        }
        .input-group label {
            display: block;
            margin-bottom: 0.5em;
            color: #555;
        }
        .input-group input {
            width: 100%;
            padding: 0.8em;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        .input-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .error-message {
            color: red;
            margin-bottom: 1em;
            font-size: 0.9em;
        }
        .submit-btn {
            background-color: #007bff;
            color: #fff;
            padding: 0.8em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            width: 100%;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message">Invalid Username or Password</div>
        <?php endif; ?>
        
        <form action="../public/index.php?controller=login&action=login" method="POST" autocomplete="off">
            <div class="input-group">
                <label for="UserName">Username</label>
                <input type="text" name="UserName" id="UserName" required autocomplete="off">
            </div>
            <div class="input-group">
                <label for="Password">Password</label>
                <input type="password" name="Password" id="Password" required autocomplete="off">
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>
    </div>

    <script>
        // Restrict input to alphabets for username
        document.getElementById('UserName').addEventListener('input', function (event) {
            const value = event.target.value;
            event.target.value = value.replace(/[^a-zA-Z]/g, ''); // Only allow alphabets
        });

        // Restrict input to alphanumeric characters for password
        document.getElementById('Password').addEventListener('input', function (event) {
            const value = event.target.value;
            event.target.value = value.replace(/[^a-zA-Z0-9]/g, ''); // Allow alphabets and numbers only
        });
    </script>
</body>
</html>
