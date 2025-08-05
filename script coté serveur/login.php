<?php
session_start();

$valid_email = 'mohamed.mokni@gmail.com';
$valid_password = '123456789';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === $valid_email && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $welcome_message = 'Welcome, ' . $email . '!'; // Welcome message
        header('Location: telediagnostic'); // Redirect to patient.php after successful login
        exit;
    } else {
        $error_message = 'Incorrect email or password'; // Error message if credentials are incorrect
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Interface - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Body Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('banniere-medicale-medecin-tenant-stethoscope.jpg');
            background-size: cover;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form Container */
        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
            text-align: center;
            transition: transform 0.3s ease-in-out;
            margin-top: 50px;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        /* Header Styles */
        .header h1 {
            color: #333333;
            font-size: 36px;
            font-weight: bold;
            margin: 0;
        }

        /* Input Styles */
        .login-container label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
            color: #666666;
            font-size: 16px;
            font-weight: bold;
        }

        .login-container input {
            width: calc(100% - 24px);
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            transition: border-color 0.3s;
            font-size: 16px;
            outline: none;
        }

        .login-container input:focus {
            border-color: #4CAF50;
        }

        /* Button Styles */
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            outline: none;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        /* Error Message Styles */
        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: left;
        }

        /* Login Title Style */
        .login-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Login</h2> <!-- Login Title -->
        <?php
        if (isset($error_message)) {
            echo '<p class="error-message">' . $error_message . '</p>';
        }
        if (isset($welcome_message)) {
            echo '<p class="welcome-message">' . $welcome_message . '</p>';
        }
        ?>
        <form action="login.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Log in</button>
        </form>
    </div>
</body>
</html>
