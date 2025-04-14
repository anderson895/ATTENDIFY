<?php

// Handle user login logic

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password cannot be empty';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        exit();
    }

    if ($userType == "administrator") {
        $stmt = $pdo->prepare("SELECT * FROM tbladmin WHERE emailAddress = :email");
    } elseif ($userType == "depthead") {
        $stmt = $pdo->prepare("SELECT * FROM tbldepthead WHERE emailAddress = :email");
    }
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['Id'],
            'email' => $user['emailAddress'],
            'name' => $user['firstName'],
            'role' => $userType,
        ];

        header('Location: home');
        exit();
    } else {
        $errors['login'] = 'Invalid email or password';
        $_SESSION['errors'] = $errors;
    }
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}

function display_error($error, $is_main = false)
{
    global $errors;
    if (isset($errors["{$error}"])) {
        echo '<div class="' . ($is_main ? 'error-main' : 'error') . '">
                  <p>' . $errors["{$error}"] . '</p>
           </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="resources/images/tablogo.png" type="image/svg+xml">
    <title>ATTENDIFY</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="resources/assets/css/login_styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('resources/images/tup.gif');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #dd797e;
            color: #333;
        }

        .container {
            width: 1100px;
            height: 640px;
            display: flex;
            box-shadow: 0 70px 10px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            overflow: hidden;
            background-color:rgb(255, 255, 255);
            backdrop-filter: blur(5px);
        }

        .sign-in-container {
            flex: 1;
            background:rgb(253, 253, 253);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 50px;
        }

        .sign-in-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sign-in-container form {
            width: 100%;
        }

        .sign-in-container .input-group {
            margin: 10px 0;
            position: relative;
        }

        .sign-in-container .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .sign-in-container input,
        .sign-in-container select {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .sign-in-container .btn {
            width: 100%;
            padding: 10px;
            border: none;
            background: #ff4b2b;
            color: #ffffff;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }

        .sign-in-container .btn:hover {
            background: #e04326;
        }

        .sign-in-container .icons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .sign-in-container .icons i {
            margin: 0 10px;
            font-size: 20px;
            color: #666;
            cursor: pointer;
        }

        .sign-in-container .recover {
            text-align: right;
            margin-top: 10px;
        }

        .sign-in-container .recover a {
            color: #ff4b2b;
            text-decoration: none;
            font-size: 12px;
        }

        .overlay-container {
            flex: 1;
            background: linear-gradient(to right, #ff4b2b, #ff416c);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 0 50px;
            text-align: center;
        }

        .overlay-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .overlay-container p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .overlay-container .btn {
            padding: 10px 20px;
            border: 1px solid #ffffff;
            background: transparent;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        .overlay-container .btn:hover {
            background: white;
            color: #ff4b2b;
        }
        /* Responsive Design */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        height: auto;
    }

    .sign-in-container,
    .overlay-container {
        padding: 40px 20px;
    }

    .sign-in-container .btn,
    .overlay-container .btn {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .sign-in-container h1,
    .overlay-container h1 {
        font-size: 20px;
    }

    .sign-in-container input,
    .sign-in-container select {
        font-size: 10px;
    }

    .sign-in-container .btn,
    .overlay-container .btn {
        padding: 8px;
        font-size: 12px;
    }
}
.sign-in-container .logo {
    width: 150px; /* Adjust this size as needed */
    max-width: 100%;
    height: auto;
    margin-bottom: 20px;
}
    </style>
</head>

<body>
    <div class="container">
        <!-- Sign-in Section -->
        <div class="sign-in-container">
            <img src="resources/images/tablogo.png" alt="ATTENDIFY Logo" class="logo">
            <h1>ATTENDIFY</h1>
            <?php display_error('login', true); ?>
            <form method="POST" action="">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <select name="user_type" required>
                        <option value="">Select User</option>
                        <option value="administrator">Administrator</option>
                        <option value="depthead">Department Head</option>
                    </select>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                    <?php display_error('email'); ?>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                    <?php display_error('password'); ?>
                </div>
                <p class="recover">
                    <a href="signup.php">Sign up</a>
                </p>
                <input type="submit" class="btn" value="Sign In" name="login">
            </form>
        </div>

        <!-- Overlay Section -->
        <div class="overlay-container">
    <h1>TIME IN AND TIME OUT</h1>
    <!-- Updated form to redirect to attendance -->
    <form action="index.php" method="GET">
        <input type="hidden" name="request_site" value="attendance">
        <button class="btn" type="submit">ATTENDANCE HERE</button>
    </form>
    </div>
</body>

</html>
